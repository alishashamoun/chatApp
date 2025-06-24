<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['person_one_id', 'person_two_id'];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'person_one_id');
    }
    public function userTwo()
    {
        return $this->belongsTo(User::class, 'person_two_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

}

