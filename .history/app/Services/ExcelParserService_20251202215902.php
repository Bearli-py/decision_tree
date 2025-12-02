// app/Services/ExcelParserService.php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ExcelParserService
{
    /**
     * Parse file upload menjadi array of associative array:
     * [
     *   ['Kolom1' => '...', 'Kolom2' => '...'],
     *   ...
     * ]
     */
    public function parseFile(UploadedFile $file): array
    {
        $filename = $file->getPathname();

        return $this->parseCSV($filename);
    }

    protected function parseCSV(string $filename): array
    {
        $data = [];
        $handle = fopen($filename, 'r');

        if ($handle === false) {
            throw new \RuntimeException('Tidak bisa membuka file yang diupload.');
        }

        // Baca header (nama kolom)
        $header = fgetcsv($handle, 1000, ',');
        if ($header === false) {
            fclose($handle);
            throw new \RuntimeException('File kosong atau header tidak ditemukan.');
        }

        // Normalisasi nama kolom (trim spasi)
        $header = array_map('trim', $header);

        // Baca baris data
        while (($cells = fgetcsv($handle, 1000, ',')) !== false) {
            // Skip baris yang benar-benar kosong
            if (count(array_filter($cells, fn($c) => $c !== null && trim($c) !== '')) === 0) {
                continue;
            }

            $row = [];
            foreach ($header as $index => $columnName) {
                $row[$columnName] = array_key_exists($index, $cells)
                    ? trim($cells[$index])
                    : null;
            }

            $data[] = $row;
        }

        fclose($handle);

        if (empty($data)) {
            throw new \RuntimeException('Tidak ada data valid di file.');
        }

        return $data;
    }
}