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
        Schema::create('calendars', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('calendar_category_id');
            $table->string('title');
            $table->text('description');
            $table->string('date');
            $table->timestamps();

            $table->foreign('calendar_category_id')->references('id')->on('calendar_categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
