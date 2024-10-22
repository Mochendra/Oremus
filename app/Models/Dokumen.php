<?php

namespace App\Models;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions; 
use Carbon\Carbon;

class Dokumen extends Model
{
    use HasFactory;
    use HasFactory, LogsActivity;

    protected $fillable = [
        'no_registrasi_otomatis',
        'no_registrasi',
        'nama_perusahaan',
        'jenis_dokumen',
        'no_dokumen',
        'wilayah_kerja',
        'status_dokumen',
        'tanggal_terbit',
        'expired',
        'pdf_upload',
        'notified', // Include this if you added the 'notified' column
        'last_editor_id', // Tambahkan ini
        'user_id', // Tambahkan user_id ke fillable
        'pic',  // Tambahkan ini
        'created_by'  // Tambahkan ini
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lastEditor()
    {
        return $this->belongsTo(User::class, 'last_editor_id');
    }

    // Mengimplementasikan getActivitylogOptions
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Log semua atribut
            ->logOnlyDirty() // Hanya log perubahan
            ->useLogName('dokumen'); // Nama log
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Dokumen telah {$eventName}";
    }

//     public function isExpired()
// {
//     return Carbon::parse($this->expired)->isPast();
// }

// public function isExpiringSoon()
// {
//     return Carbon::parse($this->expired)->isFuture() &&
//            Carbon::parse($this->expired)->diffInDays(now()) <= 30;
// }

// public function getStatusClass()
// {
//     if ($this->isExpired()) {
//         return 'expired-warning';
//     } elseif ($this->isExpiringSoon()) {
//         return 'expiring-soon';
//     }
//     return '';
// }
}
