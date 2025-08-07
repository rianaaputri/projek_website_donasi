@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-2">Profil Saya</h1>
        <p class="text-gray-600 mb-6">Berikut adalah informasi akun Anda.</p>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-8 text-white">
                <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                <p class="capitalize">{{ $user->role ?? 'user' }}</p>
                @if($user->email_verified_at)
                    <span class="text-green-300 text-sm">Email Terverifikasi</span>
                @else
                    <span class="text-yellow-300 text-sm">Email Belum Terverifikasi</span>
                @endif
            </div>

            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold mb-4">Informasi Personal</h3>
                        <p><strong>Nama:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Telepon:</strong> {{ $user->phone ?? 'Belum diisi' }}</p>
                        <p><strong>Alamat:</strong> {{ $user->address ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-4">Informasi Akun</h3>
                        <p><strong>Bergabung Sejak:</strong> {{ $user->created_at->format('d F Y') }}</p>
                        <p><strong>Update Terakhir:</strong> {{ $user->updated_at->format('d F Y H:i') }}</p>
                        <p><strong>Status:</strong> Aktif</p>
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <a href="{{ route('profile.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit Profil</a>
                    <a href="/" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Kembali ke Beranda</a>
                    @if(!$user->email_verified_at)
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                                Kirim Verifikasi Email
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
