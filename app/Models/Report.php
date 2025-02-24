<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Issue;

class Report extends Model
{
    protected $fillable = [
        'issue_id',
        'content',
        'status',
        'access'
    ];

    public function issue(): BelongsTo {
        return $this->belongsTo(Issue::class);
    }
}
