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
        'goal_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'deadline' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'boolean',
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

    public function updateCollectedAmount()
    {
        $totalSuccess = $this->donations()
            ->where('payment_status', 'success')
            ->sum('amount');

        $this->collected_amount = $totalSuccess;
        $this->progress_percentage = $this->target_amount > 0 
            ? min(100, ($totalSuccess / $this->target_amount) * 100) 
            : 0;

        $this->save();

        Log::info("Campaign {$this->id} stats updated", [
            'collected' => $this->collected_amount,
            'target' => $this->target_amount,
            'progress' => $this->progress_percentage
        ]);
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount <= 0) return 0;
        $percentage = ($this->collected_amount / $this->target_amount) * 100;
        return number_format($percentage, 1);
    }

    public function getFormattedCollectedAttribute()
    {
        return 'Rp ' . number_format($this->collected_amount, 0, ',', '.');
    }

    public function getFormattedTargetAttribute()
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }
}