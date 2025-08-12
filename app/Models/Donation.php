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
        'message',
        'is_anonymous',
        'payment_method',
        'payment_status',
        'payment_reference'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relasi: Donation belongs to User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Donation belongs to Campaign.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Accessor: Nama donatur dengan opsi anonim.
     */
    public function getDonorNameAttribute(): string
    {
        return $this->is_anonymous
            ? 'Hamba Allah'
            : ($this->user ? $this->user->name : 'Unknown');
    }

    /**
     * Accessor: Label status pembayaran.
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
            'failed' => [
                'text' => 'Gagal',
                'class' => 'bg-danger text-white',
                'icon' => 'fa-times'
            ],
            'cancelled' => [
                'text' => 'Dibatalkan',
                'class' => 'bg-secondary text-white',
                'icon' => 'fa-ban'
            ],
            default => [
                'text' => 'Unknown',
                'class' => 'bg-light text-dark',
                'icon' => 'fa-question'
            ]
        };
    }

    /**
     * Scope: Hanya donasi yang berhasil.
     */
    public function scopeSuccess($query)
    {
        return $query->where('payment_status', 'success');
    }

    /**
     * Scope: Filter by payment status.
     */
    public function scopePaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope: Hanya donasi anonim.
     */
    public function scopeAnonymous($query)
    {
        return $query->where('is_anonymous', true);
    }

    /**
     * Scope: Donasi publik.
     */
    public function scopePublic($query)
    {
        return $query->where('is_anonymous', false);
    }

    /**
     * Boot model untuk update jumlah donasi di campaign.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($donation) {
            if ($donation->payment_status === 'success') {
                $donation->campaign->increment('collected_amount', $donation->amount);
            }
        });

        static::updated(function ($donation) {
            if ($donation->wasChanged('payment_status')) {
                $campaign = $donation->campaign;
                $campaign->collected_amount = $campaign->donations()->success()->sum('amount');
                $campaign->save();
            }
        });

        static::deleted(function ($donation) {
            if ($donation->payment_status === 'success') {
                $donation->campaign->decrement('collected_amount', $donation->amount);
            }
        });
    }
}
