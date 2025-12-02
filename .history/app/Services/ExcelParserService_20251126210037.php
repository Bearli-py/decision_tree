<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\UploadedFile;

class ExcelParserService
{
    public function parseFile(UploadedFile $file)
    {
        try {
            // Load Excel file
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            
            $data = [];
            $rowCount = 0;

            // Read rows starting from row 2 (skip header)
            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                
                $rowData = [];
                $cellIndex = 0;

                foreach ($cellIterator as $cell) {
                    $value = $cell->getValue();
                    
                    if ($cellIndex == 0) $rowData['No'] = (int)$value;
                    else if ($cellIndex == 1) $rowData['Peserta'] = $value;
                    else if ($cellIndex == 2) $rowData['Budget'] = $value;
                    else if ($cellIndex == 3) $rowData['Speaker'] = $value;
                    else if ($cellIndex == 4) $rowData['Topik'] = $value;
                    else if ($cellIndex == 5) $rowData['Play'] = strtolower(trim($value)) === 'yes' ? 'yes' : 'no';
                    
                    $cellIndex++;
                    
                    if ($cellIndex > 5) break;
                }

                // Skip if all values are empty
                if (!empty($rowData) && isset($rowData['No'])) {
                    $data[] = $rowData;
                    $rowCount++;
                }

                if ($rowCount >= 1000) break; // Safety limit
            }

            if (empty($data)) {
                throw new \Exception('Tidak ada data valid di file Excel!');
            }

            return $data;

        } catch (\Exception $e) {
            throw new \Exception('Error parsing Excel: ' . $e->getMessage());
        }
    }
}