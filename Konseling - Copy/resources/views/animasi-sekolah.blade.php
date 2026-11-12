<!DOCTYPE html>
<html lang="id" class="kunci">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem BK - SMKN 7 Samarinda</title>

    {{-- Kalau dibuka dengan ?langsung (mis. dari halaman login), lewati intro dan langsung ke halaman utama --}}
    <script>
        if (location.search.indexOf('langsung') > -1) { document.documentElement.className = 'masuk'; }
    </script>

    <!-- Google Fonts, Bootstrap & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- Mulai muat gambar lebih awal supaya intro tidak menunggu --}}
    <link rel="preload" as="image" href="{{ asset('img/tengah.svg') }}">
    <link rel="preload" as="image" href="{{ asset('img/kiri.svg') }}">
    <link rel="preload" as="image" href="{{ asset('img/kanan.svg') }}">

    <style>
        /* =====================================================================
           PENGATURAN (cukup ubah angka di sini)
           ===================================================================== */
        :root {
            --zoom: 1.07;                 /* seberapa dekat kamera di akhir */
            --jeda: 90vh;                 /* tinggi zona peralihan krim -> biru tua (makin besar = transisi makin panjang) */
            --kamera-durasi: 7s;          /* kamera mendekat pelan & terus melambat */
            --tengah-durasi: 3.8s;        /* gerak halus gedung tengah */
            --tengah-pudar: 3.2s;         /* lama dissolve tengah (transparan -> jelas) */
            --tengah-delay: 1.5s;         /* tengah muncul TERAKHIR, setelah kiri & kanan hampir sampai */
            --samping-durasi: 3.6s;       /* bangunan kiri & kanan meluncur masuk */
            --samping-delay: .1s;         /* kiri & kanan muncul PERTAMA */
            --samping-pudar: 2s;          /* kiri & kanan cepat terlihat saat meluncur */
            --fajar-durasi: 6s;           /* langit berubah dari putih ke biru */
            --ease: cubic-bezier(.22, .61, .36, 1);        /* keluar halus */
            --ease-kamera: cubic-bezier(.25, .46, .3, 1);

            /* Ukuran bingkai 16:9 (selalu menutupi layar, seperti "cover") */
            --lebar-bingkai: max(100vw, calc(100vh * 16 / 9));

            --langit: linear-gradient(to bottom,
                #4fb3ea 0%, #84cdf2 30%, #b8e4f7 55%, #e3f5fc 72%, #f4fbfe 100%);
        }

        html, body { margin: 0; padding: 0; width: 100%; }

        /* Sebelum tombol ditekan, halaman dikunci (tidak bisa di-scroll) */
        html.kunci, html.kunci body { height: 100%; overflow: hidden; overscroll-behavior: none; }

        /* Saat layar bergeser turun: tidak bisa di-scroll manual (tapi tetap bisa digeser lewat script) */
        html.turun, html.turun body { overflow: hidden; overscroll-behavior: none; }

        /* Setelah selesai: intro & zona peralihan dibuang, tidak bisa kembali ke atas */
        html.masuk .animasi-sekolah,
        html.masuk .jeda-warna { display: none; }

        .animasi-sekolah {
            position: relative;
            width: 100%;
            height: 100vh;
            height: 100dvh;         /* mobile: ikut tinggi layar yang sebenarnya */
            overflow: hidden;
            background-color: #b8e4f7;
            background-image: var(--langit);
        }

        /* Animasi baru jalan setelah semua gambar siap (lihat script di bawah) */
        .animasi-sekolah:not(.siap) *,
        .animasi-sekolah:not(.siap) *::before,
        .animasi-sekolah:not(.siap) *::after {
            animation-play-state: paused !important;
        }

        /* =====================================================================
           LANGIT: putih yang memudar jadi biru + awan yang melayang pelan
           ===================================================================== */
        .fajar {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, #ffffff 0%, #f4fbfe 100%);
            animation: fajarMemudar var(--fajar-durasi) ease-in-out both;
            pointer-events: none;
        }

        @keyframes fajarMemudar {
            from { opacity: 1; }
            to   { opacity: 0; }
        }

        .awan {
            position: absolute;
            inset: 0 0 35% 0;       /* hanya di langit bagian atas */
            overflow: hidden;
            pointer-events: none;
            animation: munculPelan 3s ease-out 1s both;
        }

        .awan svg {
            position: absolute;
            left: 0;
            fill: #fff;
            opacity: .92;
            will-change: transform;
            animation: melayang linear infinite;
        }
        .awan .a1 { top: 8%;  width: 18vw; animation-duration: 140s; animation-delay: -30s; }
        .awan .a2 { top: 22%; width: 11vw; animation-duration: 190s; animation-delay: -120s; opacity: .8; }
        .awan .a3 { top: 4%;  width: 14vw; animation-duration: 170s; animation-delay: -90s;  opacity: .85; }
        .awan .a4 { top: 30%; width: 9vw;  animation-duration: 230s; animation-delay: -10s;  opacity: .7; }

        @keyframes melayang {
            from { transform: translate3d(-30vw, 0, 0); }
            to   { transform: translate3d(115vw, 0, 0); }
        }

        @keyframes munculPelan {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* =====================================================================
           PANGGUNG: bingkai 16:9 yang pas dengan koordinat gambar asli
           ===================================================================== */
        .bingkai {
            position: absolute;
            bottom: 0;
            width: var(--lebar-bingkai);
            aspect-ratio: 16 / 9;
            left: calc(50% - var(--lebar-bingkai) / 2);
        }

        /* KAMERA: mendekat (dolly-in) untuk SEMUA layer sekaligus, jadi
           sambungan antar layer tidak pernah bergeser. Titik zoom: tengah-bawah. */
        .kamera {
            position: absolute;
            inset: 0;
            transform-origin: 50% 100%;
            will-change: transform;
            animation: kameraMendekat var(--kamera-durasi) var(--ease-kamera) both;
        }

        @keyframes kameraMendekat {
            from { transform: scale3d(1, 1, 1); }
            to   { transform: scale3d(var(--zoom), var(--zoom), 1); }
        }

        .animasi-sekolah.selesai .kamera,
        .animasi-sekolah.selesai .grup-tengah,
        .animasi-sekolah.selesai .layer-samping {
            will-change: auto;      /* lepas setelah selesai agar gambar tajam */
        }

        .layer {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            display: block;
            user-select: none;
            pointer-events: none;
            -webkit-user-drag: none;
        }

        /* --- TENGAH (gedung + lapangan): muncul dari bawah sambil mendekat --- */
        .grup-tengah {
            position: absolute;
            inset: 0;
            transform-origin: 50% 100%;
            will-change: transform, opacity;
            animation:
                tengahPudar var(--tengah-pudar) ease-in-out var(--tengah-delay) both,
                tengahGerak var(--tengah-durasi) var(--ease) var(--tengah-delay) both;
        }

        /* Dissolve: muncul perlahan dari transparan */
        @keyframes tengahPudar {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* Gerak halus menyatu dengan kamera (sangat tipis, bukan "melompat") */
        @keyframes tengahGerak {
            from { transform: translate3d(0, .8%, 0) scale3d(.985, .985, 1); }
            to   { transform: translate3d(0, 0, 0) scale3d(1, 1, 1); }
        }

        /* --- KIRI & KANAN: masuk dari tepi sambil sedikit lebih besar (terasa lebih dekat) --- */
        .layer-samping { will-change: transform; }

        .layer-kiri {
            transform-origin: 0% 100%;
            animation:
                sampingPudar var(--samping-pudar) ease-out var(--samping-delay) both,
                masukKiri var(--samping-durasi) var(--ease) var(--samping-delay) both;
        }

        .layer-kanan {
            transform-origin: 100% 100%;
            animation:
                sampingPudar var(--samping-pudar) ease-out var(--samping-delay) both,
                masukKanan var(--samping-durasi) var(--ease) var(--samping-delay) both;
        }

        /* Dissolve sama seperti tengah: muncul dari transparan sambil masuk */
        @keyframes sampingPudar {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes masukKiri {
            from { transform: translate3d(-52%, 0, 0) scale3d(1.14, 1.14, 1); }
            to   { transform: translate3d(0, 0, 0) scale3d(1, 1, 1); }
        }

        @keyframes masukKanan {
            from { transform: translate3d(52%, 0, 0) scale3d(1.14, 1.14, 1); }
            to   { transform: translate3d(0, 0, 0) scale3d(1, 1, 1); }
        }


        /* Teks hak cipta */
        .copyright {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 1.5vh;
            margin: 0;
            text-align: center;
            color: #fff;
            font: 500 clamp(10px, 1.05vw, 16px) 'Montserrat', 'Segoe UI', sans-serif;
            text-shadow: 0 1px 3px rgba(0, 0, 0, .35);
            animation: munculPelan 1.2s ease-out 4.6s both;
        }



        /* =====================================================================
           ZONA PERALIHAN: krim (tanah intro) berubah pelan jadi biru tua (navbar)
           Warna atas = warna tanah di tengah.svg, warna bawah = .bg-navy
           ===================================================================== */
        .jeda-warna {
            position: relative;
            height: var(--jeda);
            margin-top: -2px;           /* tutup garis tipis di sambungan dengan intro */
            background: linear-gradient(to bottom,
                #cec3ae 0%,
                #c8beab 10%,
                #b8b1a4 20%,
                #a09e98 30%,
                #84888a 40%,
                #67707b 50%,
                #4b596c 60%,
                #32445e 70%,
                #1e3352 80%,
                #10284a 90%,
                #0b2447 100%);
        }

        /* =====================================================================
           SAMPUL: judul + tombol di tengah, muncul setelah pemandangan tampil
           ===================================================================== */
        .sampul {
            position: absolute;
            left: 0;
            right: 0;
            top: 9%;
            z-index: 5;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: clamp(14px, 2.2vw, 28px);
            padding: 0 5vw;
            text-align: center;
            animation: sampulMuncul 1.6s var(--ease) 4.2s both;
        }

        @keyframes sampulMuncul {
            from { opacity: 0; transform: translate3d(0, 16px, 0); }
            to   { opacity: 1; transform: translate3d(0, 0, 0); }
        }

        .sampul-judul {
            margin: 0;
            font: 800 clamp(2.2rem, 5.2vw, 4.6rem)/1.08 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            letter-spacing: -.02em;
            color: #0B2447;
            text-shadow: 0 2px 18px rgba(255, 255, 255, .55);
        }

        .tombol-masuk {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: .9em 2em;
            border: 0;
            border-radius: 999px;
            background: #FFD700;
            color: #0B2447;
            font: 700 clamp(1rem, 1.5vw, 1.25rem) 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(11, 36, 71, .25);
            transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease;
        }
        .tombol-masuk:hover {
            background: #ffe14d;
            color: #0B2447;
            transform: translateY(-3px);
            box-shadow: 0 14px 34px rgba(11, 36, 71, .3);
        }
        .tombol-masuk:focus-visible { outline: 3px solid #0B2447; outline-offset: 3px; }
        .tombol-masuk i { animation: panahTurun 1.6s ease-in-out infinite; }

        @keyframes panahTurun {
            0%, 100% { transform: translateY(-2px); }
            50%      { transform: translateY(3px); }
        }

        /* Hemat baterai: animasi berhenti saat intro sudah tidak terlihat */
        .animasi-sekolah.diam *,
        .animasi-sekolah.diam *::before,
        .animasi-sekolah.diam *::after { animation-play-state: paused !important; }

        /* =====================================================================
           MOBILE / LAYAR TEGAK (portrait): tampilkan SELURUH lapangan
           Bingkai dipakai selebar layar ("contain"), menempel di bawah,
           langit mengisi bagian atas, seperti tampilan di website utama.
           ===================================================================== */
        @media (orientation: portrait) {
            :root {
                --lebar-bingkai: 100vw;
                --zoom: 1.02;               /* zoom kecil supaya tepi tidak terpotong */
            }

            .sampul { top: 24%; }
            .sampul-judul { font-size: clamp(2.1rem, 10vw, 3.4rem); }

            .awan .a1 { width: 34vw; }
            .awan .a2 { width: 22vw; }
            .awan .a3 { width: 28vw; }
            .awan .a4 { width: 18vw; }
        }

        /* Pengguna yang mematikan animasi di sistem operasinya: tampilkan hasil akhir */
        @media (prefers-reduced-motion: reduce) {
            .fajar, .awan svg { display: none; }
            .kamera, .grup-tengah, .layer-samping, .awan, .copyright, .sampul { animation: none; }
        }
    </style>

    {{-- Kalau JavaScript dimatikan, animasi tetap jalan --}}
    <noscript>
        <style>
            html.kunci, html.kunci body { height: auto !important; overflow: visible !important; }
            .animasi-sekolah:not(.siap) *,
            .animasi-sekolah:not(.siap) *::before,
            .animasi-sekolah:not(.siap) *::after { animation-play-state: running !important; }
        </style>
    </noscript>
    <style>
        /* ===== Halaman utama (landing) ===== */
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; scroll-padding-top: 80px; }

        .bg-navy { background-color: #0B2447; }
        .text-navy { color: #0B2447; }
        .text-gold { color: #FFD700; }
        .text-blue-accent { color: #0d6efd; }

        .btn-gold { background-color: #FFD700; color: #0B2447; font-weight: 600; }
        .btn-gold:hover { background-color: #e6c200; color: #0B2447; }
        .btn-primary-custom { background-color: #0d6efd; color: white; font-weight: 600; }
        .btn-primary-custom:hover { background-color: #0b5ed7; color: white; }
        .btn-outline-custom { border: 1px solid #ced4da; color: #495057; font-weight: 600; }
        .btn-outline-custom:hover { background-color: #f8f9fa; }

        .nav-link { color: #ffffff !important; font-weight: 500;}
        .nav-link:hover { color: #FFD700 !important; }

        .badge-soft-blue { background-color: #e0f0ff; color: #0d6efd; font-weight: 600; padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; display: inline-block;}

        .avatar-group img { width: 35px; height: 35px; border: 2px solid white; border-radius: 50%; margin-left: -10px; }
        .avatar-group img:first-child { margin-left: 0; }
        .avatar-text { font-size: 0.85rem; color: #6c757d; margin-left: 10px; }

        .service-card { transition: all 0.3s ease; cursor: pointer; border: 2px solid transparent; }
        .service-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; border-color: #0d6efd; }

        /* Kustomisasi Box di dalam Modal */
        .box-hijau { background-color: #f0fdf4; border-left: 5px solid #16a34a; padding: 15px; border-radius: 8px; }
        .box-kuning { background-color: #fffbeb; border-left: 5px solid #d97706; padding: 15px; border-radius: 8px; }
        .box-biru { background-color: #eff6ff; border-left: 5px solid #2563eb; padding: 15px; border-radius: 8px; }

        /* Kustomisasi Kartu Profil Guru */
        .team-card { background: #ffffff; border-radius: 24px; padding: 40px 20px 30px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: transform 0.3s ease; border: 1px solid #f1f3f5; }
        .team-card:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .team-avatar { width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 6px solid #e0f0ff; margin-bottom: 20px; background-color: #f8f9fa; }

        /* Kustomisasi Kontak Cards */
        .contact-box { background: #ffffff; border: 1px solid #e9ecef; border-radius: 20px; padding: 25px; transition: all 0.3s ease; text-decoration: none; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .contact-box:hover { transform: translateY(-5px); border-color: #0d6efd; box-shadow: 0 10px 25px rgba(13,110,253,0.1); }
        .contact-icon { width: 55px; height: 55px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }

        /* Footer Styling */
        .footer-custom { background-color: #07172e; color: #94a3b8; font-size: 0.9rem; }
        .footer-custom h5 { color: #ffffff; font-weight: 700; margin-bottom: 1.2rem; }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 0.7rem; }
        .footer-links a { color: #94a3b8; text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: #FFD700; }

        /* ===== Tambahan: judul section, kapan ke BK, cara kerja, FAQ, animasi muncul ===== */

        /* Label kecil di atas judul section (menggantikan style inline yang diulang-ulang) */
        .eyebrow { display: inline-block; color: #0d6efd; font-weight: 700; font-size: .85rem; letter-spacing: 2px; text-transform: uppercase; }

        /* Judul besar section */
        .section-title { font-weight: 800; color: #0B2447; font-size: clamp(1.8rem, 4vw, 2.5rem); letter-spacing: -.01em; }

        #layanan h3.h4, .team-card h4, .step h4 { color: #0B2447; }

        /* "Kapan sebaiknya datang ke BK?" : kartu kecil dengan ikon */
        .when-item { display: flex; align-items: center; gap: 14px; height: 100%; padding: 16px 18px; background: #ffffff; border: 1px solid #e9ecef; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease; }
        .when-item:hover { transform: translateY(-4px); border-color: #0d6efd; box-shadow: 0 10px 25px rgba(13,110,253,0.1); }
        .when-item i { flex-shrink: 0; width: 46px; height: 46px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: #e0f0ff; color: #0d6efd; font-size: 1.3rem; }
        .when-item span { color: #495057; line-height: 1.5; }

        /* Cara kerja: lingkaran angka + garis putus-putus yang menyambungkan */
        .steps-line { position: relative; }
        .step { position: relative; }
        .step-num { position: relative; z-index: 1; display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, #0d6efd, #0a4fc4); color: #ffffff; font-size: 1.5rem; font-weight: 800; box-shadow: 0 8px 20px rgba(13,110,253,0.3); }
        @media (min-width: 768px) {
            /* garis dari tengah lingkaran 1 ke tengah lingkaran 3 */
            .steps-line::before { content: ""; position: absolute; top: calc(32px + var(--bs-gutter-y)); left: 16.6667%; right: 16.6667%; border-top: 2px dashed #9ec5fe; }
        }

        /* FAQ (accordion Bootstrap, dirapikan) */
        .accordion-item { border: 1px solid #e9ecef; border-radius: 16px; overflow: hidden; margin-bottom: .75rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .accordion-item:not(:first-of-type) { border-top: 1px solid #e9ecef; }
        .accordion-button { font-weight: 600; color: #0B2447; padding: 1.1rem 1.25rem; }
        .accordion-button:not(.collapsed) { background-color: #e0f0ff; color: #0B2447; box-shadow: none; }
        .accordion-button:focus { border-color: #0d6efd; box-shadow: 0 0 0 .2rem rgba(13,110,253,.2); }
        .accordion-body { line-height: 1.7; }

        /* Animasi muncul saat di-scroll (class .reveal). Aktif hanya kalau JavaScript jalan. */
        html.reveal-aktif .reveal { opacity: 0; transform: translate3d(0, 28px, 0); transition: opacity .8s ease, transform .8s cubic-bezier(.22, .61, .36, 1); }
        html.reveal-aktif .reveal.tampil { opacity: 1; transform: none; }
        /* Kartu dalam satu baris muncul bergantian */
        .row > .reveal:nth-child(3n+2) { transition-delay: .12s; }
        .row > .reveal:nth-child(3n+3) { transition-delay: .24s; }
        @media (prefers-reduced-motion: reduce) {
            html.reveal-aktif .reveal { opacity: 1; transform: none; transition: none; }
        }
    </style>
</head>
<body>

    <div class="animasi-sekolah">

        {{-- Langit --}}
        <div class="fajar"></div>
        <div class="awan" aria-hidden="true">
            <svg class="a1" viewBox="0 0 140 70"><use href="#awan"/></svg>
            <svg class="a2" viewBox="0 0 140 70"><use href="#awan"/></svg>
            <svg class="a3" viewBox="0 0 140 70"><use href="#awan"/></svg>
            <svg class="a4" viewBox="0 0 140 70"><use href="#awan"/></svg>
        </div>

        {{-- Bentuk awan (dipakai berulang) --}}
        <svg width="0" height="0" style="position:absolute" aria-hidden="true">
            <defs>
                <g id="awan">
                    <circle cx="38" cy="48" r="20"/>
                    <circle cx="66" cy="36" r="27"/>
                    <circle cx="100" cy="46" r="22"/>
                    <rect x="22" y="46" width="98" height="22" rx="11"/>
                </g>
            </defs>
        </svg>

        {{-- Pemandangan: urutan penting, tengah paling bawah, kiri & kanan di atasnya --}}
        <div class="bingkai">
            <div class="kamera">
                <div class="grup-tengah">
                    <img class="layer" src="{{ asset('img/tengah.svg') }}" alt="Gedung tengah dan lapangan" decoding="async">
                </div>
                <img class="layer layer-samping layer-kiri"  src="{{ asset('img/kiri.svg') }}"  alt="Bangunan kiri"  decoding="async">
                <img class="layer layer-samping layer-kanan" src="{{ asset('img/kanan.svg') }}" alt="Bangunan kanan" decoding="async">
            </div>
        </div>

        <div class="sampul">
            <h1 class="sampul-judul">Konseling SMK 7</h1>
            <a href="#situs" id="tombolMasuk" class="tombol-masuk">
                Mulai Jelajahi <i class="bi bi-arrow-down"></i>
            </a>
        </div>

        <p class="copyright">Copyright &copy; SMK Negeri 7 Samarinda | All Rights Reserved</p>
    </div>

    {{-- Zona peralihan: gradasi krim -> biru tua (nyambung ke navbar) --}}
    <div class="jeda-warna" aria-hidden="true"></div>

    {{-- =====================================================================
         HALAMAN UTAMA (muncul setelah tombol di intro ditekan)
         ===================================================================== --}}
    <div id="situs">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-navy shadow-sm py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="#hero">
                <span class="text-gold">E-</span>Konseling SMKN 7
            </a>
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="#hero">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tim">Informasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                </ul>
                <div class="d-flex">
                    <a href="/login" class="btn btn-gold px-4 rounded-pill">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section id="hero" class="py-5 bg-white" style="min-height: 80vh; display: flex; align-items: center;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-start pe-lg-5 mb-5 mb-lg-0">
                    <div class="mb-4">
                        <span class="badge-soft-blue px-3 py-2 rounded-pill"><i class="bi bi-shield-check me-1"></i> LAYANAN BK TERPADU</span>
                    </div>
                    <h1 class="fw-bolder mb-4" style="font-size: 3rem; line-height: 1.2;">
                        Satu Platform untuk Seluruh Layanan <span class="text-blue-accent fst-italic">Bimbingan dan Konseling</span> SMKN 7
                    </h1>
                    <p class="lead text-secondary mb-4" style="font-size: 1.1rem; line-height: 1.6;">
                        Platform digital yang membantu Guru BK, Wali Kelas, dan Siswa mengelola jadwal konseling, pengembangan karir, serta pemantauan kedisiplinan secara terpadu.
                    </p>
                    <div class="d-flex gap-3 mb-4">
                        <a href="/login" class="btn btn-primary-custom px-4 py-2 rounded-pill shadow-sm">Mulai Sekarang &rarr;</a>
                        <a href="#layanan" class="btn btn-outline-custom px-4 py-2 rounded-pill">Jelajahi Layanan</a>
                    </div>
                    <div class="d-flex align-items-center mt-4">
                        <div class="avatar-group d-flex">
                            <img src="https://ui-avatars.com/api/?name=G&background=random" alt="User">
                            <img src="https://ui-avatars.com/api/?name=S&background=random" alt="User">
                            <img src="https://ui-avatars.com/api/?name=W&background=random" alt="User">
                        </div>
                        <span class="avatar-text">Bergabung dengan 1.000+ Siswa & Guru SMKN 7.</span>
                    </div>
                </div>
               <div class="col-lg-6">
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 450px;">
                        <img src="{{ asset('img/logo-smkn7.png') }}"
                            alt="Logo SMK Negeri 7 Samarinda"
                            class="logo-hero img-fluid"
                            width="380" height="380">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION TENTANG -->
    <section id="tentang" class="py-5 bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center mb-5">
                    <span class="text-blue-accent fw-bold text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem;">Kenali Layanan Kami</span>
                    <h2 class="fw-bolder mb-4 mt-2 text-navy" style="font-size: 2.5rem;">Ruang Aman untuk Berkembang</h2>
                    <p class="text-secondary mx-auto" style="line-height: 1.8; font-size: 1.15rem;">
                        Bimbingan dan Konseling (BK) adalah ruang aman, teman bercerita, tempat bertanya, dan penuntun langkah bagi setiap siswa di <strong>SMKN 7 Samarinda</strong>.
                    </p>
                    <p class="text-secondary mx-auto mt-3" style="line-height: 1.8; font-size: 1.1rem;">
                        Masa sekolah tentu tidak selalu mudah. Di sinilah BK hadir untuk mendengarkan tanpa menghakimi. Kami hadir untuk mematahkan stigma; BK bukanlah sekadar ruang sidang bagi siswa, melainkan sahabat diskusi untuk memastikan <strong>kamu tidak pernah menghadapi semua itu sendirian</strong>.
                    </p>
                </div>
            </div>

            <div class="row justify-content-center mt-2">
                <div class="col-12 text-center mb-4">
                    <h4 class="fw-bold text-navy">Apa yang kami lakukan?</h4>
                    <p class="text-secondary">BK membantu siswa SMKN 7 berkembang di empat bidang utama:</p>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                        <div class="mb-3 text-blue-accent"><i class="bi bi-person-heart fs-1"></i></div>
                        <h5 class="fw-bold text-navy">Pribadi</h5>
                        <p class="text-secondary small mb-0 mt-2">Mengenal diri sendiri, mengelola emosi, membangun percaya diri, dan menghadapi stres.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                        <div class="mb-3 text-success"><i class="bi bi-people fs-1"></i></div>
                        <h5 class="fw-bold text-navy">Sosial</h5>
                        <p class="text-secondary small mb-0 mt-2">Menjalin pertemanan sehat, menyelesaikan konflik, dan mencegah perundungan.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                        <div class="mb-3 text-warning"><i class="bi bi-book fs-1"></i></div>
                        <h5 class="fw-bold text-navy">Belajar</h5>
                        <p class="text-secondary small mb-0 mt-2">Menemukan gaya belajar yang cocok dan menjaga semangat saat motivasi menurun.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                        <div class="mb-3 text-danger"><i class="bi bi-briefcase fs-1"></i></div>
                        <h5 class="fw-bold text-navy">Karier</h5>
                        <p class="text-secondary small mb-0 mt-2">Memahami minat bakat, merencanakan PKL, hingga memilih jalur setelah lulus.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CARA KERJA -->
    <section id="cara-kerja" class="py-5 bg-light">
        <div class="container py-4">
            <div class="text-center mb-5 reveal">
                <span class="eyebrow">Mudah &amp; Cepat</span>
                <h2 class="section-title mt-2">Mulai dalam 3 Langkah</h2>
            </div>
            <div class="row g-4 text-center steps-line">
                <div class="col-md-4 step reveal">
                    <div class="step-num mb-3">1</div>
                    <h4 class="h5 fw-bold">Masuk dengan NISN</h4>
                    <p class="text-secondary small px-lg-3">Login menggunakan NISN dan password akunmu.</p>
                </div>
                <div class="col-md-4 step reveal">
                    <div class="step-num mb-3">2</div>
                    <h4 class="h5 fw-bold">Pilih Layanan &amp; Jadwal</h4>
                    <p class="text-secondary small px-lg-3">Tentukan guru BK, tanggal, jam, dan tulis singkat apa yang ingin kamu bicarakan.</p>
                </div>
                <div class="col-md-4 step reveal">
                    <div class="step-num mb-3">3</div>
                    <h4 class="h5 fw-bold">Datang &amp; Bercerita</h4>
                    <p class="text-secondary small px-lg-3">Tunggu status <em>Disetujui</em>, lalu temui guru BK di ruang BK pada jadwalmu.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION LAYANAN -->
    <section id="layanan" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5">
                <span class="text-blue-accent fw-bold text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem;">Fasilitas Kami</span>
                <h2 class="fw-bolder mt-2 text-navy" style="font-size: 2.5rem;">Layanan E-Konseling</h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">
                    Klik pada layanan yang kamu butuhkan untuk melihat detail informasi dan mendaftar sesi.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center service-card" data-bs-toggle="modal" data-bs-target="#modalKonseling">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                            <i class="bi bi-chat-dots fs-1 text-primary"></i>
                        </div>
                        <h4 class="fw-bold text-navy mb-0">E-Konseling Pribadi</h4>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center service-card" data-bs-toggle="modal" data-bs-target="#modalKarir">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                            <i class="bi bi-compass fs-1 text-gold"></i>
                        </div>
                        <h4 class="fw-bold text-navy mb-0">Bimbingan Karir & PKL</h4>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center service-card" data-bs-toggle="modal" data-bs-target="#modalAkademik">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                            <i class="bi bi-journal-bookmark fs-1 text-success"></i>
                        </div>
                        <h4 class="fw-bold text-navy mb-0">Konsultasi Akademik</h4>
                    </div>
                </div>
            </div>

            <!-- Kapan datang ke BK -->
            <div class="mt-5 pt-4 reveal">
                <h3 class="h4 fw-bold text-center mb-4">Kapan sebaiknya datang ke BK?</h3>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4"><div class="when-item"><i class="bi bi-cloud-rain"></i><span class="small">Merasa cemas, sedih, atau tertekan berkepanjangan</span></div></div>
                    <div class="col-md-6 col-lg-4"><div class="when-item"><i class="bi bi-emoji-frown"></i><span class="small">Mengalami atau melihat perundungan (<em>bullying</em>)</span></div></div>
                    <div class="col-md-6 col-lg-4"><div class="when-item"><i class="bi bi-graph-down-arrow"></i><span class="small">Nilai turun atau sulit fokus saat belajar</span></div></div>
                    <div class="col-md-6 col-lg-4"><div class="when-item"><i class="bi bi-signpost-split"></i><span class="small">Bingung memilih tempat PKL atau jalur setelah lulus</span></div></div>
                    <div class="col-md-6 col-lg-4"><div class="when-item"><i class="bi bi-house-heart"></i><span class="small">Ada masalah dengan keluarga atau teman dekat</span></div></div>
                    <div class="col-md-6 col-lg-4"><div class="when-item"><i class="bi bi-ear"></i><span class="small">Sekadar ingin ada yang mendengarkan</span></div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- TIM -->
    <section id="tim" class="py-5 bg-light">
        <div class="container py-4">
            <div class="text-center mb-5 reveal">
                <span class="eyebrow">Tenaga Pendidik</span>
                <h2 class="section-title mt-2">Tim Guru BK SMKN 7</h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">Siap mendengarkan, mendampingi, dan membantu menemukan solusi terbaik untuk setiap langkahmu.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="team-card h-100">
                        <img src="https://ui-avatars.com/api/?name=Pak+Hanafi&background=0d6efd&color=fff&size=150" alt="Foto Pak Hanafi" class="team-avatar shadow-sm" loading="lazy" width="124" height="124">
                        <h4 class="h5 fw-bold">Pak Hanafi</h4>
                        <div class="badge-soft-blue mb-3">Guru Bimbingan Konseling</div>
                        <p class="text-secondary small mb-0 px-2">"Setiap masalah pasti ada jalan keluarnya. Mari berdiskusi santai untuk mencari solusi bersama tanpa rasa takut."</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="team-card h-100">
                        <img src="https://ui-avatars.com/api/?name=Pak+Desnya&background=f78b00&color=fff&size=150" alt="Foto Pak Desnya" class="team-avatar shadow-sm" loading="lazy" width="124" height="124">
                        <h4 class="h5 fw-bold">Pak Desnya</h4>
                        <div class="badge-soft-blue mb-3">Guru Bimbingan Konseling</div>
                        <p class="text-secondary small mb-0 px-2">"Fokus pada potensi dan masa depanmu. Jangan ragu datang ke ruang BK untuk pemetaan karier dan PKL."</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="team-card h-100">
                        <img src="https://ui-avatars.com/api/?name=Bu+April&background=16a34a&color=fff&size=150" alt="Foto Bu April" class="team-avatar shadow-sm" loading="lazy" width="124" height="124">
                        <h4 class="h5 fw-bold">Bu April</h4>
                        <div class="badge-soft-blue mb-3">Guru Bimbingan Konseling</div>
                        <p class="text-secondary small mb-0 px-2">"Ruang BK adalah ruang aman untukmu. Jangan dipendam sendirian, ibu selalu siap mendengarkan ceritamu."</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="team-card h-100">
                        <img src="https://ui-avatars.com/api/?name=Pak+Alam&background=7c3aed&color=fff&size=150" alt="Foto Pak Alam" class="team-avatar shadow-sm" loading="lazy" width="124" height="124">
                        <h4 class="h5 fw-bold">Pak Alam</h4>
                        <div class="badge-soft-blue mb-3">Guru Bimbingan Konseling</div>
                        <p class="text-secondary small mb-0 px-2">"Setiap siswa punya potensi masing-masing. Ceritakan apa yang kamu rasakan, kita cari jalannya bersama."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-5 bg-white">
        <div class="container py-4">
            <div class="text-center mb-5 reveal">
                <span class="eyebrow">Pertanyaan Umum</span>
                <h2 class="section-title mt-2">Masih Ragu? Cek Dulu Ini</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8 reveal">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h3 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#f1" aria-expanded="true">Apakah ke BK berarti aku bermasalah?</button></h3>
                            <div id="f1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion"><div class="accordion-body text-secondary">Tidak. BK bukan hanya untuk siswa bermasalah. Siapa pun boleh datang, bahkan untuk hal kecil seperti bingung memilih jurusan atau sekadar ingin bercerita.</div></div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f2" aria-expanded="false">Apakah ceritaku akan dibocorkan?</button></h3>
                            <div id="f2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body text-secondary">Guru BK memegang teguh asas kerahasiaan. Ceritamu tidak disebarkan tanpa izinmu. Satu-satunya pengecualian adalah jika keselamatanmu atau orang lain sedang terancam, dan itu pun ditangani dengan hati-hati serta kamu akan diberi tahu.</div></div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f3" aria-expanded="false">Apakah layanan ini berbayar?</button></h3>
                            <div id="f3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body text-secondary">Tidak. Semua layanan BK gratis untuk seluruh siswa SMKN 7 Samarinda.</div></div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f4" aria-expanded="false">Kalau lupa password atau tidak bisa login?</button></h3>
                            <div id="f4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body text-secondary">Hubungi admin lewat WhatsApp atau email di bagian Kontak, atau datang langsung ke ruang BK.</div></div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f5" aria-expanded="false">Bagaimana kalau situasinya darurat?</button></h3>
                            <div id="f5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body text-secondary">Jangan menunggu persetujuan jadwal. Segera temui guru BK atau guru mana pun di sekolah, atau hubungi orang dewasa yang kamu percaya. Keselamatanmu yang utama.</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION KONTAK -->
    <section id="kontak" class="py-5 bg-white">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="text-blue-accent fw-bold text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem;">Hubungi Kami</span>
                <h2 class="fw-bolder mt-2 text-navy" style="font-size: 2.5rem;">Ada Pertanyaan Lebih Lanjut?</h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">
                    Hubungi tim administrasi kami untuk bantuan kendala sistem atau layanan bimbingan sekolah.
                </p>
            </div>

            <!-- Kartu Kontak (Email & WhatsApp) -->
            <div class="row g-4 justify-content-center mb-5">
                <div class="col-md-6 col-lg-5">
                    <a href="mailto:bk@smkn7-smr.sch.id" class="contact-box">
                        <div class="contact-icon bg-primary bg-opacity-15 text-primary me-3">
                            <i class="bi bi-envelope-at"></i>
                        </div>
                        <div>
                            <span class="text-uppercase fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Kirim Email</span>
                            <h5 class="fw-bold text-navy mb-0">bk@smkn7-smr.sch.id</h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-5">
                    <a href="https://wa.me/6281234567890" target="_blank" class="contact-box">
                        <div class="contact-icon bg-success bg-opacity-15 text-success me-3">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div>
                            <span class="text-uppercase fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Hubungi WhatsApp</span>
                            <h5 class="fw-bold text-navy mb-0">+62 812-3456-7890</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER UTAMA -->
    <footer class="footer-custom pt-5 pb-4">
        <div class="container">
            <div class="row g-4 justify-content-between mb-4">
                <!-- Kolom 1: Info Singkat -->
                <div class="col-lg-4">
                    <h5 class="text-white fw-bold"><span class="text-gold">E-</span>Konseling SMKN 7</h5>
                    <p class="small text-secondary" style="line-height: 1.7;">
                        Platform digital bimbingan dan konseling terintegrasi untuk kenyamanan perkembangan psikologis, akademik, dan karir murid di lingkungan sekolah.
                    </p>
                </div>
                <!-- Kolom 2: Navigasi Halaman -->
                <div class="col-6 col-lg-3">
                    <h5>Navigasi Halaman</h5>
                    <ul class="footer-links small">
                        <li><a href="#hero">Halaman Beranda</a></li>
                        <li><a href="#tentang">Tentang Sistem</a></li>
                        <li><a href="#layanan">Layanan E-Konseling</a></li>
                        <li><a href="#tim">Tim Guru BK</a></li>
                    </ul>
                </div>
                <!-- Kolom 3: Tautan Penting -->
                <div class="col-6 col-lg-3">
                    <h5>Tautan Penting</h5>
                    <ul class="footer-links small">
                        <li><a href="/login?sebagai=siswa">Portal Login Siswa</a></li>
                        <li><a href="/login?sebagai=guru">Portal Login Guru</a></li>
                        <li><a href="#kontak">Pusat Bantuan & Pengaduan</a></li>
                    </ul>
                </div>
            </div>

            <hr class="border-secondary opacity-25 my-4">

            <!-- Copyright & Kebijakan -->
            <div class="row align-items-center small text-secondary">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    Copyright © 2026 SMK Negeri 7 Samarinda | All Rights Reserved
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-secondary text-decoration-none me-3">Kebijakan Privasi</a>
                    <a href="#" class="text-secondary text-decoration-none">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    </div>{{-- /#situs --}}

    <!-- ============================================== -->
    <!-- DAFTAR POP-UP (MODALS) -->
    <!-- ============================================== -->
    <div class="modal fade" id="modalKonseling" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h4 class="modal-title fw-bolder text-navy"><i class="bi bi-chat-heart text-primary me-2"></i> E-Konseling Pribadi</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <h6 class="fw-bold text-navy">Apa itu E-Konseling Pribadi?</h6>
                    <p class="text-secondary mb-4">Layanan komunikasi empat mata antara siswa dan Guru Bimbingan Konseling. Ini adalah tempat yang tepat jika kamu ingin bercerita tentang masalah keluarga, tekanan pertemanan (bullying), kesehatan mental, atau beban pikiran lainnya tanpa merasa dihakimi.</p>
                    <div class="box-hijau mb-4">
                        <h6 class="fw-bold text-success mb-2"><i class="bi bi-shield-lock-fill me-1"></i> Asas Kerahasiaan Terjamin 100%</h6>
                        <p class="text-success mb-0 small" style="opacity: 0.9;"><strong>Sumpah Profesi BK:</strong> Segala bentuk cerita, identitas, dan data yang kamu sampaikan dalam sesi konseling dijamin kerahasiaannya. Guru BK tidak akan pernah menyebarkan ceritamu kepada pihak lain tanpa izin langsung darimu.</p>
                    </div>
                    <h6 class="fw-bold text-navy">Cara Mendaftar:</h6>
                    <ul class="text-secondary small mb-0" style="line-height: 1.8;">
                        <li>Masuk (Login) menggunakan NISN dan Password.</li>
                        <li>Pada dashboard, pilih menu <strong>Buat Janji Baru</strong>.</li>
                        <li>Pilih Guru BK, tanggal, jam, dan ketikkan sedikit keluhanmu.</li>
                        <li>Sistem otomatis memberitahu Guru. Tunggu statusnya "Disetujui"!</li>
                    </ul>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4 justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Tutup</button>
                    <a href="/login" class="btn btn-primary-custom rounded-pill px-4">Saya Mengerti, Lanjut Login &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalKarir" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h4 class="modal-title fw-bolder text-navy"><i class="bi bi-compass text-warning me-2"></i> Bimbingan Karir & PKL</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <h6 class="fw-bold text-navy">Apa itu Bimbingan Karir?</h6>
                    <p class="text-secondary mb-4">Layanan diskusi dan tes peminatan untuk membantu kamu mengenali potensi diri. Sangat cocok bagi kamu yang masih bingung memilih tempat PKL, ingin melanjutkan kuliah, atau sedang menyiapkan diri untuk melamar kerja setelah lulus SMK.</p>
                    <div class="box-kuning mb-4">
                        <h6 class="fw-bold mb-2" style="color: #d97706;"><i class="bi bi-map-fill me-1"></i> Fokus Pemetaan Masa Depan</h6>
                        <p class="mb-0 small" style="color: #d97706; opacity: 0.9;">Kami membantu merancang peta jalan karirmu agar sesuai dengan kompetensi keahlian/jurusan yang kamu pelajari di sekolah. Jangan ragu berdiskusi agar kamu lebih siap dan percaya diri melangkah!</p>
                    </div>
                    <h6 class="fw-bold text-navy">Cara Mendaftar:</h6>
                    <ul class="text-secondary small mb-0" style="line-height: 1.8;">
                        <li>Masuk (Login) menggunakan NISN dan Password.</li>
                        <li>Pada dashboard, pilih menu <strong>Buat Janji Bimbingan Karir</strong>.</li>
                        <li>Pilih Guru BK spesialis karir, tentukan jadwal luangmu.</li>
                        <li>Hadir pada sesi untuk mengisi tes minat bakat atau konsultasi PKL.</li>
                    </ul>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4 justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Tutup</button>
                    <a href="/login" class="btn btn-primary-custom rounded-pill px-4">Saya Mengerti, Lanjut Login &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAkademik" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h4 class="modal-title fw-bolder text-navy"><i class="bi bi-journal-bookmark text-primary me-2"></i> Konsultasi Akademik</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <h6 class="fw-bold text-navy">Apa itu Konsultasi Akademik?</h6>
                    <p class="text-secondary mb-4">Layanan pendampingan khusus untuk mengatasi kesulitan belajar, masalah absensi, atau kendala adaptasi dengan mata pelajaran kejuruan yang mungkin dirasa terlalu berat.</p>
                    <div class="box-biru mb-4">
                        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-lightbulb-fill me-1"></i> Solusi Tanpa Penghakiman</h6>
                        <p class="text-primary mb-0 small" style="opacity: 0.9;">Nilai turun atau kesulitan belajar bukanlah sebuah aib. Guru BK hadir untuk berdiskusi, membantu menemukan gaya belajar yang paling efektif untukmu, serta mencari akar masalah dari kendala belajarmu di kelas.</p>
                    </div>
                    <h6 class="fw-bold text-navy">Cara Mendaftar:</h6>
                    <ul class="text-secondary small mb-0" style="line-height: 1.8;">
                        <li>Masuk (Login) menggunakan NISN dan Password.</li>
                        <li>Pada dashboard, pilih menu <strong>Buat Janji Konsultasi Akademik</strong>.</li>
                        <li>Pilih jadwal yang tidak bentrok dengan jam pelajaran inti.</li>
                        <li>Datang dan temukan solusi belajar terbaik bersama Guru BK-mu!</li>
                    </ul>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4 justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Tutup</button>
                    <a href="/login" class="btn btn-primary-custom rounded-pill px-4">Saya Mengerti, Lanjut Login &rarr;</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Memanggil Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function () {
            var scene = document.querySelector('.animasi-sekolah');
            var kamera = scene.querySelector('.kamera');
            var imgs = Array.prototype.slice.call(scene.querySelectorAll('.bingkai img'));

            function mulai() { scene.classList.add('siap'); }

            // Tunggu semua gambar selesai di-decode, baru mulai (hindari patah di awal)
            Promise.all(imgs.map(function (img) {
                return img.decode ? img.decode().catch(function () {}) : Promise.resolve();
            })).then(function () { requestAnimationFrame(mulai); });

            // Jaga-jaga kalau ada gambar yang tak kunjung selesai
            setTimeout(mulai, 3000);

            // Hanya hitung animasi KAMERA (anak-anaknya selesai lebih dulu)
            kamera.addEventListener('animationend', function (e) {
                if (e.target === kamera) scene.classList.add('selesai');
            });

            // Pause animasi saat intro sudah tidak terlihat
            if ('IntersectionObserver' in window) {
                new IntersectionObserver(function (en) {
                    scene.classList.toggle('diam', !en[0].isIntersecting);
                }).observe(scene);
            }

            // Tombol: geser layar ke bawah, lalu intro & zona peralihan DIHAPUS
            // dari halaman sehingga pengunjung tidak bisa kembali ke atas.
            var root = document.documentElement;
            var tombol = document.getElementById('tombolMasuk');
            var situs = document.getElementById('situs');
            var kurangGerak = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
            var sedangTurun = false;

            // Setelah di-refresh, selalu mulai dari intro (jangan dipulihkan ke tengah halaman)
            if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
            window.scrollTo(0, 0);

            function halus(t) { return -(Math.cos(Math.PI * t) - 1) / 2; }   // mulai & berhenti sangat lembut

            // Hilangkan intro + zona peralihan, lalu posisikan halaman utama di paling atas
            function selesai() {
                root.classList.remove('turun');
                root.classList.add('masuk');          // intro & zona peralihan jadi display:none
                var sebelum = root.style.scrollBehavior;
                root.style.scrollBehavior = 'auto';
                window.scrollTo(0, 0);                // tampilan sama persis, hanya "atas"-nya sudah tidak ada
                root.style.scrollBehavior = sebelum;
                sedangTurun = false;
            }

            tombol.addEventListener('click', function (e) {
                e.preventDefault();
                if (sedangTurun) return;
                sedangTurun = true;

                // Selama bergeser, pengunjung tidak bisa menggulir sendiri
                root.classList.remove('kunci');
                root.classList.add('turun');

                var tujuan = situs.getBoundingClientRect().top + window.pageYOffset;
                if (kurangGerak) { window.scrollTo(0, tujuan); selesai(); return; }

                var awal = window.pageYOffset, jarak = tujuan - awal, t0 = null;
                // Makin jauh jaraknya, makin lama (antara 2,2 s dan 3,6 s). Ubah angka ini kalau mau lebih cepat/lambat.
                var durasi = Math.min(3600, Math.max(2200, jarak * 1.5));
                root.style.scrollBehavior = 'auto';   // jangan bentrok dengan smooth-scroll CSS

                function langkah(t) {
                    if (t0 === null) t0 = t;
                    var p = Math.min((t - t0) / durasi, 1);
                    window.scrollTo(0, awal + jarak * halus(p));
                    if (p < 1) { requestAnimationFrame(langkah); }
                    else { selesai(); }
                }
                requestAnimationFrame(langkah);
            });

            // Animasi muncul untuk elemen ber-class "reveal" saat masuk layar
            if ('IntersectionObserver' in window) {
                root.classList.add('reveal-aktif');
                var obs = new IntersectionObserver(function (daftar) {
                    daftar.forEach(function (d) {
                        if (d.isIntersecting) { d.target.classList.add('tampil'); obs.unobserve(d.target); }
                    });
                }, { threshold: .15, rootMargin: '0px 0px -6% 0px' });
                Array.prototype.forEach.call(document.querySelectorAll('.reveal'), function (el) { obs.observe(el); });
            }
        })();
    </script>

</body>
</html>
