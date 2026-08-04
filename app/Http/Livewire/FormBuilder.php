<?php

namespace App\Http\Livewire;

use App\Models\Form;
use Illuminate\Support\Str;
use Livewire\Component;

class FormBuilder extends Component
{
    public ?Form $form = null;
    public string $title = '';
    public string $description = '';
    public string $status = 'draft';
    public string $schemaJson = '{"fields": []}';
    public array $schema = [
        'fields' => [],
    ];

    protected $listeners = [
        'fieldReordered',
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:1024',
        'status' => 'required|in:draft,published',
        'schemaJson' => 'required|string',
    ];

    public function mount(Form $form = null)
    {
        if ($form) {
            $this->form = $form;
            $this->title = $form->title;
            $this->description = (string) $form->description;
            $this->status = $form->status;
            $this->schema = $this->prepareSchemaForUi($form->schema ?? ['fields' => []]);
            $this->syncSchemaJson();
        }
    }

    public function updated($name): void
    {
        if (Str::startsWith($name, 'schema.')) {
            $this->syncSchemaJson();
        }
    }

    public function updatedSchemaJson(): void
    {
        $decoded = json_decode($this->schemaJson, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $this->schema = $this->prepareSchemaForUi($decoded);
        }
    }

    public function addField(string $type): void
    {
        $label = Str::title(str_replace('_', ' ', $type));
        $key = Str::slug($label, '_') . '_' . (count($this->schema['fields']) + 1);

        $this->schema['fields'][] = [
            'type' => $type,
            'label' => $label,
            'key' => $key,
            'placeholder' => '',
            'help_text' => '',
            'default' => null,
            'required' => false,
            'validation' => [
                'min_length' => null,
                'max_length' => null,
                'min' => null,
                'max' => null,
                'regex' => null,
                'file_type' => null,
                'file_size' => null,
            ],
            'options' => '',
        ];

        $this->syncSchemaJson();
    }

    public function removeField(int $index): void
    {
        if (isset($this->schema['fields'][$index])) {
            array_splice($this->schema['fields'], $index, 1);
            $this->syncSchemaJson();
        }
    }

    public function duplicateField(int $index): void
    {
        if (! isset($this->schema['fields'][$index])) {
            return;
        }

        $field = $this->schema['fields'][$index];
        $field['key'] = Str::slug($field['label'], '_') . '_' . (count($this->schema['fields']) + 1);
        $field['label'] = $field['label'] . ' copy';

        array_splice($this->schema['fields'], $index + 1, 0, [$field]);
        $this->syncSchemaJson();
    }

    public function moveField(int $index, int $direction): void
    {
        $target = $index + $direction;
        if (! isset($this->schema['fields'][$index]) || ! isset($this->schema['fields'][$target])) {
            return;
        }

        $field = $this->schema['fields'][$index];
        $this->schema['fields'][$index] = $this->schema['fields'][$target];
        $this->schema['fields'][$target] = $field;
        $this->syncSchemaJson();
    }

    public function reorderField(int $from, int $to): void
    {
        if ($from === $to || ! isset($this->schema['fields'][$from]) || ! isset($this->schema['fields'][$to])) {
            return;
        }

        $field = $this->schema['fields'][$from];
        array_splice($this->schema['fields'], $from, 1);
        array_splice($this->schema['fields'], $to, 0, [$field]);
        $this->syncSchemaJson();
    }

    public function syncSchemaJson(): void
    {
        $schema = $this->normalizeSchema($this->schema);
        $this->schemaJson = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    protected function normalizeSchema(array $schema): array
    {
        $schema['fields'] = array_map(function ($field) {
            if (! isset($field['options'])) {
                $field['options'] = '';
            }

            if (is_string($field['options'])) {
                $field['options'] = array_values(array_filter(array_map('trim', explode(',', $field['options']))));
            }

            if (! empty($field['validation']) && is_array($field['validation'])) {
                $field['validation'] = array_filter($field['validation'], function ($value) {
                    return $value !== null && $value !== '';
                });
            } else {
                $field['validation'] = [];
            }

            $field['required'] = filter_var($field['required'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $field['default'] = $field['default'] ?? null;
            $field['help_text'] = $field['help_text'] ?? '';

            if (isset($field['options']) && is_array($field['options'])) {
                $field['options'] = array_values(array_filter($field['options'], fn ($item) => $item !== null && $item !== ''));
            }

            return $field;
        }, $schema['fields'] ?? []);

        return $schema;
    }

    protected function prepareSchemaForUi(array $schema): array
    {
        $schema['fields'] = array_map(function ($field) {
            if (isset($field['options']) && is_array($field['options'])) {
                $field['options'] = implode(', ', $field['options']);
            }

            if (! isset($field['validation']) || ! is_array($field['validation'])) {
                $field['validation'] = [
                    'min_length' => null,
                    'max_length' => null,
                    'min' => null,
                    'max' => null,
                    'regex' => null,
                    'file_type' => null,
                    'file_size' => null,
                ];
            }

            $field['required'] = filter_var($field['required'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $field['default'] = $field['default'] ?? null;
            $field['help_text'] = $field['help_text'] ?? '';

            return $field;
        }, $schema['fields'] ?? []);

        return $schema;
    }

    public function save(): void
    {
        $this->validate();

        $decoded = json_decode($this->schemaJson, true);
        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded) || ! isset($decoded['fields'])) {
            $this->addError('schemaJson', 'Schema must be valid JSON with a fields array.');
            return;
        }

        $decoded = $this->normalizeSchema($decoded);
        if (empty($decoded['fields'])) {
            $this->addError('schemaJson', 'The form schema must include at least one field.');
            return;
        }

        $form = $this->form ?? new Form();

        $form->title = $this->title;
        $form->description = $this->description;
        $form->status = $this->status;
        $form->schema = $decoded;
        if (empty($form->slug)) {
            $form->slug = Str::slug($form->title) . '-' . substr((string) Str::uuid(), 0, 8);
        }
        $form->save();

        session()->flash('message', 'Form saved successfully.');

        redirect()->route('forms.edit', ['form' => $form]);
    }

    public function fieldReordered(int $from, int $to): void
    {
        $this->reorderField($from, $to);
    }

    public function render()
    {
        return view('livewire.form-builder');
    }
}
