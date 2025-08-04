<?php

use Illuminate\Http\Request; // Pastikan ini di-import
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\{
    HomeController,
    MidtransController, // Pastikan ini di-import jika digunakan
    CampaignController,
    UserController,
    ProfileController,
    DonationController,
    AuthController,
    AdminDashboardController,
    PasswordController // Pastikan ini di-import untuk route admin
};

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show');


//pasword reset routes
Route::post('/password/update', [PasswordController::class, 'update'])->name('password.update');

// Guest Routes (Login/Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register']); 
});

// Donation Routes (Public) - Ini adalah rute untuk menampilkan form donasi
Route::get('/donation', [DonationController::class, 'index'])->name('donation.index');
Route::get('/donation/create/{campaign}', [DonationController::class, 'create'])->name('donation.create');
Route::post('/donation', [DonationController::class, 'store'])->name('donation.store');

// User Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Email Verification Notice
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // --- MODIFIKASI UNTUK PENGGUNA BIASA ---
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill(); // Tandai email sebagai terverifikasi

        // Opsional: Logout pengguna agar mereka harus login lagi
        auth()->guard('web')->logout();

        // Arahkan ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Email Anda berhasil diverifikasi! Silakan login untuk melanjutkan.');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi sudah dikirim!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');


    // Dashboard Redirect (tetap sama, ini untuk saat login/akses /dashboard secara umum)
    // web.php
// web.php

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'user' => redirect()->route('profile.show'),
        default => redirect('/'),
    };
})->name('dashboard')->middleware('auth');

    // Rute untuk halaman pembayaran donasi
    // DILINDUNGI OLEH MIDDLEWARE 'auth'
    Route::get('/donation/payment/{id}', [DonationController::class, 'payment'])
        ->name('donation.payment'); // Middleware 'auth' sudah diterapkan di group ini
    
    // Rute untuk halaman sukses pembayaran
    Route::get('/donation-success/{id}', [DonationController::class, 'success'])->name('donation.success');
    // Rute untuk check status Midtrans (biasanya dipanggil oleh webhook atau AJAX)
    Route::get('/donation/status/{id}', [DonationController::class, 'checkStatus'])->name('donation.status');
});


// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest admin routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // Protected admin routes
    Route::middleware(['auth:admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'layouts.admin'])->name('dashboard');

        
        // Campaign routes
        Route::resource('campaigns', \App\Http\Controllers\Admin\CampaignController::class);
        
        // Donation routes
        Route::get('/donations', [\App\Http\Controllers\Admin\DonationController::class, 'index'])->name('donations.index');
        Route::get('/donations/{donation}', [\App\Http\Controllers\Admin\DonationController::class, 'show'])->name('donations.show');
        Route::patch('/donations/{donation}/status', [\App\Http\Controllers\Admin\DonationController::class, 'updateStatus'])->name('donations.update-status');
    });
});

// Public Donation Routes
Route::prefix('donation')->name('donation.')->group(function () {
    Route::get('/', [DonationController::class, 'index'])->name('index');
    Route::get('/create/{campaign}', [DonationController::class, 'create'])->name('create');
    Route::post('/', [DonationController::class, 'store'])->name('store');
    Route::get('/payment/{donation}', [DonationController::class, 'payment'])->name('payment');
    Route::get('/success/{donation}', [DonationController::class, 'success'])->name('success');
    Route::get('/status/{donation}', [DonationController::class, 'checkStatus'])->name('status');
});

// Debugging Route (Optional)
Route::get('/debug-auth', function () {
    dd([
        'authenticated' => auth()->check(),
        'current_user' => auth()->user(),
        'user_role' => auth()->user()->role ?? 'not logged in',
        'email_verified' => auth()->check() ? auth()->user()->hasVerifiedEmail() : false,
    ]);
});

//midtrans callback route
// Proses Pembayaran Donasi
Route::get('/donation/payment/{id}', [DonationController::class, 'payment'])->name('donation.payment');
Route::get('/donation-success/{id}', [DonationController::class, 'success'])->name('donation.success');
Route::get('/donation/status/{id}', [DonationController::class, 'checkStatus'])->name('donation.status');
Route::get('/donation/{campaign}', [DonationController::class, 'create'])->name('donation.create');
Route::get('/donation/{id}/check-status', [DonationController::class, 'checkStatus'])->name('donation.checkStatus');

// Midtrans Callback (dari dashboard Midtrans)
Route::post('/midtrans/callback', [DonationController::class, 'handleCallback'])->name('midtrans.callback');
Route::get('/donation/status/{id}', [DonationController::class, 'checkStatus'])->name('donation.status');
Route::get('/donation/{campaign}', [DonationController::class, 'create'])->name('donation.create');
Route::get('/donation/{id}/check-status', [DonationController::class, 'checkStatus'])->name('donation.checkStatus');

// Midtrans Callback (dari dashboard Midtrans)
Route::post('/midtrans/callback', [DonationController::class, 'handleCallback'])->name('midtrans.callback');
Route::get('/donation/payment/{id}', [DonationController::class, 'payment'])->name('donation.payment');
Route::get('/donation-success/{id}', [DonationController::class, 'success'])->name('donation.success');
Route::get('/donation/status/{id}', [DonationController::class, 'checkStatus'])->name('donation.status');
Route::get('/donation/{campaign}', [DonationController::class, 'create'])->name('donation.create');
Route::get('/donation/{id}/check-status', [DonationController::class, 'checkStatus'])->name('donation.checkStatus');

// Midtrans Callback (dari dashboard Midtrans)
Route::post('/midtrans/callback', [DonationController::class, 'handleCallback'])->name('midtrans.callback');
Route::get('/donation/status/{id}', [DonationController::class, 'checkStatus'])->name('donation.status');
Route::get('/donation/{campaign}', [DonationController::class, 'create'])->name('donation.create');
Route::get('/donation/{id}/check-status', [DonationController::class, 'checkStatus'])->name('donation.checkStatus');



// Midtrans Callback (dari dashboard Midtrans)
Route::post('/midtrans/callback', [DonationController::class, 'handleCallback'])->name('midtrans.callback');

