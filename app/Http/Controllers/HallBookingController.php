<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HallBookingController extends Controller
{
    public function index(Request $request)
    {
        $halls = Hall::with('currentBooking')->get();

        $startTime = $request->get('start_time');
        $endTime = $request->get('end_time');

        if ($startTime && $endTime) {
            $start = Carbon::parse($startTime);
            $end = Carbon::parse($endTime);

            $halls = $halls->filter(function ($hall) use ($start, $end) {
                // Check if hall is free during the entire period (no overlap with lectures or bookings)
                $overlappingLecture = $hall->lectures()
                    ->where('start_time', '<', $end)
                    ->where('end_time', '>', $start)
                    ->exists();

                $overlappingBooking = $hall->bookings()
                    ->where('status', 'booked')
                    ->where('booked_at', '<', $end)
                    ->where(function ($query) use ($start) {
                        $query->where('end_time', '>', $start)->orWhereNull('end_time');
                    })
                    ->exists();

                return !$overlappingLecture && !$overlappingBooking;
            });
        }

        return view('halls.index', compact('halls', 'startTime', 'endTime'));
    }

    public function checkAvailability(Request $request, Hall $hall)
    {
        $startTime = $request->get('start_time');
        $endTime = $request->get('end_time');

        if (!$startTime || !$endTime) {
            return response()->json(['available' => false, 'message' => 'Start time and end time are required.']);
        }

        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        if ($start >= $end) {
            return response()->json(['available' => false, 'message' => 'End time must be after start time.']);
        }

        // Check for overlapping lectures
        $overlappingLectures = $hall->lectures()
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        // Check for overlapping bookings
        $overlappingBookings = $hall->bookings()
            ->where('status', 'booked')
            ->where('booked_at', '<', $end)
            ->where(function ($query) use ($start) {
                $query->where('end_time', '>', $start)->orWhereNull('end_time');
            })
            ->exists();

        if ($overlappingLectures || $overlappingBookings) {
            return response()->json(['available' => false, 'message' => 'This hall is not available during the selected time.']);
        }

        return response()->json(['available' => true, 'message' => 'This hall is available during the selected time.']);
    }

    public function book(Request $request, Hall $hall)
    {
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        if (!$startTime || !$endTime) {
            return redirect()->route('halls.index')->with('error', 'Start time and end time are required.');
        }

        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        if ($start >= $end) {
            return redirect()->route('halls.index')->with('error', 'End time must be after start time.');
        }

        // Check for overlapping lectures
        $overlappingLectures = $hall->lectures()
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($overlappingLectures) {
            return redirect()->route('halls.index')->with('error', 'Cannot book this hall as it has overlapping lectures.');
        }

        // Check for overlapping bookings
        $overlappingBookings = $hall->bookings()
            ->where('status', 'booked')
            ->where('booked_at', '<', $end)
            ->where(function ($query) use ($start) {
                $query->where('end_time', '>', $start)->orWhereNull('end_time');
            })
            ->exists();

        if ($overlappingBookings) {
            return redirect()->route('halls.index')->with('error', 'Cannot book this hall as it has overlapping bookings.');
        }

        Booking::create([
            'user_id' => Auth::id(),
            'hall_id' => $hall->id,
            'booked_at' => $start,
            'end_time' => $end,
            'status' => 'booked',
        ]);

        return redirect()->route('halls.index')->with('success', 'Hall booked successfully!');
    }

    public function release(Request $request, Hall $hall)
    {
        if ($hall->status === 'booked' && Auth::check()) {
            $booking = $hall->currentBooking;
            if ($booking && $booking->user_id === Auth::id()) {
                $booking->update(['status' => 'cancelled']);
                // Recompute status based on lectures instead of directly setting to available
                $hall->updateStatusBasedOnLectures();

                return redirect()->route('halls.index')->with('success', 'Hall released successfully!');
            }
        }

        return redirect()->route('halls.index')->with('error', 'Cannot release this hall.');
    }
}
