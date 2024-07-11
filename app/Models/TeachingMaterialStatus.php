<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingMaterialStatus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::addGlobalScope('limited', function (Builder $query) {
            if (auth()->check() && auth()->user()->is_student) {
                $query->where('user_id', auth()->user()->id);
            }
            if (auth()->check() && auth()->user()->is_tutor)
            {
                $query->join('batch_user', 'teaching_material_statuses.user_id','=','batch_user.user_id')
                    ->join('batch_curriculum', 'batch_user.batch_id', '=', 'batch_curriculum.batch_id')
                    ->where('batch_curriculum.tutor_id', auth()->user()->id);
            }
        });
    }

    public function teaching_material()
    {
        return $this->belongsTo(TeachingMaterial::class);
    }

    public function teaching_material_assignment()
    {
        return $this->belongsTo(TeachingMaterial::class, 'teaching_material_id', 'id')
            ->where('doc_type',2);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
