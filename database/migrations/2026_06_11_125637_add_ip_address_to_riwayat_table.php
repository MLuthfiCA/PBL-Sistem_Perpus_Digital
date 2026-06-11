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
        Schema::table('riwayat', function (Blueprint $table) {
            if (!Schema::hasColumn('riwayat', 'ip_address')) {
                $table->string('ip_address')->nullable();
            }
            if (!Schema::hasColumn('riwayat', 'user_agent')) {
                $table->text('user_agent')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat', function (Blueprint $table) {
            if (Schema::hasColumn('riwayat', 'ip_address')) {
                $table->dropColumn('ip_address');
            }
            if (Schema::hasColumn('riwayat', 'user_agent')) {
                $table->dropColumn('user_agent');
            }
        });
    }
};
