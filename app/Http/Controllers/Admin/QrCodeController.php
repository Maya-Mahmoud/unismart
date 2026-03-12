<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecture;
use Illuminate\Http\Request;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\LectureAttendance;
use App\Models\Student;
use App\Models\Subject;
use App\Models\StudentSubjectAttendance;

class QrCodeController extends Controller
{
    /**
     * Show the QR code generation page.
     */
    public function index()
    {
        return view('admin.generate-qr');
    }

    /**
     * Generate a time-limited QR code for attendance.
     */
    public function generateQrCode(Request $request)
    {
        try {
            $request->validate([
                'lecture_id' => 'required|exists:lectures,id',
            ]);

            $lecture = Lecture::with('hall', 'user')->findOrFail($request->lecture_id);

            // Ensure department_id is set if missing
            if (!$lecture->department_id && $lecture->subject_id) {
                $subject = Subject::find($lecture->subject_id);
                if ($subject) {
                    $lecture->department_id = $subject->department_id;
                    $lecture->save();
                }
            }

            // Always generate a new QR code for attendance
            $qrCode = (string) Str::uuid();
            $lecture->qr_code = $qrCode;
            $lecture->save();

            // Initialize attendance records for eligible students as 'absent'
            $subject = Subject::find($lecture->subject_id);
            if ($subject) {
                $eligibleStudents = Student::where('department_id', $lecture->department_id)
                    ->where('year', $subject->year)
                    ->where(function($q) use ($subject) {
                        $q->where('semester', $subject->semester)
                          ->orWhereNull('semester');
                    })
                    ->pluck('id');

                foreach ($eligibleStudents as $studentId) {
                    LectureAttendance::firstOrCreate(
                        [
                            'student_id' => $studentId,
                            'lecture_id' => $lecture->id,
                        ],
                        [
                            'status' => 'absent',
                            'scanned_at' => null,
                        ]
                    );

                    // Handle student_subject_attendance - check if exists for student and subject
                    $ssa = StudentSubjectAttendance::firstOrCreate(
                        [
                            'student_id' => $studentId,
                            'subject_id' => $lecture->subject_id,
                        ],
                        [
                            'presence_count' => 0,
                            'absence_count' => 0,
                        ]
                    );

                    $ssa->increment('absence_count');
                }
            }

            // URL for attendance scan
            $attendanceUrl = url('/student/scan-qr?qr=' . $qrCode);

            // Generate QR code SVG using BaconQrCode directly
            $renderer = new ImageRenderer(
                new RendererStyle(480),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $qrCodeSvg = $writer->writeString($attendanceUrl);

            return response()->json([
                'success' => true,
                'qr_code_svg' => $qrCodeSvg,
                'lecture' => $lecture,
                'attendance_url' => $attendanceUrl,
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating QR code: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code: ' . $e->getMessage(),
            ], 500);
        }
    }
}