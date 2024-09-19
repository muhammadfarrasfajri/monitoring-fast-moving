<?php

namespace App\Http\Controllers;

use App\Models\Statusrunningmaterial;
use Illuminate\Http\Request;
use Revolution\Google\Sheets\Facades\Sheets;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yajra\DataTables\Facades\DataTables;


class StatusrunningmaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statusrun = Sheets::spreadsheet('1XXvzQIo2uXpu0CfUZUgYpfaNcrVkz5ZDVocU5p6Hfho')->sheet('FAST MOVING')->get();
        $header = $statusrun->pull(0);
        $values = Sheets::collection($header, $statusrun);
        return view('admin.status-running-material.index', ['data' => $values->toArray()]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mail = new PHPMailer(true);

        try {
            // Ambil data dari Google Sheets
            $data = Sheets::spreadsheet('1XXvzQIo2uXpu0CfUZUgYpfaNcrVkz5ZDVocU5p6Hfho')->sheet('FAST MOVING')->get();
            // Konversi data ke format Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Isi data ke dalam sheet Excel
            foreach ($data as $rowIndex => $row) {
                foreach ($row as $colIndex => $value) {
                    $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $value);
                }
            }
            // Simpan file Excel sementara
            $filePath = 'google_sheet_data.xlsx';
            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);

            // Konfigurasi server email
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Port = env('MAIL_PORT');

            // Penerima
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

            // Memproses banyak email
            $emails = explode(',', $request->emails); // Pecah string email berdasarkan koma

            foreach ($emails as $email) {
                $email = trim($email); // Hapus spasi ekstra

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) { // Validasi setiap email
                    $mail->addAddress($email);
                }
            }

            // Isi email
            $mail->isHTML(true);
            $mail->Subject = 'Data Baru Saja Di Update';
            $mail->Body = 'Sehubungan dengan pemenuhan kebutuhan material fast moving sparepart & lube oil yang harus terjaga ketersediaannya, bersama email ini telah dilakukan running MRP rutin dan kami lampirkan daftar material untuk dilakukan penerbitan PR.<br>
Untuk penerbitan PR harap mempertimbangkan :<br>
1. Quantity of Requirement (QOR) tetap mangacu pada nilai Max atau ROP < QOR < MAX dan tetap memperhatikan status pada tcode MD04.<br>
2. Item yang memiliki masa atau waktu penyimpanan dan jika disimpan terlalu lama dapat menyebabkan kerusakan atau penurunan kualitas harap dapat memperhatikan histori pemakaian.<br>
3. Item yang tercukupi PR/PO, harap berkoordinasi dengan unit kerja terkait untuk kelanjutan proses pengadaannya<br>
Untuk penerbitan PR jika dibutuhkan dasar penerbitan PR maka dapat mengacu pada email ini atau surat DOF.<br><br>
Demikian kami sampaikan, atas perhatian dan kerja samanya diucapkan terima kasih.<br><br>
<b>R.H. Victor Gusmara</b><br>
<b>Sr. Officer Evtek, Kataloging & Perputaran Persediaan</b>';
            // Lampirkan file Excel
            $mail->addAttachment($filePath, 'MonitoringFastMoving.xlsx');
            // Kirim email
            if ($mail->send()) {
                // Hapus file lokal setelah berhasil dikirim
                unlink($filePath);
                return back()->with('success', 'Email has been sent successfully.');
            } else {
                return back()->with('error', 'Email not sent. ' . $mail->ErrorInfo);
            }
        } catch (Exception $e) {
            return back()->with('error', 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }
    }


    public function downloadExcel()
    {
        try {
            // Ambil data dari Google Sheets
            $data = Sheets::spreadsheet('1XXvzQIo2uXpu0CfUZUgYpfaNcrVkz5ZDVocU5p6Hfho')->sheet('FAST MOVING')->get();

            // Konversi data ke format Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Isi data ke dalam sheet Excel
            foreach ($data as $rowIndex => $row) {
                foreach ($row as $colIndex => $value) {
                    $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $value);
                }
            }

            // Simpan file Excel sementara di memori
            $writer = new Xlsx($spreadsheet);
            $fileName = 'MonitoringFastMoving.xlsx';

            // Simpan file Excel ke output response
            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $fileName);
        } catch (\Exception $e) {
            return back()->with('error', 'Download failed: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Statusrunningmaterial  $statusrunningmaterial
     * @return \Illuminate\Http\Response
     */
    public function show(Statusrunningmaterial $statusrunningmaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Statusrunningmaterial  $statusrunningmaterial
     * @return \Illuminate\Http\Response
     */
    public function edit(Statusrunningmaterial $statusrunningmaterial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Statusrunningmaterial  $statusrunningmaterial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Statusrunningmaterial $statusrunningmaterial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Statusrunningmaterial  $statusrunningmaterial
     * @return \Illuminate\Http\Response
     */
    public function destroy(Statusrunningmaterial $statusrunningmaterial)
    {
        //
    }

    public function calculateQOHPercentage()
    {
        // Mengambil semua data dari tabel statusrunningmaterial
        $allData = Statusrunningmaterial::all();

        $totalRecords = $allData->count();
        $filledQOH = 0;

        foreach ($allData as $data) {
            $qohData = json_decode($data->qoh, true);

            // Memeriksa apakah ada data QOH yang terisi
            foreach ($qohData as $value) {
                if (!empty($value)) {
                    $filledQOH++;
                    break;
                }
            }
        }
        $emptyQOH = $totalRecords - $filledQOH;

        $filledPercentage = ($filledQOH / $totalRecords) * 100;
        $emptyPercentage = ($emptyQOH / $totalRecords) * 100;

        return response()->json([
            'filledPercentage' => $filledPercentage,
            'emptyPercentage' => $emptyPercentage,
        ]);
    }
}
