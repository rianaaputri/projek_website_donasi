<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'target_amount',
        'collected_amount',
        'category',
        'end_date',
        'image',
        'status',
        'verification_status',
        'rejection_reason',
        'goal_amount',
        'is_active',
    ];

    protected $casts = [
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
    ];

    protected $attributes = [
        'collected_amount' => 0,
        'status' => 'active',
        'verification_status' => 'pending',
        'is_active' => 1,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($campaign) {
            if (empty($campaign->verification_status)) {
                $campaign->verification_status = 'pending';
            }
            if (empty($campaign->status)) {
                $campaign->status = 'active';
            }
            if (empty($campaign->collected_amount)) {
                $campaign->collected_amount = 0;
            }
            if (empty($campaign->is_active)) {
                $campaign->is_active = 1;
            }
        });
    }

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Update collected_amount berdasarkan donasi sukses
     */
    public function updateCollectedAmount(): void
    {
        $this->collected_amount = $this->donations()
            ->where('payment_status', 'success')
            ->sum('amount');
        $this->saveQuietly(); // ✅ supaya ga trigger event berulang
    }

    /**
     * Accessors
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        return min(100, ($this->collected_amount / $this->target_amount) * 100);
    }

    public function getFormattedTargetAttribute(): string
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

    public function getFormattedCollectedAttribute(): string
    {
        return 'Rp ' . number_format($this->collected_amount, 0, ',', '.');
    }

    public function getDaysElapsedAttribute(): ?int
    {
        return $this->created_at ? now()->diffInDays($this->created_at) : null;
    }

    /**
     * Helpers
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->verification_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }

    public function isApproved(): bool
    {
        return $this->verification_status === 'approved';
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('verification_status', 'rejected');
    }

    /**
     * Static options
     */
    public static function getCategories(): array
    {
        return [
            'kesehatan'     => 'Kesehatan',
            'pendidikan'    => 'Pendidikan',
            'infrastruktur' => 'Infrastruktur',
            'bencana_alam'  => 'Bencana Alam',
            'kemanusiaan'   => 'Kemanusiaan',
            'lingkungan'    => 'Lingkungan'
        ];
    }

    public static function getStatuses(): array
    {
        return [
            'active'    => 'Aktif',
            'completed' => 'Selesai',
            'inactive'  => 'Tidak Aktif',
        ];
    }

    public static function getVerificationStatuses(): array
    {
        return [
            'pending'  => 'Menunggu Verifikasi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ];
    }
}
