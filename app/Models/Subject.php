<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'semester',
        'year',
        'department_id',
        'department',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
}
