<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope('limited', function (Builder $query) {
            if (auth()->check() && auth()->user()->is_student) {
                $query->where('schedule_at', '<=', Carbon::now()->format('Y-m-d H:i:s'));
            }
        });
    }

    protected $guarded = [];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Add a cast for batch_ids
    protected $casts = [
        'batch_ids' => 'array',
    ];

    // Getter for batch_ids
    public function getBatchIdsAttribute($value)
    {
        return explode(',', $value);
    }

    // Setter for batch_ids
    public function setBatchIdsAttribute($value)
    {
        $this->attributes['batch_ids'] = implode(',', $value);
    }
}
