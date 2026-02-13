<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_knowledges', function (Blueprint $table) {
            $table->id();
            $table->string('topic'); 
            // examples: registration, practical_repeat, halls, exams

            $table->text('content'); 
            // الشرح يلي بدنا الذكاء يفهمه

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_knowledges');
    }
};
