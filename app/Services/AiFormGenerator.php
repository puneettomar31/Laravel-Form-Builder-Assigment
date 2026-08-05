<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use OpenAI\Client;

class AiFormGenerator
{
    public function __construct(protected Client $client)
    {
    }

    public function generate(string $prompt, int $attempts = 2): array
    {
        $system = <<<SYSTEM
You are a Laravel form schema assistant. Return JSON only, without markdown or code fences. The response must be an object with a top-level "fields" array. Each field must include: type, key, label, placeholder, required, validation, and options when needed.
SYSTEM;

        $messages = [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $this->buildPrompt($prompt)],
        ];

        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            $response = $this->client->responses()->create([
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'input' => $messages,
                'max_tokens' => 900,
            ]);

            $output = data_get($response, 'output.0.content.0.text');
            $tokens = data_get($response, 'usage.total_tokens');

            if (! $output) {
                if ($attempt === $attempts) {
                    throw new \RuntimeException('AI response did not contain text output.');
                }
                continue;
            }

            $schema = json_decode($output, true);
            if (json_last_error() !== JSON_ERROR_NONE || ! is_array($schema) || ! isset($schema['fields']) || ! is_array($schema['fields'])) {
                if ($attempt === $attempts) {
                    throw new \RuntimeException('AI returned invalid JSON schema.');
                }
                $messages[] = ['role' => 'user', 'content' => 'The previous response was not valid JSON. Respond again with only the JSON schema object and no additional explanation.'];
                continue;
            }

            return [
                'schema' => $schema,
                'tokens' => $tokens ? intval($tokens) : null,
            ];
        }

        throw new \RuntimeException('AI generation failed after multiple attempts.');
    }

    protected function buildPrompt(string $userPrompt): string
    {
        return <<<PROMPT
Create a complete JSON schema for a form from this user request:

"{$userPrompt}"

Return a JSON object only. Example:
{
  "fields": [
    {"type":"text","key":"first_name","label":"First Name","placeholder":"Enter first name","required":true,"validation":{"min_length":2}},
    {"type":"email","key":"email","label":"Email","placeholder":"Enter email","required":true,"validation":{"email":true}}
  ]
}
PROMPT;
    }
}
