<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class task extends Model
{
    use HasFactory;

    protected $guarded = null;

    protected $casts = [
        'posted_date' => 'date',
        'due_date' => 'date',
        'completed_date' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(project::class);
    }

    public function type()
    {
        return $this->belongsTo(type::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo( Manager::class);
    }



}


