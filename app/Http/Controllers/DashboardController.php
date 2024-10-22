<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use App\Models\User; // Import model User

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user() && (auth()->user()->role == 'admin' || auth()->user()->role == 'user')) {
        // Fetch all Dokumen data
        $dokumens = Dokumen::all();

        // Ambil semua pengguna dengan role 'user'
        $users = User::where('role', 'user')->get();


        // Pass the data to the 'dashboard' view
        return view('dashboard', ['dokumens' => $dokumens, 'users' => $users]);
    }}
    
}
