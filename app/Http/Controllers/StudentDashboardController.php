<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lecture;
use App\Models\Subject;
use App\Models\LectureAttendance;
use App\Models\StudentSubjectAttendance;
use App\Models\LectureFile;
use Illuminate\Support\Facades\Log;

class StudentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $departmentId = $student->department_id;
        $studentYear = strtolower($student->year ?? 'first');
        $studentSemester = strtolower($student->semester ?? 'first');

        $year = strtolower($request->input('year', $studentYear));
        $semester = strtolower($request->input('semester', $studentSemester));
        $date = $request->input('date');

        $query = Lecture::join('subjects', 'lectures.subject', '=', 'subjects.name')
            ->where('lectures.department_id', $departmentId)
            ->where('subjects.year', $year)
            ->where('subjects.semester', $semester)
            ->select('lectures.*')
            ->with('hall');

        if ($date) {
            $startOfDay = \Carbon\Carbon::parse($date)->startOfDay();
            $endOfDay = \Carbon\Carbon::parse($date)->endOfDay();
            $query->whereBetween('lectures.start_time', [$startOfDay, $endOfDay]);
        } else {
            $query->where('lectures.start_time', '>=', now());
        }

        $lectures = $query->orderBy('lectures.start_time')->get();

        $displayYear = ucfirst($year);
        $displaySemester = ucfirst($semester);

        return view('student.dashboard', compact('lectures', 'displayYear', 'displaySemester', 'date'));
    }

    public function subjects(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $studentYear = $student->year ?? 'first';
        $studentSemester = $student->semester ?? 'first';
        $year = strtolower($request->input('year', $studentYear));
        $semester = strtolower($request->input('semester', $studentSemester));
        $department = strtolower($student->department->name ?? '');

        $displayYear = ucfirst($year);
        $displaySemester = ucfirst($semester);

        $subjects = Subject::where('department', $department)
            ->where('year', $year)
            ->where('semester', $semester)
            ->with(['lectures'])
            ->withCount('allFiles')
            ->get();

        return view('student.subjects', compact('subjects', 'displayYear', 'displaySemester'));
    }
    public function scanQr()
    {
        return view('student.scan-qr');
    }
    public function showSubjectFiles($subject)
    {
        $user = Auth::user();
        $student = $user->student;
        $department = strtolower($student->department->name ?? '');

        $subjectModel = Subject::where('id', $subject)
            ->where('department', $department)
            ->firstOrFail();

        // Get all unique files from lectures of this subject
        $files = LectureFile::whereHas('lecture.subject', function($q) use ($subject) {
            $q->where('id', $subject);
        })
        ->whereHas('lecture', function($q) use ($department, $subjectModel) {
            // Optional: filter by dept/year/semester
            $q->where('department_id', $subjectModel->department_id);
        })
        ->with(['lecture', 'uploadedBy'])
        ->orderByDesc('created_at')
        ->get();

        $displayYear = ucfirst($subjectModel->year);
        $displaySemester = ucfirst($subjectModel->semester);

        return view('student.subjects', compact('subjectModel', 'files', 'displayYear', 'displaySemester'));
    }

    // --- تابع حضور المحاضرات ---
    public function attendance(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $year = strtolower($request->input('year', $student->year ?? 'first'));
        $semester = strtolower($request->input('semester', $student->semester ?? 'first'));

        $displayYear = ucfirst($year);
        $displaySemester = ucfirst($semester);

        $attendanceData = StudentSubjectAttendance::join('subjects', 'student_subject_attendances.subject_id', '=', 'subjects.id')
            ->join('students', 'student_subject_attendances.student_id', '=', 'students.id')
            ->where('students.user_id', $user->id)
            ->where('subjects.year', $year)
            ->where('subjects.semester', $semester)
            ->where('subjects.department', $student->department->name)
            ->select('subjects.name', 'student_subject_attendances.presence_count', 'student_subject_attendances.absence_count')
            ->get()
            ->keyBy('name')
            ->map(function ($item) {
                return [
                    'present' => $item->presence_count,
                    'absent' => $item->absence_count
                ];
            });

        return view('student.attendance', compact('attendanceData', 'displayYear', 'displaySemester'));
    }

    // --- التعامل مع الملفات والكويزات ---
    public function handleFileScan($fileId)
    {
        $file = LectureFile::findOrFail($fileId);
        if ($file->file_type === 'application/json') {
            return redirect()->route('student.quiz.play', ['id' => $file->id]);
        }
        return response()->file(storage_path('app/public/' . $file->file_path));
    }

    public function playQuiz($id) {
        $file = \App\Models\LectureFile::with('lecture')->findOrFail($id);
        $path = storage_path('app/private/' . $file->file_path);
        if (!file_exists($path)) return abort(404, "Quiz file not found");
        $quizData = file_get_contents($path);
        return view('student.play-quiz', compact('file', 'quizData'));
    }

    /**
     * [الخطوة الأساسية لذكاء فيلوريا]
     * هذا التابع يجلب النصوص المستخرجة للمادة ليرسلها كـ Context للـ AI
     */
    public function getAiContext(Request $request)
    {
        $userMessage = $request->input('message');
        $subjectId = $request->input('subject_id');
        
        $context = "";

        // البحث عن كلمات مفتاحية في رسالة الطالب لتحديد إذا كان يحتاج شرحاً علمياً
        $scientificKeywords = ['اشرح', 'شرح', 'لخص', 'معلومات عن', 'ما هو', 'كيف', 'أهم الأفكار', 'تلخيص'];
        $isScientificQuery = false;

        foreach ($scientificKeywords as $keyword) {
            if (str_contains($userMessage, $keyword)) {
                $isScientificQuery = true;
                break;
            }
        }

        if ($isScientificQuery && $subjectId) {
            // جلب نصوص المحاضرات المستخرجة لهذه المادة حصراً
            $lecturesContext = LectureFile::whereHas('lecture', function($q) use ($subjectId) {
                    $q->where('subject_id', $subjectId);
                })
                ->whereNotNull('extracted_text')
                ->get(['file_name', 'extracted_text']);

            foreach ($lecturesContext as $file) {
                $context .= "Source Lecture: " . $file->file_name . "\nContent: " . $file->extracted_text . "\n---\n";
            }
        }

        return response()->json([
            'context' => $context,
            'type' => empty($context) ? 'general' : 'scientific'
        ]);
    }

    // التابع القديم الذي طلبتِه محدثاً
    public function getExplanationFiles(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $year = $request->input('year', $student->year ?? 'First');
        $semester = $request->input('semester', $student->semester ?? 'First');
        $deptId = $student->department_id;

        $subjects = Subject::where('department_id', $deptId)
            ->where('year', $year)
            ->where('semester', $semester)
            ->get(['id', 'name']);

        $files = [];
        foreach ($subjects as $subject) {
            $subjectFiles = LectureFile::whereHas('lecture', function($q) use ($subject) {
                $q->where('subject_id', $subject->id);
            })->with('lecture')->get(['id', 'file_name', 'extracted_text', 'lecture_id']);

            $files[$subject->id] = $subjectFiles->map(fn($f) => [
                'id' => $f->id,
                'name' => $f->file_name,
                'text_preview' => mb_substr($f->extracted_text ?? 'No text extracted', 0, 100) . '...',
                'lecture_title' => $f->lecture->title ?? 'N/A'
            ]);
        }

        return response()->json([
            'subjects' => $subjects,
            'files' => $files,
            'student' => [
                'year' => $student->year,
                'semester' => $student->semester,
                'department_id' => $deptId
            ]
        ]);
    }
    public function getSubjectFilesJson($subjectId)
{
    try {
        $files = \App\Models\LectureFile::whereHas('lecture', function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            })
            ->select('id', 'file_name')
            ->get();

        return response()->json($files);
    } catch (\Exception $e) {
        // هذا السطر سيخبرك بالخطأ الحقيقي في الـ Network tab بالمتصفح
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}