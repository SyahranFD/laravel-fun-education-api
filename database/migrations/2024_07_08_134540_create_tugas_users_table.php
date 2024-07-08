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
        Schema::create('tugas_users', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('tugas_id');
            $table->string('user_id');
            $table->string('status');
            $table->text('note')->nullable()->default("");
            $table->integer('grade')->nullable()->default(0);
            $table->timestamps();

            $table->foreign('tugas_id')->references('id')->on('tugas')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_users');
    }
};
