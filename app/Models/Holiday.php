<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
