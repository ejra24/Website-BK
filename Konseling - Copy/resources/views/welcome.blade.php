<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem BK - SMKN 7 Samarinda</title>
    
    <!-- Memanggil Google Fonts (Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Memanggil Bootstrap CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
        }

        /* Tema Warna Hero Section */
        .text-navy { color: #0B2447; }
        .text-blue-accent { color: #0d6efd; }
        
        .btn-primary-custom {
            background-color: #0d6efd;
            color: white;
            font-weight: 600;
        }
        .btn-primary-custom:hover {
            background-color: #0b5ed7;
            color: white;
        }
        .btn-outline-custom {
            border: 1px solid #ced4da;
            color: #495057;
            font-weight: 600;
        }
        .btn-outline-custom:hover {
            background-color: #f8f9fa;
        }
        .badge-soft-blue {
            background-color: #e0f0ff;
            color: #0d6efd;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .avatar-group img {
            width: 35px;
            height: 35px;
            border: 2px solid white;
            border-radius: 50%;
            margin-left: -10px;
        }
        .avatar-group img:first-child {
            margin-left: 0;
        }
        
        /* ------------------------------------------------ */
        /* KUSTOMISASI POP-UP OFFCANVAS (SESUAI REFERENSI)  */
        /* ------------------------------------------------ */
        .offcanvas-bottom {
            height: auto !important;
            border-top-left-radius: 24px;
            border-top-right-radius: 24px;
            box-shadow: 0 -15px 40px rgba(0,0,0,0.08);
            border: none;
        }
        
        /* Tombol Close Oranye */
        .btn-close-orange {
            background-color: #f78b00;
            color: white;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            position: absolute;
            top: 5px; /* Sejajar dengan teks pertama */
            right: 0;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-close-orange:hover {
            background-color: #d67a00;
        }

        /* Daftar Menu Navigasi */
        .list-menu-custom a {
            color: #1c4587; /* Biru Navy / Gelap sesuai referensi */
            font-weight: 700;
            font-size: 1.15rem;
            padding: 16px 0;
            border-bottom: 1px solid #d1d5db; /* Garis abu-abu pembatas */
            text-decoration: none;
            display: block;
            transition: color 0.2s;
        }
        .list-menu-custom a:hover {
            color: #f78b00; /* Berubah oranye saat di-hover */
        }
    </style>
</head>
<body>

    <!-- HERO SECTION (Tampilan Awal Tanpa Navbar) -->
    <section class="py-5" style="min-height: 100vh; display: flex; align-items: center;">
        <div class="container">
            <div class="row align-items-center">
                
                <div class="col-lg-6 text-start pe-lg-5 mb-5 mb-lg-0">
                    <div class="mb-4">
                        <span class="badge-soft-blue px-3 py-2 rounded-pill">
                            <i class="bi bi-shield-check me-1"></i> LAYANAN BK TERPADU
                        </span>
                    </div>

                    <h1 class="fw-bolder mb-4" style="font-size: 3rem; line-height: 1.2;">
                        Satu Platform untuk Seluruh Layanan <span class="text-blue-accent fst-italic">Bimbingan dan Konseling</span> SMKN 7
                    </h1>
                    
                    <p class="lead text-secondary mb-4" style="font-size: 1.1rem; line-height: 1.6;">
                        Platform digital yang membantu Guru BK, Wali Kelas, dan Siswa mengelola jadwal konseling, pengembangan karir, serta pemantauan kedisiplinan secara terpadu.
                    </p>
                    
                    <div class="d-flex gap-3 mb-4">
                        <button class="btn btn-primary-custom px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="offcanvas" data-bs-target="#menuPopup">
                            Mulai Sekarang &rarr;
                        </button>
                    </div>
                    
                    <div class="d-flex align-items-center mt-4">
                        <div class="avatar-group d-flex">
                            <img src="https://ui-avatars.com/api/?name=G&background=random" alt="User">
                            <img src="https://ui-avatars.com/api/?name=S&background=random" alt="User">
                            <img src="https://ui-avatars.com/api/?name=W&background=random" alt="User">
                        </div>
                        <span class="ms-3 text-secondary" style="font-size: 0.85rem;">Bergabung dengan 1.000+ Siswa & Guru SMKN 7.</span>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="bg-light rounded-4 shadow-sm d-flex justify-content-center align-items-center" style="height: 450px; border: 2px dashed #dee2e6;">
                        <div class="text-center text-muted">
                            <i class="bi bi-image fs-1 mb-3"></i>
                            <h5>Ilustrasi Konseling</h5>
                            <p class="small">Masukkan gambar/ilustrasi di sini</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- POP-UP MENU (Telah disesuaikan dengan referensi) -->
    <div class="offcanvas offcanvas-bottom py-5" tabindex="-1" id="menuPopup" aria-labelledby="menuPopupLabel">
        <div class="offcanvas-body container overflow-hidden">
            <div class="row justify-content-between">
                
                <!-- Sisi Kiri: Judul & Deskripsi -->
                <div class="col-md-4 pe-md-4 mb-4 mb-md-0 pt-2">
                    <h3 class="fw-bolder mb-3" style="color: #f78b00;">Bimbingan & Konseling</h3>
                    <p style="color: #374151; font-weight: 500; line-height: 1.6;">
                        Website aplikasi yang akan digunakan oleh siswa untuk layanan E-Konseling, pencarian bimbingan karir, pengelolaan jadwal diskusi, serta pemantauan kedisiplinan dalam lingkungan sekolah sehari-hari.
                    </p>
                </div>

                <!-- Sisi Kanan: Daftar Menu & Tombol Close -->
                <div class="col-md-7 position-relative">
                    
                    <!-- Tombol Close Oranye (Menempel di kanan container menu) -->
                    <button type="button" class="btn-close-orange shadow-sm" data-bs-dismiss="offcanvas" aria-label="Close">
                        <i class="bi bi-x fs-4"></i>
                    </button>

                    <!-- List Menu -->
                    <div class="list-menu-custom pe-5">
                        <a href="/tentang">Tentang Bimbingan Konseling</a>
                        <a href="/layanan">E-Konseling & Bimbingan Karir</a>
                        <a href="/kontak">Informasi & Kontak Pengaduan</a>
                        <a href="/login">Masuk / Login ke Portal Siswa</a>
                    </div>
                    
                </div>

            </div>
        </div>
    </div>

    <!-- Memanggil Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>