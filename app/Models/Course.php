<?php

namespace App\Models;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Permission\Traits\HasRoles;

class Course extends Model
{
    use HasFactory, HasRoles, HasPanelShield;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::addGlobalScope('limited', function (Builder $query) {
            if (auth()->check() && auth()->user()->is_student) {
                //$query->whereHas('students');
            }

            if (auth()->check() && auth()->user()->is_tutor) 
            {
                $query->select('courses.*')
                ->join('batches', 'courses.id','=','batches.course_package_id')
                ->join('batch_curriculum', 'batches.id', '=', 'batch_curriculum.batch_id')                
                ->where('batch_curriculum.tutor_id', auth()->user()->id);
            }
        });
    }


    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_courses',
            'course_id','batch_id');
    }


    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    protected function isLiveCourse(): Attribute
    {
        return Attribute::make(
            get: fn($value) => [$value],
        );
    }

    protected function copyFromExistingCourse(): Attribute
    {
        return Attribute::make(
            get: fn($value) => [$value],
        );
    }

    protected function allowCourseComplete(): Attribute
    {
        return Attribute::make(
            get: fn($value) => [ $value],
        );
    }

    protected function contentAccessAfterCompletion(): Attribute
    {
        return Attribute::make(
            get: fn($value) => [$value],
        );
    }

    protected function courseUnenrolling(): Attribute
    {
        return Attribute::make(
            get: fn($value) => [$value],
        );
    }


    /** @return MorphToMany<Curriculum> */
    public function curriculums(): MorphToMany
    {
        return $this->morphToMany(Curriculum::class, 'curricula', 'curricula');
    }


    /** @return MorphToMany<Curriculum> */
    /*public function branches(): MorphToMany
    {
        return $this->morphToMany(Branch::class, 'branchable', 'branchable');
    }*/



    /** @return MorphToMany<Curriculum> */
    /*public function team(): MorphToMany
    {
        return $this->morphToMany(Branch::class, 'branchable', 'branchable');
    }*/

    public function sub_categories()
    {
        return $this->belongsToMany(SubCategory::class, 'course_sub_categories',
            'course_id', 'sub_category_id');
    }

    public function getFormattedCourseTypeAttribute()
    {
        return $this->course_type == 1 ? "Online Only" : "Classroom Program";
    }

    public function getFormattedAllowCourseCompleteAttribute()
    {
        return $this->allow_couse_complete == true ? "Yes" : "False";
    }

}

