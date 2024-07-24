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
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id');
            $table->string('laporan_harian_id')->nullable();
            $table->string('tugas_user_id')->nullable();
            $table->integer('point');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('laporan_harian_id')->references('id')->on('laporan_harians')->cascadeOnDelete();
            $table->foreign('tugas_user_id')->references('id')->on('tugas_users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboards');
    }
};
