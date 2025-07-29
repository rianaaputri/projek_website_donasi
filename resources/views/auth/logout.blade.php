<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container">
        <h2>Konfirmasi Logout</h2>
        
        <!-- User Logout -->
        @auth('web')
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <p>Apakah Anda yakin ingin logout?</p>
            <button type="submit" class="btn btn-danger">Ya, Logout</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
        </form>
        @endauth
        
        <!-- Admin Logout -->
        @auth('admin')
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <p>Apakah Anda yakin ingin logout sebagai Admin?</p>
            <button type="submit" class="btn btn-danger">Ya, Logout Admin</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
        </form>
        @endauth
    </div>
</body>
</html>