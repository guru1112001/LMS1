<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

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

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id');
    }

    public function batches()
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id');
    }

    public function branch_batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function curricula()
    {
        return $this->belongsToMany(Curriculum::class, 'curriculum_team', 'team_id', 'curriculum_id');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

}
