<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {

            // Primary key internal database
            $table->id('category_id');

            // Nama kategori
            $table->string('name')->unique();

            // Slug URL
            $table->string('slug')->unique();

            // Deskripsi kategori
            $table->text('description')->nullable();

            $table->timestamps();

            // Soft delete
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};