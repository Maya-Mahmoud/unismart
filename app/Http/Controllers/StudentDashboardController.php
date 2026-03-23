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

        // Display versions
        $displayYear = ucfirst($year);
        $displaySemester = ucfirst($semester);

        return view('student.dashboard', compact('lectures', 'displayYear', 'displaySemester', 'date'));
    }

    public function subjects(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        // السنة والفصل الافتراضيين (من حساب الطالب)
        $studentYear = $student->year ?? 'first';
        $studentSemester = $student->semester ?? 'first';
        $year = strtolower($request->input('year', $studentYear));
        $semester = strtolower($request->input('semester', $studentSemester));
        $department = strtolower($student->department->name ?? '');

        // Display versions for view (capitalized)
        $displayYear = ucfirst($year);
        $displaySemester = ucfirst($semester);

        // فلترة المواد حسب القسم والسنة والفصل
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

    public function attendance(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $year = strtolower($request->input('year', $student->year ?? 'first'));
        $semester = strtolower($request->input('semester', $student->semester ?? 'first'));

        $displayYear = ucfirst($year);
        $displaySemester = ucfirst($semester);

        // Get attendance data from student_subject_attendances for this student's subjects
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
    // 1. تابع معالجة الرابط بعد السكان
public function handleFileScan($fileId)
{
    $file = LectureFile::findOrFail($fileId);

    // التحقق من نوع الملف في قاعدة البيانات
    if ($file->file_type === 'application/json') {
        // إذا كان اختبار، نرسله لصفحة التشغيل التفاعلية
        return redirect()->route('student.quiz.play', ['id' => $file->id]);
    }

    // إذا كان PDF أو غيره، نفتحه بشكل طبيعي في المتصفح
    return response()->file(storage_path('app/public/' . $file->file_path));
}

// 2. تابع عرض صفحة الاختبار للطالب
 public function playQuiz($id) {
    $file = \App\Models\LectureFile::with('lecture')->findOrFail($id);
    
    // قراءة الملف من المجلد الخاص (Private)
    $path = storage_path('app/private/' . $file->file_path);
    
    if (!file_exists($path)) {
        return abort(404, "Quiz file not found");
    }

    $quizData = file_get_contents($path);

    return view('student.play-quiz', compact('file', 'quizData'));
}
}

