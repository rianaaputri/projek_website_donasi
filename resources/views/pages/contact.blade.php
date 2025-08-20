@extends('layouts.app')

@section('title', 'Hubungi Kami - Kindify.id')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="text-center mb-5">Hubungi Kami</h2>

                <div class="card shadow-sm">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.send') }}" method="POST">
                            @csrf

                            <!-- Nama -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                @if(auth()->check())
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name', auth()->user()->name) }}" 
                                        readonly 
                                        style="background-color: #f8f9fa; cursor: not-allowed;"
                                    >
                                    <small class="text-muted">Login sebagai: {{ auth()->user()->name }} | 
                                        <a href="{{ route('logout') }}" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Ganti akun?
                                        </a>
                                    </small>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                @else
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name') }}" 
                                        placeholder="Masukkan nama Anda" 
                                        required
                                    >
                                @endif
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                @if(auth()->check())
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email', auth()->user()->email) }}" 
                                        readonly 
                                        style="background-color: #f8f9fa; cursor: not-allowed;"
                                    >
                                    <small class="text-muted">Digunakan: {{ auth()->user()->email }}</small>
                                @else
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        placeholder="contoh@email.com" 
                                        required
                                    >
                                @endif
                            </div>

                            <!-- Subjek -->
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subjek</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="subject" 
                                    name="subject" 
                                    value="{{ old('subject') }}" 
                                    placeholder="Apa yang ingin Anda sampaikan?" 
                                    required 
                                >
                            </div>

                            <!-- Pesan -->
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan</label>
                                <textarea 
                                    class="form-control" 
                                    id="message" 
                                    name="message" 
                                    rows="5" 
                                    placeholder="Tulis pesan Anda di sini..." 
                                    required
                                >{{ old('message') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Kontak Alternatif -->
                <div class="text-center mt-5">
                    <h5><i class="fas fa-envelope"></i> info@kindify.id</h5>
                    <h5><i class="fas fa-phone"></i> +62 821 3088 6804</h5>
                    <h5><i class="fas fa-map-marker-alt"></i> Cimahi, Jawa Barat, Indonesia</h5>
                </div>
            </div>
        </div>
    </div>
@endsection