<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lecture;
use App\Models\LectureAttendance;
use App\Models\Student;
use App\Models\Subject;
use App\Models\StudentSubjectAttendance;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    /**
     * Handle QR code scanning for attendance.
     */
    public function scanQr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $qrCode = $request->qr_code;
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student record not found. Please contact admin.',
            ], 404);
        }

        // Find lecture by QR code
        $lecture = Lecture::where('qr_code', $qrCode)->first();

        if (!$lecture) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code.',
            ], 404);
        }

        // Check eligibility: same department, year, semester (allow null student semester for any)
        $subject = Subject::find($lecture->subject_id);
        if (!$subject ||
            $student->department_id !== $lecture->department_id ||
            strtolower($student->year) !== strtolower($subject->year) ||
            ($student->semester !== null && $subject->semester !== null && strtolower($student->semester) !== strtolower($subject->semester))) {
            return response()->json([
                'success' => false,
                'message' => 'You are not eligible for this lecture.',
            ], 403);
        }

        // Get or create attendance record
        $attendance = LectureAttendance::firstOrCreate(
            [
                'student_id' => $student->id,
                'lecture_id' => $lecture->id,
            ],
            [
                'status' => 'absent',
                'scanned_at' => null,
            ]
        );

        if ($attendance->status === 'present') {
            return response()->json([
                'success' => false,
                'message' => 'You have already marked attendance for this lecture.',
            ], 409);
        }

        // Update to present
        $attendance->status = 'present';
        $attendance->scanned_at = now();
        $attendance->save();

        // Verify save
        $updatedAttendance = LectureAttendance::where('id', $attendance->id)->first();
        if (!$updatedAttendance || $updatedAttendance->status !== 'present') {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update attendance. Please try again.',
            ], 500);
        }

        // Decrement absence_count and increment presence_count in student_subject_attendances
        $subjectAttendance = StudentSubjectAttendance::firstOrCreate(
            [
                'student_id' => $student->id,
                'subject_id' => $lecture->subject_id,
            ],
            [
                'presence_count' => 0,
                'absence_count' => 0,
            ]
        );
        $subjectAttendance->decrement('absence_count');
        $subjectAttendance->increment('presence_count');

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully for ' . $lecture->title . ' (' . $subject->name . ')',
            'subject' => $subject->name,
        ]);
    }
}
