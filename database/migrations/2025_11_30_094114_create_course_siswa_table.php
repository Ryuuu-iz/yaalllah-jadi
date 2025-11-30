<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_course');
            $table->unsignedBigInteger('id_siswa');
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamps();

            $table->foreign('id_course')->references('id_course')->on('course')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id_siswa')->on('data_siswa')->onDelete('cascade');
            
            $table->unique(['id_course', 'id_siswa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_siswa');
    }
};