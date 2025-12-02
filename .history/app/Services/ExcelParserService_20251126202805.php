<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;

class ExcelParserService
{
    public function parseFile(UploadedFile $file)
    {
        try {
            $rows = Excel::toArray(null, $file)[0];
            
            if (empty($rows)) {
                throw new \Exception('File Excel kosong!');
            }

            // Skip header row
            $data = [];
            foreach (array_slice($rows, 1) as $row) {
                // Skip empty rows
                if (empty(array_filter($row))) continue;

                $data[] = [
                    'No' => (int)$row[0],
                    'Peserta' => $row[1],
                    'Budget' => $row[2],
                    'Speaker' => $row[3],
                    'Topik' => $row[4],
                    'Play' => strtolower(trim($row[5])) === 'yes' ? 'yes' : 'no'
                ];
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