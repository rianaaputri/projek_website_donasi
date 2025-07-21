<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Donasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS warna biru putih --}}
    <style>
        body {
            background-color: #e3f2fd;
            font-family: 'Segoe UI', sans-serif;
        }
        .donation-form {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 255, 0.1);
            max-width: 600px;
            margin: 50px auto;
        }
        .donation-form h2 {
            color: #0d6efd;
            margin-bottom: 30px;
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-primary:hover {
            background-color: #084298;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="donation-form">
            <h2 class="text-center">Form Donasi</h2>

            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('donasi.store') }}" method="POST">
                @csrf

                {{-- Pilih nominal --}}
                <div class="mb-3">
                    <label class="form-label">Nominal Donasi</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="nominal" value="50000" id="nominal50">
                        <label class="form-check-label" for="nominal50">Rp 50.000</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="nominal" value="100000" id="nominal100">
                        <label class="form-check-label" for="nominal100">Rp 100.000</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="nominal" value="200000" id="nominal200">
                        <label class="form-check-label" for="nominal200">Rp 200.000</label>
                    </div>
                    <input type="number" class="form-control mt-2" name="nominal_custom" placeholder="Atau isi nominal sendiri">
                </div>

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label">Nama Donatur</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">Email Donatur</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                {{-- Komentar --}}
                <div class="mb-3">
                    <label class="form-label">Komentar (opsional)</label>
                    <textarea name="komentar" class="form-control" rows="3" placeholder="Tulis pesan..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Lanjut ke Pembayaran</button>
            </form>
        </div>
    </div>
</body>
</html>
