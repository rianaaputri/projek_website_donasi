<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\{
    HomeController,
    MidtransController, // Pastikan ini ada dan berisi logika Midtrans Anda
    CampaignController,
    UserController,
    ProfileController,
    DonationController,
    Auth\LoginController, // Menggunakan controller autentikasi standar Laravel
    Auth\RegisterController, // Menggunakan controller autentikasi standar Laravel
    Auth\VerificationController // Menggunakan controller verifikasi standar Laravel
};

// --- Public Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show');

// Donation Routes (Publicly accessible for initial form, but payment needs auth)
Route::get('/donations', [DonationController::class, 'index'])->name('donation.index');
Route::get('/donations/create/{campaign}', [DonationController::class, 'create'])->name('donation.create');
Route::post('/donations/store', [DonationController::class, 'store'])->name('donation.store');

// Midtrans Callback - Harus bisa diakses publik oleh Midtrans
Route::post('/midtrans/callback', [MidtransController::class, 'handleCallback'])->name('midtrans.callback');

// --- Guest Routes (Login/Register) ---
// Menggunakan rute autentikasi bawaan Laravel
Route::middleware('guest')->group(function () {
    // Authentication Routes (Laravel UI/Breeze default)
    // Jika Anda menggunakan Breeze/Jetstream, rute ini sudah ada
    // Jika tidak, Anda perlu membuat controller dan view secara manual
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// --- Authenticated User Routes ---
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // Menggunakan LoginController

    // LOGIKA REDIRECT SETELAH LOGIN UNTUK SEMUA ROLE (ADMIN/USER)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            // UNTUK ROLE 'user', LANGSUNG ARAHKAN KE HOME PAGE
            'user' => redirect()->route('home'), // <-- PERUBAHAN DI SINI
            default => redirect()->route('home'), // Fallback for other roles
        };
    })->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Donation Payment & Status (requires authentication)
    Route::get('/donations/payment/{id}', [DonationController::class, 'payment'])->name('donation.payment');
    Route::get('/donations/success/{id}', [DonationController::class, 'success'])->name('donation.success');
    // Rute ini sudah benar namanya, cocok dengan yang dipanggil di payment.blade.php
    Route::get('/donations/status/{id}', [DonationController::class, 'checkStatus'])->name('donation.status');
});

// --- Email Verification Routes (for regular Users) ---
// Note: Laravel's default VerifyEmail middleware handles `auth`
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        // Redirect user based on their role after verification
        if ($request->user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Email Admin berhasil diverifikasi! Anda sekarang dapat mengakses dashboard admin.');
        }
        // UNTUK ROLE 'user', ARAHKAN KE HOME PAGE SETELAH VERIFIKASI
        return redirect()->route('home')->with('success', 'Email Anda berhasil diverifikasi! Silakan login untuk melanjutkan.'); // <-- PERUBAHAN DI SINI
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi baru telah dikirimkan ke email Anda!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});


// --- Authenticated & Verified User Routes (Frontend Dashboard) ---
// Rute ini sekarang TIDAK AKAN DIGUNAKAN UNTUK ROLE 'user' karena mereka langsung ke 'home'
// Anda bisa hapus atau biarkan jika suatu saat butuh user dashboard spesifik
// Jika user Anda akan diarahkan ke views/home.blade.php, maka rute ini tidak diperlukan untuk `role` user.
Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    // Jika Anda TIDAK punya user.dashboard, Anda bisa hapus baris ini
    // Atau jika home.blade.php adalah dashboard untuk user, maka rute ini bisa diarahkan ke sana
    // Route::get('/dashboard', fn () => view('home'))->name('dashboard'); // Contoh: jika home adalah dashboard
    // Atau bisa dihapus saja jika tidak ada rute khusus user.dashboard
    // Route::get('/dashboard', fn () => view('user.dashboard'))->name('dashboard'); // Aslinya
});

// --- Admin Routes ---
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Login/Register (Guard: 'admin')
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'showAdminLoginForm'])->name('login'); // Admin specific login form
        Route::post('/login', [LoginController::class, 'adminLogin']);
        // Route::get('/register', [RegisterController::class, 'showAdminRegistrationForm'])->name('register'); // Jika admin bisa daftar sendiri
        // Route::post('/register', [RegisterController::class, 'adminRegister']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [LoginController::class, 'adminLogout'])->name('logout'); // Admin specific logout

        // Admin Email Verification (Guard: 'admin')
        Route::get('/email/verify', [VerificationController::class, 'showAdmin'])->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();
            auth()->guard('admin')->logout(); // Optional: force re-login after verification
            return redirect()->route('admin.login')->with('success', 'Email Admin berhasil diverifikasi! Silakan login untuk melanjutkan.');
        })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

        Route::post('/email/verification-notification', function (Request $request) {
            $request->user('admin')->sendEmailVerificationNotification();
            return back()->with('message', 'Link verifikasi admin baru telah dikirim!');
        })->middleware(['throttle:6,1'])->name('verification.send');

        // Admin Dashboard and Management Routes (requires admin auth & verified)
        Route::middleware(['verified:admin'])->group(function () {
            Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
            // Campaign Management (Full CRUD)
            // Route::resource('campaigns', CampaignController::class)->except(['index']); // index already defined below
            Route::get('/campaigns', [CampaignController::class, 'adminIndex'])->name('campaigns.index'); // Admin's view of campaigns
            Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
            Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
            Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
            Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
            Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store'); // Tambahkan ini jika belum ada
            Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show'); // Tambahkan ini jika belum ada

            // Donation Management (Admin View)
            Route::get('/donations', [DonationController::class, 'adminIndex'])->name('donations.index');
            // Anda mungkin ingin menambahkan rute untuk melihat detail donasi admin atau mengubah status
            Route::get('/donations/{donation}', [DonationController::class, 'showAdmin'])->name('donations.show');
        });
    });
});

// Debugging Route (Optional) - Pindahkan ke akhir setelah semua rute didefinisikan
Route::get('/debug-auth', function () {
    dd([
        'authenticated' => auth()->check(),
        'current_user' => auth()->user(),
        'user_role' => auth()->check() ? (auth()->user()->role ?? 'undefined') : 'not logged in',
        'email_verified' => auth()->check() ? auth()->user()->hasVerifiedEmail() : false,
        'guard_web_check' => auth()->guard('web')->check(),
        'guard_admin_check' => auth()->guard('admin')->check(),
        'guard_web_user' => auth()->guard('web')->user(),
        'guard_admin_user' => auth()->guard('admin')->user(),
    ]);
});