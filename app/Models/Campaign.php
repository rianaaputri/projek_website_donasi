<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'goal_amount',
        'current_amount',
        'deadline',
        'status',
        'image',
        'is_active',
        'target_amount',
        'category', // <--- ini belum ada, tambahkan
    ];

    protected $casts = [
    'end_date' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'target_amount' => 'decimal:2',
    'collected_amount' => 'decimal:2',
];
    /**
     * Campaign belongs to a user (creator)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Campaign has many donations
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Scope: Active campaigns
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Closed campaigns
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public static function getCategories()
    {
        return [
            'education' => 'Pendidikan',
            'health'    => 'Kesehatan',
            'charity'   => 'Amal',
            'environment' => 'Lingkungan',
            // tambahin sesuai kebutuhan
        ];
    }

    public function getDaysElapsedAttribute()
    {
        if (!$this->created_at) {
            return 0;
        }
        return $this->created_at->startOfDay()->diffInDays(now()->startOfDay());
    }
    
    // Hapus 'collected_amount' dari $fillable dan $casts jika ada di migration/database

    // Getter untuk jumlah terkumpul, selalu hitung real-time
    public function getCollectedAmountAttribute()
    {
        return $this->donations()
            ->where('payment_status', 'success')
            ->sum('amount');
    }

    public function getFormattedCollectedAttribute()
    {
        return 'Rp ' . number_format($this->collected_amount, 0, ',', '.');
    }

    public function getFormattedTargetAttribute()
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

    public function getProgressPercentageAttribute()
    {
        if (!$this->target_amount || $this->target_amount <= 0) {
            return 0;
        }
        $collected = $this->collected_amount;
        return min(100, ($collected / $this->target_amount) * 100);
    }

    // recalculateCollectedAmount tidak perlu update/simpan ke database
    public function recalculateCollectedAmount()
    {
        // Tidak perlu update field, cukup return nilai
        return $this->collected_amount;
    }
}