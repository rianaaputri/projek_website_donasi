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

    // Password Reset
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Logout (authenticated) — satu route global untuk semua users
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==============================
// EMAIL VERIFICATION
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
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi sudah dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

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
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [AdminDashboardController::class, 'getStatistics'])->name('statistics');
    
    // Admin logout - FIXED: menggunakan POST method dan controller method yang benar
    Route::post('/logout', [AdminDashboardController::class, 'logout'])->name('logout');
    
    // Admin Management
    Route::get('/add-admin', [AdminController::class, 'showAddAdminForm'])->name('add-admin');
    Route::post('/add-admin', [AdminController::class, 'storeAdmin'])->name('store-admin');
    Route::get('/list-admins', [AdminController::class, 'listAdmins'])->name('list-admins');
    Route::delete('/delete-admin/{id}', [AdminController::class, 'deleteAdmin'])->name('delete-admin');

    // Campaign Management
   Route::resource('campaigns', AdminCampaignController::class)->names([
    'index' => 'campaigns.index',
    'create' => 'campaigns.create',     // ← INI YANG MISSING!
    'store' => 'campaigns.store',
    'show' => 'campaigns.show',
    'edit' => 'campaigns.edit',
    'update' => 'campaigns.update',
    'destroy' => 'campaigns.destroy',
]);

    // Donation Management
    Route::prefix('donations')->name('donations.')->group(function () {
        Route::get('/', [AdminDonationController::class, 'index'])->name('index');
        Route::get('/{donation}', [AdminDonationController::class, 'show'])->name('show');
        Route::patch('/{donation}/status', [AdminDonationController::class, 'updateStatus'])->name('update-status');
    });
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
// DEBUG ROUTES (Remove in production)
// ==============================
if (app()->environment('local')) {
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
            'Email Verified At' => $user->email_verified_at,
        ]);
    })->middleware(['auth']);

    Route::get('/debug-user-only', function () {
        return 'HALAMAN INI HANYA BISA DIAKSES USER';
    })->middleware(['auth', 'role.check:user']);

    Route::get('/debug-admin-only', function () {
        return 'HALAMAN INI HANYA BISA DIAKSES ADMIN';
    })->middleware(['auth', 'role.check:admin']);

    // DEBUG EMAIL VERIFICATION
    Route::get('/debug-verification', function () {
        $user = auth()->user();
        
        if (!$user) {
            return 'Not authenticated';
        }

        return response()->json([
            'user_id' => $user->id,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'has_verified_email' => $user->hasVerifiedEmail(),
            'email_verified_at_raw' => $user->getRawOriginal('email_verified_at'),
        ]);
    })->middleware('auth');

    Route::get('/debug-manual-verify', function () {
        $user = auth()->user();
        
        if (!$user) {
            return 'Not authenticated';
        }

        $user->markEmailAsVerified();
        
        return response()->json([
            'message' => 'Manual verification completed',
            'user_id' => $user->id,
            'email' => $user->email,
            'email_verified_at' => $user->fresh()->email_verified_at,
            'has_verified_email' => $user->fresh()->hasVerifiedEmail(),
        ]);
    })->middleware('auth');
}