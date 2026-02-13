<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiKnowledge;
use App\Models\Lecture;
use App\Models\Hall;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StudentChatController extends Controller
{
    public function chat(Request $request)
    {
        try {
            $question = $request->message;
            $questionLower = strtolower($question);

            // جلب كل القوانين والتعليمات النشطة (للاستخدام في الرد العام فقط)
            $instructions = AiKnowledge::where('is_active', true)->get();

            // جلب بيانات المحاضرات
            $lectures = Lecture::with(['hall', 'subject', 'department', 'user'])->get();

            // جلب بيانات القاعات
            $halls = Hall::all();

            $reply = '';

            // تحليل السؤال للعثور على كلمات مفتاحية محددة
            $specificHall = $this->extractHallName($question);
            $specificSubject = $this->extractSubjectName($question);

            // التحقق من نوع السؤال
            if ($specificSubject) {
                // سؤال عن مادة محددة - إظهار الأستاذ
                $reply = $this->getSubjectProfessor($lectures, $specificSubject);
            } elseif ($specificHall) {
                // سؤال عن قاعة محددة
                $reply = $this->getSpecificHallInfo($halls, $specificHall);
            } elseif (strpos($questionLower, 'محاضر') !== false || strpos($questionLower, 'موعد') !== false || strpos($questionLower, 'مادة') !== false) {
                // سؤال عام عن المحاضرات
                $reply = $this->getLecturesInfo($lectures);
            } elseif (strpos($questionLower, 'قاعة') !== false || strpos($questionLower, 'مكان') !== false || strpos($questionLower, 'مبنى') !== false) {
                // سؤال عام عن القاعات
                $reply = $this->getHallsInfo($halls);
            } elseif (strpos($questionLower, 'تسجيل') !== false || strpos($questionLower, 'إعادة') !== false || strpos($questionLower, 'عمل') !== false) {
                // سؤال عن الإجراءات - تحقق من نوع محدد
                
                // 1. محاولة جلب الإجراء المحدد (البحث عن سجل 'practical_repeat' النشط)
                $specificProcedure = $this->getSpecificProcedure($questionLower); 
                
                if ($specificProcedure) {
                    $reply = $specificProcedure; // 👈 يتم استخدام الرد الصحيح (العربي مع الرابط)
                } else {
                    // 2. إذا لم يوجد إجراء محدد، نعرض كل الإجراءات العامة
                    $reply = $this->getProceduresInfo($instructions); 
                }
            } else {
                // سؤال عام
                $reply = 'أنا مساعد جامعي. يمكنني مساعدتك في معلومات المحاضرات، القاعات، والإجراءات الجامعية. حاول سؤال محدد مثل "ما هي مواعيد المحاضرات؟" أو "أين تقع قاعة 101؟" أو "من أستاذ مادة الرياضيات؟"';
            }

            // إرجاع الرد للطالب
            return response()->json([
                'reply' => $reply
            ]);

        } catch (\Throwable $e) {
            // تسجيل الخطأ بالـ log عشان نعرف شو السبب
            Log::error($e->getMessage());
            return response()->json([
                'reply' => 'حدث خطأ داخلي، حاول لاحقاً'
            ], 500);
        }
    }

    private function getLecturesInfo($lectures)
    {
        if ($lectures->isEmpty()) {
            return 'لا توجد محاضرات مسجلة حالياً.';
        }

        $info = "معلومات المحاضرات الحالية:\n\n";
        foreach ($lectures as $lecture) {
            $info .= "📚 المحاضرة: {$lecture->title}\n";
            $info .= "📖 المادة: " . ($lecture->subject ? $lecture->subject->name : 'غير محدد') . "\n";
            $info .= "👨‍🏫 الأستاذ: " . ($lecture->user ? $lecture->user->name : $lecture->professor) . "\n";
            $info .= "🏢 القاعة: " . ($lecture->hall ? $lecture->hall->hall_name . ' - ' . $lecture->hall->building . ' - الطابق ' . $lecture->hall->floor : 'غير محدد') . "\n";
            $info .= "⏰ وقت البداية: " . ($lecture->start_time ? $lecture->start_time->format('Y-m-d H:i') : 'غير محدد') . "\n";
            $info .= "⏰ وقت النهاية: " . ($lecture->end_time ? $lecture->end_time->format('Y-m-d H:i') : 'غير محدد') . "\n";
            $info .= "🏛️ القسم: " . ($lecture->department ? $lecture->department->name : 'غير محدد') . "\n\n";
        }

        return $info;
    }

    private function getHallsInfo($halls)
    {
        if ($halls->isEmpty()) {
            return 'لا توجد قاعات مسجلة حالياً.';
        }

        $info = "معلومات القاعات:\n\n";
        foreach ($halls as $hall) {
            $info .= "🏢 القاعة: {$hall->hall_name}\n";
            $info .= "🏗️ المبنى: {$hall->building}\n";
            $info .= "📶 الطابق: {$hall->floor}\n";
            $info .= "👥 السعة: {$hall->capacity} طالب\n";
            $info .= "🛠️ المعدات: {$hall->equipment}\n\n";
        }

        return $info;
    }

    /**
     * 🛑 الدالة المحدثة: تستخدم Eloquent للبحث عن السجل النشط
     */
    private function getSpecificProcedure($question)
    {
        $questionLower = mb_strtolower($question);
        $actionKeywords = 'إعادة عملي|اعاده عملي|اعادة عملي|إعاده عملي';

        // Check for specific procedures: إعادة عملي using regex for proper matching
        if (preg_match('/(' . $actionKeywords . ')/u', $questionLower) && strpos($questionLower, 'عملي') !== false) {

            // البحث عن أحدث سجل نشط لـ 'practical_repeat'
            $instruction = AiKnowledge::where('topic', 'practical_repeat')
                                      ->where('is_active', true) // يجب أن يكون السجل نشطاً
                                      ->latest() // نجلب الأحدث
                                      ->first();

            if ($instruction) {
                return $instruction->content;
            }
        }

        // Add more specific checks here for other procedures

        return null; // No specific procedure found or activated
    }

    private function getProceduresInfo($instructions)
    {
        if ($instructions->isEmpty()) {
            return 'لا توجد إجراءات مسجلة حالياً.';
        }

        $info = "الإجراءات والتعليمات الجامعية:\n\n";
        foreach ($instructions as $instruction) {
            $topic = Str::title(str_replace('_', ' ', $instruction->topic));
            $info .= "📋 **{$topic}:**\n{$instruction->content}\n\n";
        }

        return $info;
    }

    private function extractHallName($question)
    {
        // البحث عن أسماء القاعات المختلفة في السؤال
        if (preg_match('/قاعة\s+([^?\s]+(?:\s+[^?\s]+)*)/u', $question, $matches)) {
            $hallName = trim($matches[1]);
            $hallName = str_replace(['و', 'في', 'على', 'رقم', '؟', '?', 'أين', 'وين', 'معلومات', 'تفاصيل'], '', $hallName);
            $hallName = trim($hallName);
            if (!empty($hallName) && strlen($hallName) > 0) {
                return $hallName;
            }
        }

        // ثانياً: البحث عن أنماط أخرى
        $patterns = [
            '/(?:أين|معلومات|تفاصيل)\s+(?:ال)?قاعة\s+(?:رقم\s+)?([a-zA-Z0-9\s]+)/u',
            '/(?:ال)?قاعة\s+(?:رقم\s+)?([a-zA-Z0-9\s]+)(?:\s+وين|\s+أين|\?)?/u',
            '/([a-zA-Z0-9]+)\s*(?:ال)?قاعة/u',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $question, $matches)) {
                $hallName = trim($matches[1]);
                $hallName = str_replace(['و', 'في', 'على', 'رقم', '؟', '?', 'أين', 'وين', 'معلومات', 'تفاصيل'], '', $hallName);
                $hallName = trim($hallName);
                if (!empty($hallName) && strlen($hallName) > 0) {
                    return $hallName;
                }
            }
        }

        return null;
    }

    private function extractSubjectName($question)
    {
        // قائمة بأسماء المواد الشائعة
        $subjects = [
            'رياضيات', 'فيزياء', 'كيمياء', 'أحياء', 'تاريخ', 'جغرافيا',
            'عربي', 'إنجليزي', 'فرنسي', 'علوم', 'اجتماعيات', 'فلسفة',
            'اقتصاد', 'إدارة', 'محاسبة', 'قانون', 'طب', 'هندسة', 'حاسوب'
        ];

        $questionLower = strtolower($question);

        foreach ($subjects as $subject) {
            if (strpos($questionLower, strtolower($subject)) !== false) {
                return $subject;
            }
        }

        return null;
    }

    private function getSubjectProfessor($lectures, $subjectName)
    {
        $foundLectures = $lectures->filter(function ($lecture) use ($subjectName) {
            return $lecture->subject && strtolower($lecture->subject->name) === strtolower($subjectName);
        });

        if ($foundLectures->isEmpty()) {
            return "لم أجد محاضرات لمادة '{$subjectName}' في الجدول الحالي.";
        }

        $info = "معلومات مادة '{$subjectName}':\n\n";

        foreach ($foundLectures as $lecture) {
            $professorName = $lecture->user ? $lecture->user->name : $lecture->professor;
            $info .= "👨‍🏫 الأستاذ: {$professorName}\n";
            $info .= "📚 المحاضرة: {$lecture->title}\n";
            $info .= "🏢 القاعة: " . ($lecture->hall ? $lecture->hall->hall_name . ' - ' . $lecture->hall->building : 'غير محدد') . "\n";
            $info .= "⏰ الوقت: " . ($lecture->start_time ? $lecture->start_time->format('Y-m-d H:i') : 'غير محدد') . "\n\n";
        }

        return $info;
    }

    private function getSpecificHallInfo($halls, $hallName)
    {
        // البحث عن القاعة بالضبط أو جزئياً
        $foundHall = $halls->first(function ($hall) use ($hallName) {
            $hallNameLower = strtolower($hallName);
            $dbHallNameLower = strtolower($hall->hall_name);

            // مطابقة دقيقة
            if ($dbHallNameLower === $hallNameLower) {
                return true;
            }

            // مطابقة جزئية (إذا كان الاسم في قاعدة البيانات يحتوي على الاسم المطلوب)
            if (strpos($dbHallNameLower, $hallNameLower) !== false) {
                return true;
            }

            // مطابقة عكسية (إذا كان الاسم المطلوب يحتوي على اسم قاعدة البيانات)
            if (strpos($hallNameLower, $dbHallNameLower) !== false) {
                return true;
            }

            return false;
        });

        if (!$foundHall) {
            return "لم أجد معلومات عن قاعة '{$hallName}' في قاعدة البيانات. يرجى التأكد من اسم القاعة أو المحاولة بصيغة مختلفة.";
        }

        $info = "معلومات قاعة '{$foundHall->hall_name}':\n\n";
        $info .= "🏗️ المبنى: {$foundHall->building}\n";
        $info .= "📶 الطابق: {$foundHall->floor}\n";
        $info .= "👥 السعة: {$foundHall->capacity} طالب\n";
        $info .= "🛠️ المعدات: {$foundHall->equipment}\n";
        $info .= "📍 الموقع: مبنى {$foundHall->building} - الطابق {$foundHall->floor}\n";

        return $info;
    }
}