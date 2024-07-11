<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function batch()
    {
        return $this->belongsTo(Batch::class); // Replace 'Batch' with your actual model name
    }

    // Relationship with Course model (assuming a syllabus belongs to one course)
    public function course()
    {
        return $this->belongsTo(Course::class); // Replace 'Course' with your actual model name
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
