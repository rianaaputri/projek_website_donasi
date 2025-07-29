<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPaymentStatusEnumInDonationsTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE donations MODIFY COLUMN payment_status ENUM('pending', 'success','failed', 'expired') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE donations MODIFY COLUMN payment_status ENUM('pending', 'succes', 'failed','expired') NOT NULL DEFAULT 'pending'");
    }
}

