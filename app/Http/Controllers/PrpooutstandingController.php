<?php

namespace App\Http\Controllers;

use App\Models\Prpooutstanding;
use Illuminate\Http\Request;
use Revolution\Google\Sheets\Facades\Sheets;

class PrpooutstandingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = Prpooutstanding::all();
        // return view('admin.pr-po-outstanding.index', compact('data')); 
        $prpo = Sheets::spreadsheet('1XXvzQIo2uXpu0CfUZUgYpfaNcrVkz5ZDVocU5p6Hfho')->sheet('PR-PO OUTSTANDING')->get();
        $header = $prpo->pull(0);
        $values = Sheets::collection($header, $prpo);
        return view('admin.pr-po-outstanding.index', ['data' => $values->toArray()]);
        // $sheetdb = new SheetDB('ofe4ueryhx83u');
        // $data = $sheetdb->get(); // Ganti 'SheetName' dengan nama sheet yang sesuai
        // return view('admin.pr-po-outstanding.index', compact('data'));
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
     * @param  \App\Models\Prpooutstanding  $prpooutstanding
     * @return \Illuminate\Http\Response
     */
    public function show(Prpooutstanding $prpooutstanding)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prpooutstanding  $prpooutstanding
     * @return \Illuminate\Http\Response
     */
    public function edit(Prpooutstanding $prpooutstanding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prpooutstanding  $prpooutstanding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prpooutstanding $prpooutstanding)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prpooutstanding  $prpooutstanding
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prpooutstanding $prpooutstanding)
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

        // Definisikan kolom statis
        $staticColumns = [
            'No' => 'no',
            'Material No' => 'material_no',
            'Description' => 'description',
            'No PR' => 'no_pr',
            'Jenis PR' => 'jenis_pr',
            'Tgl Create PR' => 'tgl_create_pr',
            'Quantity PR' => 'quantity_pr',
            'Satuan PR' => 'satuan_pr',
            'MRP Controller' => 'mrp_controller',
            'Purchasing Group' => 'purchasing_group',
            'Nilai PO' => 'nilai_po',
            'Nomor PO' => 'nomor_po',
            'PO RL1' => 'po_rl1',
            'Tgl Supply' => 'tgl_supply',
            'STATUS' => 'status',
        ];

        foreach ($data as $row) {
            $dataRow = array_combine($header, $row);
            $dbData = [];

            foreach ($dataRow as $key => $value) {
                if (array_key_exists($key, $staticColumns)) {
                    $field = $staticColumns[$key];

                    // Handle specific date fields using strtotime
                    if (in_array($key, ['Tgl Create PR', 'PO RL1', 'Tgl Supply'])) {
                        $dbData[$staticColumns[$key]] = $value ? date('Y-m-d', strtotime($value)) : null;
                    } else {
                        // Handle other non-date fields
                        $dbData[$field] = !empty($value) ? $value : null;
                    }
                }
            }

            // Ensure `nomor_po` and `tgl_supply` are not null
            $dbData['nomor_po'] = !empty($dbData['nomor_po']) ? $dbData['nomor_po'] : '0'; // Set default nomor_po
            $dbData['tgl_supply'] = !empty($dbData['tgl_supply']) ? $dbData['tgl_supply'] : date('Y-m-d'); // Set default tgl_supply jika kosong

            // Create or update record in database
            Prpooutstanding::updateOrCreate(
                ['material_no' => $dbData['material_no'], 'no_pr' => $dbData['no_pr']], // Use a unique identifier or combination
                $dbData
            );
        }

        return redirect()->route('admin.pr-po-outstanding.index')->with('success', 'CSV file has been uploaded and data inserted successfully.');
    }


    public function deleteAll()
    {
        // Hapus semua data dari tabel
        Prpooutstanding::truncate();

        // Redirect dengan pesan sukses
        return redirect()->route('admin.pr-po-outstanding.index')->with('success', 'Semua data CSV telah dihapus.');
    }
}
