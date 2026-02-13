<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\StudentSubjectAttendance;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $departments = \App\Models\Department::all();

        // Calculate initial stats - default to 0
        $stats = [
            'total_students' => 0,
            'average_attendance' => 0,
            'average_absence' => 0,
        ];

        // If AJAX request for subjects
        if ($request->expectsJson() || $request->is('admin/api/*')) {
            return $this->getSubjects($request);
        }

        return view('admin.performance', compact('departments', 'stats'));
    }

    private function calculateStats(Request $request)
    {
        $studentQuery = \App\Models\Student::query();

        if ($request->filled('year')) {
            $studentQuery->where('year', $request->year);
        }

        if ($request->filled('department_id')) {
            $studentQuery->where('department_id', $request->department_id);
        }

        $totalStudents = $studentQuery->count();

        return [
            'total_students' => $totalStudents,
            'average_attendance' => 0,
            'average_absence' => 0,
        ];
    }

    private function getSubjects(Request $request)
    {
        $query = Subject::query()->with(['department', 'lectures']);

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('id', $request->subject_id);
        }

        $subjects = $query->get()->map(function ($subject) {
            $attendanceData = StudentSubjectAttendance::where('subject_id', $subject->id)->get();
            $totalPresence = $attendanceData->sum('presence_count');
            $totalAbsence = $attendanceData->sum('absence_count');
            $totalLectures = $subject->lectures->count();

            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'year' => $subject->year,
                'semester' => $subject->semester,
                'department' => $subject->department ? $subject->department->name : 'N/A',
                'total_lectures' => $totalLectures,
                'total_presence' => $totalPresence,
                'total_absence' => $totalAbsence,
                'attendance_rate' => $totalLectures > 0 ? round(($totalPresence / ($totalPresence + $totalAbsence)) * 100, 2) : 0,
            ];
        });

        return response()->json($subjects);
    }

    public function getSubjectsApi(Request $request)
    {
        $query = Subject::query();

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $subjects = $query->get(['id', 'name']);

        return response()->json($subjects);
    }

    public function getStatsApi(Request $request)
    {
        $stats = $this->calculateStats($request);
        return response()->json($stats);
    }

    public function getSubjectStatsApi(Request $request)
    {
        $subjectId = $request->subject_id;
        if (!$subjectId) {
            return response()->json(['average_attendance' => 0, 'average_absence' => 0, 'total_lectures' => 0]);
        }

        $attendanceData = StudentSubjectAttendance::where('subject_id', $subjectId)->get();
        $totalPresence = $attendanceData->sum('presence_count');
        $totalAbsence = $attendanceData->sum('absence_count');
        $total = $totalPresence + $totalAbsence;

        $averageAttendance = $total > 0 ? round(($totalPresence / $total) * 100, 2) : 0;
        $averageAbsence = $total > 0 ? round(($totalAbsence / $total) * 100, 2) : 0;

        $totalLectures = \App\Models\Lecture::where('subject_id', $subjectId)->count();

        return response()->json([
            'average_attendance' => $averageAttendance,
            'average_absence' => $averageAbsence,
            'total_lectures' => $totalLectures,
        ]);
    }public function exportCsv(Request $request)
{
    $subjectId = $request->subject_id;
    if (!$subjectId) {
        return response()->json(['error' => 'Subject ID is required'], 400);
    }

    $subject = Subject::find($subjectId);
    if (!$subject) {
        return response()->json(['error' => 'Subject not found'], 404);
    }

    $filename = 'attendance_' . $subject->name . '_' . now()->format('Y-m-d_H-i-s') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function () use ($subjectId) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['Student Name', 'Number of Attendees', 'Number of Absences', 'Attendance Rate']);

        StudentSubjectAttendance::join('students', 'student_subject_attendances.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('student_subject_attendances.subject_id', $subjectId)
            ->select(
                'users.name',
                'student_subject_attendances.presence_count',
                'student_subject_attendances.absence_count'
            )
            ->chunk(1000, function ($attendances) use ($file) {
                foreach ($attendances as $data) {
                    $totalLectures = $data->presence_count + $data->absence_count; // حساب إجمالي المحاضرات
                    $attendanceRate = $totalLectures > 0 ? round(($data->presence_count / $totalLectures) * 100, 2) : 0; // نسبة الحضور
                    fputcsv($file, [
                        $data->name,
                        $data->presence_count,
                        $data->absence_count,
                        $attendanceRate . '%'
                    ]);
                }
            });

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}
