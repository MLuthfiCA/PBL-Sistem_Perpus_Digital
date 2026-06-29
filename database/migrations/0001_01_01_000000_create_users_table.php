<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            // === Kolom ERD (PENGGUNA) ===
            $table->id('id_pengguna');
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'mahasiswa'])->default('mahasiswa');

            // === Kolom tambahan ===
            // identity_number: NIM untuk mahasiswa, NIK untuk admin
            $table->string('identity_number')->unique()->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
