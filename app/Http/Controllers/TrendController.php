<?php

namespace App\Http\Controllers;

use App\Models\Trend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrendController extends Controller
{

    public function index()
    {
        // Mengambil data dari spreadsheet
       
    }

    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Ambil file CSV
        $file = $request->file('csv_file');
        $trends = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($trends);

        // Log headers dan data untuk debugging
        Log::info('CSV Header:', $header);
        Log::info('CSV Data:', $trends);

        // Daftar kolom yang digunakan
        $columns = [
            'No' => 'no',
            'Bulan' => 'bulan',
            'JUMLAH KAT-A' => 'jumlah_kat_a',
            'QOH > ROP' => 'qoh_rop',
            'QOH < ROP' => 'qoh_rop_kurang',
            'QOH TERISI' => 'qoh_terisi',
            'CUKUP PR' => 'cukup_pr',
            'CUKUP PO' => 'cukup_po',
            'CUKUP PR & PO' => 'cukup_pr_po',
            'CREATE PR' => 'create_pr',
            'Ketersediaan (%)' => 'ketersediaan'
        ];

        // Proses setiap baris data CSV
        foreach ($trends as $row) {
            $dataRow = array_combine($header, $row);

            // Log setiap baris data untuk debugging
            Log::info('Data Row:', $dataRow);

            $dbData = [];

            // Mapping data CSV ke kolom database
            foreach ($dataRow as $key => $value) {
                if (array_key_exists($key, $columns)) {
                    $field = $columns[$key];

                    // Handle kolom persentase
                    if ($key == 'Ketersediaan (%)') {
                        $dbData[$field] = !empty($value) ? str_replace('%', '', $value) : null;
                    }
                    // Handle kolom integer
                    elseif (in_array($key, ['JUMLAH KAT-A', 'QOH > ROP', 'QOH < ROP', 'QOH TERISI', 'CUKUP PR', 'CUKUP PO', 'CUKUP PR & PO', 'CREATE PR'])) {
                        $dbData[$field] = !empty($value) && is_numeric($value) ? (int)$value : 0; // Set 0 jika kosong atau tidak valid
                    }
                    // Handle lainnya
                    else {
                        $dbData[$field] = $value;
                    }
                }
            }

            // Log data sebelum disimpan ke database
            Log::info('DB Data:', $dbData);

            // Buat atau update data di database
            Trend::updateOrCreate(
                ['no' => $dbData['no'], 'bulan' => $dbData['bulan']], // Gunakan No dan Bulan sebagai identifier unik
                $dbData
            );
        }

        return redirect()->back()->with('success', 'CSV file has been uploaded and data inserted successfully.');
    }

}
