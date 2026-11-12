<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Layanan Bimbingan Siswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /*
          UBAH WARNA DI SINI:
          Cukup ganti --tema-utama dengan warna utama website kamu.
          Warna hover, latar lembut, dan border akan mengikuti otomatis.
        */
        :root {
            --tema-utama: #2563eb;
            --tema-hover: color-mix(in srgb, var(--tema-utama) 85%, black);
            --tema-gelap: color-mix(in srgb, var(--tema-utama) 70%, black);
            --tema-lembut: color-mix(in srgb, var(--tema-utama) 7%, white);
            --tema-border: color-mix(in srgb, var(--tema-utama) 30%, white);
            --tema-bg: #f1f5f9;
            --teks: #0f172a;
            --teks-redup: #64748b;
            --garis: #e2e8f0;

            /* Warna dekorasi background (format R, G, B) */
            --bg-kuning: 253, 224, 71;
            --bg-biru: 37, 99, 235;
        }

        * { -webkit-tap-highlight-color: transparent; }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background-color: var(--tema-bg);
            color: var(--teks);
            font-size: 1.05rem;
            overflow-x: hidden;
        }

        /* =====================================================
           BACKGROUND DEKORATIF
           ===================================================== */

        /* Lapisan 1: cahaya lembut + pola titik (memudar ke tengah) */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background:
                radial-gradient(620px circle at 8% 12%, rgba(var(--bg-kuning), .32), transparent 60%),
                radial-gradient(760px circle at 92% 90%, rgba(var(--bg-biru), .22), transparent 60%),
                radial-gradient(rgba(var(--bg-biru), .16) 1.5px, transparent 1.7px) 0 0 / 30px 30px;
            -webkit-mask-image: radial-gradient(ellipse at center, transparent 22%, #000 85%);
                    mask-image: radial-gradient(ellipse at center, transparent 22%, #000 85%);
        }

        /* Lapisan 2: lingkaran konsentris di dua sudut, bergerak pelan */
        body::after {
            content: '';
            position: fixed;
            inset: -40px;
            z-index: -1;
            pointer-events: none;
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='520' height='520' fill='none'%3E%3Ccircle cx='260' cy='260' r='90' stroke='rgba(234,179,8,0.55)' stroke-width='2'/%3E%3Ccircle cx='260' cy='260' r='150' stroke='rgba(37,99,235,0.22)' stroke-width='2'/%3E%3Ccircle cx='260' cy='260' r='210' stroke='rgba(37,99,235,0.14)' stroke-width='2'/%3E%3Ccircle cx='260' cy='260' r='255' stroke='rgba(37,99,235,0.08)' stroke-width='2'/%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='420' height='420' fill='none'%3E%3Ccircle cx='210' cy='210' r='70' stroke='rgba(37,99,235,0.30)' stroke-width='2'/%3E%3Ccircle cx='210' cy='210' r='125' stroke='rgba(234,179,8,0.40)' stroke-width='2'/%3E%3Ccircle cx='210' cy='210' r='180' stroke='rgba(37,99,235,0.12)' stroke-width='2'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right -120px top -120px, left -100px bottom -100px;
            animation: bg-melayang 18s ease-in-out infinite alternate;
        }

        @keyframes bg-melayang {
            from { transform: translate3d(0, 0, 0) scale(1); }
            to   { transform: translate3d(18px, -14px, 0) scale(1.04); }
        }

        :focus-visible {
            outline: 3px solid color-mix(in srgb, var(--tema-utama) 45%, white);
            outline-offset: 2px;
        }

        /* ---------- Kontainer ---------- */
        .page-container { position: relative; z-index: 1; }

        .form-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 20px 50px rgba(15, 23, 42, .10);
            border-radius: 28px;
            overflow: hidden;
        }

        /* ---------- Sidebar ---------- */
        .sidebar-theme {
            background: linear-gradient(160deg, var(--tema-utama) 0%, var(--tema-gelap) 100%);
            color: white;
            padding: 3rem;
            position: relative;
        }
        .sidebar-inner { position: sticky; top: 2rem; display: flex; flex-direction: column; min-height: calc(100% - 1rem); }
        .eyebrow { letter-spacing: 2px; font-size: .8rem; font-weight: 700; opacity: .8; }
        .sidebar-theme h1 { line-height: 1.2; }

        .stepper { list-style: none; padding: 0; margin: 2.5rem 0 0; }
        .stepper li {
            display: flex; align-items: center; gap: 1rem;
            padding: .6rem 0; position: relative; opacity: .65;
            transition: opacity .25s;
            font-weight: 600;
        }
        .stepper li:not(:last-child)::after {
            content: ''; position: absolute; left: 17px; top: 2.7rem;
            width: 2px; height: 1.2rem; background: rgba(255,255,255,.3);
        }
        .stepper .dot {
            width: 36px; height: 36px; border-radius: 50%;
            border: 2px solid rgba(255,255,255,.6);
            display: grid; place-items: center; font-size: .9rem; flex-shrink: 0;
            transition: all .25s;
        }
        .stepper li.aktif { opacity: 1; }
        .stepper li.aktif .dot { background: white; color: var(--tema-utama); border-color: white; }
        .stepper li.selesai { opacity: 1; }
        .stepper li.selesai .dot { background: rgba(255,255,255,.2); border-color: white; }
        .stepper li.selesai .dot .num { display: none; }
        .stepper li:not(.selesai) .dot .bi { display: none; }

        .progress-mini { height: 6px; background: rgba(255,255,255,.2); border-radius: 99px; overflow: hidden; margin-top: 2rem; }
        .progress-mini > div { height: 100%; background: white; width: 0; transition: width .35s ease; border-radius: 99px; }

        .privasi-box {
            background: rgba(255,255,255,.12); border-radius: 16px;
            padding: 1rem 1.1rem; font-size: .92rem; margin-top: 2rem;
        }
        .darurat-box {
            margin-top: auto; padding-top: 1.5rem; font-size: .9rem; opacity: .85;
            border-top: 1px solid rgba(255,255,255,.25);
        }

        /* ---------- Konten ---------- */
        .content-white { padding: 3rem; }

        .section-block { padding-bottom: 2.5rem; margin-bottom: 2.5rem; border-bottom: 1px dashed var(--garis); }
        .section-block:last-of-type { border-bottom: 0; margin-bottom: 1rem; }

        .step-title {
            font-size: .8rem; font-weight: 700; color: var(--tema-utama);
            text-transform: uppercase; letter-spacing: 1.5px;
        }
        .step-heading { font-size: 1.5rem; font-weight: 800; margin: .2rem 0 0; }

        .form-label { font-weight: 600; color: #334155; font-size: .95rem; }
        .form-control, .form-select {
            border-radius: 14px; border: 2px solid var(--garis); padding: .8rem 1rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--tema-utama);
            box-shadow: 0 0 0 .25rem color-mix(in srgb, var(--tema-utama) 18%, transparent);
        }
        .form-control[readonly] { background: #f8fafc; color: #475569; border-style: dashed; }

        .badge-auto {
            background: var(--tema-lembut); color: var(--tema-utama);
            font-weight: 600; font-size: .85rem;
        }

        /* ---------- Kartu pilihan (layanan & metode) ---------- */
        .service-card {
            display: block; position: relative; height: 100%;
            border: 2px solid var(--garis); border-radius: 18px;
            padding: 1.25rem; cursor: pointer; background: white;
            transition: border-color .2s, transform .2s, box-shadow .2s, background-color .2s;
        }
        .service-card:hover { border-color: var(--tema-border); transform: translateY(-3px); box-shadow: 0 8px 20px rgba(15,23,42,.06); }
        .btn-check:focus-visible + .service-card { outline: 3px solid color-mix(in srgb, var(--tema-utama) 45%, white); outline-offset: 2px; }
        .btn-check:checked + .service-card {
            border-color: var(--tema-utama); background: var(--tema-lembut);
            box-shadow: 0 6px 18px color-mix(in srgb, var(--tema-utama) 15%, transparent);
        }
        .service-icon {
            width: 48px; height: 48px; border-radius: 14px; display: grid; place-items: center;
            background: var(--tema-lembut); color: var(--tema-utama); font-size: 1.4rem; margin-bottom: .9rem;
            transition: all .2s;
        }
        .btn-check:checked + .service-card .service-icon { background: var(--tema-utama); color: white; }
        .check-mark {
            position: absolute; top: 14px; right: 14px; width: 26px; height: 26px; border-radius: 50%;
            background: var(--tema-utama); color: white; display: grid; place-items: center; font-size: .85rem;
            opacity: 0; transform: scale(.5); transition: all .2s;
        }
        .btn-check:checked + .service-card .check-mark { opacity: 1; transform: scale(1); }
        .service-card h5 { font-size: 1.05rem; font-weight: 700; margin-bottom: .3rem; }
        .service-card p { font-size: .92rem; color: var(--teks-redup); margin: 0; }

        .metode-card { display: flex; gap: 1rem; align-items: center; padding: 1rem 1.25rem; }
        .metode-card .service-icon { margin: 0; flex-shrink: 0; }

        /* ---------- Panel dinamis ---------- */
        .panel-dinamis {
            background: var(--tema-lembut); border: 1px solid var(--tema-border);
            border-radius: 20px; padding: 1.75rem; margin-top: 1.5rem;
            animation: muncul .3s ease;
        }
        @keyframes muncul { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }

        .anon-toggle {
            display: flex; align-items: center; gap: .75rem; background: white;
            border: 2px solid var(--garis); border-radius: 14px; padding: .6rem 1rem;
            transition: border-color .2s;
        }
        .anon-toggle:has(input:checked) { border-color: var(--tema-utama); }
        .anon-toggle .form-check-input { width: 2.6em; height: 1.4em; cursor: pointer; margin: 0; }
        .anon-toggle .form-check-input:checked { background-color: var(--tema-utama); border-color: var(--tema-utama); }
        .anon-toggle label { cursor: pointer; font-weight: 600; margin: 0; }

        .anon-info { font-size: .92rem; color: var(--teks-redup); }
        .anon-info.on { color: var(--tema-gelap); font-weight: 600; }

        .char-count { font-size: .85rem; color: var(--teks-redup); }

        /* ---------- Tombol ---------- */
        .btn-tema {
            background: var(--tema-utama); color: white; border: 0;
            transition: transform .2s, box-shadow .2s, background-color .2s;
            box-shadow: 0 8px 20px color-mix(in srgb, var(--tema-utama) 30%, transparent);
        }
        .btn-tema:hover { background: var(--tema-hover); color: white; transform: translateY(-2px); }
        .btn-tema:disabled { opacity: .7; transform: none; }

        .error-box {
            display: none; color: #b91c1c; background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 12px; padding: .6rem 1rem; font-size: .92rem; margin-top: .9rem;
        }
        .error-box.tampil { display: flex; gap: .5rem; align-items: center; }

        /* ---------- Responsif ---------- */
        @media (max-width: 991.98px) {
            .sidebar-theme, .content-white { padding: 1.75rem; }
            .sidebar-inner { position: static; min-height: 0; }
            .stepper { display: flex; gap: .5rem; margin-top: 1.5rem; }
            .stepper li { flex-direction: column; text-align: center; font-size: .8rem; flex: 1; gap: .4rem; }
            .stepper li::after { display: none; }
            .privasi-box { display: none; }
            .darurat-box { margin-top: 1.5rem; }
            .form-wrapper { border-radius: 20px; }
        }

        @media (max-width: 575.98px) {
            body::after { opacity: .6; background-size: 300px, 240px; }
        }

        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; animation: none !important; }
        }
    </style>
</head>
<body>
@php
    $siswa = auth('siswa')->user();
    $daftarGuru = [
        1 => 'Pak Hanafi',
        2 => 'Pak Desnya',
        3 => 'Bu April',
        4 => 'Pak Alam',
    ];
    $layananOld = old('layanan', []);
@endphp

<div class="container py-4 py-lg-5 page-container">
    <div class="form-wrapper d-flex flex-column flex-lg-row">

        <!-- ===== Sidebar Kiri ===== -->
        <aside class="sidebar-theme col-lg-4">
            <div class="sidebar-inner">
                <div>
                    <p class="eyebrow mb-2">BIMBINGAN KONSELING</p>
                    <h1 class="h2 fw-bold mb-3">Cerita kamu layak didengar.</h1>
                    <p class="mb-0 opacity-75">Pilih dukungan yang kamu butuhkan. Tim BK akan mendampingi dengan aman, nyaman, dan tanpa menghakimi.</p>

                    <ol class="stepper" id="stepper" aria-label="Progres pengisian form">
                        <li data-step="1" class="selesai">
                            <span class="dot"><span class="num">1</span><i class="bi bi-check-lg"></i></span>
                            <span>Data diri</span>
                        </li>
                        <li data-step="2" class="aktif">
                            <span class="dot"><span class="num">2</span><i class="bi bi-check-lg"></i></span>
                            <span>Pilih layanan</span>
                        </li>
                        <li data-step="3">
                            <span class="dot"><span class="num">3</span><i class="bi bi-check-lg"></i></span>
                            <span>Atur pendampingan</span>
                        </li>
                    </ol>

                    <div class="progress-mini" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="33" id="progressWrap">
                        <div id="progressBar"></div>
                    </div>

                    <div class="privasi-box">
                        <i class="bi bi-shield-lock-fill me-1"></i>
                        <strong>Privasi terjaga.</strong> Isi konseling hanya dapat dilihat oleh guru pembimbing yang kamu pilih.
                    </div>
                </div>

                <div class="darurat-box">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    Jika kamu berada dalam situasi darurat, segera hubungi guru piket atau orang dewasa tepercaya di sekolah.
                </div>
            </div>
        </aside>

        <!-- ===== Form Kanan ===== -->
        <main class="content-white col-lg-8">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Layanan Konseling</h2>
                    <p class="text-secondary mb-0">Lengkapi form agar sesi disiapkan dengan tepat.</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger rounded-pill px-3">
                        <i class="bi bi-box-arrow-right me-1"></i><span class="d-none d-sm-inline">Keluar</span>
                    </button>
                </form>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger rounded-4" role="alert">
                    <strong>Ada isian yang perlu diperbaiki:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Ganti action="#" dengan route penyimpanan, mis. {{ route('konseling.store') }} --}}
            <form action="#" method="POST" id="formKonseling" novalidate>
                @csrf

                <!-- Langkah 1 -->
                <section class="section-block" aria-labelledby="judul-langkah-1">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <div class="step-title">Langkah 01</div>
                            <h3 class="step-heading" id="judul-langkah-1">Data Siswa</h3>
                        </div>
                        <span class="badge badge-auto px-3 py-2 rounded-pill"><i class="bi bi-magic me-1"></i>Terisi otomatis</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label" for="nama">Nama Lengkap</label>
                            <input id="nama" type="text" class="form-control" value="{{ $siswa->nama ?? 'Siswa Baru' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="kelas">Kelas</label>
                            <input id="kelas" type="text" class="form-control" value="{{ $siswa->kelas ?? 'X RPL 1' }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="jurusan">Jurusan</label>
                            <input id="jurusan" type="text" class="form-control" value="{{ $siswa->jurusan ?? 'Rekayasa Perangkat Lunak' }}" readonly>
                        </div>
                    </div>
                </section>

                <!-- Langkah 2 -->
                <section class="section-block" aria-labelledby="judul-langkah-2">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <div class="step-title">Langkah 02</div>
                            <h3 class="step-heading" id="judul-langkah-2">Pilih Layanan</h3>
                        </div>
                        <span class="text-secondary fst-italic small mt-2">Boleh lebih dari satu</span>
                    </div>

                    <div class="row g-3" id="grupLayanan">
                        <div class="col-md-4">
                            <input type="checkbox" class="btn-check" id="layanan1" name="layanan[]" value="E-Konseling Pribadi" @checked(in_array('E-Konseling Pribadi', $layananOld))>
                            <label class="service-card" for="layanan1">
                                <span class="check-mark"><i class="bi bi-check-lg"></i></span>
                                <div class="service-icon"><i class="bi bi-chat-heart"></i></div>
                                <h5>E-Konseling</h5>
                                <p>Ruang aman untuk membicarakan hal personal.</p>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="checkbox" class="btn-check" id="layanan2" name="layanan[]" value="Bimbingan Karir & PKL" @checked(in_array('Bimbingan Karir & PKL', $layananOld))>
                            <label class="service-card" for="layanan2">
                                <span class="check-mark"><i class="bi bi-check-lg"></i></span>
                                <div class="service-icon"><i class="bi bi-briefcase"></i></div>
                                <h5>Karir &amp; PKL</h5>
                                <p>Persiapan PKL dan rencana setelah lulus.</p>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="checkbox" class="btn-check" id="layanan3" name="layanan[]" value="Konsultasi Akademik" @checked(in_array('Konsultasi Akademik', $layananOld))>
                            <label class="service-card" for="layanan3">
                                <span class="check-mark"><i class="bi bi-check-lg"></i></span>
                                <div class="service-icon"><i class="bi bi-mortarboard"></i></div>
                                <h5>Akademik</h5>
                                <p>Dukungan belajar dan target akademik.</p>
                            </label>
                        </div>
                    </div>
                    <div class="error-box" id="errLayanan" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i> Pilih minimal satu layanan.
                    </div>
                </section>

                <!-- Langkah 3 -->
                <section class="section-block" aria-labelledby="judul-langkah-3">
                    <div class="mb-4">
                        <div class="step-title">Langkah 03</div>
                        <h3 class="step-heading" id="judul-langkah-3">Atur Pendampingan</h3>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="guru_id">Guru Pembimbing</label>
                        <select class="form-select" name="guru_id" id="guru_id" required>
                            <option value="" disabled {{ old('guru_id') ? '' : 'selected' }}>Pilih guru...</option>
                            @foreach ($daftarGuru as $id => $namaGuru)
                                <option value="{{ $id }}" @selected(old('guru_id') == $id)>{{ $namaGuru }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Silakan pilih guru pembimbing.</div>
                    </div>

                    <label class="form-label d-block">Metode Konseling</label>
                    <div class="row g-3" id="grupMetode">
                        <div class="col-md-6">
                            <input type="radio" class="btn-check" name="metode" id="metodeOffline" value="offline" @checked(old('metode') === 'offline')>
                            <label class="service-card metode-card" for="metodeOffline">
                                <span class="check-mark"><i class="bi bi-check-lg"></i></span>
                                <div class="service-icon"><i class="bi bi-people"></i></div>
                                <div>
                                    <h5 class="mb-0">Tatap Muka</h5>
                                    <p>Bertemu langsung di ruang BK.</p>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <input type="radio" class="btn-check" name="metode" id="metodeOnline" value="online" @checked(old('metode') === 'online')>
                            <label class="service-card metode-card" for="metodeOnline">
                                <span class="check-mark"><i class="bi bi-check-lg"></i></span>
                                <div class="service-icon"><i class="bi bi-chat-dots"></i></div>
                                <div>
                                    <h5 class="mb-0">Chat / E-Konseling</h5>
                                    <p>Tulis pesan, guru membalas lewat sistem.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="error-box" id="errMetode" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i> Pilih metode konseling.
                    </div>

                    <!-- Panel Offline -->
                    <div id="formOffline" class="panel-dinamis d-none">
                        <h5 class="fw-bold mb-3"><i class="bi bi-calendar-event me-2"></i>Jadwal Pertemuan</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="tanggal_offline">Pilih Tanggal</label>
                                <input type="date" class="form-control" id="tanggal_offline" name="tanggal_offline" value="{{ old('tanggal_offline') }}">
                                <div class="invalid-feedback">Tanggal pertemuan wajib diisi.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="waktu_offline">Pilih Waktu <span class="text-secondary fw-normal">(opsional)</span></label>
                                <input type="time" class="form-control" id="waktu_offline" name="waktu_offline" value="{{ old('waktu_offline') }}">
                            </div>
                        </div>
                        <p class="anon-info mt-3 mb-0"><i class="bi bi-info-circle me-1"></i>Guru akan mengonfirmasi jadwal sesuai ketersediaan.</p>
                    </div>

                    <!-- Panel Online -->
                    <div id="formOnline" class="panel-dinamis d-none">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                            <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2"></i>Tulis Pesan / Keluhan</h5>
                            <div class="anon-toggle form-check form-switch ps-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="isAnonymous" name="is_anonymous" value="1" @checked(old('is_anonymous'))>
                                <label for="isAnonymous"><i class="bi bi-incognito me-1"></i>Kirim sebagai Anonim</label>
                            </div>
                        </div>
                        <textarea class="form-control" id="pesan_online" name="pesan_online" rows="6" maxlength="1000" placeholder="Ceritakan dengan bebas di sini...">{{ old('pesan_online') }}</textarea>
                        <div class="invalid-feedback">Tulis pesan minimal 10 karakter.</div>
                        <div class="d-flex justify-content-between align-items-start gap-3 mt-2">
                            <p class="anon-info mb-0" id="anonInfo">Nama dan kelasmu akan terlihat oleh guru pembimbing.</p>
                            <span class="char-count text-nowrap" id="charCount">0/1000</span>
                        </div>
                    </div>
                </section>

                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mt-4">
                    <p class="text-secondary small mb-0"><i class="bi bi-lock me-1"></i>Pengajuanmu dikirim langsung ke guru pembimbing.</p>
                    <button type="submit" id="btnKirim" class="btn btn-tema btn-lg px-5 py-3 rounded-pill fw-bold">
                        <span class="label">Kirim Pengajuan <i class="bi bi-send ms-1"></i></span>
                        <span class="loading d-none"><span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...</span>
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formKonseling');
    const layanan = document.querySelectorAll('input[name="layanan[]"]');
    const metode = document.querySelectorAll('input[name="metode"]');
    const guru = document.getElementById('guru_id');
    const panelOffline = document.getElementById('formOffline');
    const panelOnline = document.getElementById('formOnline');
    const tanggal = document.getElementById('tanggal_offline');
    const pesan = document.getElementById('pesan_online');
    const anon = document.getElementById('isAnonymous');
    const anonInfo = document.getElementById('anonInfo');
    const charCount = document.getElementById('charCount');
    const errLayanan = document.getElementById('errLayanan');
    const errMetode = document.getElementById('errMetode');
    const steps = document.querySelectorAll('#stepper li');
    const bar = document.getElementById('progressBar');
    const progressWrap = document.getElementById('progressWrap');
    const btnKirim = document.getElementById('btnKirim');

    // Tanggal minimal = hari ini (zona waktu lokal perangkat)
    const now = new Date();
    tanggal.min = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().split('T')[0];

    const metodeDipilih = () => (document.querySelector('input[name="metode"]:checked') || {}).value;
    const adaLayanan = () => Array.from(layanan).some(c => c.checked);

    function tampilkanPanel() {
        const m = metodeDipilih();
        panelOffline.classList.toggle('d-none', m !== 'offline');
        panelOnline.classList.toggle('d-none', m !== 'online');
        tanggal.required = (m === 'offline');
        pesan.required = (m === 'online');
        if (m) errMetode.classList.remove('tampil');
    }

    function updateAnon() {
        anonInfo.classList.toggle('on', anon.checked);
        anonInfo.textContent = anon.checked
            ? 'Mode anonim aktif: nama dan kelasmu tidak akan terlihat di layar guru.'
            : 'Nama dan kelasmu akan terlihat oleh guru pembimbing.';
    }

    function updateCount() {
        charCount.textContent = pesan.value.length + '/1000';
    }

    function langkah3Lengkap() {
        const m = metodeDipilih();
        if (!guru.value || !m) return false;
        if (m === 'offline') return !!tanggal.value;
        return pesan.value.trim().length >= 10;
    }

    function updateStepper() {
        const status = [true, adaLayanan(), langkah3Lengkap()];
        let aktifSet = false;
        let selesai = 0;
        steps.forEach((li, i) => {
            li.classList.remove('selesai', 'aktif');
            li.removeAttribute('aria-current');
            if (status[i]) { li.classList.add('selesai'); selesai++; }
            else if (!aktifSet) { li.classList.add('aktif'); li.setAttribute('aria-current', 'step'); aktifSet = true; }
        });
        const persen = Math.round((selesai / 3) * 100);
        bar.style.width = persen + '%';
        progressWrap.setAttribute('aria-valuenow', persen);
    }

    // Event
    layanan.forEach(c => c.addEventListener('change', () => {
        if (adaLayanan()) errLayanan.classList.remove('tampil');
        updateStepper();
    }));
    metode.forEach(r => r.addEventListener('change', () => { tampilkanPanel(); updateStepper(); }));
    [guru, tanggal, pesan].forEach(el => el.addEventListener('input', () => {
        el.classList.remove('is-invalid');
        updateStepper();
    }));
    pesan.addEventListener('input', updateCount);
    anon.addEventListener('change', updateAnon);

    // Validasi saat kirim
    form.addEventListener('submit', function (e) {
        let pertamaError = null;
        const tandai = (el) => { if (!pertamaError) pertamaError = el; };

        if (!adaLayanan()) { errLayanan.classList.add('tampil'); tandai(errLayanan); }
        if (!guru.value) { guru.classList.add('is-invalid'); tandai(guru); }

        const m = metodeDipilih();
        if (!m) { errMetode.classList.add('tampil'); tandai(errMetode); }
        if (m === 'offline' && !tanggal.value) { tanggal.classList.add('is-invalid'); tandai(tanggal); }
        if (m === 'online' && pesan.value.trim().length < 10) { pesan.classList.add('is-invalid'); tandai(pesan); }

        if (pertamaError) {
            e.preventDefault();
            pertamaError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        // Cegah kirim ganda
        btnKirim.disabled = true;
        btnKirim.querySelector('.label').classList.add('d-none');
        btnKirim.querySelector('.loading').classList.remove('d-none');
    });

    // Inisialisasi (juga memulihkan pilihan setelah validasi server gagal)
    tampilkanPanel();
    updateAnon();
    updateCount();
    updateStepper();
});
</script>
</body>
</html>