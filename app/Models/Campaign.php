<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    // app/Models/Campaign.php
    protected $fillable = [
        'title',
        'description',
        'category',
        'target_amount',
        'collected_amount',
        // 'current_amount', // HAPUS INI
        'image',
        'status',
        'end_date',
        'is_active',
        'user_id' // akan ditambahkan otomatis jika kolom ada
    ];

    protected $casts = [
    'target_amount' => 'decimal:2',
    'collected_amount' => 'decimal:2',
    // 'current_amount' => 'decimal:2', // HAPUS INI
    'end_date' => 'datetime',
    'is_active' => 'boolean'
    ];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        // Add user_id to fillable if column exists
        if (Schema::hasColumn('campaigns', 'user_id')) {
            $this->fillable[] = 'user_id';
        }
    }

    /**
     * Get available campaign categories
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
     * Get available campaign statuses
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
     * User relationship - only if user_id column exists
     */
    public function user()
    {
        if (Schema::hasColumn('campaigns', 'user_id')) {
            return $this->belongsTo(User::class);
        }
        
        return null;
    }

    /**
     * Donations relationship
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    // ... rest of the methods from previous model
}