<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'role' => ['required', 'in:student,professor'],
            'verification_code' => ['nullable', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'year' => ['nullable', 'in:first,second,third,fourth,fifth'],
        ])->after(function ($validator) use ($input) {
            if ($input['role'] === 'professor') {
                $verificationCode = env('PROFESSOR_VERIFICATION_CODE', 'PROF_2025_KEY');
                if (!isset($input['verification_code']) || $input['verification_code'] !== $verificationCode) {
                    $validator->errors()->add('verification_code', 'The verification code is invalid.');
                }
            } elseif ($input['role'] === 'student') {
                if (!isset($input['department_id'])) {
                    $validator->errors()->add('department_id', 'Department is required for students.');
                }
                if (!isset($input['year'])) {
                    $validator->errors()->add('year', 'Year is required for students.');
                }
            }
        })->validate();

        $role = 'student'; // default role

        if ($input['role'] === 'professor' && isset($input['verification_code']) && $input['verification_code'] === 'PROF_2025_KEY') {
            $role = 'professor';
        }

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $role,
            'status' => 'active',
        ]);

if ($role === 'student') {
    Student::create([
        'user_id' => $user->id,
        'department_id' => $input['department_id'],
        'year' => $input['year'],
        'semester' => 'first', // default semester value
        'attendance_count' => 0,
    ]);
}

        return $user;
    }
}
