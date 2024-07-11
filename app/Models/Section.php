<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class,'batch_section',
            'section_id','batch_id');
    }

    public function teaching_material()
    {
        return $this->hasMany(TeachingMaterial::class);
    }


    public function teachingMaterials()
    {
        return $this->hasMany(TeachingMaterial::class,'section_id');
    }
}
