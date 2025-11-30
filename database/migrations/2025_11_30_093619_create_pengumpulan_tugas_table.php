<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id('id_pengumpulan');
            $table->unsignedBigInteger('id_tugas');
            $table->unsignedBigInteger('id_siswa');
            $table->string('file_pengumpulan')->nullable();
            $table->text('keterangan')->nullable();
            $table->dateTime('tgl_pengumpulan');
            $table->enum('status', ['tepat_waktu', 'terlambat'])->default('tepat_waktu');
            $table->integer('nilai')->nullable();
            $table->text('feedback_guru')->nullable();
            $table->timestamps();

            $table->foreign('id_tugas')->references('id_tugas')->on('tugas')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id_siswa')->on('data_siswa')->onDelete('cascade');
            
            // Unique constraint: satu siswa hanya bisa mengumpulkan satu kali per tugas
            $table->unique(['id_tugas', 'id_siswa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};