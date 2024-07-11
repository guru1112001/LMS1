<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchSection extends Model
{
    protected $table = 'batch_section';

    protected $guarded = [];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}
