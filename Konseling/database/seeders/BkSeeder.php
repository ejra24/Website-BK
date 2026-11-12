<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\GuruBk;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Data contoh untuk mencoba login.
 * Jalankan: php artisan db:seed --class=BkSeeder
 *
 * Aturan password:
 *  - Siswa  : username = NISN, password awal = NISN yang sama
 *  - Guru BK: username = NIP,  password milik guru sendiri
 *  - Admin  : username + password sendiri (login di /admin/login)
 * GANTI sebelum dipakai sungguhan!
 */
class BkSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['username' => 'admin'],
            ['nama' => 'Administrator', 'password' => Hash::make('admin123')]
        );

        $guru = [
            ['198001012005011001', 'Pak Hanafi'],
            ['198203152006041002', 'Pak Desnya'],
            ['198507202009032003', 'Bu April'],
            ['198809102012011004', 'Pak Alam'],
        ];
        foreach ($guru as [$nip, $nama]) {
            GuruBk::updateOrCreate(
                ['nip' => $nip],
                ['nama' => $nama, 'password' => Hash::make('guru123')]
            );
        }

        $siswa = [
            ['2425001', 'Contoh Siswa Satu', 'X RPL 1', 'Rekayasa Perangkat Lunak'],
            ['2425002', 'Contoh Siswa Dua', 'XI TKJ 2', 'Teknik Komputer dan Jaringan'],
        ];
        foreach ($siswa as [$nis, $nama, $kelas, $jurusan]) {
            Siswa::updateOrCreate(
                ['nis' => $nis],
                ['nama' => $nama, 'kelas' => $kelas, 'jurusan' => $jurusan, 'password' => Hash::make($nis)]
            );
        }
    }
}
