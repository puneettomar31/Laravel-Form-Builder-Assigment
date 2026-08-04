<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'submission_data',
        'search_text',
        'user_ip',
        'user_agent',
        'stored_files',
    ];

    protected $casts = [
        'submission_data' => AsArrayObject::class,
        'stored_files' => AsArrayObject::class,
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
