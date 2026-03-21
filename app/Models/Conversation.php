<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
   protected $fillable = ['user_id', 'title'];

    // علاقة عكسية: المحادثة بتخص مستخدم واحد
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة: المحادثة فيها رسائل كتير
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
