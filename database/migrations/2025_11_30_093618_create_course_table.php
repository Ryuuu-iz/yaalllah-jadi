<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course', function (Blueprint $table) {
            $table->id('id_course');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('tgl_upload')->nullable();
            $table->unsignedBigInteger('id_mapel');
            $table->unsignedBigInteger('id_kelas');
            $table->string('enrollment_key')->unique();
            $table->unsignedBigInteger('id_guru');
            $table->timestamps();

            $table->foreign('id_mapel')->references('id_mapel')->on('mata_pelajaran')->onDelete('cascade');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');
            $table->foreign('id_guru')->references('id_guru')->on('data_guru')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course');
    }
};