<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="h3 fw-bold">Halo, {{ auth('siswa')->user()->nama }}</h1>
        <p class="text-secondary">{{ auth('siswa')->user()->kelas }}. Ini halaman siswa (sementara), isinya menyusul.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Keluar</button>
        </form>
    </div>
</body>
</html>