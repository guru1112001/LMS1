<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::addGlobalScope('limited', function (Builder $query) {
            if (auth()->check() && auth()->user()->is_student) {
                $query->where('user_id', auth()->user()->id);
            }

            if (auth()->check() && auth()->user()->is_tutor) 
            {
                $query->join('batch_user', 'attendances.user_id','=','batch_user.user_id')
                ->join('batch_curriculum', 'batch_user.batch_id', '=', 'batch_curriculum.batch_id')                
                ->where('batch_curriculum.tutor_id', auth()->user()->id);
            }
        });
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function attendance()
    {
        return $this->belongsTo(User::class,'attendance_by');
    }
}
