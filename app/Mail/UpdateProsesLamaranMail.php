<?php

namespace App\Mail;

use App\Models\Pelamar;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateProsesLamaranMail extends Mailable
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
        return $this->subject('Update Proses Lamaran PT. Tidarjaya Solidindo')
            ->view('emails.update_proses_lamaran');
    }
}
