<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;

class Issue extends Model
{
    protected $fillable = [
        'name',
        'status',
        'category_id'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }
}
