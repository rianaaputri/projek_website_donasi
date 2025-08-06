{{-- resources/views/admin/logout.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }
        .logout-card {
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
        }
        .logout-card h3 {
            font-weight: 600;
        }
        .btn-confirm {
            background-color: #ff4d4d;
            color: white;
            border: none;
        }
        .btn-confirm:hover {
            background-color: #e04343;
        }
        .btn-cancel {
            background-color: rgba(255,255,255,0.2);
            color: white;
            border: none;
        }
        .btn-cancel:hover {
            background-color: rgba(255,255,255,0.3);
        }
    </style>
</head>
<body>
    <div class="logout-card">
        <i class="fas fa-sign-out-alt fa-3x mb-3"></i>
        <h3>Konfirmasi Logout</h3>
        <p>Anda yakin ingin keluar dari akun admin?</p>

     <form action="{{ route('logout') }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-confirm me-2">
        <i class="fas fa-check me-1"></i> Ya, Logout
    </button>
</form>


        <a href="{{ route('admin.dashboard') }}" class="btn btn-cancel">
            <i class="fas fa-times me-1"></i> Batal
        </a>
    </div>
</body>
</html>
