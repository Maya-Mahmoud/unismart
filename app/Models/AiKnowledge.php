<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiKnowledge extends Model
{
    use HasFactory;

    protected $table = 'ai_knowledges';

    protected $fillable = [
        'topic',
        'content',
        'is_active',
    ];
}
