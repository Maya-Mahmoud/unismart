<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Department;

class UpdateSubjectDepartments extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = Subject::all();
        foreach ($subjects as $subject) {
            if ($subject->department && !$subject->department_id) {
                $dept = Department::where('name', $subject->department)->first();
                if ($dept) {
                    $subject->update(['department_id' => $dept->id]);
                }
            }
        }
    }
}
