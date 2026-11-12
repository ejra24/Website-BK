<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    public $timestamps = false;

    protected $fillable = ['username', 'nama', 'password'];

    protected $hidden = ['password'];

    // Tabel ini tidak punya kolom remember_token
    public function getRememberTokenName()
    {
        return '';
    }
}
