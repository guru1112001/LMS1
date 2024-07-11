<?php

namespace App\Models;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Permission\Traits\HasRoles;

class Batch extends Model
{
    use HasFactory, HasRoles, HasPanelShield;

    //protected $table = "batches";

    protected $guarded = [];

    //protected $primaryKey = 'batches.id';

//    protected $casts = [
//        'curriculum_data' => 'array',
//    ];

    protected static function booted(): void
    {
        static::addGlobalScope('limited', function (Builder $query) {
            if (auth()->check() && auth()->user()->is_student) {

                // $query->select('batches.*')
                // ->join('batch_user', 'batches.id', '=', 'batch_user.batch_id')
                // ->where('batch_user.user_id', auth()->user()->id);

                $query->select('batches.*')->whereHas('student_batches');
            }
            // if (auth()->check() && auth()->user()->is_tutor) {
            //     $query->select('batches.*')

            //     ->join('batch_curriculum', 'batches.id', '=', 'batch_curriculum.batch_id')
            //         ->where('batch_curriculum.tutor_id', auth()->user()->id);
            // }
        });
    }


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'batch_courses',
            'batch_id', 'course_id');
    }

    public function curriculums(): HasMany
    {
        return $this->hasMany(BatchCurriculum::class, 'batch_id','id')
//            ->when(auth()->check() && auth()->user()->is_tutor, function ($query) {
//                $query->where('batch_curriculum.tutor_id', auth()->user()->id);
//            })
            ;
    }

    public function teaching_materials_curriculums(): HasManyThrough
    {
        return $this->hasManyThrough(
            Curriculum::class,
            BatchCurriculum::class,
            'batch_id', // Foreign key on the BatchCurriculum table
            'id', // Foreign key on the Curriculum table
            'id', // Local key on the Branch table
            'curriculum_id' // Local key on the BatchCurriculum table
        );
    }


    public function sections()
    {
        return $this->belongsToMany(Section::class, 'batch_section',
            'batch_id', 'section_id');
    }

    public function teaching_materials()
    {
        return $this->belongsToMany(TeachingMaterial::class, 'batch_teaching_materials',
            'batch_id', 'teaching_material_id');
    }

//    public function curriculums()
//    {
//        return $this->belongsToMany(Curriculum::class, 'batch_curriculum',
//             'batch_id', 'curriculum_id');
//    }

//    public function curriculums(): MorphToMany
//    {
//        return $this->morphToMany(Curriculum::class, 'curricula', 'curricula');
//    }

    public function course_package()
    {
        return $this->belongsTo(Course::class)->with('curriculums');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }

    public function getCalendarCountAttribute()
    {
        return $this->calendars()->count();
    }
    /*
        public function team() : BelongsToMany
        {
            return $this->course->team();
        }*/


    /* public function branches()
     {
         return $this->belongsToMany(Branch::class, 'branch_curriculum',
             'curriculum_id', 'branch_id');
     }

     public function teams(): BelongsToMany
     {
         return $this->belongsToMany(Team::class);
     }*/

    public function team()
    {
        return $this->belongsTo(Team::class)->where('team_id', Filament::getTenant()->id);
        //return $this->teams()->where('team_id', Filament::getTenant()->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id')
            ->where('role_id', 7);
    }

    public function student_batches()
    {
        return $this->belongsToMany(
            User::class,
            'batch_user',
            'batch_id',
            'user_id',
        )
        ->where('user_id', auth()->user()->id)
        ->where('role_id', 6);
    }

    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'batch_user',
            'batch_id',
            'user_id',
        );
    }


    public function curriculum()
    {
        return $this->belongsToMany(
            User::class,
            'batch_user',
            'user_id',
            'batch_id'
        )
            ->where('role_id', 6);
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'batch_user',
            'user_id',
            'batch_id'
        )
            ->where('role_id', 6);
    }

    public function syllabi()
    {
        return $this->hasMany(Syllabus::class);
    }

    public function getStartEndDateAttribute()
    {
        return "S: " . date('d M Y', strtotime($this->start_date))
            . "<br/> E: " . date('d M Y', strtotime($this->end_date));
    }

    public function getAdditionalDetailsAttribute()
    {
//        $curriculumsNames = $this->curriculums()->pluck('curriculum')->join('<br> ');

//        if(empty($this->curriculums))
//            return '';

        $curriculumNames = $this->curriculums->map(fn($bc) => $bc->curriculum?->name)->join('<br>');
//dd($curriculumNames);
        return sprintf(
            '<table style="border-top: 1px solid #cccccc;border-collapse: collapse;">
                    <tr>
                        <td width="200px" valign="top">%s</td>
                        <td width="100px" valign="top"><i style="font-size:11px;font-weight: normal;color: #cccccc">Students</i><br> %d</td>
                        <td width="100px" valign="top"><i style="font-size:11px;font-weight: normal;color: #cccccc">Curriculums</i><br> %s</td>
                    </tr>
                    </table>',

            $this->name,
            $this->users()->count(),
            $curriculumNames
        );
    }
}
