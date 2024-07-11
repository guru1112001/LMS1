<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
//use Illuminate\Database\Eloquent\Relations\Pivot;

class BatchCurriculum extends Model
{
    protected $table = 'batch_curriculum';

    protected $guarded = [];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }
}
