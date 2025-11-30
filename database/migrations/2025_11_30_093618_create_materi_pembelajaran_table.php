<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_pembelajaran', function (Blueprint $table) {
            $table->id('id_materi');
            $table->string('nama_materi');
            $table->text('desk_materi')->nullable();
            $table->string('file_materi')->nullable();
            $table->unsignedBigInteger('id_TA');
            $table->unsignedBigInteger('id_course');
            $table->timestamps();

            $table->foreign('id_TA')->references('id_TA')->on('tahun_ajaran')->onDelete('cascade');
            $table->foreign('id_course')->references('id_course')->on('course')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_pembelajaran');
    }
};