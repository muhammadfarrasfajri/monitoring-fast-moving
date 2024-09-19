<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExpiringContractNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Revolution\Google\Sheets\Facades\Sheets;

class NotifyExpiringContracts extends Command
{
    protected $signature = 'notify:expiring-contracts';
    protected $description = 'Send notification emails for contracts expiring in less than 3 months';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('NotifyExpiringContracts command started');

        // Ambil data dari Google Sheets
        $kontrak = Sheets::spreadsheet('1XXvzQIo2uXpu0CfUZUgYpfaNcrVkz5ZDVocU5p6Hfho')->sheet('Kontrak')->get();
        $header = $kontrak->pull(0); // Ambil header
        $kontraks = Sheets::collection($header, $kontrak)->toArray(); // Konversi ke array
        // Tanggal 3 bulan ke depan
        $threeMonthsLater = Carbon::now()->addMonths(3);
        $recipients = ['farrasfajri@gmail.com'];
        // Debug: Tampilkan header dan baris pertama untuk memeriksa struktur data
        Log::info('Header: ' . json_encode($header));
        if (!empty($kontraks)) {
            foreach ($kontraks as $index => $row) {
                Log::info('Row ' . $index . ': ' . json_encode($row));

                // Periksa apakah kolom VALIDITY END ada dan tidak kosong
                if (isset($row[14]) && !empty($row[14])) {
                    try {
                        // Ambil nilai 'VALIDITY END' dan parse sebagai tanggal
                        $validityEnd = Carbon::parse($row[14]);

                        // Cek apakah kontrak akan berakhir dalam 3 bulan ke depan
                        if ($validityEnd <= $threeMonthsLater) {
                            // Kirim email notifikasi
                            Mail::to($recipients)->send(new ExpiringContractNotification($row));
                            Log::info("Notification sent for contract ID: {$row['No']}.");
                        }
                    } catch (\Exception $e) {
                        Log::error('Error parsing date for row ' . $index . ': ' . $e->getMessage());
                    }
                } else {
                    Log::info('Column index 14 does not have data in row ' . $index);
                }
            }
        } else {
            Log::info('No data found.');
        }
        Log::info('NotifyExpiringContracts command finished');
    }
}
