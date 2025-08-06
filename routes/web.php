<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\{
    HomeController,
    MidtransController,
    CampaignController,
    UserController,
    ProfileController,
    DonationController,
    Auth\LoginController,
    Auth\PasswordResetLinkController,
    Auth\NewPasswordController,
    AdminDashboardController
};
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;

// ==============================
// PUBLIC ROUTES
// ==============================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/add-admin', [AdminController::class, 'addAdmin'])->name('admin.add-admin');
    Route::post('/admin/add-admin', [AdminController::class, 'storeAdmin'])->name('admin.store-admin');
    // routes lainnya...
});

// ==============================
// AUTH ROUTES (guest only)
// ==============================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register']);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store'); // UBAH NAMA INI jika perlu
});

// Logout (authenticated) — satu route global untuk semua users
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==============================
// EMAIL VERIFICATION (auth)
// ==============================
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        auth()->logout();
        return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi sudah dikirim!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// ==============================
// DASHBOARD REDIRECT (optional)
// ==============================
Route::get('/dashboard', function () {
    $user = auth()->user();
    return $user->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect('/');
})->middleware(['auth'])->name('dashboard');

// ==============================
// USER PROTECTED ROUTES
// ==============================
// Profile & user routes: protected by auth (so admin can access their profile too)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');

    // Manual Password Update
    Route::post('/password/update', [PasswordController::class, 'update'])->name('profile.password.update');
});

// ==============================
// ADMIN ROUTES
// ==============================
// Use auth + role.check:admin. No 'verified' so admin not blocked by email verification.
Route::prefix('admin')->middleware(['auth', 'role.check:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Admin logout confirmation page (GET) — displays view with form that posts to global logout
    Route::get('/logout', function () {
        return view('admin.logout'); // resources/views/admin/logout.blade.php
    })->name('logout');

    // Campaign Management
    Route::resource('campaigns', AdminCampaignController::class);

    // Donation Management
    Route::get('/donations', [AdminDonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/{donation}', [AdminDonationController::class, 'show'])->name('donations.show');
    Route::patch('/donations/{donation}/status', [AdminDonationController::class, 'updateStatus'])->name('donations.update-status');
});

// ==============================
// DONATION PUBLIC ROUTES
// ==============================
Route::prefix('donation')->name('donation.')->group(function () {
    Route::get('/', [DonationController::class, 'index'])->name('index');
    Route::get('/create/{campaign}', [DonationController::class, 'create'])->name('create');
    Route::post('/', [DonationController::class, 'store'])->name('store');

    Route::get('/payment/{donation}', [DonationController::class, 'payment'])->name('payment');
    Route::get('/success/{donation}', [DonationController::class, 'success'])->name('success');
    Route::get('/status/{donation}', [DonationController::class, 'checkStatus'])->name('status');
});

// ==============================
// MIDTRANS CALLBACK
// ==============================
Route::post('/midtrans/callback', [MidtransController::class, 'handleCallback'])->name('midtrans.callback');

// ==============================
// DEBUG (Remove in production)
// ==============================
Route::get('/debug-auth', function () {
    dd([
        'authenticated' => auth()->check(),
        'current_user' => auth()->user(),
        'user_role' => auth()->user()->role ?? 'not logged in',
        'email_verified' => auth()->check() ? auth()->user()->hasVerifiedEmail() : false,
    ]);
});

Route::get('/debug-role', function () {
    $user = auth()->user();

    if (!$user) {
        return 'Belum login.';
    }

    return response()->json([
        'ID' => $user->id,
        'Nama' => $user->name,
        'Email' => $user->email,
        'Role' => $user->role,
        'Email Verified' => $user->hasVerifiedEmail(),
    ]);
})->middleware(['auth']);

Route::get('/debug-user-only', function () {
    return 'HALAMAN INI HANYA BISA DIAKSES USER';
})->middleware(['auth', 'role.check:user']);

Route::get('/debug-admin-only', function () {
    return 'HALAMAN INI HANYA BISA DIAKSES ADMIN';
})->middleware(['auth', 'role.check:admin']);
