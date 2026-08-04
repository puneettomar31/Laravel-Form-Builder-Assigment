<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FormSubmissionController extends Controller
{
    public function submit(Request $request, string $publicUuid)
    {
        $form = Form::where('public_uuid', $publicUuid)->where('status', 'published')->firstOrFail();
        $rules = $this->buildValidationRules($form->schema['fields'] ?? []);

        $validated = $request->validate($rules);
        $submissionData = [];
        $storedFiles = [];

        foreach ($form->schema['fields'] as $field) {
            if ($field['type'] === 'heading') {
                continue;
            }

            $key = $field['key'];
            if (! $request->has($key) && $field['type'] !== 'file') {
                $submissionData[$key] = null;
                continue;
            }

            if ($field['type'] === 'file' && $request->file($key)) {
                $file = $request->file($key);
                $path = $file->store('submissions', 'public');
                $storedFiles[$key] = $path;
                $submissionData[$key] = $path;
                continue;
            }

            $submissionData[$key] = $validated[$key] ?? $request->input($key);
        }

        $searchText = Arr::except($submissionData, array_keys($storedFiles));

        FormSubmission::create([
            'form_id' => $form->id,
            'submission_data' => $submissionData,
            'search_text' => is_array($searchText) ? implode(' ', array_filter($searchText)) : (string) $searchText,
            'user_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'stored_files' => $storedFiles ?: null,
        ]);

        return redirect()->route('forms.fill', $form->public_uuid)->with('message', 'Submission saved successfully.');
    }

    public function submissions(Form $form, Request $request)
    {
        $query = $form->submissions()->latest();

        if ($search = $request->query('search')) {
            $query->where('search_text', 'like', "%{$search}%");
        }

        $submissions = $query->paginate(15);

        return view('forms.submissions', compact('form', 'submissions'));
    }

    public function export(Form $form): StreamedResponse
    {
        $filename = 'form-' . $form->id . '-submissions.csv';

        return new StreamedResponse(function () use ($form) {
            $handle = fopen('php://output', 'w');
            $headers = ['id', 'submitted_at', 'submission_data'];
            fputcsv($handle, $headers);

            foreach ($form->submissions()->cursor() as $submission) {
                fputcsv($handle, [
                    $submission->id,
                    $submission->created_at->toDateTimeString(),
                    json_encode($submission->submission_data, JSON_UNESCAPED_UNICODE),
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    protected function buildValidationRules(array $fields): array
    {
        $rules = [];

        foreach ($fields as $field) {
            if (! isset($field['key']) || $field['type'] === 'heading') {
                continue;
            }

            $key = $field['key'];
            $fieldRules = [];
            $required = data_get($field, 'required', false);
            $fieldRules[] = $required ? 'required' : 'nullable';

            if ($field['type'] === 'email') {
                $fieldRules[] = 'email';
            }
            if ($field['type'] === 'url') {
                $fieldRules[] = 'url';
            }
            if ($field['type'] === 'number') {
                $fieldRules[] = 'numeric';
            }
            if ($field['type'] === 'rating') {
                $fieldRules[] = 'integer';
                $fieldRules[] = 'min:1';
                $fieldRules[] = 'max:5';
            }
            if ($field['type'] === 'date') {
                $fieldRules[] = 'date';
            }
            if ($field['type'] === 'file') {
                $fieldRules[] = 'file';
                $validation = $field['validation'] ?? [];
                if (! empty($validation['file_type'])) {
                    $fieldRules[] = 'mimes:' . implode(',', array_map('trim', explode(',', $validation['file_type'])));
                }
                if (! empty($validation['file_size'])) {
                    $fieldRules[] = 'max:' . intval($validation['file_size']);
                }
            }
            if (in_array($field['type'], ['dropdown', 'radio'], true) && ! empty($field['options'])) {
                $fieldRules[] = 'in:' . implode(',', array_map('strval', $field['options']));
            }
            if ($field['type'] === 'checkbox') {
                $fieldRules[] = 'array';
                if (! empty($field['options'])) {
                    $rules["{$key}.*"] = 'in:' . implode(',', array_map('strval', $field['options']));
                }
            }

            $validation = $field['validation'] ?? [];
            if (isset($validation['min_length'])) {
                $fieldRules[] = 'min:' . intval($validation['min_length']);
            }
            if (isset($validation['max_length'])) {
                $fieldRules[] = 'max:' . intval($validation['max_length']);
            }
            if (isset($validation['min'])) {
                $fieldRules[] = 'min:' . intval($validation['min']);
            }
            if (isset($validation['max'])) {
                $fieldRules[] = 'max:' . intval($validation['max']);
            }
            if (! empty($validation['regex'])) {
                $fieldRules[] = 'regex:' . $validation['regex'];
            }

            $rules[$key] = implode('|', $fieldRules);
        }

        return $rules;
    }
}
