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
         Schema::table('student_subject_attendances', function (Blueprint $table) {
            // Drop the existing foreign key to avoid duplicate key error
            $table->dropForeign(['lecture_id']);

            // نعدل العمود ليصير NOT NULL (إذا بتحبي تربطي كل سجل بمحاضرة)
            $table->unsignedBigInteger('lecture_id')->nullable(false)->change();

            // نعيد إنشاء العلاقة مع الحذف التلقائي
            $table->foreign('lecture_id')->references('id')->on('lectures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('student_subject_attendances', function (Blueprint $table) {
            // نرجع العلاقة مثل قبل (nullable)
            $table->dropForeign(['lecture_id']);
            $table->unsignedBigInteger('lecture_id')->nullable()->change();
            $table->foreign('lecture_id')->references('id')->on('lectures');
        });
    }
};
