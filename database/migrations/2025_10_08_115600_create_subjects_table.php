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
    Schema::create('subjects', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->enum('semester', ['first', 'second']);
        $table->enum('year', ['first', 'second', 'third', 'fourth', 'fifth']);
        $table->enum('department', [
            'communications',
            'energy',
            'marine',
            'design_and_production',
            'computers',
            'medical',
            'mechatronics',
            'power',
        ]);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
