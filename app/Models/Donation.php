<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'donor_name',
        'donor_email',
        'amount',
        'payment_status',
        'midtrans_order_id',
        'comment',
        'payment_method', // <-- Tambahkan ini
        'transaction_id', // <-- Tambahkan ini
        'midtrans_response', // <-- Tambahkan ini
        'paid_at',          // <-- Tambahkan ini
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'paid_at' => 'datetime', // <-- Tambahkan ini
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function scopePaid($query)
    {
        // Ganti 'paid' menjadi 'success' jika Anda konsisten menggunakan 'success'
        // di MidtransController untuk status berhasil
        return $query->where('payment_status', 'success');
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}