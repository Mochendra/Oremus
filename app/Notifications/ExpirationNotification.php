<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentExpiringSoon extends Notification
{
    use Queueable;

    protected $dokumen;

    public function __construct($dokumen)
    {
        $this->dokumen = $dokumen;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Notify via database and email
    }    

    public function toArray($notifiable)
    {
        return [
            'message' => 'Dokumen ' . $this->dokumen->no_registrasi . ' akan kadaluarsa pada ' . $this->dokumen->expired,
            'dokumen_id' => $this->dokumen->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Dokumen Kadaluarsa')
            ->line('Dokumen ' . $this->dokumen->no_registrasi . ' akan kadaluarsa pada ' . $this->dokumen->expired)
            ->action('Lihat Dokumen', url('/dokumen/' . $this->dokumen->id))
            ->line('Terima kasih!');
    }
}
