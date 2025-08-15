<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\{
    HomeController,
    AdminController,
    MidtransController,
    UserController,
    ProfileController,
    DonationController,
    Auth\LoginController,
    Auth\PasswordResetLinkController,
    Auth\NewPasswordController,
    AdminDashboardController,
    CampaignController // ✅ Tambahkan import ini untuk route publik & admin
};
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\User\CampaignController as UserCampaignController;
use App\Http\Controllers\Auth\VerificationController; // ✅ Tambah import
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show');

/*
|--------------------------------------------------------------------------
| Auth (Guest Only)
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| Email Verification
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Verification notice - gunakan controller
    Route::get('/email/verify', [VerificationController::class, 'notice'])
        ->name('verification.notice');
    
    // Resend verification
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

// Verification verify - TANPA middleware signed dan auth
// Karena kita handle manual di controller
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['throttle:6,1'])
    ->name('verification.verify');

// Optional: Route untuk expired link (jika mau bikin halaman khusus)
Route::get('/email/verify/expired', function () {
    return view('auth.verification-link-expired');
})->name('verification.expired');
/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();
    return $user->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect('/');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes (User)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/password/update', [PasswordController::class, 'update'])->name('profile.password.update');
});

/*
|--------------------------------------------------------------------------
| User Campaign Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role.check:user'])->group(function () {
    Route::get('/campaigns/create', [UserCampaignController::class, 'create'])->name('user.campaigns.create');
    Route::post('/campaigns/store', [UserCampaignController::class, 'store'])->name('user.campaigns.store');
    Route::get('/campaigns/history', [UserCampaignController::class, 'history'])->name('campaign.history');
    Route::get('/campaigns/{id}', [UserCampaignController::class, 'detail'])->name('user.campaigns.detail');
});

// Route publik untuk detail campaign (hindari bentrok dengan milik user)
Route::get('/campaign-detail/{id}', [CampaignController::class, 'detail'])->name('campaign.detail');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'role.check:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/logout', function () {
            return view('admin.logout');
        })->name('logout');

        Route::get('/add-admin', [AdminController::class, 'showAddAdminForm'])->name('add-admin');
        Route::post('/add-admin', [AdminController::class, 'storeAdmin'])->name('store-admin');
        Route::get('/list-admins', [AdminController::class, 'listAdmins'])->name('list-admins');
        Route::delete('/delete-admin/{id}', [AdminController::class, 'deleteAdmin'])->name('delete-admin');

        // Verifikasi campaign
        Route::get('/campaigns/verify', [CampaignController::class, 'verifyIndex'])->name('campaigns.verify');
        Route::patch('/campaigns/{id}/verify', [CampaignController::class, 'verifyApprove'])->name('campaigns.verify.approve');
        Route::patch('/campaigns/{id}/reject', [CampaignController::class, 'verifyReject'])->name('campaigns.verify.reject');

        // CRUD Campaign
        Route::resource('campaigns', CampaignController::class);

        // Donations
        Route::get('/donations', [AdminDonationController::class, 'index'])->name('donations.index');
        Route::get('/donations/{donation}', [AdminDonationController::class, 'show'])->name('donations.show');
        Route::patch('/donations/{donation}/status', [AdminDonationController::class, 'updateStatus'])->name('donations.update-status');
    });

/*
|--------------------------------------------------------------------------
| Donation Public Routes
|--------------------------------------------------------------------------
*/
Route::prefix('donation')->name('donation.')->group(function () {
    Route::get('/', [DonationController::class, 'index'])->name('index');
    Route::get('/create/{campaign}', [DonationController::class, 'create'])->name('create');
    Route::post('/', [DonationController::class, 'store'])->name('store');
    Route::get('/payment/{donation}', [DonationController::class, 'payment'])->name('payment');
    Route::get('/success/{donation}', [DonationController::class, 'success'])->name('success');
    Route::get('/status/{donation}', [DonationController::class, 'checkStatus'])->name('status');
    Route::get('/history', [DonationController::class, 'myDonations'])->name('history');
});

/*
|--------------------------------------------------------------------------
| Midtrans Callback
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/callback', [MidtransController::class, 'handleCallback'])->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| Verified User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/donations/history', [DonationController::class, 'myDonations'])->name('donation.history');
});
