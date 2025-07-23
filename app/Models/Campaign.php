<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'target_amount',
        'collected_amount',
        'image',
        'status',
        'is_active',
    ];

    public $timestamps = true; // karena lo pakai created_at & updated_at
}
