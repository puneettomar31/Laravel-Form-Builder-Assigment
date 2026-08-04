<?php

namespace App\Http\Livewire;

use App\Models\Form;
use Illuminate\Support\Str;
use Livewire\Component;

class ImportPreview extends Component
{
    public array $preview = [];
    public string $formTitle = 'Imported form';
    public string $formDescription = 'Form imported from a document file';
    public array $fields = [];
    public array $fieldTypes = ['text','textarea','number','email','phone','date','dropdown','radio','checkbox','file','heading','rating'];

    public function mount(array $preview = [])
    {
        $this->preview = $preview;
        $this->fields = array_map(function ($item) {
            return [
                'type' => $item['type'] ?? 'text',
                'label' => $item['label'] ?? 'Field',
                'key' => $item['key'] ?? Str::slug($item['label'] ?? 'field', '_'),
                'placeholder' => $item['placeholder'] ?? '',
                'help_text' => $item['help_text'] ?? '',
                'default' => $item['default'] ?? null,
                'required' => $item['required'] ?? false,
                'validation' => $item['validation'] ?? [],
                'options' => is_array($item['options']) ? implode(', ', $item['options']) : (string) ($item['options'] ?? ''),
            ];
        }, $preview);
    }

    public function save()
    {
        $fields = [];

        foreach ($this->fields as $field) {
            $fields[] = [
                'type' => $field['type'] ?? 'text',
                'label' => $field['label'] ?? 'Field',
                'key' => $field['key'] ?: Str::slug($field['label'] ?? 'field', '_'),
                'placeholder' => $field['placeholder'] ?? '',
                'help_text' => $field['help_text'] ?? '',
                'default' => $field['default'] ?? null,
                'required' => filter_var($field['required'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'validation' => $field['validation'] ?? [],
                'options' => array_values(array_filter(array_map('trim', explode(',', (string) ($field['options'] ?? ''))), fn ($value) => $value !== '')),
            ];
        }

        $form = Form::create([
            'title' => $this->formTitle ?: 'Imported form',
            'description' => $this->formDescription ?: 'Form imported from a document file',
            'schema' => ['fields' => $fields],
            'status' => 'draft',
        ]);

        return redirect()->route('forms.edit', $form)->with('message', 'Imported form created.');
    }

    public function render()
    {
        return view('livewire.import-preview');
    }
}
