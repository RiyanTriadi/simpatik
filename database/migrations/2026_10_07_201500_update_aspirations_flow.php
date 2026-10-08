<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('aspirations', function (Blueprint $table) {
            $table->enum('status', ['baru', 'ditindaklanjuti', 'selesai', 'ditolak'])
                ->default('baru')->change();
            $table->dropForeign(['assigned_to']);
            $table->dropColumn(['assigned_to', 'assigned_at']);
        });
    }

    public function down(): void
    {
        Schema::table('aspirations', function (Blueprint $table) {
            $table->enum('status', ['baru', 'dibaca', 'ditindaklanjuti', 'ditolak'])
                ->default('baru')->change();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
        });
    }
};
