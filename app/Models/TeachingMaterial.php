<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingMaterial extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'prerequisite' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_teaching_materials',
            'teaching_material_id', 'batch_id');
    }
}
