<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Halaman utama (intro animasi + landing)
Route::get('/', fn () => view('animasi-sekolah'))->name('beranda');

// Login siswa + guru BK (satu form)
Route::get('/login', [AuthController::class, 'form'])->name('login');
Route::post('/login', [AuthController::class, 'masuk'])->name('login.proses');

// Login admin (form terpisah, tidak ditautkan di halaman publik)
Route::get('/admin/login', [AuthController::class, 'formAdmin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'masukAdmin'])->name('admin.login.proses');

// Logout (semua peran)
Route::post('/logout', [AuthController::class, 'keluar'])->name('logout');

// Halaman setelah login (sementara), masing-masing dijaga guard-nya
Route::get('/siswa', [DashboardController::class, 'siswa'])->middleware('auth:siswa')->name('siswa.dashboard');
Route::get('/guru', [DashboardController::class, 'guru'])->middleware('auth:guru')->name('guru.dashboard');
Route::get('/admin', [DashboardController::class, 'admin'])->middleware('auth:admin')->name('admin.dashboard');
