<?php

// File: app/Models/Donation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
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

    /**
     * The attributes that should be cast.
     */
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
        if ($this->is_anonymous) {
            return 'Hamba Allah';
        }

        return $this->user ? $this->user->name : 'Unknown';
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

 public function scopePaid($query)
{
    return $query->where('status', 'paid');
}

    public function scopeSuccess($query)
    {
        return $query->where('payment_status', 'success');
    }

    /**
     * Scope: Filter by payment status
     */
    public function scopePaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope: Filter anonymous donations
     */
    public function scopeAnonymous($query)
    {
        return $query->where('is_anonymous', true);
    }

    /**
     * Scope: Filter public donations
     */
    public function scopePublic($query)
    {
        return $query->where('is_anonymous', false);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Update campaign current_amount when donation is created/updated/deleted
        static::created(function ($donation) {
            if ($donation->payment_status === 'success') {
                $donation->campaign->increment('current_amount', $donation->amount);
            }
        });

        static::updated(function ($donation) {
            if ($donation->wasChanged('payment_status')) {
                $campaign = $donation->campaign;
                $campaign->current_amount = $campaign->donations()->success()->sum('amount');
                $campaign->save();
            }
        });

        static::deleted(function ($donation) {
            if ($donation->payment_status === 'success') {
                $donation->campaign->decrement('current_amount', $donation->amount);
            }
        });
    }
}