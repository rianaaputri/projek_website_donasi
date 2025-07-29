<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Campaign extends Model
{
    use HasFactory;

 protected $fillable = [
    'title',
    'description',
    'category',
    'target_amount',
    'image',
    'collected_amount',
    'status',
    'is_active',
];



    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    // Progress %
   public function getProgressPercentageAttribute()
{
    if ($this->target_amount == 0) {
        return 0;
    }

    $total = $this->donations()
        ->where('payment_status', 'success')
        ->sum('amount');

    return min(100, ($total / $this->target_amount) * 100);
}


    public function getFormattedTargetAttribute()
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

   public function getFormattedCollectedAttribute()
{
    $total = $this->donations()
        ->where('payment_status', 'success')
        ->sum('amount');

    return 'Rp ' . number_format($total, 0, ',', '.');
}


    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('is_active', true);
    }

    public function updateCollectedAmount()
    {
        $total = $this->donations()->paid()->sum('amount');
        $this->update(['collected_amount' => $total]);

        if ($total >= $this->target_amount) {
            $this->update(['status' => 'completed']);
        }
    }
public function getSuccessfulDonorCountAttribute()
{
    return $this->donations()->where('payment_status', 'success')->count();
}

   
public function daysPassed()
{
    return now()->diffInDays($this->created_at ?? now());
}

public function totalDays()
{
    return $this->created_at->diffInDays($this->end_date);
}

public function daysProgressPercentage()
{
    $totalDays = $this->totalDays();
    if ($totalDays == 0) return 0;
    return round(($this->daysPassed() / $totalDays) * 100);
}
public function getDaysPassedAttribute()
{
    return now()->diffInDays($this->created_at);
}

public function getDaysLeftAttribute()
{
    if (!$this->end_date) {
        return null;
    }

    $today = Carbon::today();
    $end = Carbon::parse($this->end_date);

    if ($today->gt($end)) {
        return 0;
    }

    return $today->diffInDays($end);
}
}
