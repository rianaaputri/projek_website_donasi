<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Tambah kolom verification_status jika belum ada
            if (!Schema::hasColumn('campaigns', 'verification_status')) {
                $table->string('verification_status')->default('pending')->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('verification_status');
        });
    }
};