<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show');

Route::get('/campaign', [CampaignController::class, 'index'])->name('campaign.index');
Route::get('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
Route::post('/campaign', [CampaignController::class, 'store'])->name('campaign.store');
Route::get('/campaign/{id}/edit', [CampaignController::class, 'edit'])->name('campaign.edit');
Route::put('/campaign/{id}', [CampaignController::class, 'update'])->name('campaign.update');
Route::delete('/campaign/{id}', [CampaignController::class, 'destroy'])->name('campaign.destroy');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// > user 
// Ganti prefix dari 'user' jadi 'auth'
    Route::prefix('auth')->name('user.')->group(function () {
    Route::get('/user-register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/user-register', [UserController::class, 'register']);
    
    Route::get('/user-login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/user-login', [UserController::class, 'login']);
    
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    
    Route::get('/email/verify', [UserController::class, 'verificationNotice'])
        ->middleware('auth')
        ->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('user.dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang! 🎉');
    })->middleware(['auth', 'signed'])->name('verification.verify');
    
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi sudah dikirim! 📧');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('dashboard');
    });
});

// > admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/email/verify', function () {
        return view('auth.admin-verify-email');
    })->middleware('auth:admin')->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('admin.dashboard')->with('success', 'Email admin berhasil diverifikasi! 🎉');
    })->middleware(['auth:admin', 'signed'])->name('verification.verify');
    
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user('admin')->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi admin sudah dikirim! 📧');
    })->middleware(['auth:admin', 'throttle:6,1'])->name('verification.send');
    
    Route::middleware(['auth:admin', 'verified'])->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });
});