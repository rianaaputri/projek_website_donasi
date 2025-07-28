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
    AuthController, // Ini akan menangani login dan logout untuk kedua user & admin
    AdminDashboardController
};

// --- PUBLIC ROUTES ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign', [CampaignController::class, 'index'])->name('campaign.index');
Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show');

// --- DONATION ROUTES (Public) ---
Route::get('/donation/{campaign}', [DonationController::class, 'create'])->name('donation.create');
Route::post('/donation', [DonationController::class, 'store'])->name('donation.store');

// --- GUEST ROUTES (belum login) ---
// Ini adalah blok rute untuk pengguna yang BELUM terautentikasi (baik user maupun admin).
// Middleware 'guest' tanpa argumen akan menggunakan guard 'web' secara default.
Route::middleware('guest')->group(function () {
    // Rute Login Universal (untuk User Biasa DAN Admin)
    // AuthController akan membedakan guard saat proses login.
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); 
    Route::post('/login', [AuthController::class, 'login']); 

    // Rute Register untuk User Biasa
    // Tetap di UserController jika Anda punya UserController terpisah untuk register user.
    // Jika Anda ingin AuthController juga handle register user, sesuaikan di sini.
    Route::get('/register', [UserController::class, 'showRegister'])->name('register'); 
    Route::post('/register', [UserController::class, 'register']);
});

// --- ADMIN SPECIFIC GUEST ROUTES (untuk register admin jika terpisah, atau jika ada login admin khusus) ---
Route::prefix('admin')->name('admin.')->group(function () {
    // Middleware 'guest:admin' ini SANGAT PENTING.
    // Ini memastikan jika admin sudah login dan mencoba mengakses /admin/login,
    // mereka akan langsung di-redirect ke admin.dashboard oleh RedirectIfAuthenticated.
    Route::middleware('guest:admin')->group(function () {
        // Rute Register Admin
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); // Arahkan ke AuthController
        Route::post('/register', [AuthController::class, 'register']); // Arahkan ke AuthController
        
        // **CATATAN PENTING UNTUK LOGIN ADMIN:**
        // Rute '/login' di bawah ini adalah RUTE DUPLIKAT dari rute '/login' yang universal di atas.
        // Secara fungsional, keduanya mengarah ke AuthController::showLogin dan AuthController::login.
        // Jika Anda ingin *satu* form login universal untuk user dan admin (seperti yang direkomendasikan),
        // Anda BISA MENGHAPUS DUA BARIS di bawah ini.
        // Namun, jika Anda ingin /admin/login memiliki URL khusus (walaupun view-nya sama),
        // atau jika ada skenario di mana Anda ingin menargetkan rute 'admin.login' secara spesifik,
        // maka biarkan rute ini.
        // Karena `AuthController::login` sudah dirancang untuk memeriksa kedua guard ('admin' dan 'web'),
        // menjaga rute ini tidak akan menyebabkan kesalahan fungsionalitas, tapi bisa sedikit redundant.
        // Saya biarkan untuk saat ini agar sesuai dengan struktur sebelumnya, namun ini bisa dioptimalkan.
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); 
        Route::post('/login', [AuthController::class, 'login'])->name('login'); 
    });
});

// --- USER AUTHENTICATED ROUTES ---
// Menggunakan middleware 'auth:web' untuk melindungi rute-rute user.
Route::middleware(['auth:web'])->group(function () {
    // Logout user - Arahkan ke AuthController::logout (universal)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); 

    // Email Verification Notice
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        // Penting: Pastikan logout hanya untuk guard 'web' di sini
        auth()->guard('web')->logout(); 
        return redirect()->route('login')->with('success', 'Email Anda berhasil diverifikasi! Silakan login untuk melanjutkan.');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        // Menggunakan $request->user() secara default akan merujuk ke guard 'web' jika autentikasi berhasil
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi sudah dikirim!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Profile User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- USER VERIFIED ROUTES (fitur yang butuh email user terverifikasi) ---
// Middleware 'verified:web' untuk memastikan user (guard 'web') sudah verifikasi email.
Route::middleware(['auth:web', 'verified:web'])->group(function () {
    Route::get('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
    Route::post('/campaign', [CampaignController::class, 'store'])->name('campaign.store');
    Route::get('/campaign/{id}/edit', [CampaignController::class, 'edit'])->name('campaign.edit');
    Route::put('/campaign/{id}', [CampaignController::class, 'update'])->name('campaign.update');
    Route::delete('/campaign/{id}', [CampaignController::class, 'destroy'])->name('campaign.destroy');
});

// --- ADMIN AUTHENTICATED ROUTES ---
Route::prefix('admin')->name('admin.')->group(function () {
    // Logout Admin - Arahkan ke AuthController::logout (universal)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); 

    // Admin Authenticated (sudah login)
    // Menggunakan middleware 'auth:admin' untuk melindungi rute-rute admin.
    Route::middleware(['auth:admin'])->group(function () {
        // Rute Verifikasi Email Admin
        Route::get('/email/verify', fn () => view('auth.admin-verify-email'))->name('verification.notice');
        Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();
            // Penting: Pastikan logout hanya untuk guard 'admin' di sini
            auth()->guard('admin')->logout(); 
            return redirect()->route('admin.login')->with('success', 'Email Admin berhasil diverifikasi! Silakan login untuk melanjutkan.');
        })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
        Route::post('/email/verification-notification', function (Request $request) {
            // Menggunakan $request->user('admin') untuk merujuk ke user yang terautentikasi oleh guard 'admin'
            $request->user('admin')->sendEmailVerificationNotification();
            return back()->with('message', 'Link verifikasi admin sudah dikirim!');
        })->middleware(['throttle:6,1'])->name('verification.send');

        // Admin Verified Routes (fitur yang butuh email admin terverifikasi)
        // Middleware 'verified:admin' untuk memastikan admin (guard 'admin') sudah verifikasi email.
        Route::middleware(['verified:admin'])->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('/dashboard/statistics', [AdminDashboardController::class, 'getStatistics'])->name('dashboard.statistics');
        });
    });
});

// --- MIDTRANS RELATED ROUTES ---
Route::get('/donation/payment/{id}', [DonationController::class, 'payment'])->name('donation.payment');
Route::get('/donation-success/{id}', [DonationController::class, 'success'])->name('donation.success');
Route::get('/donation/status/{id}', [DonationController::class, 'checkStatus'])->name('donation.status');
Route::post('/midtrans/callback', [DonationController::class, 'handleCallback'])->name('midtrans.callback');