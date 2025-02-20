<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Workspace;
use App\Models\Issue;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'status',
        'workspace_id',
        'order',
        'departments'
    ];

    protected $casts = [
        'departments' => 'array'
    ];

    public function workspace(): BelongsTo {
        return $this->belongsTo(Workspace::class);
    }

    public function issues(): HasMany {
        return $this->hasMany(Issue::class);
    }
}
