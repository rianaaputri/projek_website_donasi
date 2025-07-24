<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donation_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('donor_name');
            $table->text('comment');
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            $table
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_comments');
    }
};
