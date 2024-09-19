<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiringContractNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $row;

    public function __construct($row)
    {
        $this->row = $row;
    }

    public function build()
    {
        return $this->view('emails.expiring_contract') // Pastikan ini sesuai dengan file view yang Anda buat
            ->with(['row' => $this->row]);
    }
}
