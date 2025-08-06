<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    protected $guarded = ['id'];

    public function quizAttempt(){
        return $this->belongsTo(QuizAttempt::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function answer(){
        return $this->belongsTo(Answer::class);
    }
}
