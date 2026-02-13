<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_subject_attendances', function (Blueprint $table) {
    // أولاً نحذف المفتاح الأجنبي القديم (إن وجد)
    $table->dropForeign(['lecture_id']);

    // بعدين نضيف فقط العلاقة الجديدة بدون إنشاء العمود مرة تانية
    $table->foreign('lecture_id')
          ->references('id')
          ->on('lectures')
          ->onDelete('cascade');
});

    }

    public function down()
    {
        Schema::table('student_subject_attendances', function (Blueprint $table) {
            // نتراجع: نحذف الـ cascade ونرجع FK عادي (أو تحذفه لو تحبين)
            $table->dropForeign(['lecture_id']);

            // إعادة الربط بدون cascade (اختياري — عدّلي حسب ما كان سابقاً)
            $table->foreign('lecture_id')->references('id')->on('lectures');
        });
    }
};
