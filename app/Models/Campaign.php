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
        'end_date',
        'is_active',
        'user_id'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * List kategori yang tersedia.
     */
    public static function getCategories()
    {
        return [
            'kesehatan' => 'Kesehatan',
            'pendidikan' => 'Pendidikan',
            'kemanusiaan' => 'Kemanusiaan',
            'lingkungan' => 'Lingkungan',
            'infrastruktur' => 'Infrastruktur',
            'bencana' => 'Bencana Alam',
            'sosial' => 'Sosial',
            'agama' => 'Keagamaan',
            'teknologi' => 'Teknologi',
            'lainnya' => 'Lainnya'
        ];
    }

    /**
     * Status yang tersedia.
     */
    public static function getStatuses()
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
    }

    /**
     * Relasi ke user pembuat campaign.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke donasi-donasi pada campaign ini.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Scope campaign yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>', now());
            });
    }
}
