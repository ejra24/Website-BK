<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel aplikasi BK sesuai soal projek:
 * siswa, guru_bk, konseling, riwayat_konseling
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $t) {
            $t->increments('id_admin');
            $t->string('username', 50)->unique();
            $t->string('nama', 100);
            $t->string('password', 255);
        });

        Schema::create('siswa', function (Blueprint $t) {
            $t->increments('id_siswa');
            $t->string('nis', 20)->unique();      // Nomor induk siswa
            $t->string('nama', 100);
            $t->string('kelas', 20);               // contoh: X RPL 1
            $t->string('jurusan', 50);
            $t->string('password', 255);
            $t->string('foto', 255)->nullable();
        });

        Schema::create('guru_bk', function (Blueprint $t) {
            $t->increments('id_guru');
            $t->string('nip', 20)->unique();       // Nomor induk pegawai
            $t->string('nama', 100);
            $t->string('password', 255);
            $t->string('foto', 255)->nullable();
        });

        Schema::create('konseling', function (Blueprint $t) {
            $t->increments('id_konseling');
            $t->unsignedInteger('id_siswa');
            $t->unsignedInteger('id_guru');
            $t->text('masalah');                   // permasalahan siswa
            $t->text('solusi')->nullable();        // rekomendasi / solusi
            $t->date('tanggal');
            $t->enum('status', ['terjadwal', 'selesai', 'batal'])->default('terjadwal');

            $t->foreign('id_siswa')->references('id_siswa')->on('siswa')->cascadeOnDelete();
            $t->foreign('id_guru')->references('id_guru')->on('guru_bk')->restrictOnDelete();
        });

        Schema::create('riwayat_konseling', function (Blueprint $t) {
            $t->increments('id_riwayat');
            $t->unsignedInteger('id_konseling');
            $t->date('tanggal');                   // tanggal update
            $t->text('catatan');                   // catatan lanjutan dari guru BK
            $t->unsignedInteger('guru');           // FK -> guru_bk.id_guru

            $t->foreign('id_konseling')->references('id_konseling')->on('konseling')->cascadeOnDelete();
            $t->foreign('guru')->references('id_guru')->on('guru_bk')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_konseling');
        Schema::dropIfExists('konseling');
        Schema::dropIfExists('guru_bk');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('admin');
    }
};
