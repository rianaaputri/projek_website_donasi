<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Langkah 1: Ubah ENUM jadi VARCHAR sementara
        DB::statement("ALTER TABLE users MODIFY role VARCHAR(20) NOT NULL DEFAULT 'user';");

        // Langkah 2: Ubah kembali ke ENUM, termasuk nilai baru
        DB::statement("ALTER TABLE users MODIFY role ENUM('user', 'admin', 'campaign_creator') NOT NULL DEFAULT 'user';");
    }

    public function down()
    {
        // Kembalikan ke kondisi sebelumnya (tanpa campaign_creator)
        DB::statement("ALTER TABLE users MODIFY role VARCHAR(20) NOT NULL DEFAULT 'user';");
        DB::statement("ALTER TABLE users MODIFY role ENUM('user', 'admin') NOT NULL DEFAULT 'user';");
    }
};