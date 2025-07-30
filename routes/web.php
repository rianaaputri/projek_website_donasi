 <?php

use Illuminate\Http\Request; // Pastikan ini di-import
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\{
    HomeController,
    MidtransController, // Pastikan ini di-import jika digunakan
    CampaignController,
    UserController,
    ProfileController,
    DonationController,
    AuthController
};

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign', [CampaignController::class, 'index'])->name('admin.campaigns.index');
Route::post('/campaign', [CampaignController::class, 'store'])->name('admin.campaigns.store');
Route::get('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
Route::get('/campaign/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaign.edit');
Route::put('/campaign/{campaign}', [CampaignController::class, 'update'])->name('campaign.update');
Route::delete('/campaign/{campaign}', [CampaignController::class, 'destroy'])->name('campaign.destroy');

Route::get('/campaign/{id}', [HomeController::class, 'showCampaign'])->name('campaign.show');

// Guest Routes (Login/Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register']); 
});

// Donation Routes (Public) - Ini adalah rute untuk menampilkan form donasi
Route::get('/donation', [DonationController::class, 'index'])->name('donation.index');
Route::get('/donation/create/{campaign}', [DonationController::class, 'create'])->name('donation.create');
Route::post('/donation', [DonationController::class, 'store'])->name('donation.store');

// User Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Email Verification Notice
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // --- MODIFIKASI UNTUK PENGGUNA BIASA ---
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill(); // Tandai email sebagai terverifikasi

        // Opsional: Logout pengguna agar mereka harus login lagi
        auth()->guard('web')->logout();

        // Arahkan ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Email Anda berhasil diverifikasi! Silakan login untuk melanjutkan.');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi sudah dikirim!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard Redirect (tetap sama, ini untuk saat login/akses /dashboard secara umum)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'user' => redirect()->route('/'),
        };
    })->name('dashboard');

    // Rute untuk halaman pembayaran donasi
    // DILINDUNGI OLEH MIDDLEWARE 'auth'
    Route::get('/donation/payment/{id}', [DonationController::class, 'payment'])
        ->name('donation.payment'); // Middleware 'auth' sudah diterapkan di group ini
    
    // Rute untuk halaman sukses pembayaran
    Route::get('/donation-success/{id}', [DonationController::class, 'success'])->name('donation.success');
    // Rute untuk check status Midtrans (biasanya dipanggil oleh webhook atau AJAX)
    Route::get('/donation/status/{id}', [DonationController::class, 'checkStatus'])->name('donation.status');
});

// Authenticated & Verified User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', fn () => view('user.dashboard'))->name('dashboard');
    });


});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/email/verify', fn () => view('auth.admin-verify-email'))->name('verification.notice');
        // --- MODIFIKASI UNTUK ADMIN ---
        Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill(); // Tandai email admin sebagai terverifikasi

            // Opsional: Logout admin agar mereka harus login lagi
            auth()->guard('admin')->logout();

            // Arahkan ke halaman login admin dengan pesan sukses
            return redirect()->route('admin.login')->with('success', 'Email Admin berhasil diverifikasi! Silakan login untuk melanjutkan.');
        })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

        Route::post('/email/verification-notification', function (Request $request) {
            $request->user('admin')->sendEmailVerificationNotification();
            return back()->with('message', 'Link verifikasi admin sudah dikirim!');
        })->middleware(['throttle:6,1'])->name('verification.send');

        Route::middleware(['verified'])->group(function () {
            Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
        });
    });
});

// Debugging Route (Optional)
Route::get('/debug-auth', function () {
    dd([
        'authenticated' => auth()->check(),
        'current_user' => auth()->user(),
        'user_role' => auth()->user()->role ?? 'not logged in',
        'email_verified' => auth()->check() ? auth()->user()->hasVerifiedEmail() : false,
    ]);
});

//midtrans callback route
// Proses Pembayaran Donasi
Route::get('/donation/payment/{id}', [DonationController::class, 'payment'])->name('donation.payment');
Route::get('/donation-success/{id}', [DonationController::class, 'success'])->name('donation.success');
Route::get('/donation/status/{id}', [DonationController::class, 'checkStatus'])->name('donation.status');
Route::get('/donation/{campaign}', [DonationController::class, 'create'])->name('donation.create');
Route::get('/donation/{id}/check-status', [DonationController::class, 'checkStatus'])->name('donation.checkStatus');



// Midtrans Callback (dari dashboard Midtrans)
Route::post('/midtrans/callback', [DonationController::class, 'handleCallback'])->name('midtrans.callback');

Route::resource('campaigns', CampaignController::class); 