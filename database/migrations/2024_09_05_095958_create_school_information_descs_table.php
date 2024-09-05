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
        Schema::create('school_information_descs', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('school_information_id');
            $table->text('body');
            $table->timestamps();

            $table->foreign('school_information_id')->references('id')->on('school_information')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_information_descs');
    }
};
