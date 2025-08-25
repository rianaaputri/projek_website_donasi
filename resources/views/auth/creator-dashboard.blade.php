@extends('layouts.app')
@section('title', 'Dashboard Pembuat Campaign')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h2 class="mb-0"><i class="fas fa-bullhorn me-2"></i> Selamat Datang, Pembuat Campaign!</h2>
                    <p class="mb-0 opacity-90">Akun kamu sudah terverifikasi. Sekarang kamu bisa mulai membuat campaign donasi.</p>
                </div>
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="text-success mb-3">Email Berhasil Diverifikasi!</h4>
                    <p class="text-muted mb-4">Terima kasih sudah menyelesaikan proses verifikasi. Kamu sekarang bisa membuat campaign donasi untuk membantu sesama.</p>

                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center mt-5">
                        <a href="{{ route('user.campaigns.create') }}" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-plus-circle me-2"></i> Buat Campaign Baru
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg px-5">
                            <i class="fas fa-home me-2"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #2196F3, #64B5F6);
}
.card {
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}
</style>
@endsection