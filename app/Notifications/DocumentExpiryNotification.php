<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentExpiryNotification extends Notification
{
    use Queueable;

    protected $document;

    public function __construct($document)
    {
        $this->document = $document;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pemberitahuan Kedaluwarsa Dokumen')
            ->line('Dokumen Anda akan kedaluwarsa dalam waktu 30 hari.')
            ->line('No. Registrasi: ' . $this->document->no_registrasi)
            ->line('Nama Perusahaan: ' . $this->document->nama_perusahaan)
            ->line('Tanggal Expired: ' . \Carbon\Carbon::parse($this->document->expired)->format('d-m-Y'))
            ->action('Lihat Dokumen', route('dokumen.view-pdf', $this->document->id))
            ->line('Terima kasih telah menggunakan layanan kami!');
    }
}
    