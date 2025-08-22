<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // PERBAIKAN: Pastikan kolom ada sebelum diubah
        if (Schema::hasColumn('campaigns', 'status')) {
            // Update ENUM untuk menambah 'draft' dan 'pending'
            DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('active', 'inactive', 'completed', 'cancelled', 'draft', 'pending', 'rejected') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('campaigns', 'status')) {
            // Kembalikan ke ENUM lama
            DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('active', 'inactive', 'completed', 'cancelled') DEFAULT 'active'");
        }
    }
};