<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\{
    HomeController,
    CampaignController,
    UserController,
    ProfileController
};

Route::middleware(['auth', 'verify.or.admin'])->group(function () {
    Route::get('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
});

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DonationController; 

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show')

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show');
Route::get('/campaign', [CampaignController::class, 'index'])->name('campaign.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
    
    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register']);
});


Route::get('/donate/{campaign}', [DonationController::class, 'create'])->name('donation.create');
Route::post('/donate', [DonationController::class, 'store'])->name('donation.store');


Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/email/verify', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.verify-email');
    })->name('verification.notice');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::prefix('auth')->name('user.')->group(function () {

// > user 
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
        if ($request->user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang admin! 🎉');
        }
        
        $request->fulfill();
        return redirect()->route('user.dashboard')->with('success', 'Email berhasil diverifikasi! 🎉');

    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

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
        return redirect()->route('user.dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang! 🎉');
    })->middleware('signed')->name('verification.verify');
    
    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi telah dikirim ulang!');
    })->middleware('throttle:6,1')->name('verification.send');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/dashboard', function () {
        if (auth()->user()->role !== 'user') {
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }
        return view('user.dashboard');
    })->name('user.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Admin access required!');
        }
        
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/campaign/create', [CampaignController::class, 'create'])
        ->middleware('verified') 
        ->name('campaign.create');
    Route::post('/campaign', [CampaignController::class, 'store'])
        ->middleware('verified')
        ->name('campaign.store');
    Route::get('/campaign/{id}/edit', [CampaignController::class, 'edit'])
        ->middleware('verified')
        ->name('campaign.edit');
    Route::put('/campaign/{id}', [CampaignController::class, 'update'])
        ->middleware('verified')
        ->name('campaign.update');
    Route::delete('/campaign/{id}', [CampaignController::class, 'destroy'])
        ->middleware('verified')
        ->name('campaign.destroy');
});

Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    $user = auth()->user();
    
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    
    return redirect()->route('user.dashboard');
})->name('dashboard');

Route::get('/admin/login', function() {
    return redirect()->route('login');
});

Route::post('/admin/login', function() {
    return redirect()->route('login');
});

Route::get('/auth/user-login', function() {
    return redirect()->route('login');
});

Route::post('/auth/user-login', function() {
    return redirect()->route('login');
});

Route::get('/debug-auth', function() {
    dd([
        'authenticated' => auth()->check(),
        'current_user' => auth()->user(),
        'user_role' => auth()->user()->role ?? 'not logged in',
        'email_verified' => auth()->check() ? auth()->user()->hasVerifiedEmail() : false,
    ]);
});
