<?php

namespace App\Jobs;

use App\Models\AiTask;
use App\Models\Form;
use App\Services\AiFormGenerator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;

class RunAiFormGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public AiTask $task)
    {
    }

    public function handle(AiFormGenerator $generator): void
    {
        $this->task->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        try {
            $start = microtime(true);
            $result = $generator->generate($this->task->prompt);
            $schema = $result['schema'];
            $tokens = $result['tokens'];
            $latency = (int) round((microtime(true) - $start) * 1000);

            $taskData = [
                'status' => 'completed',
                'output_schema' => $schema,
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'tokens' => $tokens,
                'latency_ms' => $latency,
                'completed_at' => now(),
            ];

            if ($this->task->form_id) {
                $form = Form::find($this->task->form_id);
                if ($form) {
                    $form->update([
                        'schema' => $schema,
                        'status' => 'draft',
                    ]);
                }
            } else {
                Form::create([
                    'title' => Str::limit($this->task->prompt, 80, ''),
                    'description' => 'Generated with AI based on the provided prompt.',
                    'slug' => Str::slug($this->task->prompt) . '-' . Str::lower(Str::random(6)),
                    'schema' => $schema,
                    'status' => 'draft',
                ]);
            }

            $this->task->update($taskData);
        } catch (\Throwable $exception) {
            $this->task->update([
                'status' => 'failed',
                'error' => $exception->getMessage(),
                'completed_at' => now(),
            ]);
        }
    }
}
