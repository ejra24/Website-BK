<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - E-Konseling SMKN 7 Samarinda</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: linear-gradient(160deg, #0B2447 0%, #19376D 60%, #2b5aa8 100%);
        }
        .text-navy { color: #0B2447; }
        .text-gold { color: #FFD700; }
        .kartu-login {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 24px;
            padding: 40px 32px 32px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, .3);
        }
        .btn-gold { background: #FFD700; color: #0B2447; font-weight: 700; border: 0; }
        .btn-gold:hover, .btn-gold:focus-visible { background: #e6c200; color: #0B2447; }
        .btn-gold:disabled { background: #FFD700; color: #0B2447; opacity: .7; }
        .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .15); }
        .input-group-text { background: #f8f9fa; color: #6c757d; }
    </style>
</head>
<body>

    <main class="w-100 d-flex flex-column align-items-center">
        <a href="{{ url('/') }}?langsung" class="text-white fw-bold fs-4 mb-4 text-decoration-none">
            <span class="text-gold">E-</span>Konseling SMKN 7
        </a>

        <div class="kartu-login">
            <h1 class="h3 fw-bolder text-navy mb-1">Masuk</h1>
            <p class="text-secondary small mb-4">Siswa masuk dengan NISN, guru dengan NIP. Kamu akan diarahkan ke halamanmu secara otomatis.</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small" role="alert">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.proses') }}" id="formLogin">
                @csrf

                <label for="nomor" class="form-label fw-semibold small">NISN / NIP</label>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                    <input type="text" id="nomor" name="nomor" value="{{ old('nomor') }}"
                           class="form-control form-control-lg" inputmode="numeric"
                           autocomplete="username" required autofocus>
                </div>

                <label for="password" class="form-label fw-semibold small">Password</label>
                <div class="input-group mb-4">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" id="password" name="password"
                           class="form-control form-control-lg" autocomplete="current-password" required>
                    <button class="btn btn-outline-secondary" type="button" id="lihat" aria-label="Tampilkan password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                <button type="submit" id="tombol" class="btn btn-gold btn-lg rounded-pill w-100">Masuk</button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ url('/') }}?langsung" class="small text-decoration-none text-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke beranda
                </a>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('lihat').addEventListener('click', function () {
            var p = document.getElementById('password');
            var tampil = p.type === 'password';
            p.type = tampil ? 'text' : 'password';
            this.querySelector('i').className = tampil ? 'bi bi-eye-slash' : 'bi bi-eye';
            this.setAttribute('aria-label', tampil ? 'Sembunyikan password' : 'Tampilkan password');
        });
        document.getElementById('formLogin').addEventListener('submit', function () {
            var b = document.getElementById('tombol');
            b.disabled = true;
            b.textContent = 'Memeriksa…';
        });
    </script>
</body>
</html>