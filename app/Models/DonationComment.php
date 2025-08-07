<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'campaign_id',
        'donor_name',
        'comment',
        'is_anonymous',
        'is_approved'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_approved' => 'boolean'
    ];

    // Relationships
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->is_anonymous ? 'Anonim' : $this->donor_name;
    }

    public function getAvatarInitialsAttribute()
    {
        if ($this->is_anonymous) {
            return 'A';
        }
        
        $words = explode(' ', $this->donor_name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->donor_name, 0, 1));
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}