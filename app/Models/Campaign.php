<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'target_amount',
        'collected_amount',
        'image',
        'status',
        'is_active'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationship dengan donations
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    // Relationship dengan comments (jika nanti ditambahkan)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Helper method untuk menghitung persentase progress
    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount == 0) {
            return 0;
        }
        return min(100, ($this->collected_amount / $this->target_amount) * 100);
    }

    // Helper method untuk format currency
    public function getFormattedTargetAttribute()
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

    public function getFormattedCollectedAttribute()
    {
        return 'Rp ' . number_format($this->collected_amount, 0, ',', '.');
    }

    // Scope untuk campaign yang aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('is_active', true);
    }

    // Helper method untuk update collected amount
    public function updateCollectedAmount()
    {
        $totalCollected = $this->donations()
            ->successful()
            ->sum('amount');

        $this->update(['collected_amount' => $totalCollected]);

        // Auto complete jika sudah mencapai target
        if ($totalCollected >= $this->target_amount && $this->status === 'active') {
            $this->update(['status' => 'completed']);
        }
    }
}
