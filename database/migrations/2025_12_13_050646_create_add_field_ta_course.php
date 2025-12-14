<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course', function (Blueprint $table) {
            // Cek apakah kolom id_TA belum ada
            if (!Schema::hasColumn('course', 'id_TA')) {
                $table->unsignedBigInteger('id_TA')->nullable()->after('id_kelas');
                $table->foreign('id_TA')->references('id_TA')->on('tahun_ajaran')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('course', function (Blueprint $table) {
            if (Schema::hasColumn('course', 'id_TA')) {
                $table->dropForeign(['id_TA']);
                $table->dropColumn('id_TA');
            }
        });
    }
};