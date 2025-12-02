<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ExcelParserService
{
    public function parseFile(UploadedFile $file)
    {
        $filename = $file->getPathname();
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext === 'csv') {
            return $this->parseCSV($filename);
        } else if ($ext === 'xlsx' || $ext === 'xls') {
            return $this->parseXLSX($filename);
        }

        throw new \Exception('Format file tidak didukung. Gunakan CSV atau XLSX!');
    }

    protected function parseXLSX($filename)
    {
        // Simple XLSX parser menggunakan ZipArchive
        $zip = new \ZipArchive();
        
        if (!$zip->open($filename)) {
            throw new \Exception('Tidak bisa membuka file XLSX!');
        }

        // Read sheet1.xml dari archive
        $xml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if (!$xml) {
            throw new \Exception('Sheet tidak ditemukan di file XLSX!');
        }

        // Parse XML
        $data = [];
        $dom = new \DOMDocument();
        @$dom->loadXML($xml);

        $rows = $dom->getElementsByTagName('row');
        $rowNum = 0;

        foreach ($rows as $row) {
            $rowNum++;
            if ($rowNum == 1) continue; // Skip header

            $cells = $row->getElementsByTagName('c');
            $rowData = [];
            $cellNum = 0;

            foreach ($cells as $cell) {
                $value = '';
                $vNode = $cell->getElementsByTagName('v')->item(0);
                
                if ($vNode) {
                    $value = $vNode->nodeValue;
                }

                if ($cellNum == 0) $rowData['No'] = (int)$value;
                else if ($cellNum == 1) $rowData['Peserta'] = $value;
                else if ($cellNum == 2) $rowData['Budget'] = $value;
                else if ($cellNum == 3) $rowData['Speaker'] = $value;
                else if ($cellNum == 4) $rowData['Topik'] = $value;
                else if ($cellNum == 5) $rowData['Play'] = strtolower(trim($value)) === 'yes' ? 'yes' : 'no';

                $cellNum++;
            }

            if (!empty($rowData) && isset($rowData['No'])) {
                $data[] = $rowData;
            }
        }

        if (empty($data)) {
            throw new \Exception('Tidak ada data valid di file!');
        }

        return $data;
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
                    'Peserta' => $cells[1],
                    'Budget' => $cells[2],
                    'Speaker' => $cells[3],
                    'Topik' => $cells[4],
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