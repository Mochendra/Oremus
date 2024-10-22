<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DokumenNotification extends Notification
{
    use Queueable;

    protected $dokumen;

    public function __construct($dokumen)
    {
        $this->dokumen = $dokumen;
    }

    public function via($notifiable)
    {
        return ['database']; // You can add 'mail' or other channels if needed
    }

    public function toDatabase($notifiable)
    {
        return [
            'dokumen_id' => $this->dokumen->id,
            'message' => 'Dokumen "' . $this->dokumen->no_registrasi . '" akan kedaluwarsa dalam waktu kurang dari sebulan.',
        ];
    }

    // Optional: If you still want to keep this method for some reason
    public function toArray($notifiable)
    {
        return [
            'dokumen_id' => $this->dokumen->id,
            'message' => 'Dokumen "' . $this->dokumen->no_registrasi . '" akan kedaluwarsa dalam waktu kurang dari sebulan.',
        ];
    }
}