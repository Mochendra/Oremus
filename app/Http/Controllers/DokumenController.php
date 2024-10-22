<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\User; // Tambahkan import User
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;
use App\Notifications\ExpirationNotification;
use App\Notifications\DocumentExpiringSoon; // Make sure this path is correct
use Illuminate\Support\Facades\Notification; // If you're using Notification facade
use Illuminate\Notifications\DatabaseNotification;

class DokumenController extends Controller
{

    public function index()
    {
        // Mengambil data dokumen dan pengguna yang bukan admin
        $data = [
            'dokumens' => Dokumen::all(),  // atau query lain sesuai kebutuhan
            'users' => User::where('role', '!=', 'admin')->get(),  // Mengambil user yang bukan admin
    
            // Menampilkan notifikasi belum terbaca untuk admin yang sedang login
            'notifications' => auth()->user()->unreadNotifications
        ];
    
        return view('dashboard', $data);
    }
    

    public function dashboard()
    {
        $dokumens = Dokumen::all();
        return view('dashboard', compact('dokumens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_registrasi' => 'required',
            'no_registrasi' => 'required',
            'nama_perusahaan' => 'required',
            'jenis_dokumen' => 'required',
            'no_dokumen' => 'required',
            'wilayah_kerja' => 'required',
            'status_dokumen' => 'required',
            'tanggal_terbit' => 'required|date',
           'expired' => 'nullable|date', // Make expired optional
            'pdf_upload' => 'required|file|mimes:pdf',
        ]);
        
        // Logika untuk membuat nomor registrasi otomatis
        $year = date('Y');
        $month = date('m');

        // Dapatkan dokumen terakhir yang disimpan pada tahun dan bulan ini
        $lastDokumen = Dokumen::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        // Menentukan nomor urut berikutnya
        $nextNumber = $lastDokumen ? intval(substr($lastDokumen->no_registrasi_otomatis, -3)) + 1 : 1;
        $no_registrasi_otomatis = sprintf('REG/%s/%s/%03d', $year, $month, $nextNumber);

        // Store the uploaded PDF file in the public disk
        $filePath = $request->file('pdf_upload')->store('dokumen', 'public');

        // Create a new Dokumen record
        Dokumen::create([
            'no_registrasi_otomatis' => $no_registrasi_otomatis, // Menggunakan nomor registrasi otomatis
            'no_registrasi' => $request->no_registrasi,
            'nama_perusahaan' => $request->nama_perusahaan,
            'jenis_dokumen' => $request->jenis_dokumen,
            'no_dokumen' => $request->no_dokumen,
            'wilayah_kerja' => $request->wilayah_kerja,
            'status_dokumen' => $request->status_dokumen,
            'tanggal_terbit' => $request->tanggal_terbit,
            'expired' => $request->expired, // Set to null if not provided
            'pdf_upload' => $filePath,
            'user_id' => $request->user_id, // Menyimpan ID pengguna yang terautentikasi
            'created_by' => auth()->user()->name, // Menambahkan ini
            'pic' => auth()->user()->name, // Menambahkan ini
        ]);
        

        return redirect()->back()->with('success', 'Dokumen berhasil ditambahkan!');
    }

    public function view($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $pdfPath = storage_path('app/public/' . $dokumen->pdf_upload);

        \Log::info("Attempting to access PDF at: " . $pdfPath);

        if (!file_exists($pdfPath)) {
            \Log::error("PDF file not found at: " . $pdfPath);
            abort(404);
        }

        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($pdfPath) . '"'
        ]);
    }

    public function viewPdf($id)
    {
        \Log::info("Attempting to view PDF with ID: " . $id);
        return view('pdf-viewer', compact('id'));

        $dokumen->pic = auth()->id();
        $dokumen->save();
    }


    public function download($id)
    {
        \Log::info("Attempting to download document ID: " . $id);

        // Find the document by its ID
        $dokumen = Dokumen::findOrFail($id);
        \Log::info("Dokumen found: ", $dokumen->toArray());

        // Get the file path
        $filePath = $dokumen->pdf_upload;
        \Log::info("File path: " . $filePath); // Log the file path

        // Check if the file exists


        // Log the missing file situation
        \Log::error("File not found for path: " . $filePath);
        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function update(Request $request, $id)
{
    $dokumen = Dokumen::findOrFail($id);
    
    // Update document fields
    $dokumen->expired = $request->expired;
    $dokumen->pic = auth()->user()->name;
    $dokumen->save();

    // Check if the document's expiration date is within 30 days
    // $thirtyDaysFromNow = now()->addDays(30);
    // if ($dokumen->expired <= $thirtyDaysFromNow) {
    //     // Trigger notification if the expiration date is near
    //     $this->sendDocumentExpiryNotification($dokumen);
    // }

    return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diupdate');
}

    public function editExpired($id)
    {
        // Pastikan pengguna yang mengakses adalah admin
        if (auth()->user()->role != 'admin') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit.');
        }

        $dokumen = Dokumen::findOrFail($id);
        return view('dokumen.edit-expired', compact('dokumen'));
    }

    public function updateExpired(Request $request, $id)
    {
        if (auth()->user()->role != 'admin') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengupdate.');
        }

        $request->validate([
       'expired' => 'sometimes|nullable|date',
            'pic' => 'required|string|max:255', // Validasi untuk PIC
        ]);

        $dokumen = Dokumen::findOrFail($id);
        $dokumen->expired = $request->expired;
        $dokumen->pic = $request->pic; // Menyimpan PIC
        $dokumen->save();

        return redirect()->route('dashboard')->with('success', 'Expired date and PIC updated successfully.');
    }

    public function edit($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        return view('dokumen.edit-expired', compact('dokumen'));
    }
    public function create()
    {
        $users = User::all();
        return view('dokumen.create', compact('users'));
    }

    public function exportExcel()
    {
        $dokumens = Dokumen::all();

        $filePath = storage_path('dokumens.xlsx');

        $writer = SimpleExcelWriter::create($filePath)->addHeader([
            'No Registrasi Otomatis', 
            'No Registrasi', 
            'Nama Perusahaan', 
            'No Dokumen', 
            'Jenis Dokumen', 
            'Wilayah Kerja', 
            'Tanggal Terbit', 
            'Expired', 
            'Status Dokumen', 
            'PIC / Created By'
        ]);

        foreach ($dokumens as $dokumen) {
            $writer->addRow([
                $dokumen->no_registrasi_otomatis ?? 'Tidak Ada Data',
                $dokumen->no_registrasi,
                $dokumen->nama_perusahaan,
                $dokumen->no_dokumen,
                $dokumen->jenis_dokumen,
                $dokumen->wilayah_kerja,
                $dokumen->tanggal_terbit,
                $dokumen->expired,
                $dokumen->status_dokumen,
                $dokumen->pic ?? $dokumen->created_by ?? 'Tidak Diketahui'
            ]);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function showHistory()
    {
        $activities = Activity::with('subject', 'causer')
            ->where('log_name', 'dokumen')
            ->get();

        return view('dokumen.history', compact('activities'));  // Render document history view
    }

    public function notifikasi()
    {
        $today = now();
        $thirtyDaysFromNow = now()->addDays(30);
    
        $dokumens = Dokumen::where('expired', '<=', $thirtyDaysFromNow)
            ->where('notified', false)
            ->get();
    
        \Log::info('Found ' . $dokumens->count() . ' documents expiring soon');
    
        $admins = User::where('role', 'admin')->get();
        \Log::info('Found ' . $admins->count() . ' admin users');
    
        foreach ($dokumens as $dokumen) {
            \Log::info('Processing document: ' . $dokumen->id);
            foreach ($admins as $admin) {
                \Log::info('Sending notification to admin: ' . $admin->email . ' for document: ' . $dokumen->id);
                $admin->notify(new DocumentExpiringSoon($dokumen));
            }
    
            $dokumen->notified = true;
            $dokumen->save();
        }
    
        notify()->success('Notifikasi dokumen yang akan kadaluarsa berhasil dikirim!', 'Sukses');
    
        return redirect()->back();
    }
    
    

    public function showNotification($id)
{
    // Retrieve the specific notification
    $notification = auth()->user()->notifications()->findOrFail($id);
    
    // Mark the notification as read
    $notification->markAsRead();

    // Redirect to the relevant page (customize this to your needs)
    return redirect()->route('documents.show', $notification->data['document_id']); // Assuming your notification contains a document ID

}

public function markAsRead($id)
{
    $notification = DatabaseNotification::findOrFail($id);
    $notification->markAsRead();

    return response()->json(['success' => true]);
}


public function sendDocumentExpiryNotification(Dokumen $dokumen)
{
    // Ambil semua admin
    $admins = User::where('role', 'admin')->get();

    // Loop melalui setiap admin dan kirim notifikasi
    foreach ($admins as $admin) {
        \Log::info('Sending notification to admin: ' . $admin->email); // Log untuk debug
        $admin->notify(new DocumentExpiringSoon($dokumen));
    }
}
}

