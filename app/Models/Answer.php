<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $guarded = ['id'];

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function quizAttemptAnswer(){
        return $this->hasMany(QuizAttemptAnswer::class);
    }
}
