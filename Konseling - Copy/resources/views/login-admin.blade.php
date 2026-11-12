<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Login Admin - E-Konseling SMKN 7</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root { --navy: #0B2447; --gold: #FFD700; --biru: #0d6efd; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh; min-height: 100dvh;
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
            background: radial-gradient(circle at 20% 15%, #17458a 0%, #0B2447 45%, #07172e 100%);
        }

        .kartu-admin {
            width: 100%; max-width: 420px; background: #fff; border-radius: 24px;
            padding: 2.25rem 2rem; box-shadow: 0 25px 60px rgba(0, 0, 0, .35);
        }
        .lencana {
            width: 64px; height: 64px; margin: -3.4rem auto 1rem; border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            background: var(--gold); color: var(--navy); font-size: 1.9rem;
            box-shadow: 0 12px 28px rgba(255, 215, 0, .4);
        }
        .kartu-admin h1 { font-weight: 800; color: var(--navy); font-size: 1.6rem; text-align: center; }
        .kartu-admin .sub { color: #6c757d; text-align: center; font-size: .9rem; }

        .form-label { font-weight: 600; color: var(--navy); }
        .input-group-text { background: #f8f9fa; color: #6c757d; }
        .form-control:focus, .btn:focus-visible { box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .2); border-color: var(--biru); }

        .btn-admin {
            background: var(--navy); color: #fff; font-weight: 700; border: 0; border-radius: 12px; padding: .8rem 1rem;
            transition: background-color .2s, transform .2s;
        }
        .btn-admin:hover { background: #123a73; color: #fff; transform: translateY(-2px); }
        .btn-admin:disabled { opacity: .75; transform: none; }

        .tautan { text-align: center; margin-top: 1.5rem; font-size: .85rem; }
        .tautan a { color: #6c757d; text-decoration: none; font-weight: 600; }
        .tautan a:hover { color: var(--biru); }
    </style>
</head>
<body>

    <div class="kartu-admin mt-4">
        <div class="lencana"><i class="bi bi-shield-lock-fill"></i></div>

        <h1 class="mb-1">Login Admin</h1>
        <p class="sub mb-4">Area khusus pengelola sistem E-Konseling.</p>

        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-start gap-2 py-2 small" role="alert" aria-live="polite">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.proses') }}" id="formAdmin" novalidate>
            @csrf

            <div class="mb-3">
                <label for="identitas" class="form-label">Username</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text"><i class="bi bi-person-gear"></i></span>
                    <input type="text" id="identitas" name="identitas" value="{{ old('identitas') }}"
                           class="form-control {{ $errors->any() ? 'is-invalid' : '' }}"
                           placeholder="Masukkan username" autocomplete="username" autocapitalize="off" spellcheck="false" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" id="password" name="password"
                           class="form-control {{ $errors->any() ? 'is-invalid' : '' }}"
                           placeholder="Masukkan password" autocomplete="current-password" required>
                    <button class="btn btn-outline-secondary" type="button" id="lihatPassword" aria-label="Tampilkan password" aria-pressed="false">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-admin w-100" id="tombolMasuk">Masuk sebagai Admin</button>
        </form>

        <div class="tautan">
            <a href="{{ route('login') }}"><i class="bi bi-arrow-left me-1"></i> Login siswa / guru BK</a>
        </div>
    </div>

    <script>
        (function () {
            var pw = document.getElementById('password');
            var tombolMata = document.getElementById('lihatPassword');
            tombolMata.addEventListener('click', function () {
                var tampil = pw.type === 'password';
                pw.type = tampil ? 'text' : 'password';
                tombolMata.setAttribute('aria-pressed', tampil ? 'true' : 'false');
                tombolMata.setAttribute('aria-label', tampil ? 'Sembunyikan password' : 'Tampilkan password');
                tombolMata.firstElementChild.className = tampil ? 'bi bi-eye-slash' : 'bi bi-eye';
            });

            document.getElementById('formAdmin').addEventListener('submit', function () {
                var t = document.getElementById('tombolMasuk');
                t.disabled = true;
                t.textContent = 'Memproses...';
            });
        })();
    </script>
</body>
</html>
