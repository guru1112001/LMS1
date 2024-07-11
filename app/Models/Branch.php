<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Branch extends Model
{

    use HasFactory;
    protected $table = "teams";

    protected $guarded = ['id'];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function branches()
    {
        return $this->belongsToMany(
            Curriculum::class,
            'branch_curriculum',
            'branch_id',
            'curriculum_id'
        );
    }

    /** @return MorphToMany<Course> */
    public function courses(): MorphToMany
    {
        return $this->morphedByMany(Course::class, 'branchable', 'branchable');
    }

    /** @return belongsToMany <Course> */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id');
    }

    /** @return belongsToMany <Course> */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id')
            ->where('role_id', 6);
    }

    /** @return belongsToMany <Course> */
    public function other(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id')
            ->where('role_id','<>', 6);
    }

    public function members() :BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
