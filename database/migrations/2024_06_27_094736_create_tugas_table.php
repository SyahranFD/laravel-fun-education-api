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
        Schema::create('tugas', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('tugas_category_id');
            $table->string('title');
            $table->text('description');
            $table->string('status')->nullable()->default('tersedia');
            $table->date('deadline');
            $table->timestamps();

            $table->foreign('tugas_category_id')->references('id')->on('tugas_categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
