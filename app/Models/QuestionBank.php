<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

//    public function question_bank_subject()
//    {
//        return $this->belongsTo(QuestionBankSubject::class, 'question_bank_subject_id');
//    }

    public function question_bank_difficulty()
    {
        return $this->belongsTo(QuestionBankDifficulty::class, 'question_bank_difficulty_id');
    }
    public function getFormattedDifficultyAttribute()
    {
        return $this->question_bank_difficulty->name;
    }

//    public function question_bank_chapter()
//    {
//        return $this->belongsTo(QuestionBankChapter::class, 'question_bank_chapter_id');
//    }

    public function question_bank_type()
    {
        return $this->belongsTo(QuestionBankType::class, 'question_bank_type_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class,'question_bank_subject_id');
    }
}
