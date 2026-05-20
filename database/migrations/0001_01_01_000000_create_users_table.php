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

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
