<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            // Primary key internal database
            $table->id('user_id');

            // NIM / kode admin /  identitas pengguna (mahasiswa, admin)
            $table->string('identity_number')->unique();

            // Nama lengkap
            $table->string('full_name');

            // Untuk login
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'student'])->default('student');

            // Status akun
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
