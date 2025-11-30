<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekap_absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->date('tanggal');
            $table->enum('status_absensi', ['hadir', 'izin', 'sakit', 'alpha']);
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_kelas');
            $table->unsignedBigInteger('id_guru');
            $table->unsignedBigInteger('id_mapel');
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('data_siswa')->onDelete('cascade');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');
            $table->foreign('id_guru')->references('id_guru')->on('data_guru')->onDelete('cascade');
            $table->foreign('id_mapel')->references('id_mapel')->on('mata_pelajaran')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_absensi');
    }
};