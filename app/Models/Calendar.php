<?php

namespace App\Models;

use App\Models\User;
use App\Models\Batch;
use App\Models\Branch;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Calendar extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'calendars.id';

    //protected $fillable = ['branch_id', 'batch_id', 'tutor_id', 'subject', 'classroom_id', 'start_time', 'end_time'];

    protected static function booted(): void
    {
        static::addGlobalScope('limited', function (Builder $query) {
            if (auth()->check() && auth()->user()->is_student) 
            {
                $query->join('batches', 'batches.id','=','calendars.batch_id')
                    ->join('batch_user', 'batches.id', '=', 'batch_user.batch_id')       
                    ->where('batch_user.user_id', auth()->user()->id);
            }

            if (auth()->check() && auth()->user()->is_tutor) 
            {
                $query->select('calendars.*')
                ->join('batches', 'batches.id','=','calendars.batch_id')
                ->join('batch_curriculum', 'batches.id', '=', 'batch_curriculum.batch_id')                
                ->where('batch_curriculum.tutor_id', auth()->user()->id);
            }
        });
    }

    protected $casts = [
        'start_time' => 'datetime', // Ensure start_time is cast to a DateTime object
        'end_time'=>'datetime',
    ];

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
