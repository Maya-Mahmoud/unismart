<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecture;
use App\Models\Hall;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Department;
use App\Models\StudentSubjectAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class LectureController extends Controller
{ public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $query = Lecture::with(['hall', 'user', 'subject']);
            if (Auth::user()->role === 'professor') {
                $query->where('user_id', Auth::id());
            }
            return $query->get();
        }

        $halls = Hall::all();
        $subjects = Subject::all();
        $departments = \App\Models\Department::all();
        $professors = User::where('role', 'professor')->get();
        return view('admin.lecture-management', compact('halls', 'subjects', 'departments', 'professors'));
    }



    public function getAvailableHalls(Request $request)
    {
        $startTime = $request->query('start_time');
        $endTime = $request->query('end_time');

        if (!$startTime || !$endTime) {
            // If no time parameters, return all halls (fallback)
            $halls = Hall::all(['id', 'hall_name']);
            return response()->json($halls);
        }

        // Get halls that do not have overlapping lectures or bookings
        $halls = Hall::whereDoesntHave('lectures', function ($query) use ($startTime, $endTime) {
            $query->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
        })
        ->whereDoesntHave('bookings', function ($query) use ($startTime, $endTime) {
            $query->where('status', 'booked')
                  ->where('booked_at', '<', $endTime)
                  ->where('booked_at', '>', $startTime);
        })
        ->get(['id', 'hall_name']);

        return response()->json($halls);
    }

    public function getLecturesByHall(Request $request, $hallId)
    {
        try {
            $hall = Hall::findOrFail($hallId);
            $lectures = $hall->lectures()
                ->with(['user', 'subject'])
                ->orderBy('start_time', 'asc')
                ->get()
                ->map(function ($lecture) {
                    $startTime = $lecture->start_time;
                    $endTime = $lecture->end_time;
                    $status = 'completed'; // default
                    if ($startTime && $endTime) {
                        if ($startTime->isPast() && $endTime->isFuture()) {
                            $status = 'ongoing';
                        } elseif ($startTime->isFuture()) {
                            $status = 'upcoming';
                        }
                    }

                    return [
                        'id' => $lecture->id,
                        'title' => $lecture->title,
                        'subject' => $lecture->subject ?? 'N/A',
                        'professor' => $lecture->user ? $lecture->user->name : ($lecture->professor ?? 'N/A'),
                        'start_time' => $startTime ? $startTime->format('Y-m-d H:i') : null,
                        'end_time' => $endTime ? $endTime->format('Y-m-d H:i') : null,
                        'status' => $status,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $lectures
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching lectures for hall: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch lectures'
            ], 500);
        }
    }

    public function advancedScheduler()
    {
        $halls = Hall::all();
        $subjects = Subject::all();
        $departments = \App\Models\Department::all();
        $professors = User::where('role', 'professor')->get();
        return view('admin.advanced-scheduler', compact('halls','subjects', 'departments', 'professors'));
    }

    public function store(Request $request)
    {
        try {
            // Manual check to ensure start_time is before end_time
            if (strtotime($request->start_time) >= strtotime($request->end_time)) {
                return response()->json(['success' => false, 'message' => 'Start time must be before end time.'], 400);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'subject_id' => 'required|exists:subjects,id',
                'professor_id' => 'required|exists:users,id',
                'hall_id' => 'required|exists:halls,id',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'max_students' => 'nullable|integer|min:1',
                'recurringLecture' => 'nullable|boolean',
                'repeat_pattern' => ['nullable', Rule::in(['daily', 'weekly', 'monthly'])],
                'end_date' => 'nullable|date|after:start_time',
            ]);

            // Check for overlapping lectures or bookings in the selected hall
            $hall = Hall::find($validated['hall_id']);
            if (!$hall) {
                return response()->json(['success' => false, 'message' => 'Hall not found.'], 404);
            }

            $overlappingLectures = $hall->lectures()
                ->where(function ($query) use ($validated) {
                    $query->where('start_time', '<', $validated['end_time'])
                          ->where('end_time', '>', $validated['start_time']);
                })
                ->exists();

            $overlappingBookings = $hall->bookings()
                ->where('status', 'booked')
                ->where('booked_at', '<', $validated['end_time'])
                ->where('booked_at', '>', $validated['start_time'])
                ->exists();

            if ($overlappingLectures || $overlappingBookings) {
                return response()->json(['success' => false, 'message' => 'The hall is already booked during this time.'], 409);
            }

            Log::info('Lecture creation attempt', ['validated_data' => $validated]);

            $subject = Subject::find($validated['subject_id']);
            if (!$subject) {
                return response()->json(['success' => false, 'message' => 'Subject not found.'], 404);
            }

            // Set both subject and subject_id
            $validated['subject'] = $subject->name;
            $validated['department_id'] = $subject->department_id;

            $professor = User::find($validated['professor_id']);
            $validated['professor'] = $professor->name;
            $validated['user_id'] = $professor->id;

            // Handle recurring lectures
            if (!empty($validated['recurringLecture']) && $validated['recurringLecture']) {
                $startDate = new \DateTime($validated['start_time']);
                $endDate = new \DateTime($validated['end_date']);
                $intervalSpec = 'P1W';
                if ($validated['repeat_pattern'] === 'daily') $intervalSpec = 'P1D';
                elseif ($validated['repeat_pattern'] === 'monthly') $intervalSpec = 'P1M';
                $interval = new \DateInterval($intervalSpec);
                $period = new \DatePeriod($startDate, $interval, $endDate);

                $lectures = [];
                foreach ($period as $date) {
                    $start = $date->format('Y-m-d H:i:s');
                    $end = (clone $date)->add(new \DateInterval('PT' . (strtotime($validated['end_time']) - strtotime($validated['start_time'])) . 'S'))->format('Y-m-d H:i:s');

                    $lectures[] = [
                        'title' => $validated['title'],
                        'subject' => $validated['subject'],
                        'subject_id' => $validated['subject_id'],
                        'department_id' => $validated['department_id'],
                        'professor' => $validated['professor'],
                        'hall_id' => $validated['hall_id'],
                        'start_time' => $start,
                        'end_time' => $end,
                        'max_students' => $validated['max_students'],
                        'user_id' => $validated['user_id'],
                        'qr_code' => Str::uuid(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                Lecture::insert($lectures);

                // Send notifications for each recurring lecture
                $createdLectures = Lecture::where('user_id', $validated['user_id'])
                    ->where('subject_id', $validated['subject_id'])
                    ->whereBetween('start_time', [$startDate, $endDate])
                    ->get();

                foreach ($createdLectures as $lecture) {
                    $this->sendLectureNotifications($lecture);
                }

                return response()->json(['success' => true, 'message' => 'Recurring lectures created successfully!'], 201);
            }

            $lecture = Lecture::create($validated);
            $lecture->qr_code = Str::uuid();
            $lecture->save();

            // Send notifications
            $this->sendLectureNotifications($lecture);

            // Update hall status after creating lecture
            $hall->updateStatusBasedOnLectures();

            return response()->json(['success' => true, 'message' => 'Lecture created successfully!', 'data' => $lecture->load(['hall', 'user', 'subject'])], 201);
        } catch (\Exception $e) {
            Log::error('Error storing lecture: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to schedule lecture due to server error.', 'error' => $e->getMessage()], 500);
        }
    }

public function showAttendance($id)
{
    $lecture = Lecture::with(['hall', 'user', 'subject'])->findOrFail($id);
    $attendances = \App\Models\LectureAttendance::with(['student.user'])
        ->where('lecture_id', $id)
        ->get();

    // Load subject separately to ensure it works
    $subject = null;
    if ($lecture->subject_id) {
        $subject = Subject::find($lecture->subject_id);
    }
    if (!$subject && $lecture->subject) {
        // Fallback: find subject by name if subject_id is null
        $subject = Subject::whereRaw('LOWER(name) = LOWER(?)', [$lecture->subject])->first();
    }

    // Calculate total students based on department and year
    $totalStudents = 0;
    if ($subject) {
        // Get department_id from subject (which has department_id)
        $departmentId = $subject->department_id;
        $year = $subject->year;

        $totalStudents = Student::where('department_id', $departmentId)
            ->where('year', $year)
            ->count();
    }

    $presentCount = $attendances->where('status', 'present')->count(); // عدد الحاضرين
    $absentCount = $attendances->where('status', 'absent')->count(); // عدد الغائبين (الفرق)

    Log::info('Lecture Attendance Debug', [
        'lecture_id' => $id,
        'subject_name' => $subject ? $subject->name : null,
        'subject_department' => $subject ? $subject->department : null,
        'subject_year' => $subject ? $subject->year : null,
        'department_id' => $subject ? $subject->department_id : null,
        'totalStudents' => $totalStudents,
        'presentCount' => $presentCount,
        'absentCount' => $absentCount
    ]);

    return view('admin.lecture-attendance', compact('lecture', 'attendances', 'totalStudents', 'presentCount', 'absentCount'));
}
    public function show(string $id)
    {
        $lecture = Lecture::with(['hall', 'user'])->findOrFail($id);
        return response()->json($lecture);
    }

    public function lecturesByDate(Request $request)
    {
        $date = $request->query('date');
        if (!$date) return response()->json(['error' => 'Date parameter is required'], 400);

        $query = Lecture::with(['hall', 'user'])->whereDate('start_time', $date);
        if (Auth::user()->role === 'professor') $query->where('user_id', Auth::id());
        return response()->json($query->get());
    }

    public function update(Request $request, string $id)
    {
        $lecture = Lecture::findOrFail($id);

        // Manual check to ensure start_time is before end_time
        if (strtotime($request->start_time) >= strtotime($request->end_time)) {
            return response()->json(['success' => false, 'message' => 'Start time must be before end time.'], 400);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'hall_id' => 'required|exists:halls,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $lecture->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Lecture updated successfully!',
            'data' => $lecture->load(['hall', 'user'])
        ]);
    }

    public function destroy(string $id)
    {
        $lecture = Lecture::findOrFail($id);
    $hall = $lecture->hall;

    Log::info("Deleting lecture ID: {$id}");

    // جلب سجلات الحضور المرتبطة بهذه المحاضرة
    $attendances = $lecture->lectureAttendances()->get();

    // ----------------------------------------------------
    // الخطوة الإضافية: تحديث عدادات الغياب في الجدول التجميعي
    // ----------------------------------------------------
    
    // 1. نبدأ بعملية قاعدة بيانات (Transaction) لضمان تنفيذ كل العمليات بنجاح أو فشلها كلها
    DB::beginTransaction();

    try {
        $subjectId = $lecture->subject_id;

        // 2. نمر على كل سجل حضور
        foreach ($attendances as $attendance) {
            // 3. إذا كان الطالب مسجلًا كـ 'غياب'
            if ($attendance->status === 'absent') {
                // 4. نجد سجل العداد التجميعي للطالب في هذه المادة
                $summaryRecord = StudentSubjectAttendance::where('student_id', $attendance->student_id)
                    ->where('subject_id', $subjectId)
                    ->first();
                
                // 5. نتحقق ونخفض العداد
                if ($summaryRecord) {
                    // نستخدم decrement لضمان تخفيض القيمة بأمان
                    $summaryRecord->decrement('absence_count'); 
                    Log::info("Decremented absence_count for student {$attendance->student_id} in subject {$subjectId}");
                }
            }
        }
        
        // 6. حذف سجلات الحضور الفردية للمحاضرة (كما كان لديك)
        $lectureAttendancesCount = $lecture->lectureAttendances()->count();
        $lecture->lectureAttendances()->delete(); 
        Log::info("Deleted {$lectureAttendancesCount} lecture attendance records for lecture ID: {$id}");

        // 7. ثم حذف المحاضرة نفسها
        $lecture->delete();
        Log::info("Lecture ID: {$id} deleted successfully");

        // 8. تحديث حالة القاعة
        if ($hall) {
            $hall->updateStatusBasedOnLectures();
        }

        DB::commit(); // تأكيد كل العمليات

        return response()->json([
            'success' => true,
            'message' => 'Lecture and its attendance records deleted successfully and counters updated!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack(); // التراجع عن كل العمليات في حال حدوث خطأ
        Log::error("Error during lecture deletion and counter update for ID: {$id}. Error: " . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete lecture due to a server error.',
            'error' => $e->getMessage()
        ], 500);
    }
    }



    public function exportAttendance($id)
    {
        $lecture = Lecture::findOrFail($id);
        $attendances = \App\Models\LectureAttendance::with(['student.user'])
            ->where('lecture_id', $id)
            ->get();

        $filename = 'lecture_attendance_' . $lecture->id . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Student Name', 'Status', 'Scanned At']);

            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->student->user->name,
                    ucfirst($attendance->status),
                    $attendance->scanned_at ? $attendance->scanned_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function sendLectureNotifications(Lecture $lecture)
    {
        // Notify students in the same department AND year about the new lecture
        // First, get the subject to determine the year
        $subject = Subject::find($lecture->subject_id);
        if (!$subject) {
            \Illuminate\Support\Facades\Log::warning('Lecture has no subject associated', ['lecture_id' => $lecture->id]);
            return;
        }

        $students = User::where('role', 'student')
            ->where('department_id', $lecture->department_id)
            ->whereHas('student', function ($query) use ($subject) {
                $query->where('year', $subject->year);
            })
            ->get();

        foreach ($students as $student) {
            $student->notify(new \App\Notifications\LectureCreated($lecture));
        }

        // Notify admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\LectureCreated($lecture));
        }

        // Schedule reminder for students (24 hours before)
        $studentReminderTime = $lecture->start_time->copy()->subHours(24);
        if ($studentReminderTime->isFuture()) {
            \App\Jobs\SendStudentLectureReminder::dispatch($lecture)->delay($studentReminderTime);
        }

        // Schedule reminder for professor (30 minutes before)
        $reminderTime = $lecture->start_time->copy()->subMinutes(30);
        if ($reminderTime->isFuture()) {
            \App\Jobs\SendProfessorLectureReminder::dispatch($lecture)->delay($reminderTime);
        }

        // Schedule lecture started notifications (when lecture starts)
        $startTime = $lecture->start_time;
        if ($startTime->isFuture()) {
            \App\Jobs\SendLectureStartedNotifications::dispatch($lecture)->delay($startTime);
        }
    }
}
