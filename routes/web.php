<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    AdminController,
    MidtransController,
    UserController,
    ProfileController,
    DonationController,
    SupportController,
    Auth\LoginController,
    Auth\PasswordResetLinkController,
    Auth\NewPasswordController,
    AdminDashboardController,
    CampaignController
};
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\User\CampaignController as UserCampaignController;
use App\Http\Controllers\Auth\VerificationController;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show');
Route::get('/campaign-detail/{id}', [CampaignController::class, 'detail'])->name('campaign.detail');

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
| Email Verification Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['throttle:6,1'])
    ->name('verification.verify');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();
    return $user->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes (User)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
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
Route::middleware(['auth', 'role.check:user', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/campaigns/create', [UserCampaignController::class, 'create'])->name('campaigns.create');
        Route::post('/campaigns/store', [UserCampaignController::class, 'store'])->name('campaigns.store');
        Route::get('/campaigns/history', [UserCampaignController::class, 'history'])->name('campaigns.history');

        // FIX: pakai .show (bukan .detail)
        Route::get('/campaigns/{id}', [UserCampaignController::class, 'detail'])->name('campaigns.show');

        // Opsional: donations biar tombol di blade nggak error
        Route::get('/campaigns/{id}/donations', [UserCampaignController::class, 'donations'])->name('campaigns.donations');
    });

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
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // Admin Management
        Route::get('/add-admin', [AdminController::class, 'showAddAdminForm'])->name('add-admin');
        Route::post('/add-admin', [AdminController::class, 'storeAdmin'])->name('store-admin');
        Route::get('/list-admins', [AdminController::class, 'listAdmins'])->name('list-admins');
        Route::delete('/delete-admin/{id}', [AdminController::class, 'deleteAdmin'])->name('delete-admin');

        // Campaign Verification
        Route::get('/campaigns/verify', [AdminCampaignController::class, 'verifyIndex'])->name('campaigns.verify');
        Route::patch('/campaigns/{id}/verify', [AdminCampaignController::class, 'verifyApprove'])->name('campaigns.verify.approve');
        Route::patch('/campaigns/{id}/reject', [AdminCampaignController::class, 'verifyReject'])->name('campaigns.verify.reject');

        // CRUD Campaign (Admin Only)
        Route::prefix('campaigns')->name('campaigns.')->group(function () {
            Route::get('/', [AdminCampaignController::class, 'index'])->name('index');
            Route::get('/create', [AdminCampaignController::class, 'create'])->name('create');
            Route::post('/', [AdminCampaignController::class, 'store'])->name('store');
            Route::get('/{campaign}', [AdminCampaignController::class, 'show'])->name('show');
            Route::get('/{campaign}/edit', [AdminCampaignController::class, 'edit'])->name('edit');
            Route::put('/{campaign}', [AdminCampaignController::class, 'update'])->name('update');
            Route::delete('/{campaign}', [AdminCampaignController::class, 'destroy'])->name('destroy');

            Route::patch('/{campaign}/status', [AdminCampaignController::class, 'updateStatus'])->name('update-status');
            Route::get('/{campaign}/donations', [AdminCampaignController::class, 'donations'])->name('donations');
            Route::post('/bulk-action', [AdminCampaignController::class, 'bulkAction'])->name('bulk-action');
        });

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
    
     Route::middleware(['auth'])->group(function () {
        Route::get('/history', [DonationController::class, 'myDonations'])->name('history');
        Route::get('/pending', [DonationController::class, 'pending'])->name('pending');
        Route::get('/{id}/edit', [DonationController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DonationController::class, 'update'])->name('update');
    });

});

/*
|--------------------------------------------------------------------------
| Midtrans Callback
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/callback', [MidtransController::class, 'handleCallback'])->name('midtrans.callback');

// Halaman Bantuan
Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

Route::get('/cara-berdonasi', function () {
    return view('pages.donation-guide');
})->name('donation.guide');

Route::get('/hubungi-kami', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/pusat-bantuan', function () {
    return view('pages.support-center');
})->name('support.center');

/*
|--------------------------------------------------------------------------
| Support Pages
|--------------------------------------------------------------------------
*/
Route::get('/faq', [SupportController::class, 'faq'])->name('faq');
Route::get('/cara-berdonasi', [SupportController::class, 'donationGuide'])->name('donation.guide');
Route::get('/hubungi-kami', [SupportController::class, 'contact'])->name('contact');
Route::post('/hubungi-kami', [SupportController::class, 'sendContact'])->name('contact.send');
Route::get('/pusat-bantuan', [SupportController::class, 'supportCenter'])->name('support.center');
