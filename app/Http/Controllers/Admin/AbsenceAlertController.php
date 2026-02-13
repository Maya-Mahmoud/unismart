<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentSubjectAttendance;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsenceAlertController extends Controller
{
    public function index(Request $request)
    {
        // Get all student subject attendances and calculate percentage in PHP
        $allRecords = StudentSubjectAttendance::with(['student.user', 'subject'])
            ->get()
            ->map(function ($record) {
                // Assuming total_lectures is calculated or stored; for now, use a placeholder or calculate from data
                // Since total_lectures column doesn't exist, we need to calculate total lectures differently
                // For demonstration, let's assume total_lectures is the sum of attended + absent, but since we don't have attended, perhaps it's stored elsewhere
                // To fix, we'll assume total_lectures is available or calculate based on subject lectures
                // For now, to avoid error, we'll fetch and calculate percentage in PHP after getting all records

                // Placeholder: if total_lectures is not in table, we need to get it from lectures table or assume
                // Let's check if we can get total lectures from Lecture model
                $totalLectures = \App\Models\Lecture::where('subject_id', $record->subject_id)->count(); // Assuming Lecture model exists
                $absencePercentage = $totalLectures > 0 ? min(($record->absence_count / $totalLectures) * 100, 100) : 0;

                return [
                    'student_name' => $record->student->user->name ?? 'Unknown',
                    'student_id' => $record->student->id,
                    'subject_name' => $record->subject->name ?? 'Unknown',
                    'absence_count' => $record->absence_count,
                    'total_lectures' => $totalLectures,
                    'absence_percentage' => round($absencePercentage, 2),
                    'department' => $record->student->department->name ?? 'Unknown',
                    'year' => $record->student->year ?? 'Unknown',
                ];
            })
            ->filter(function ($record) {
                return $record['absence_percentage'] > 55; // Filter for > 55%
            })
            ->sortByDesc('absence_percentage');

        
        // Group by student for summary
        $studentsWithAlerts = $allRecords->groupBy('student_id')->map(function ($studentAlerts) {
            $firstAlert = $studentAlerts->first();
            $totalAbsences = $studentAlerts->sum('absence_count');
            $totalLectures = $studentAlerts->sum('total_lectures');
            $overallAbsenceRate = $totalLectures > 0 ? min(($totalAbsences / $totalLectures) * 100, 100) : 0;

            return [
                'student_name' => $firstAlert['student_name'],
                'student_id' => $firstAlert['student_id'],
                'department' => $firstAlert['department'],
                'year' => $firstAlert['year'],
                'total_absences' => $totalAbsences,
                'total_lectures' => $totalLectures,
                'overall_absence_rate' => round($overallAbsenceRate, 2),
                'subjects' => $studentAlerts->map(function ($alert) {
                    return [
                        'subject_name' => $alert['subject_name'],
                        'absence_count' => $alert['absence_count'],
                        'total_lectures' => $alert['total_lectures'],
                        'absence_percentage' => $alert['absence_percentage'],
                    ];
                })->toArray(),
            ];
        })->values();

        $alerts = $allRecords;

        return view('admin.absence-alerts', compact('studentsWithAlerts', 'alerts'));
    }

    public function sendAlert(Request $request, $studentId)
    {
        $student = Student::with('user')->findOrFail($studentId);

        // Get subjects with high absence rates for this student
        $highAbsenceSubjects = StudentSubjectAttendance::with('subject')
            ->where('student_id', $studentId)
            ->get()
            ->map(function ($record) {
                $totalLectures = \App\Models\Lecture::where('subject_id', $record->subject_id)->count();
                $absencePercentage = $totalLectures > 0 ? min(($record->absence_count / $totalLectures) * 100, 100) : 0;

                return [
                    'subject_name' => $record->subject->name ?? 'Unknown',
                    'absence_percentage' => round($absencePercentage, 2),
                    'absence_count' => $record->absence_count,
                    'total_lectures' => $totalLectures,
                ];
            })
            ->filter(function ($subject) {
                return $subject['absence_percentage'] > 55;
            })
            ->values()
            ->toArray();

        // Send notification to the student with subject details
        $student->user->notify(new \App\Notifications\AbsenceAlert($student, $highAbsenceSubjects));

        return response()->json(['success' => true, 'message' => 'Alert sent successfully to ' . $student->user->name]);
    }

    public function sendAlertsToAll(Request $request)
    {
        // Get all student subject attendances and calculate percentage in PHP
        $allRecords = StudentSubjectAttendance::with(['student.user', 'subject'])
            ->get()
            ->map(function ($record) {
                $totalLectures = \App\Models\Lecture::where('subject_id', $record->subject_id)->count();
                $absencePercentage = $totalLectures > 0 ? min(($record->absence_count / $totalLectures) * 100, 100) : 0;

                return [
                    'student_id' => $record->student->id,
                    'absence_percentage' => round($absencePercentage, 2),
                ];
            })
            ->filter(function ($record) {
                return $record['absence_percentage'] > 55;
            });

        $studentIds = $allRecords->pluck('student_id')->unique();

        $students = Student::with('user')->whereIn('id', $studentIds)->get();

        foreach ($students as $student) {
            // Get subjects with high absence rates for this student
            $highAbsenceSubjects = StudentSubjectAttendance::with('subject')
                ->where('student_id', $student->id)
                ->get()
                ->map(function ($record) {
                    $totalLectures = \App\Models\Lecture::where('subject_id', $record->subject_id)->count();
                    $absencePercentage = $totalLectures > 0 ? min(($record->absence_count / $totalLectures) * 100, 100) : 0;

                    return [
                        'subject_name' => $record->subject->name ?? 'Unknown',
                        'absence_percentage' => round($absencePercentage, 2),
                        'absence_count' => $record->absence_count,
                        'total_lectures' => $totalLectures,
                    ];
                })
                ->filter(function ($subject) {
                    return $subject['absence_percentage'] > 55;
                })
                ->values()
                ->toArray();

            $student->user->notify(new \App\Notifications\AbsenceAlert($student, $highAbsenceSubjects));
        }

        return response()->json(['success' => true, 'message' => 'Alerts sent to all students with high absence rates']);
    }
}
