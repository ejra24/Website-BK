<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function siswa(): View
    {
        return view('siswa.dashboard');
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