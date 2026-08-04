<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'action',
        'prompt',
        'status',
        'output_schema',
        'model',
        'tokens',
        'latency_ms',
        'error',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'output_schema' => AsArrayObject::class,
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
