<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\{
    HomeController,
    AdminController,
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
use App\Models\User;

// ==============================
// PUBLIC ROUTES
// ==============================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show');

// ==============================
// AUTH ROUTES (guest only)
// ==============================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register']);

    Route::get('/admin/statistics', [StatisticsController::class, 'index'])
    ->name('admin.statistics');

    // Password Reset
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
    Route::post('/password/update', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Logout (authenticated) — satu route global untuk semua users
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==============================
// EMAIL VERIFICATION (fixed)
// ==============================

// Halaman notice — butuh login
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Proses verifikasi — tidak butuh login
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    // Cek hash email
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Link verifikasi tidak valid.');
    }

    // Kalau sudah terverifikasi
    if ($user->hasVerifiedEmail()) {
        return redirect()->route('login')->with('success', 'Email sudah terverifikasi, silakan login.');
    }

    // Tandai sebagai terverifikasi
    $user->markEmailAsVerified();

    // Redirect ke login
    return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

// Kirim ulang email — hanya bisa saat login
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});
// ==============================
// DASHBOARD REDIRECT
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
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');

    Route::post('/password/update', [PasswordController::class, 'update'])->name('profile.password.update');
});

// ==============================
// ADMIN ROUTES
// ==============================
Route::prefix('admin')->middleware(['auth', 'role.check:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/logout', function () {
        return view('admin.logout');
    })->name('logout');

    Route::get('/add-admin', [AdminController::class, 'showAddAdminForm'])->name('add-admin');
    Route::post('/add-admin', [AdminController::class, 'storeAdmin'])->name('store-admin');
    Route::get('/list-admins', [AdminController::class, 'listAdmins'])->name('list-admins');
    Route::delete('/delete-admin/{id}', [AdminController::class, 'deleteAdmin'])->name('delete-admin');

    Route::resource('campaigns', AdminCampaignController::class);

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
    Route::get('/history', [DonationController::class, 'myDonations'])->name('history');
});

// ==============================
// MIDTRANS CALLBACK
// ==============================
Route::post('/midtrans/callback', [MidtransController::class, 'handleCallback'])->name('midtrans.callback');