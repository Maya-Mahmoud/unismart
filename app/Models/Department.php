<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
    
public function subjects() {
    return $this->hasMany(Subject::class);
}
}
