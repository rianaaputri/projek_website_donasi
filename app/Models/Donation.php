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
        'donor_phone',
        'amount',
        'message',
        'is_anonymous',
        'payment_status',
        'midtrans_order_id',
        'payment_method',
        'transaction_id',
        'midtrans_response',
        'paid_at',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'amount'       => 'decimal:2',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'paid_at'      => 'datetime',
    ];

    // Relasi ke campaign
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    // Scope donasi yang berhasil
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'success');
    }

    // Format jumlah donasi
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    // Nama donor ditampilkan berdasarkan status anonimitas
    public function getDisplayNameAttribute()
    {
        return $this->is_anonymous ? 'Hamba Allah' : $this->donor_name;
    }
}
