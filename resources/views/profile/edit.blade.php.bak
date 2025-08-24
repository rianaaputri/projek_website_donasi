@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">Edit Profil</h1>

        @if(session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="block font-medium">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full border-gray-300 rounded px-4 py-2">
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block font-medium">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="w-full border-gray-300 rounded px-4 py-2">
                @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="block font-medium">No. Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full border-gray-300 rounded px-4 py-2">
                @error('phone') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="address" class="block font-medium">Alamat</label>
                <textarea name="address" id="address" rows="3"
                    class="w-full border-gray-300 rounded px-4 py-2">{{ old('address', $user->address) }}</textarea>
                @error('address') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
                <a href="{{ route('profile.show') }}" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
