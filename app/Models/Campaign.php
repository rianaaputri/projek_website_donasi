<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // Pastikan ini di-import

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
        // 'end_date', // <-- Anda bisa tambahkan ini jika Anda punya kolom end_date di database
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'is_active' => 'boolean',
        // 'end_date' => 'datetime', // <-- Tambahkan ini jika Anda punya kolom end_date
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

        // Pastikan ini juga mengambil dari donasi yang sukses
        $total = $this->donations()->where('payment_status', 'success')->sum('amount');

        return min(100, ($total / $this->target_amount) * 100);
    }

    public function getFormattedTargetAttribute()
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

    public function getFormattedCollectedAttribute()
    {
        // Pastikan ini juga mengambil dari donasi yang sukses
        $total = $this->donations()->where('payment_status', 'success')->sum('amount');
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('is_active', true);
    }

    public function updateCollectedAmount()
    {
        // Pastikan ini juga mengambil dari donasi yang sukses
        $total = $this->donations()->where('payment_status', 'success')->sum('amount');
        $this->update(['collected_amount' => $total]);

        if ($total >= $this->target_amount) {
            $this->update(['status' => 'completed']);
        }
    }

    public function getSuccessfulDonorCountAttribute()
    {
        return $this->donations()->where('payment_status', 'success')->count();
    }

    // --- Perbaikan untuk "Hari berjalan" dan durasi kampanye ---

    /**
     * Hitung berapa hari campaign ini sudah berjalan dari created_at.
     * @return int
     */
    public function getDaysPassedAttribute()
    {
        return $this->created_at ? now()->diffInDays($this->created_at) : 0;
    }

    /**
     * Hitung sisa hari campaign jika ada end_date.
     * Jika tidak ada end_date atau sudah lewat, return 0 atau null.
     * @return int|null
     */
    public function getDaysLeftAttribute()
    {
        // Jika ada kolom `end_date` di database, pastikan juga diisi di $fillable dan $casts
        // Anda perlu menambahkan kolom `end_date` ke tabel `campaigns` di database Anda
        // Contoh migration: $table->timestamp('end_date')->nullable();
        if (!$this->end_date) {
            return null; // Atau return 0 jika Anda ingin selalu angka
        }

        $today = Carbon::today();
        $end = Carbon::parse($this->end_date);

        if ($today->gt($end)) {
            return 0; // Sudah kadaluarsa
        }

        return $today->diffInDays($end);
    }

    /**
     * Total durasi campaign dalam hari (dari created_at hingga end_date).
     * @return int
     */
    public function getTotalDurationAttribute()
    {
        if (!$this->end_date || !$this->created_at) {
            return 0; // Atau null, tergantung kebutuhan
        }
        return $this->created_at->diffInDays($this->end_date);
    }

    /**
     * Persentase progress hari yang berjalan (jika ada end_date).
     * @return int
     */
    public function getDaysProgressPercentageAttribute()
    {
        $totalDuration = $this->getTotalDurationAttribute();
        if ($totalDuration == 0) return 0;

        $daysPassed = $this->getDaysPassedAttribute();
        return min(100, round(($daysPassed / $totalDuration) * 100));
    }
}