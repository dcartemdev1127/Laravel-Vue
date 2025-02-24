<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends Model
{
    protected $fillable = [
        'name',
        'status',
        'category_id',
        'order'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function reports(): HasMany {
        return $this->hasMany(Report::class);
    }
}
