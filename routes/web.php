<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ProfessorMiddleware;
use App\Http\Middleware\AdminOrProfessorMiddleware;
use App\Http\Middleware\StudentMiddleware;
use App\Livewire\Admin\Classrooms;
use App\Livewire\Admin\Users;
use App\Http\Controllers\Admin\LectureController;
use App\Http\Controllers\Professor\HallController;
use App\Http\Controllers\StudentChatController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\SubjectController;
use Illuminate\Support\Facades\Auth;

Route::middleware([AdminOrProfessorMiddleware::class])->prefix('admin/api')->group(function () {
    Route::get('lectures-by-date', [LectureController::class, 'lecturesByDate']);
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'professor') {
            return redirect()->route('professor.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    })->name('dashboard');

    // Halls Booking Routes
    Route::get('/halls', [\App\Http\Controllers\HallBookingController::class, 'index'])->name('halls.index');
    Route::post('/halls/{hall}/book', [\App\Http\Controllers\HallBookingController::class, 'book'])->name('halls.book');
    Route::post('/halls/{hall}/release', [\App\Http\Controllers\HallBookingController::class, 'release'])->name('halls.release');

});



// مسارات واجهة المدير (Admin Panel Routes) - محمية بـ AdminMiddleware
Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users', function () {
        $departments = \App\Models\Department::all();
        return view('admin.users', compact('departments'));
    })->name('users');
    Route::get('classrooms', Classrooms::class)->name('classrooms');
    Route::get('halls', function () {
        return view('admin.hall-management');
    })->name('halls');
    Route::get('subjects', [App\Http\Controllers\Admin\SubjectController::class, 'index'])->name('subjects');
    Route::post('subjects', [App\Http\Controllers\Admin\SubjectController::class, 'store'])->name('subjects.store');
    Route::put('subjects/{subject}', [App\Http\Controllers\Admin\SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('subjects/{subject}', [App\Http\Controllers\Admin\SubjectController::class, 'destroy'])->name('subjects.destroy');
    Route::view('professors', 'admin.professors')->name('professors');
    Route::get('dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/', function () {
        return view('admin.dashboard');
    });

    // Profile routes
    Route::get('profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'show'])->name('profile');
    Route::put('profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');
});

// مسارات مشتركة بين المدير والبروفيسور (مثل الـ API الذي تحتاجه القائمة المنسدلة) - محمية بـ AdminOrProfessorMiddleware
Route::middleware([AdminOrProfessorMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('lectures', [LectureController::class, 'index'])->name('lectures'); // تم نقل هذا من AdminMiddleware
        Route::get('generate-qr', [\App\Http\Controllers\Admin\QrCodeController::class, 'index'])->name('generate-qr');
        Route::post('api/generate-qr', [\App\Http\Controllers\Admin\QrCodeController::class, 'generateQrCode']);

        // 🚨 التعديل الرئيسي: API لـ halls وlectures تم وضعه هنا ليكون متاحاً للمدير والبروفيسور
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('lectures', [LectureController::class, 'index'])->name('api.lectures');
            Route::get('available-halls', [LectureController::class, 'getAvailableHalls'])->name('available-halls');
            Route::withoutMiddleware([AdminOrProfessorMiddleware::class])->middleware(['auth'])->get('halls/{hallId}/lectures', [LectureController::class, 'getLecturesByHall'])->name('halls.lectures');
            Route::apiResource('halls', \App\Http\Controllers\Admin\HallController::class);
            Route::apiResource('lectures', LectureController::class); // إضافة هذا لدعم POST وCRUD الكامل
            Route::apiResource('users', \App\Http\Controllers\Admin\UsersController::class);
            Route::get('lectures/{id}/attendance', [LectureController::class, 'showAttendance'])->name('lectures.attendance');
            Route::get('lectures/{id}/attendance/export', [LectureController::class, 'exportAttendance'])->name('lectures.attendance.export');
            Route::get('subjects', [App\Http\Controllers\Admin\SubjectController::class, 'index'])->name('api.subjects');
            Route::get('subjects-performance', [\App\Http\Controllers\Admin\PerformanceController::class, 'getSubjectsApi'])->name('api.subjects-performance');
            Route::get('stats', [\App\Http\Controllers\Admin\PerformanceController::class, 'getStatsApi'])->name('api.stats');
            Route::get('subject-stats', [\App\Http\Controllers\Admin\PerformanceController::class, 'getSubjectStatsApi'])->name('api.subject-stats');
        });

        Route::get('advanced-scheduler', [LectureController::class, 'advancedScheduler'])->name('advanced-scheduler');
        Route::get('performance', [\App\Http\Controllers\Admin\PerformanceController::class, 'index'])->name('performance');
        Route::get('performance/export-csv', [\App\Http\Controllers\Admin\PerformanceController::class, 'exportCsv'])->name('performance.export-csv');
        Route::get('absence/alerts', [\App\Http\Controllers\Admin\AbsenceAlertController::class, 'index'])->name('absence.alerts');
        Route::post('absence/alerts/send/{studentId}', [\App\Http\Controllers\Admin\AbsenceAlertController::class, 'sendAlert'])->name('absence.alerts.send');
        Route::post('absence/alerts/send-all', [\App\Http\Controllers\Admin\AbsenceAlertController::class, 'sendAlertsToAll'])->name('absence.alerts.send-all');
         // Profile routes
    Route::get('profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'show'])->name('profile');
    Route::put('profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');

    });

// مسارات البروفيسور (Professor Routes) - محمية بـ AdminOrProfessorMiddleware
Route::middleware([AdminOrProfessorMiddleware::class])->prefix('professor')->name('professor.')->group(function () {
    Route::get('dashboard', function () {
        return redirect()->route('halls.index'); // Redirect professor dashboard to halls page
    })->name('dashboard');

    Route::get('lectures', [LectureController::class, 'index'])->name('lectures');

    Route::prefix('api')->name('api.')->group(function () {
        Route::apiResource('lectures', LectureController::class);
        Route::get('lectures-by-date', [LectureController::class, 'lecturesByDate']);
        Route::get('available-halls', [LectureController::class, 'getAvailableHalls'])->name('available-halls');
    });
});

// مسارات البروفيسور (Professor Routes) - محمية بـ ProfessorMiddleware فقط (قد تحتاج إلى مراجعة استخدام هذا الـ Middleware)
Route::middleware([ProfessorMiddleware::class])->prefix('professor')->name('professor.')->group(function () {
    Route::get('dashboard', function () {
        return redirect()->route('halls.index'); // Redirect professor dashboard to halls page
    })->name('dashboard');
});

// مسارات الطالب (Student Routes)
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');

use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentProfileController;

Route::middleware([StudentMiddleware::class])->prefix('student')->name('student.')->group(function () {
    Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('subjects', [StudentDashboardController::class, 'subjects'])->name('subjects');
    Route::get('scan-qr', [StudentDashboardController::class, 'scanQr'])->name('scan-qr');
    Route::post('scan-qr', [AttendanceController::class, 'scanQr'])->name('scan-qr.scan');
    Route::get('attendance', [StudentDashboardController::class, 'attendance'])->name('attendance');

    // Chat routes
    Route::get('chat', function() {
        return view('student.chat');
    })->name('chat.view');
    Route::post('chat', [StudentChatController::class, 'chat'])->name('chat');

    // Profile routes
    Route::get('profile', [StudentProfileController::class, 'show'])->name('profile');
    Route::get('profile/edit', [StudentProfileController::class, 'edit'])->name('edit-profile');
    Route::put('profile', [StudentProfileController::class, 'update'])->name('update-profile');
});

// Notification routes
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/api/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/api/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::get('/api/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.get');
    Route::post('/api/notifications/{id}/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/api/notifications/mark-all-as-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/api/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'delete'])->name('notifications.delete');
    Route::delete('/api/notifications', [\App\Http\Controllers\NotificationController::class, 'deleteAll'])->name('notifications.delete-all');
});
