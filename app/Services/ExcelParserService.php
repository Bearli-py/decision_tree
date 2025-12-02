<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ExcelParserService
{
    public function parseFile(UploadedFile $file)
    {
        $filename = $file->getPathname();
        
        return $this->parseCSV($filename);
    }

    protected function parseCSV($filename)
    {
        $data = [];
        $row = 0;

        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($cells = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $row++;
                if ($row == 1) continue; // Skip header

                if (count($cells) < 6) continue;

                $data[] = [
                    'No' => (int)$cells[0],
                    'Peserta' => trim($cells[1]),
                    'Budget' => trim($cells[2]),
                    'Speaker' => trim($cells[3]),
                    'Topik' => trim($cells[4]),
                    'Play' => strtolower(trim($cells[5])) === 'yes' ? 'yes' : 'no'
                ];
            }
            fclose($handle);
        }

        if (empty($data)) {
            throw new \Exception('Tidak ada data valid di file!');
        }

        return $data;
    }
}