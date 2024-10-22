<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dokumen; 
use App\Notifications\DokumenNotification; // Use your existing notification class
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class NotifyDocumentExpiration extends Command
{
    protected $signature = 'notify:document-expiration';
    protected $description = 'Notify admin 30 days before document expiration';

    public function handle()
    {
        // Fetch documents expiring in 30 days or less that have not been notified yet
        $documents = Dokumen::where('expired', '<=', Carbon::now()->addDays(30))
                            ->where('notified', false)
                            ->get();

        foreach ($documents as $document) {
            // Send notification to the user/admin
            Notification::send($document->user, new DokumenNotification($document));

            // Mark the document as notified
            $document->notified = true;
            $document->save();
        }

        $this->info('Notifications sent for documents expiring in less than 30 days.');
    }
}