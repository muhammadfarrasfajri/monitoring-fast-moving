<?php

namespace App\Http\Controllers;

use App\Models\kontrak;
use Illuminate\Http\Request;
use Revolution\Google\Sheets\Facades\Sheets;

class KontrakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kontrak = Sheets::spreadsheet('1XXvzQIo2uXpu0CfUZUgYpfaNcrVkz5ZDVocU5p6Hfho')->sheet('Kontrak')->get();
        $header = $kontrak->pull(0);
        $kontraks = Sheets::collection($header, $kontrak)->toArray(); // Konversi ke array di sini
        return view('admin.kontrak.index', ['data' => $kontraks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\kontrak  $kontrak
     * @return \Illuminate\Http\Response
     */
    public function show(kontrak $kontrak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\kontrak  $kontrak
     * @return \Illuminate\Http\Response
     */
    public function edit(kontrak $kontrak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\kontrak  $kontrak
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, kontrak $kontrak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\kontrak  $kontrak
     * @return \Illuminate\Http\Response
     */
    public function destroy(kontrak $kontrak)
    {
        //
    }

    public function upload(Request $request)
    {
        // Validasi file upload
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        // Ambil file CSV
        $file = $request->file('csv_file');

        // Parse CSV file
        $data = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($data);

        // Definisikan kolom statis yang sesuai dengan tabel 'kontrak'
        $staticColumns = [
            'Material' => 'material',
            'Material Description' => 'material_description',
            'UOM' => 'uom',
            'ABC' => 'abc',
            'MRP Type' => 'mrp_type',
            'MRP Contrl' => 'mrp_control',
            'PG' => 'pg',
            'MG' => 'mg',
            'ROP' => 'rop',
            'MAX' => 'max',
            'NO KONTRAK' => 'no_kontrak',
            'VALIDITY END' => 'validity_end',
            'PR KONTRAK' => 'pr_kontrak',
            'TGL PR' => 'tgl_pr',
        ];

        foreach ($data as $row) {
            $dataRow = array_combine($header, $row);

            $dbData = [];
            $qoh = [];

            foreach ($dataRow as $key => $value) {
                if (array_key_exists($key, $staticColumns)) {
                    if (in_array($key, ['VALIDITY END', 'TGL PR'])) {
                        // Handle date fields
                        $dbData[$staticColumns[$key]] = $value ? date('Y-m-d', strtotime($value)) : null;
                    } else {
                        $dbData[$staticColumns[$key]] = $value;
                    }
                } elseif (strpos($key, 'QOH') !== false) {
                    $qoh[$key] = $value;
                }
            }

            // Handle QOH as JSON
            $dbData['qoh'] = json_encode($qoh);

            // Create the record in the database
            Kontrak::create($dbData);
        }

        return redirect()->route('admin.kontrak.index')->with('success', 'CSV file has been uploaded and data inserted successfully.');
    }

    public function deleteAll()
    {
        // Hapus semua data dari tabels
        kontrak::truncate();

        // Redirect dengan pesan sukses
        return redirect()->route('admin.kontrak.index')->with('success', 'Semua data CSV telah dihapus.');
    }
}
