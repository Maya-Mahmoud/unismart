<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hall;
use App\Models\Booking;
use App\Models\Lecture;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $students = User::where('role', 'student')->count();
        $professors = User::where('role', 'professor')->count();
        $admins = User::where('role', 'admin')->count();

        // Hall utilization: count halls with any bookings (assuming all are active/relevant)
        $totalHalls = Hall::count();
        $bookedHalls = Hall::whereHas('bookings')->distinct()->count('id');
        $availableHalls = $totalHalls - $bookedHalls;
        $hallUtilization = $totalHalls > 0 ? round(($bookedHalls / $totalHalls) * 100, 1) : 0;

        // Lectures
        $todayLectures = Lecture::whereDate('start_time', today())->count();
        $totalLectures = Lecture::count();

        // User distribution percentages (include admins)
        $adminPercent = $totalUsers > 0 ? round(($admins / $totalUsers) * 100, 1) : 0;
        $professorPercent = $totalUsers > 0 ? round(($professors / $totalUsers) * 100, 1) : 0;
        $studentPercent = $totalUsers > 0 ? round(($students / $totalUsers) * 100, 1) : 0;

        // Hall booked/available percentages
        $bookedPercent = $totalHalls > 0 ? round(($bookedHalls / $totalHalls) * 100, 1) : 0;
        $availablePercent = 100 - $bookedPercent;

        // Change percentages (simple: compare to previous month or static; here use 0 for now)
        $userChange = '+12%'; // Or calculate: compare to last month
        $hallChange = '+8%';
        $lectureChange = '+5%';
        $health = '98.5%';
        $healthChange = '+0.2%';

        // Recent users (last 4)
        $recentUsers = User::latest()->limit(4)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'students', 'professors', 'admins',
            'totalHalls', 'bookedHalls', 'availableHalls', 'hallUtilization',
            'todayLectures', 'totalLectures',
            'adminPercent', 'professorPercent', 'studentPercent',
            'bookedPercent', 'availablePercent',
            'userChange', 'hallChange', 'lectureChange', 'health', 'healthChange',
            'recentUsers'
        ));
    }
}
