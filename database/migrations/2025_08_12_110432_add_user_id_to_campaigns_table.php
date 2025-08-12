<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Only add user_id if it doesn't exist
            if (!Schema::hasColumn('campaigns', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                
                // Add foreign key constraint if users table exists
                if (Schema::hasTable('users')) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('campaigns', 'user_id')) {
                // Drop foreign key first if it exists
                if (Schema::hasTable('users')) {
                    $table->dropForeign(['user_id']);
                }
                $table->dropColumn('user_id');
            }
        });
    }
};