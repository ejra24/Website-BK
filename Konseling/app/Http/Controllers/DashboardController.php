<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function siswa(): View
    {
        // Ubah dari 'siswa.dashboard' menjadi 'dashboard'
        return view('dashboard');
    }

    public function guru(): View
    {
        return view('guru.dashboard');
    }

    public function admin(): View
    {
        return view('admin.dashboard');
    }
}