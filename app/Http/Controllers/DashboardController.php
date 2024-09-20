<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Models\Activity;
use App\Http\Requests\SettingRequest;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     *
     * @return \Illuminate\Http\Response
     */


    public function index(Request $request)
    {

        $prpo = Sheets::spreadsheet('1XXvzQIo2uXpu0CfUZUgYpfaNcrVkz5ZDVocU5p6Hfho')->sheet('PR-PO OUTSTANDING')->get();
        $header = $prpo->pull(0);
        $values = Sheets::collection($header, $prpo)->toArray();
        $statusrun = Sheets::spreadsheet('1XXvzQIo2uXpu0CfUZUgYpfaNcrVkz5ZDVocU5p6Hfho')->sheet('FAST MOVING')->get();
        $header = $statusrun->pull(0);
        $valuessr = Sheets::collection($header, $statusrun)->toArray();
        //trend
        // Ambil data dari Google Sheets
        $katA = Sheets::spreadsheet('1XXvzQIo2uXpu0CfUZUgYpfaNcrVkz5ZDVocU5p6Hfho')->sheet('Trend Kat-A')->get();

        // Ambil header (kolom) dan hapus dari data
        $header = $katA->shift();

        // Memastikan setiap baris memiliki jumlah elemen yang sama dengan header
        $valuesA = [];
        foreach ($katA as $row) {
            if (count($header) == count($row)) {
                // Buat array dari header dan row
                $rowData = array_combine($header, $row);

                // Ekstrak tahun dari kolom 'Bulan'
                $dateParts = explode(' ', $rowData['Bulan']); // Misalnya: ['1', 'Januari', '2024']
                $rowData['Tahun'] = end($dateParts); // Mengambil tahun sebagai elemen terakhir

                // Masukkan data ke dalam array
                $valuesA[] = $rowData;
            }
        }

        // Konversi ke collection untuk memudahkan manipulasi
        $trends = collect($valuesA);

        // Ambil semua tahun yang tersedia dari data untuk dropdown
        $availableYears = $trends->pluck('Tahun')->unique();

        // Filter data berdasarkan tahun (default tahun sekarang)
        $selectedYear = request()->input('year', date('Y'));
        $trendsByYear = $trends->where('Tahun', $selectedYear);

        // Kirim data ke view

        //ENDtrend

        // Mengambil semua data dari Statusrunningmaterial
        $collection = collect($valuessr);
        $totalRecords = $collection->count();
        $filledQOH = 0;
        $emptyQOH = 0;
        $cukupPR = 0;
        $cukupPO = 0;
        $cukupprpo = 0;
        $createPR = 0;
        // Array untuk mengelompokkan data berdasarkan kontrol MRP
        $dataByMRP = [
            'P02' => [
                'cukup_pr' => 0,
                'qoh_rop' => 0,
                'cukup_pr_po' => 0,
                'create_pr' => 0,
                'cukup_po' => 0,
            ],
            'P03' => [
                'cukup_pr' => 0,
                'qoh_rop' => 0,
                'cukup_pr_po' => 0,
                'create_pr' => 0,
                'cukup_po' => 0,
            ],
            'P04' => [
                'cukup_pr' => 0,
                'qoh_rop' => 0,
                'cukup_pr_po' => 0,
                'create_pr' => 0,
                'cukup_po' => 0,
            ],
            'P05' => [
                'cukup_pr' => 0,
                'qoh_rop' => 0,
                'cukup_pr_po' => 0,
                'create_pr' => 0,
                'cukup_po' => 0,
            ],
            'P06' => [
                'cukup_pr' => 0,
                'qoh_rop' => 0,
                'cukup_pr_po' => 0,
                'create_pr' => 0,
                'cukup_po' => 0,
            ],
        ];
        $year = 2024; // Tahun yang Anda inginkan
        $result = [];
        $totalPRYear = 0;
        $totalPOYear = 0;
        foreach ($valuessr as $data) {
            // Mengambil nilai dari kolom status_qoh
            $statusQOH = $data['STATUS QOH'] ?? 'N/A'; // Gunakan sintaks array
            // Log nilai status_qoh untuk debugging
            Log::info('Processing Status QOH Data:', ['STATUS QOH' => $statusQOH]);

            // Pastikan status_qoh tidak kosong dan tidak null
            if (!empty($statusQOH)) {
                // Menghitung jumlah QOH terisi dan kosong
                if (trim($statusQOH) === 'TERISI') {
                    $filledQOH++;
                    Log::info('QOH Filled:', ['STATUS QOH' => $statusQOH]);
                } elseif (trim($statusQOH) === 'KOSONG') {
                    $emptyQOH++;
                    Log::info('QOH Empty:', ['STATUS QOH' => $statusQOH]);
                }
            }
            // Menghitung jumlah CUKUP PR
            if (trim($data['STATUS RUNNING MRP'] ?? 'N/A') === 'CUKUP PR') {
                $cukupPR++;
                Log::info('CUKUP PR Counted:', ['STATUS RUNNING MRP' => $data['STATUS RUNNING MRP']]);
            }
            // Menghitung jumlah CUKUP PO
            if (trim($data['STATUS RUNNING MRP'] ?? 'N/A') === 'CUKUP PO') {
                $cukupPO++;
                Log::info('CUKUP PO Counted:', ['STATUS RUNNING MRP' => $data['STATUS RUNNING MRP']]);
            }
            // Menghitung jumlah CUKUP PR & PO
            if (trim($data['STATUS RUNNING MRP'] ?? 'N/A') === 'CUKUP PR & PO') {
                $cukupprpo++;
                Log::info('CUKUP PR & PO Counted:', ['STATUS RUNNING MRP' => $data['STATUS RUNNING MRP']]);
            }
            // Menghitung jumlah CREATE PO
            if (trim($data['STATUS RUNNING MRP'] ?? 'N/A') === 'CREATE PR') {
                $createPR++;
                Log::info('CREATE PR Counted:', ['STATUS RUNNING MRP' => $data['STATUS RUNNING MRP']]);
            }

            $mrpControl = $data['MRP Contrl'] ?? 'N/A';
            $statusRunningMrp = $data['STATUS RUNNING MRP'] ?? 'N/A';

            if (isset($dataByMRP[$mrpControl])) {
                switch (trim($statusRunningMrp)) {
                    case 'CUKUP PR':
                        $dataByMRP[$mrpControl]['cukup_pr']++;
                        break;
                    case 'QOH > ROP':
                        $dataByMRP[$mrpControl]['qoh_rop']++;
                        break;
                    case 'CUKUP PR & PO':
                        $dataByMRP[$mrpControl]['cukup_pr_po']++;
                        break;
                    case 'CREATE PR':
                        $dataByMRP[$mrpControl]['create_pr']++;
                        break;
                    case 'CUKUP PO':
                        $dataByMRP[$mrpControl]['cukup_po']++;
                        break;
                }
            }
        }
        $filledPercentage = $totalRecords > 0 ? round(($filledQOH / $totalRecords) * 100, 2) : 0;
        $emptyPercentage = $totalRecords > 0 ? round(($emptyQOH / $totalRecords) * 100, 2) : 0;

        $totalTable = [
            'P02' => array_sum($dataByMRP['P02']),
            'P03' => array_sum($dataByMRP['P03']),
            'P04' => array_sum($dataByMRP['P04']),
            'P05' => array_sum($dataByMRP['P05']),
            'P06' => array_sum($dataByMRP['P06']),
        ];
        //prpooutstanding
        $year = $request->input('year', date('Y')); // Default ke tahun saat ini
        $values = $values; // Ambil data dari sumber data (CSV, database, dll.)

        $result = [];
        $totalPRYear = 0;
        $totalPOYear = 0;

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            // Filter data berdasarkan tanggal PR
            $monthlyData = collect($values)->filter(function ($row) use ($startDate, $endDate) {
                if (isset($row['Tgl Create PR'])) {
                    try {
                        $tglCreatePr = Carbon::createFromFormat('d M Y', $row['Tgl Create PR']);
                        return $tglCreatePr->between($startDate, $endDate);
                    } catch (\Exception $e) {
                        return false;
                    }
                }
                return false;
            });

            $totalPR = $monthlyData->count();

            // Filter data PO berdasarkan 'PO RL1'
            $totalPO = collect($values)->filter(function ($row) use ($startDate, $endDate) {
                if (!empty($row['PO RL1'])) {
                    try {
                        $poRL1 = Carbon::parse($row['PO RL1']);
                        return $poRL1->between($startDate, $endDate);
                    } catch (\Exception $e) {
                        return false;
                    }
                }
                return false;
            })->count();

            $totalPRYear += $totalPR;
            $totalPOYear += $totalPO;

            $groupedData = $monthlyData->groupBy('MRP Controller')->map(function ($group) {
                return $group->count();
            });

            $result[] = [
                'month' => $startDate->format('F'),
                'P02' => $groupedData->get('P02', 0),
                'P03' => $groupedData->get('P03', 0),
                'P04' => $groupedData->get('P04', 0),
                'P05' => $groupedData->get('P05', 0),
                'P06' => $groupedData->get('P06', 0),
                'P62' => $groupedData->get('P62', 0),
                'totalPR' => $totalPR,
                'totalPO' => $totalPO
            ];
        }
        // Menghindari pembagian dengan nol
        // Mengembalikan data ke view
        return view('admin.dashboard', [
            'filledQoh' => $filledQOH,
            'filledPercentage' => $filledPercentage,
            'emptyQoh' => $emptyQOH,
            'emptyPercentage' => $emptyPercentage,
            'cukupPR' => $cukupPR,
            'cukupPO' => $cukupPO,
            'cukupprpo' => $cukupprpo,
            'createPR' => $createPR,
            'totalItem' => $totalRecords,
            'dataByMRP' => $dataByMRP,
            'totalTable' => $totalTable,
            'result' => $result,
            'totalPRYear' => $totalPRYear,
            'totalPOYear' => $totalPOYear,
            'year' => $year,
            'trendsByYear' => $trendsByYear,
            'selectedYear' => $selectedYear,
            'availableYears' => $availableYears
        ]);
    }
    /**
     * Show activity logs
     *
     * @return \Illuminate\Http\Response
     */
    public function activity_logs()
    {
        if (auth()->user()->getRoleNames()[0] == "Admin") {
            $logs = Activity::with('causer')->latest()->paginate(10);
        } else {
            $logs = Activity::with('causer')->where('causer_id', auth()->id())->latest()->paginate(10);
        }
        // dd($logs->first()->causer->username);
        // dd($logs);
        return view('admin.logs', compact('logs'));
    }

    /**
     * Store settings into database
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function settings_store(SettingRequest $request)
    {
        // when you upload a logo image
        if ($request->file('logo')) {
            $filename = $request->file('logo')->getClientOriginalName();
            $filePath = $request->file('logo')->storeAs('uploads', $filename, 'public');
            setting()->set('logo', $filePath);
        }
        setting()->set('site_name', $request->site_name);
        setting()->set('keyword', $request->keyword);
        setting()->set('description', $request->description);
        setting()->set('url', $request->url);
        // save all
        setting()->save();
        return redirect()->back()->with('success', 'Settings has been successfully saved');
    }

    /**
     * Update profile user
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function profile_update(Request $request)
    {
        $data = ['name' => $request->name];

        // if password want to change
        if ($request->old_password && $request->new_password) {
            // verify if password is match
            if (!Hash::check($request->old_password, auth()->user()->password)) {
                session()->flash('failed', 'Password is wrong!');
                return redirect()->back();
            }

            $data['password'] = Hash::make($request->new_password);
        }

        // for update avatar
        if ($request->avatar) {
            $data['avatar'] = $request->avatar;

            if (auth()->user()->avatar) {
                unlink(storage_path('app/public/' . auth()->user()->avatar));
            }
        }

        // update profile
        auth()->user()->update($data);

        return redirect()->back()->with('success', 'Profile updated!');
    }

    /**
     * Store avatar images into database
     *
     * @param $request
     * @return string
     */

    public function upload_avatar(Request $request)
    {

        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Validasi file gambar
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            // Hapus gambar lama jika ada
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Simpan gambar baru
            $path = $request->file('avatar')->store('avatar', 'public');

            // Simpan path ke database
            $user->avatar = $path;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }



    public function delete_logs()
    {
        $logs = Activity::where('created_at', '<=', Carbon::now()->subWeeks())->delete();

        return back()->with('success', $logs . ' Logs successfully deleted!');
    }
}
