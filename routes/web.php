<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show');

// Landing page
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [UserController::class, 'verificationNotice'])->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('user.dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang! 🎉');
    })->middleware('signed')->name('verification.verify');
    
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi sudah dikirim! 📧');
    })->middleware('throttle:6,1')->name('verification.send');
});

// user
Route::prefix('auth')->name('user.')->group(function () {
    Route::middleware('guest')->group(function () { 
        Route::get('/user-register', [UserController::class, 'showRegister'])->name('register');
        Route::post('/user-register', [UserController::class, 'register']);
        
        Route::get('/user-login', [UserController::class, 'showLogin'])->name('login');
        Route::post('/user-login', [UserController::class, 'login']);
    });

    // authenticated yg udh login
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [UserController::class, 'logout'])->name('logout');
        
        Route::middleware('verified')->group(function () {
            Route::get('/dashboard', function () {
                return view('user.dashboard');
            })->name('dashboard');
        });
    });
});

// admin 
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
        
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });
    
    // authenticated admin
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        Route::get('/email/verify', function () {
            return view('auth.admin-verify-email');
        })->name('verification.notice');
        
        Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();
            return redirect()->route('admin.dashboard')->with('success', 'Email admin berhasil diverifikasi! 🎉');
        })->middleware('signed')->name('verification.verify');
        
        Route::post('/email/verification-notification', function (Request $request) {
            $request->user('admin')->sendEmailVerificationNotification();
            return back()->with('message', 'Link verifikasi admin sudah dikirim! 📧');
        })->middleware('throttle:6,1')->name('verification.send');
        
        Route::middleware('verified')->group(function () {
            Route::get('/dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');
        });
    });
});

// Campaign Routes (Public/Protected sesuai kebutuhan)
Route::get('/campaign', [CampaignController::class, 'index'])->name('campaign.index'); // klo bs diakses semua org

// klo campaign hrs login user yg udh verified
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
    Route::post('/campaign', [CampaignController::class, 'store'])->name('campaign.store');
    Route::get('/campaign/{id}/edit', [CampaignController::class, 'edit'])->name('campaign.edit');
    Route::put('/campaign/{id}', [CampaignController::class, 'update'])->name('campaign.update');
    Route::delete('/campaign/{id}', [CampaignController::class, 'destroy'])->name('campaign.destroy');
});

// PENTING: Ini buat fix error "Route [login] not defined"
Route::get('/login', function () {
    return redirect()->route('user.login');
})->name('login');

Route::get('/register', function () {
    return redirect()->route('user.register'); 
})->name('register');

Route::get('/dashboard', function () {
    if (auth()->guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');