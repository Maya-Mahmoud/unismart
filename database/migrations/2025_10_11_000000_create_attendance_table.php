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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('lecture_id');
            $table->enum('status', ['present', 'absent'])->default('absent');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lecture_id')->references('id')->on('lectures')->onDelete('cascade');
            $table->unique(['student_id', 'lecture_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
