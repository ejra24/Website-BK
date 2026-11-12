<?php

/*
 | Konfigurasi autentikasi dengan 3 "guard": siswa, guru, admin.
 | Guard 'web' bawaan Laravel dibiarkan (tabel users), tidak dipakai aplikasi BK.
 */

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'siswa' => [
            'driver' => 'session',
            'provider' => 'siswa',
        ],
        'guru' => [
            'driver' => 'session',
            'provider' => 'guru',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],
        'siswa' => [
            'driver' => 'eloquent',
            'model' => App\Models\Siswa::class,
        ],
        'guru' => [
            'driver' => 'eloquent',
            'model' => App\Models\GuruBk::class,
        ],
        'admin' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
