<?php

namespace App\Models;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Permission\Traits\HasRoles;

class CourseStudent extends Model
{
    use HasFactory, HasRoles, HasPanelShield;

    protected $guarded = [];
    protected $table = 'courses';

    protected static function booted(): void
    {
        static::addGlobalScope('limited', function (Builder $query) {
            if (auth()->check() && auth()->user()->is_student) {
                $user = auth()->user();

                if ($user) {
                    // Retrieve the batches assigned to the user
                    $batches = $user->batches;

                    $courses = [];
                    foreach ($batches as $batch)
                    {
                        if($batch->course)
                            $courses[] = $batch->course->id;
                    }

                    $query->whereIn('courses.id', $courses);

                    // Filter courses based on the batches assigned to the user
                    /*$query->whereHas('batch', function ($query) use ($batches) {
                        $query->whereIn('batches.id', $batches);
                    });*/
                } else {
                    // If user is not authenticated, don't return any courses
                    $query->where('id', '=', null);
                }

                //dd(auth()->user()->batches());
                //$query->whereHas('students');
            }
        });
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

