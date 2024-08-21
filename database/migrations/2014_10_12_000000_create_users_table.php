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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('full_name');
            $table->string('nickname');
            $table->string('email');
            $table->string('birth');
            $table->string('address');
            $table->string('shift')->nullable();
            $table->string('gender');
            $table->string('password');
            $table->string('profile_picture');
            $table->string('role');
            $table->string('fcm_token')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_verified_email')->default(false);
            $table->boolean('is_graduated')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
