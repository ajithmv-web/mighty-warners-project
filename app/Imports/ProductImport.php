<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Services\ImageService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class ProductImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    protected ImageService $imageService;
    protected array $errors = [];

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function model(array $row)
    {
        try {
            $categoryName = trim($row['category']);
            $colorName = trim($row['color']);
            $sizeName = trim($row['size']);

            $category = Category::whereRaw('LOWER(name) = ?', [strtolower($categoryName)])->first();
            if (!$category) {
                $category = Category::create(['name' => $categoryName]);
            }
            $color = Color::whereRaw('LOWER(name) = ?', [strtolower($colorName)])->first();
            if (!$color) {
                $color = Color::create(['name' => $colorName]);
            }
            $size = Size::whereRaw('LOWER(name) = ?', [strtolower($sizeName)])->first();
            if (!$size) {
                $size = Size::create(['name' => $sizeName]);
            }

            $imagePath = null;
            if (!empty($row['image'])) {
                try {
                    $imagePath = $this->imageService->downloadAndConvert($row['image']);
                } catch (\Exception $e) {
                    $this->errors[] = [
                        'row' => $row,
                        'error' => 'Failed to process image: ' . $e->getMessage(),
                    ];
                    return null;
                }
            }

            $existingProduct = Product::where('name', trim($row['product_name']))
                ->where('category_id', $category->id)
                ->where('color_id', $color->id)
                ->where('size_id', $size->id)
                ->first();

            if ($existingProduct) {
                $existingProduct->update([
                    'quantity' => (int) $row['qty'],
                    'price' => (float) $row['price'],
                    'image_path' => $imagePath ?? $existingProduct->image_path,
                ]);
                
                return null;
            }

            return new Product([
                'name' => trim($row['product_name']),
                'category_id' => $category->id,
                'color_id' => $color->id,
                'size_id' => $size->id,
                'quantity' => (int) $row['qty'],
                'price' => (float) $row['price'],
                'image_path' => $imagePath,
            ]);
        } catch (\Exception $e) {
            $this->errors[] = [
                'row' => $row,
                'error' => $e->getMessage(),
            ];
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'product_name' => 'required|string',
            'category' => 'required|string',
            'color' => 'required|string',
            'size' => 'required|string',
            'qty' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|url',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = [
                'row' => $failure->row(),
                'field' => $failure->attribute(),
                'error' => implode(', ', $failure->errors()),
            ];
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
