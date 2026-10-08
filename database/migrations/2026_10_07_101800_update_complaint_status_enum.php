<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->enum('status', ['baru', 'diverifikasi', 'di_assign', 'diproses', 'selesai', 'ditolak'])
                ->default('baru')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])
                ->default('baru')
                ->change();
        });
    }
};
