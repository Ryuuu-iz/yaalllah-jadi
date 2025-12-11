<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekap_absensi', function (Blueprint $table) {
            $table->dateTime('deadline')->nullable()->after('tanggal');
            $table->boolean('is_open')->default(true)->after('deadline');
            $table->text('keterangan')->nullable()->after('status_absensi');
        });
    }

    public function down(): void
    {
        Schema::table('rekap_absensi', function (Blueprint $table) {
            $table->dropColumn(['deadline', 'is_open', 'keterangan']);
        });
    }
};