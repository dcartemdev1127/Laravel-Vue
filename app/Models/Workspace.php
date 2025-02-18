<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Category;

class Workspace extends Model
{
    protected $fillable = [
        'name',
        'status',
        'form_id'
    ];

    public function categories(): HasMany {
        return $this->hasMany(Category::class);
    }
}
