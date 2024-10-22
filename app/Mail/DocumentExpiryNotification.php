<?php

namespace App\Mail;

use App\Models\Dokumen; // Ganti dengan namespace model Anda
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentExpiryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $dokumen;

    public function __construct(Dokumen $dokumen)
    {
        $this->dokumen = $dokumen;
    }

    public function build()
    {
        return $this->subject('Pemberitahuan Kedaluwarsa Dokumen')
                    ->view('emails.document_expiry_notification');
    }
}