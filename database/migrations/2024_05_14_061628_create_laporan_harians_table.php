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
        Schema::create('laporan_harians', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id');
            $table->string('datang_tepat_pada_waktunya');
            $table->string('berpakaian_rapi');
            $table->string('berbuat_baik_dengan_teman');
            $table->string('mau_menolong_dan_berbagi_dengan_teman');
            $table->string('merapikan_alat_belajar_dan_mainan_sendiri');
            $table->string('menyelesaikan_tugas');
            $table->string('membaca');
            $table->string('menulis');
            $table->string('dikte');
            $table->string('keterampilan');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_harians');
    }
};
