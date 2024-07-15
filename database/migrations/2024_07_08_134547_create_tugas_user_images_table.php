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
        Schema::create('tugas_user_images', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('tugas_user_id');
            $table->text('image');
            $table->timestamps();

            $table->foreign('tugas_user_id')->references('id')->on('tugas_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_user_images');
    }
};
