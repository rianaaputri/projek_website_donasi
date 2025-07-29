<?php
use App\Http\Controllers\MidtransController;
Route::post('/midtrans/callback', [MidtransController::class, 'callback']);
