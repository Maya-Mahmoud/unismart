<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AiKnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ai_knowledges')->insert([
            [
                'topic' => 'practical_repeat',
                'content' => 'إعادة العملي إلكترونياً عبر الرابط https://latakia-univ.edu.sy/',
                'is_active' => true,
            ],
            [
                'topic' => 'registration',
                'content' => "The student must enter the registration code on the university's Facebook page, then pay the fees and contact student affairs to confirm registration.",
                'is_active' => true,
            ],
            [
                'topic' => 'halls',
                'content' => "The student must enter the registration code on the university's Facebook page, then pay the fees and contact student affairs to confirm registration.",
                'is_active' => true,
            ],
        ]);
    }
}
