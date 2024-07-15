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
        Schema::create('tugas_images', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('tugas_id');
            $table->text('image');
            $table->timestamps();

            $table->foreign('tugas_id')->references('id')->on('tugas')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_images');
    }
};
