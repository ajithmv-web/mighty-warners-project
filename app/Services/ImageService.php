<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function uploadAndConvert(UploadedFile $file): string
    {
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Only JPG and PNG formats are supported.');
        }

        $filename = Str::uuid() . '.webp';
        $path = 'products/' . $filename;

        $image = $this->manager->read($file->getRealPath());
        
        $encoded = $image->toWebp(80);

        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }

    public function downloadAndConvert(string $url): string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('Invalid image URL.');
        }

        try {
            $imageContent = file_get_contents($url);
            
            if ($imageContent === false) {
                throw new \Exception('Failed to download image from URL.');
            }

            $tempPath = sys_get_temp_dir() . '/' . Str::uuid();
            file_put_contents($tempPath, $imageContent);
            $imageInfo = getimagesize($tempPath);
            if (!$imageInfo || !in_array($imageInfo['mime'], ['image/jpeg', 'image/png'])) {
                unlink($tempPath);
                throw new \Exception('Only JPG and PNG formats are supported.');
            }

            $filename = Str::uuid() . '.webp';
            $path = 'products/' . $filename;
            $image = $this->manager->read($tempPath);
            $encoded = $image->toWebp(80);
            Storage::disk('public')->put($path, (string) $encoded);
            unlink($tempPath);

            return $path;
        } catch (\Exception $e) {
            throw new \Exception('Failed to process image: ' . $e->getMessage());
        }
    }
    public function deleteImage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
