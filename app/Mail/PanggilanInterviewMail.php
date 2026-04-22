<?php

namespace App\Mail;

use App\Models\Pelamar;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PanggilanInterviewMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pelamar $pelamar;
    public string $pesan;

    public function __construct(Pelamar $pelamar, string $pesan)
    {
        $this->pelamar = $pelamar;
        $this->pesan = $pesan;
    }

    public function build()
    {
        return $this->subject('Panggilan Rekrutmen PT. Tidarjaya Solidindo')
            ->view('emails.panggilan_interview');
    }
}
