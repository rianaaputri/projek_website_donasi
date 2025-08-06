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
    AdminDashboardController,
    AdminController
};
use App\Http\Controllers\Auth\PasswordController; 
use App\Http\Controllers\Auth\PasswordResetLinkController; 
use App\Http\Controllers\Auth\NewPasswordController; 
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
 use App\Http\Middleware\RoleCheck; 

// ==============================
// PUBLIC ROUTES
// ==============================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show');

// ==============================
// AUTH ROUTES
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
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==============================
// EMAIL VERIFICATION
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
// DASHBOARD REDIRECT
// ==============================
Route::get('/dashboard', function () {
    $user = auth()->user();
    return $user->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==============================
// USER PROTECTED ROUTES
// ==============================
Route::middleware(['auth', 'verified', 'rolecheck:user'])->group(function () {
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
Route::prefix('admin')->middleware(['auth', 'verified', 'rolecheck:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

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
// ... (route lain sebelumnya)

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
})->middleware(['auth', 'rolecheck:user']);

Route::get('/debug-admin-only', function () {
    return 'HALAMAN INI HANYA BISA DIAKSES ADMIN';
})->middleware(['auth', 'rolecheck:admin']);

Route::get('/debug-rolecheck-test', function () {
    return 'Middleware role.check BERHASIL DIJALANKAN';
})->middleware(['auth', 'rolecheck:admin']);


