<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;

class ExcelParserService
{
    // Expected columns
    protected $expectedColumns = ['No', 'Peserta', 'Budget', 'Speaker', 'Topik', 'Play'];

    /**
     * Parse Excel file and return as array
     */
    public function parseFile(UploadedFile $file)
    {
        $rows = Excel::toArray(null, $file)[0]; // Get first sheet

        // Validate columns
        $headers = $rows[0];
        $this->validateColumns($headers);

        // Parse data
        $data = [];
        foreach (array_slice($rows, 1) as $row) {
            if (empty(array_filter($row))) continue; // Skip empty rows

            $data[] = [
                'No' => $row[0],
                'Peserta' => $row[1],
                'Budget' => $row[2],
                'Speaker' => $row[3],
                'Topik' => $row[4],
                'Play' => strtolower($row[5]) === 'yes' ? 'yes' : 'no'
            ];
        }

        return $data;
    }

    /**
     * Validate if columns match expected structure
     */
    protected function validateColumns($headers)
    {
        // Check if all expected columns exist
        foreach ($this->expectedColumns as $expected) {
            if (!in_array($expected, $headers)) {
                throw new \Exception("Kolom '{$expected}' tidak ditemukan. Pastikan file Excel memiliki kolom: " . implode(', ', $this->expectedColumns));
            }
        }
    }
}