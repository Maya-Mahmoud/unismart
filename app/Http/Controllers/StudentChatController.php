<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiKnowledge;
use App\Models\Lecture;
use App\Models\LectureFile;
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

            // جلب بيانات المحاضرات مع الملفات
            $lectures = Lecture::with(['hall', 'subject', 'department', 'user', 'lectureFiles'])->get();

            // جلب بيانات القاعات
            $halls = Hall::all();

            $reply = '';

            // تحليل السؤال للعثور على كلمات مفتاحية محددة
            $specificHall = $this->extractHallName($question);
            $specificSubject = $this->extractSubjectName($question);

            // التحقق من نوع السؤال
            if ($specificSubject) {
                // سؤال عن مادة محددة - إظهار الأستاذ والمحاضرات والملفات
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
                    $reply = $specificProcedure;
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

    private function getSpecificProcedure($question)
    {
        $questionLower = mb_strtolower($question);
        $actionKeywords = 'إعادة عملي|اعاده عملي|اعادة عملي|إعاده عملي';

        if (preg_match('/(' . $actionKeywords . ')/u', $questionLower) && strpos($questionLower, 'عملي') !== false) {
            $instruction = AiKnowledge::where('topic', 'practical_repeat')
                                      ->where('is_active', true)
                                      ->latest()
                                      ->first();

            if ($instruction) {
                return $instruction->content;
            }
        }

        return null;
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
        if (preg_match('/قاعة\s+([^?\s]+(?:\s+[^?\s]+)*)/u', $question, $matches)) {
            $hallName = trim($matches[1]);
            $hallName = str_replace(['و', 'في', 'على', 'رقم', '؟', '?', 'أين', 'وين', 'معلومات', 'تفاصيل'], '', $hallName);
            $hallName = trim($hallName);
            if (!empty($hallName) && strlen($hallName) > 0) {
                return $hallName;
            }
        }

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
        // جلب كل المواد من قاعدة البيانات
        $subjects = \App\Models\Subject::all()->pluck('name')->toArray();

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
        // التحقق من وجود المادة في قاعدة البيانات أولاً
        $subjectExists = \App\Models\Subject::whereRaw('LOWER(name) = ?', [strtolower($subjectName)])->exists();
        
        if (!$subjectExists) {
            return "المادة '{$subjectName}' غير موجودة في قاعدة البيانات.";
        }

        // جلب المحاضرات للمادة المحددة
        $foundLectures = $lectures->filter(function ($lecture) use ($subjectName) {
            return $lecture->subject && strtolower($lecture->subject->name) === strtolower($subjectName);
        });

        if ($foundLectures->isEmpty()) {
            return "لا توجد محاضرات لهذه المادة ({$subjectName}) حتى الآن.";
        }

        $info = "معلومات مادة '{$subjectName}':\n\n";

        foreach ($foundLectures as $lecture) {
            $professorName = $lecture->user ? $lecture->user->name : $lecture->professor;
            $info .= "👨‍🏫 الأستاذ: {$professorName}\n";
            $info .= "📚 المحاضرة: {$lecture->title}\n";
            $info .= "🏢 القاعة: " . ($lecture->hall ? $lecture->hall->hall_name . ' - ' . $lecture->hall->building : 'غير محدد') . "\n";
            $info .= "⏰ الوقت: " . ($lecture->start_time ? $lecture->start_time->format('Y-m-d H:i') : 'غير محدد') . "\n";
            
            // جلب الملفات المرفقة لهذه المحاضرة
            $files = $lecture->lectureFiles;
            if ($files && $files->count() > 0) {
                $info .= "📁 الملفات المرفقة:\n";
                foreach ($files as $file) {
                    $info .= "   📄 {$file->file_name}\n";
                }
                $info .= "\n";
            } else {
                $info .= "📁 لا توجد ملفات لهذه المحاضرة حتى الآن.\n\n";
            }
        }

        return $info;
    }

    private function getSpecificHallInfo($halls, $hallName)
    {
        $foundHall = $halls->first(function ($hall) use ($hallName) {
            $hallNameLower = strtolower($hallName);
            $dbHallNameLower = strtolower($hall->hall_name);

            if ($dbHallNameLower === $hallNameLower) {
                return true;
            }

            if (strpos($dbHallNameLower, $hallNameLower) !== false) {
                return true;
            }

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
