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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->enum('creator_type', ['internal', 'eksternal']);
            $table->foreignId('category_id')->constrained('categories');
            $table->boolean('is_anonymous')->default(false);
            $table->string('subject');
            $table->text('description');
            $table->date('incident_date');
            $table->string('incident_location');
            $table->string('attachment_path')->nullable();
            $table->string('reporter_name')->nullable();
            $table->string('reporter_phone', 13)->nullable();
            $table->string('reporter_email')->nullable();
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->enum('priority', ['rendah', 'sedang', 'tinggi', 'urgent'])->default('sedang');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
