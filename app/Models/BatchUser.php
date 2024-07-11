<?php

namespace App\Models;

use App\Models\Batch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BatchUser extends Model
{
    use HasFactory;
    protected $table = 'batch_user';

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
