<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Form;

class Step extends Model
{
    protected $fillable = [
        'name',
        'status',
        'order',
        'form_id',
        'fields'
    ];

    protected $casts = [
        'fields' => 'array'
    ];

    public function form(): BelongsTo {
        return $this->belongsTo(Form::class);
    }
}
