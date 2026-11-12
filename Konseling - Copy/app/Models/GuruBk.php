<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class GuruBk extends Authenticatable
{
    protected $table = 'guru_bk';
    protected $primaryKey = 'id_guru';
    public $timestamps = false;

    protected $fillable = ['nip', 'nama', 'password', 'foto'];

    protected $hidden = ['password'];

    // Tabel ini tidak punya kolom remember_token (sesuai struktur soal)
    public function getRememberTokenName()
    {
        return '';
    }
}
