<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id('id_tugas');
            $table->string('nama_tugas');
            $table->text('desk_tugas')->nullable();
            $table->string('file_tugas')->nullable();
            $table->string('tgl_upload')->nullable();
            $table->dateTime('deadline');
            $table->unsignedBigInteger('id_course');
            $table->unsignedBigInteger('id_materi');
            $table->timestamps();

            $table->foreign('id_course')->references('id_course')->on('course')->onDelete('cascade');
            $table->foreign('id_materi')->references('id_materi')->on('materi_pembelajaran')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};