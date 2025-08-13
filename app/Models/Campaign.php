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
}