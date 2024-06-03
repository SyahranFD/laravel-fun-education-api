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
        Schema::create('galleries', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('album_id')->nullable();
            $table->string('image');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('album_id')->references('id')->on('albums')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
