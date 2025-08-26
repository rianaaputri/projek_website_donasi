<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'amount',
        'comment',
        'is_anonymous',
        'payment_method',
        'payment_status',
        'midtrans_order_id',
        'transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship: Donation belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Donation belongs to Campaign
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Accessor: Get donor name (considering anonymity)
     */
    public function getDonorNameAttribute(): string
    {
        return $this->is_anonymous
            ? 'Hamba Allah'
            : ($this->user ? $this->user->name : 'Unknown');
    }

    /**
     * Accessor: Get payment status label
     */
    public function getPaymentStatusLabelAttribute(): array
    {
        return match($this->payment_status) {
            'pending' => [
                'text' => 'Menunggu',
                'class' => 'bg-warning text-dark',
                'icon' => 'fa-clock'
            ],
            'success' => [
                'text' => 'Berhasil',
                'class' => 'bg-success text-white',
                'icon' => 'fa-check'
            ],
            'failed', 'expired' => [
                'text' => 'Gagal',
                'class' => 'bg-danger text-white',
                'icon' => 'fa-times'
            ],
            default => [
                'text' => 'Unknown',
                'class' => 'bg-light text-dark',
                'icon' => 'fa-question'
            ]
        };
    }

    /**
     * Scopes
     */
    public function scopeSuccess($query)
    {
        return $query->where('payment_status', 'success');
    }

    public function scopePaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    public function scopeAnonymous($query)
    {
        return $query->where('is_anonymous', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_anonymous', false);
    }

    /**
     * Boot events to update campaign amount automatically
     */
    protected static function boot()
    {
        parent::boot();

        // Saat donasi dibuat
        static::created(function ($donation) {
            if ($donation->payment_status === 'success') {
                $donation->campaign->updateCollectedAmount();
            }
        });

        // Saat donasi diupdate (misal pending → success)
        static::updated(function ($donation) {
            if ($donation->wasChanged('payment_status')) {
                $donation->campaign->updateCollectedAmount();
            }
        });

        // Saat donasi dihapus → SELALU hitung ulang biar nggak nyangkut
        static::deleted(function ($donation) {
            $donation->campaign->updateCollectedAmount();
        });
    }
    
}
