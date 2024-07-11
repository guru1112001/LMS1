<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function questions_options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function question_bank_type()
    {
        return $this->belongsTo(QuestionBankType::class, 'question_type');
    }
}
