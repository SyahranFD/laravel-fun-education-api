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
        Schema::create('calendar_files', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('calendar_id');
            $table->string('file');
            $table->timestamps();

            $table->foreign('calendar_id')->references('id')->on('calendars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_files');
    }
};
