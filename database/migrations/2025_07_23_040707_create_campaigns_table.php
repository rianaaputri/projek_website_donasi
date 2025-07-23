<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id(); 
            
            $table->string('title'); 
            $table->text('description'); 
            $table->string('category'); 

            $table->decimal('target_amount', 15, 2); 
            $table->decimal('collected_amount', 15, 2)->default(0.00);
            
            $table->string('image')->nullable(); 

            $table->enum('status', ['active', 'completed', 'inactive'])->default('active');
            
            $table->timestamp('created_at')->nullable(); 
            $table->timestamp('updated_at')->nullable(); 

            $table->boolean('is_active')->default(1); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
