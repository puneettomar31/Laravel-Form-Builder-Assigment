<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'schema',
        'status',
        'public_uuid',
    ];

    protected $casts = [
        'schema' => AsArrayObject::class,
    ];

    public static function booted()
    {
        static::creating(function (self $form) {
            if (empty($form->public_uuid)) {
                $form->public_uuid = (string) \Illuminate\Support\Str::uuid();
            }
            if (empty($form->slug) && ! empty($form->title)) {
                $form->slug = \Illuminate\Support\Str::slug($form->title);
            }
        });
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
