<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        try {
            $request->validate([
                'qr_code' => 'required|string',
            ]);

            $qrCode = $request->qr_code;
            $user = Auth::user();
            
            Log::info('QR Scan attempt', ['user_id' => $user->id, 'qr_code' => $qrCode]);

            $student = $user->student;

            if (!$student) {
                Log::error('Student record not found', ['user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Student record not found. Please contact admin.',
                ], 404);
            }

            // Find lecture by QR code
            $lecture = Lecture::where('qr_code', $qrCode)->first();

            if (!$lecture) {
                Log::warning('Invalid QR code', ['qr_code' => $qrCode]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR code.',
                ], 404);
            }

            Log::info('Lecture found', ['lecture_id' => $lecture->id, 'subject_id' => $lecture->subject_id]);

            // Check eligibility: same department, year, semester (allow null student semester for any)
            $subject = Subject::find($lecture->subject_id);
            
            if (!$subject) {
                Log::error('Subject not found', ['subject_id' => $lecture->subject_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Subject not found for this lecture.',
                ], 404);
            }

            if ($student->department_id !== $lecture->department_id ||
                strtolower($student->year) !== strtolower($subject->year) ||
                ($student->semester !== null && $subject->semester !== null && strtolower($student->semester) !== strtolower($subject->semester))) {
                Log::warning('Student not eligible', [
                    'student_dept' => $student->department_id,
                    'lecture_dept' => $lecture->department_id,
                    'student_year' => $student->year,
                    'subject_year' => $subject->year
                ]);
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
            
            // Only decrement if absence_count is greater than 0
            if ($subjectAttendance->absence_count > 0) {
                $subjectAttendance->decrement('absence_count');
            }
            $subjectAttendance->increment('presence_count');

            // Get the first lecture file if exists
            $lectureFile = \App\Models\LectureFile::where('lecture_id', $lecture->id)->first();
            $fileUrl = null;
            if ($lectureFile) {
                // Use the correct route name - it's student.student.lecture-files.view because of nested group
                $fileUrl = route('student.student.lecture-files.view', $lectureFile->id);
                
                Log::info('File URL generated', [
                    'lecture_id' => $lecture->id,
                    'file_id' => $lectureFile->id,
                    'file_name' => $lectureFile->file_name,
                    'file_url' => $fileUrl
                ]);
            } else {
                Log::info('No lecture file found for lecture', ['lecture_id' => $lecture->id]);
            }

            Log::info('Attendance marked successfully', [
                'student_id' => $student->id,
                'lecture_id' => $lecture->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully for ' . $lecture->title . ' (' . $subject->name . ')',
                'subject' => $subject->name,
                'file_url' => $fileUrl,
                'lecture_title' => $lecture->title,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in scanQr: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
