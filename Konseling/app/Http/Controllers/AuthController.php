<?php

namespace App\Http\Controllers;

use App\Models\GuruBk;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    private const PERAN = ['siswa', 'guru', 'admin'];
    private const MAKS_PERCOBAAN = 5;
    private const KUNCI_DETIK = 60;

    /** Halaman login siswa + guru */
    public function form(): View|RedirectResponse
    {
        if ($peran = $this->peranAktif()) {
            return redirect()->route($peran . '.dashboard');
        }

        return view('login');
    }

    /** Halaman login admin */
    public function formAdmin(): View|RedirectResponse
    {
        if ($peran = $this->peranAktif()) {
            return redirect()->route($peran . '.dashboard');
        }

        return view('admin.login');
    }

    /** Proses login siswa (NIS) atau guru (NIP) lewat satu form */
    public function masuk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nomor'    => ['required', 'string', 'max:30'],
            'password' => ['required', 'string'],
        ], [
            'nomor.required'    => 'NISN atau NIP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $nomor = trim($data['nomor']);
        $kunci = 'masuk|' . Str::lower($nomor) . '|' . $request->ip();
        $this->cekBatas($kunci, 'nomor');

        // Nomor ini milik siapa? siswa (kolom nis) atau guru (kolom nip)
        $guard = null;
        $kolom = null;
        if (Siswa::where('nis', $nomor)->exists()) {
            $guard = 'siswa';
            $kolom = 'nis';
        } elseif (GuruBk::where('nip', $nomor)->exists()) {
            $guard = 'guru';
            $kolom = 'nip';
        }

        if (! $guard || ! Auth::guard($guard)->attempt([$kolom => $nomor, 'password' => $data['password']])) {
            RateLimiter::hit($kunci, self::KUNCI_DETIK);

            return back()
                ->withInput($request->only('nomor'))
                ->withErrors(['nomor' => 'NISN/NIP atau password salah.']);
        }

        return $this->berhasil($request, $guard, $kunci);
    }

    /** Proses login admin (username) */
    public function masukAdmin(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'identitas' => ['required', 'string', 'max:50'],
            'password'  => ['required', 'string'],
        ], [
            'identitas.required' => 'Username wajib diisi.',
            'password.required'  => 'Password wajib diisi.',
        ]);

        $username = trim($data['identitas']);
        $kunci = 'admin|' . Str::lower($username) . '|' . $request->ip();
        $this->cekBatas($kunci, 'identitas');

        if (! Auth::guard('admin')->attempt(['username' => $username, 'password' => $data['password']])) {
            RateLimiter::hit($kunci, self::KUNCI_DETIK);

            return back()
                ->withInput($request->only('identitas'))
                ->withErrors(['identitas' => 'Username atau password salah.']);
        }

        return $this->berhasil($request, 'admin', $kunci);
    }

    /** Keluar dari semua peran */
    public function keluar(Request $request): RedirectResponse
    {
        foreach (self::PERAN as $g) {
            Auth::guard($g)->logout();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /* ------------------------------------------------------------------ */

    private function berhasil(Request $request, string $guard, string $kunci): RedirectResponse
    {
        RateLimiter::clear($kunci);
        $request->session()->regenerate();

        // Hanya satu peran yang aktif di browser ini
        foreach (self::PERAN as $g) {
            if ($g !== $guard) {
                Auth::guard($g)->logout();
            }
        }

        return redirect()->route($guard . '.dashboard');
    }

    private function cekBatas(string $kunci, string $field): void
    {
        if (RateLimiter::tooManyAttempts($kunci, self::MAKS_PERCOBAAN)) {
            $detik = RateLimiter::availableIn($kunci);

            throw ValidationException::withMessages([
                $field => "Terlalu banyak percobaan. Coba lagi dalam {$detik} detik.",
            ]);
        }
    }

    private function peranAktif(): ?string
    {
        foreach (self::PERAN as $g) {
            if (Auth::guard($g)->check()) {
                return $g;
            }
        }

        return null;
    }
}