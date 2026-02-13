<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $student = $user->student;

        return view('student.profile', compact('user', 'student'));
    }

    public function edit()
    {
        $user = Auth::user();
        $student = $user->student;

        return view('student.edit-profile', compact('user', 'student'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'year' => 'required|in:first,second,third,fourth,fifth',
            'semester' => 'required|in:first,second',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Update user information
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->phone) {
            $user->phone = $request->phone;
        }
        $user->save();

        // Update student information
        $student->year = $request->year;
        $student->semester = $request->semester;
        $student->save();

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
        }

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully!');
    }
}
