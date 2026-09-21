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
        Schema::create('aspirations', function (Blueprint $table) {
            $table->id(); 
            $table->string('ticket_number')->unique(); 
            $table->enum('creator_type', ['internal', 'eksternal']); 
            $table->boolean('is_anonymous')->default(false); 
            $table->string('subject')->nullable(); 
            $table->text('description'); 
            $table->string('attachment_path')->nullable(); 
            $table->string('reporter_name')->nullable(); 
            $table->string('reporter_phone', 13)->nullable(); 
            $table->string('reporter_email')->nullable(); 
            $table->enum('status', ['baru', 'dibaca', 'ditindaklanjuti'])->default('baru'); 
            $table->foreignId('category_id')->nullable()->constrained('categories'); 
            $table->timestamps(); 
            $table->softDeletes();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspirations');
    }
};
