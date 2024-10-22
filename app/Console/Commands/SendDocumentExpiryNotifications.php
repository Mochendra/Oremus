<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Dokumen;
use App\Mail\DocumentExpiryNotification; // Pastikan ini sesuai

class SendDocumentExpiryNotifications extends Command
{
    protected $signature = 'documents:notify-expiry';
    protected $description = 'Send notification for documents that will expire soon';

    public function handle()
    {
        $dokumens = Dokumen::where('expired', '<=', now()->addDays(30))->get();

        foreach ($dokumens as $dokumen) {
            // Mengambil email pemilik dokumen
            $email = $dokumen->user->email ?? 'Tidak Ada Email';
            \Log::info('Mengirim email ke: ' . $email);

            if ($email !== 'Tidak Ada Email') {
                // Gunakan Mailable yang benar
                Mail::to($email)->send(new DocumentExpiryNotification($dokumen));
            } else {
                $this->error('Tidak ada email yang ditemukan untuk dokumen: ' . $dokumen->no_dokumen);
            }
        }

        $this->info('Notifikasi email untuk dokumen yang akan kedaluwarsa telah dikirim.');
    }
}