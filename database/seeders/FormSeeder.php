<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        Form::create([
            'title' => 'Sample Contact Form',
            'slug' => 'sample-contact-form',
            'public_uuid' => (string) Str::uuid(),
            'description' => 'A sample form for contact and feedback.',
            'status' => 'published',
            'schema' => [
                'fields' => [
                    [
                        'type' => 'text',
                        'label' => 'Name',
                        'key' => 'name',
                        'placeholder' => 'Enter your name',
                        'required' => true,
                        'validation' => [
                            'min_length' => 2,
                        ],
                        'options' => [],
                    ],
                    [
                        'type' => 'email',
                        'label' => 'Email',
                        'key' => 'email',
                        'placeholder' => 'Enter your email',
                        'required' => true,
                        'validation' => [],
                        'options' => [],
                    ],
                    [
                        'type' => 'textarea',
                        'label' => 'Message',
                        'key' => 'message',
                        'placeholder' => 'Write your message',
                        'required' => false,
                        'validation' => [
                            'max_length' => 500,
                        ],
                        'options' => [],
                    ],
                ],
            ],
        ]);
    }
}
