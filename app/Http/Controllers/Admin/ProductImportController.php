<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.products.import');
    }

    public function exportSample()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = ['product_name', 'category', 'color', 'size', 'qty', 'price', 'image'];
        $sheet->fromArray($headers, null, 'A1');
        $sampleData = [
            ['Shirt', 'Shirt', 'Khaki', 'XL', 33, 34, 'https://www.indianterrain.com/cdn/shop/files/ITMSH09908SS-Khaki_01_c392b4a3-676b-430b-9c8c-0f530671bceb.jpg?v=1760542088&width=800'],
            ['Men Solid Slim Fit Crew Neck T-shirt', 'T-shirt', 'Black', 'M', 77, 55, 'https://www.technosport.in/cdn/shop/files/OR10-Black_1_6501e14d-b267-4278-becf-93dcd949df7c.webp?v=1738843050&width=360'],
        ];
        $sheet->fromArray($sampleData, null, 'A2');
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
        ];
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'product_import_sample.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $imageService = app(ImageService::class);
            $import = new ProductImport($imageService);
            
            Excel::import($import, $request->file('file'));

            $errors = $import->getErrors();
            $successCount = 0; 
            $failureCount = count($errors);

            if (empty($errors)) {
                return redirect()->route('admin.products.index')
                    ->with('success', 'Products imported successfully!');
            }

            return view('admin.products.import-result', [
                'successCount' => $successCount,
                'failureCount' => $failureCount,
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }
}
