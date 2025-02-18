<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Step;

class Form extends Model
{
    protected $fillable = [
        'header',
        'footer',
        'workspace_id'
    ];

    public function steps(): HasMany {
        return $this->hasMany(Step::class);
    }
}
