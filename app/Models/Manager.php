<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manager extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'project_id', 'email', 'slack'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(project::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany( task::class);
    }
}
