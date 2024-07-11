<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;

//use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

class User extends Authenticatable implements HasTenants, FilamentUser, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable,
        AuthenticationLoggable, TwoFactorAuthenticatable, SoftDeletes;

    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /*protected $fillable = [
        'name',
        'email',
        'password',
    ];*/

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'qualification' => 'array'
    ];


    protected $adminGroup = [1];
    protected $studentGroup = [6];
    protected $tutorGroup = [7];
    protected $coordinatorGroup = [5];


    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getIsAdminAttribute()
    {
        return $this->role && in_array($this->role->id, $this->adminGroup) ? true : false;
    }

    public function getIsStudentAttribute()
    {
        return $this->role && in_array($this->role->id, $this->studentGroup) ? true : false;
    }

    public function getIsTutorAttribute()
    {
        return $this->role && in_array($this->role->id, $this->tutorGroup) ? true : false;
    }

    public function getIsCoordinatorAttribute()
    {
        return $this->role && in_array($this->role->id, $this->coordinatorGroup) ? true : false;
    }

    public function user_type()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    public function qualification()
    {
        return $this->belongsTo(Qualification::class, 'qualification_id');
    }

    /*public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }*/

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->teams->contains($tenant) && $this->is_active;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active == 1 ? true : false;
    }

    public function batches()
    {
        if ($this->is_tutor) {
            return $this->belongsToMany(
                Batch::class,
                'batch_curriculum',
                'tutor_id',
                'batch_id',

            );
        } else {
            return $this->belongsToMany(
                Batch::class,
                'batch_user',
                'user_id',
                'batch_id',

            );
        }
    }

    public function batchesstudents()
    {
        return $this->belongsToMany(
            Batch::class,
            'batch_user',
            'user_id',
            'batch_id',

        );
    }

//    public function assignments()
//    {
//        return $this->belongsToMany(TeachingMaterial::class, 'teaching_material_statuses')
//            ->where('doc_type', 2);
//    }

    public function assignments()
    {
        return $this->hasMany(TeachingMaterialStatus::class);
    }

    /***
     * Tenants
     */
    public function getTenants(Panel $panel): Collection
    {
        return $this->teams;
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function team()
    {
        return $this->teams()->where('team_id', Filament::getTenant()->id);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function getAttendanceCountAttribute()
    {
        return $this->attendance()->count();
    }


    public function getAdditionalDetailsAttribute()
    {
        return sprintf(
            '<table style="border-top: 1px solid #cccccc;border-collapse: collapse;">
                    <!--<tr>
                    <th width="100px">Batch</th>
                    <th width="100px">Users</th>
                    <th width="100px">Courses</th>
                    </tr>-->
                    <tr>
                        <td width="300px" valign="top">%s<br><i style="font-size:11px;font-weight: normal;color: #222222">%s</i></td>
                        <td width="200px" valign="top">%s</td>
                    </tr>
                    </table>',

            $this->name,
            $this->email,
            $this->phone
        );
    }

    //HasDefaultTenant
    // public function getDefaultTenant(Panel $panel): ?Model
    // {
    //     return $this->latestTeam;
    // }

    // public function latestTeam(): BelongsTo
    // {
    //     return $this->belongsTo(Team::class, 'latest_team_id');
    // }
}
