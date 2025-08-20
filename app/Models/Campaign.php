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

    /**
     * Field yang bisa diisi (fillable)
     */
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
        'is_active', // ✅ ditambahkan supaya sinkron dengan DB
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
     * Default values untuk attributes
     */
    protected $attributes = [
        'collected_amount' => 0,
        'status' => 'active',              // ✅ sesuai default DB
        'verification_status' => 'pending',// ✅ paksa pending biar tidak NULL
        'is_active' => 1,                  // ✅ sesuai default DB
    ];

    /**
     * Boot method untuk handle events
     */
    protected static function boot()
    {
        parent::boot();
        
        // Event saat campaign akan dibuat
        static::creating(function ($campaign) {
            Log::info('Campaign Creating Event', [
                'title' => $campaign->title,
                'verification_status' => $campaign->verification_status ?? 'NOT_SET',
                'status' => $campaign->status ?? 'NOT_SET',
                'user_id' => $campaign->user_id
            ]);
            
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
        
        // Event setelah campaign dibuat
        static::created(function ($campaign) {
            Log::info('Campaign Created Successfully', [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'verification_status' => $campaign->verification_status,
                'status' => $campaign->status,
                'user_id' => $campaign->user_id
            ]);
        });

        // Event saat campaign di update  
        static::updating(function ($campaign) {
            if ($campaign->isDirty('verification_status')) {
                Log::info('Campaign Verification Status Changed', [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'old_status' => $campaign->getOriginal('verification_status'),
                    'new_status' => $campaign->verification_status,
                ]);
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
     * Accessor untuk progress percentage
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        return min(100, ($this->collected_amount / $this->target_amount) * 100);
    }

    public function getFormattedTargetAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

    public function getFormattedCollectedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->collected_amount, 0, ',', '.');
    }

    /**
     * Helpers untuk status campaign
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
            'kesehatan' => 'Kesehatan',
            'pendidikan' => 'Pendidikan', 
            'infrastruktur' => 'Infrastruktur',
            'bencana_alam' => 'Bencana Alam',
            'kemanusiaan' => 'Kemanusiaan',
            'lingkungan' => 'Lingkungan'
        ];
    }

    public static function getStatuses(): array
    {
        return [
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'sinactive' => 'Tidak Aktif',
        ];
    }

    public static function getVerificationStatuses(): array
    {
        return [
            'pending' => 'Menunggu Verifikasi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ];
    }
}
