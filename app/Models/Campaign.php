<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

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
        'category',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'target_amount' => 'decimal:2', // Added cast for target_amount
        'deadline' => 'datetime',
        'end_date' => 'datetime', // Add this cast
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'collected_amount',
        'formatted_collected',
        'formatted_target',
        'progress_percentage',
        'days_elapsed',
        'days_remaining',
        'is_expired',
        'status_label'
    ];

    // Constants for better maintainability
    const STATUS_ACTIVE = 'active';
    const STATUS_CLOSED = 'closed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_EXPIRED = 'expired';

    const CATEGORY_EDUCATION = 'education';
    const CATEGORY_HEALTH = 'health';
    const CATEGORY_CHARITY = 'charity';
    const CATEGORY_ENVIRONMENT = 'environment';
    const CATEGORY_DISASTER = 'disaster';
    const CATEGORY_COMMUNITY = 'community';

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
     * Campaign has many successful donations
     */
    public function successfulDonations(): HasMany
    {
        return $this->donations()->where('payment_status', 'success');
    }

    /**
     * Scope: Active campaigns
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where('is_active', true);
    }

    /**
     * Scope: Closed campaigns
     */
    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    /**
     * Scope: Not expired campaigns
     */
    public function scopeNotExpired($query)
    {
        return $query->where('deadline', '>', now());
    }

    /**
     * Scope: Expired campaigns
     */
    public function scopeExpired($query)
    {
        return $query->where('deadline', '<=', now());
    }

    /**
     * Scope: By category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: Popular campaigns (by donation count)
     */
    public function scopePopular($query)
    {
        return $query->withCount('donations')
                    ->orderBy('donations_count', 'desc');
    }

    /**
     * Scope: Recent campaigns
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get available categories
     */
    public static function getCategories(): array
    {
        return [
            self::CATEGORY_EDUCATION => 'Pendidikan',
            self::CATEGORY_HEALTH => 'Kesehatan',
            self::CATEGORY_CHARITY => 'Amal',
            self::CATEGORY_ENVIRONMENT => 'Lingkungan',
            self::CATEGORY_DISASTER => 'Bencana Alam',
            self::CATEGORY_COMMUNITY => 'Komunitas',
        ];
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute(): string
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? ucfirst($this->category);
    }

    /**
     * Get available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_CLOSED => 'Ditutup',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_EXPIRED => 'Kedaluwarsa',
        ];
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get days elapsed since campaign creation
     */
    public function getDaysElapsedAttribute(): int
    {
        if (!$this->created_at) {
            return 0;
        }
        return $this->created_at->startOfDay()->diffInDays(now()->startOfDay());
    }

    /**
     * Get days remaining until deadline
     */
    public function getDaysRemainingAttribute(): int
    {
        if (!$this->deadline) {
            return 0;
        }
        
        $remaining = now()->startOfDay()->diffInDays($this->deadline->startOfDay(), false);
        return max(0, $remaining);
    }

    /**
     * Check if campaign is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }

    /**
     * Get collected amount (real-time calculation)
     */
    public function getCollectedAmountAttribute(): float
    {
        return $this->donations()
            ->where('payment_status', 'success')
            ->sum('amount') ?? 0;
    }

    /**
     * Get formatted collected amount
     */
    public function getFormattedCollectedAttribute(): string
    {
        return 'Rp ' . number_format($this->collected_amount, 0, ',', '.');
    }

    /**
     * Get formatted target amount
     */
    public function getFormattedTargetAttribute(): string
    {
        $target = $this->target_amount ?: $this->goal_amount;
        return 'Rp ' . number_format($target, 0, ',', '.');
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute(): float
    {
        $target = $this->target_amount ?: $this->goal_amount;
        
        if (!$target || $target <= 0) {
            return 0;
        }
        
        $collected = $this->collected_amount;
        return min(100, round(($collected / $target) * 100, 2));
    }

    /**
     * Check if campaign has reached its goal
     */
    public function hasReachedGoal(): bool
    {
        $target = $this->target_amount ?: $this->goal_amount;
        return $this->collected_amount >= $target;
    }

    /**
     * Get formatted end date for form input
     */
    public function getFormattedEndDateAttribute(): string
    {
        if (!$this->end_date) {
            return '';
        }
        
        return $this->end_date instanceof Carbon 
            ? $this->end_date->format('Y-m-d\TH:i')
            : Carbon::parse($this->end_date)->format('Y-m-d\TH:i');
    }

    /**
     * Get donation count
     */
    public function getDonationCountAttribute(): int
    {
        return $this->donations()->where('payment_status', 'success')->count();
    }

    /**
     * Auto-update campaign status based on conditions
     */
    public function updateStatus(): void
    {
        if ($this->is_expired && $this->status === self::STATUS_ACTIVE) {
            $this->update(['status' => self::STATUS_EXPIRED]);
        } elseif ($this->hasReachedGoal() && $this->status === self::STATUS_ACTIVE) {
            $this->update(['status' => self::STATUS_COMPLETED]);
        }
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-update status when retrieving model
        static::retrieved(function ($campaign) {
            $campaign->updateStatus();
        });
    }

    /**
     * Get campaigns that need status update
     */
    public static function needingStatusUpdate()
    {
        return self::where(function ($query) {
            $query->where('status', self::STATUS_ACTIVE)
                  ->where('deadline', '<=', now());
        })->orWhere(function ($query) {
            $query->where('status', self::STATUS_ACTIVE)
                  ->whereRaw('(SELECT SUM(amount) FROM donations WHERE campaign_id = campaigns.id AND payment_status = "success") >= target_amount');
        });
    }
}