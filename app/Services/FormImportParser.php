<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FormImportParser
{
    public function parseDocx(UploadedFile $file): array
    {
        $document = WordIOFactory::load($file->getRealPath());
        $preview = [];

        foreach ($document->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text = trim($element->getText());
                    if ($text === '') {
                        continue;
                    }

                    if (Str::startsWith($text, '##') || Str::startsWith($text, '#')) {
                        $preview[] = [
                            'type' => 'heading',
                            'label' => ltrim($text, '# '),
                            'key' => 'section_' . count($preview),
                            'placeholder' => '',
                            'required' => false,
                            'options' => [],
                            'validation' => [],
                        ];
                        continue;
                    }

                    if (Str::endsWith($text, '?')) {
                        $preview[] = [
                            'type' => 'text',
                            'label' => $text,
                            'key' => Str::slug($text, '_'),
                            'placeholder' => '',
                            'required' => false,
                            'options' => [],
                            'validation' => [],
                        ];
                        continue;
                    }

                    if (Str::contains($text, [',', ';']) && Str::contains($text, ['yes', 'no', 'true', 'false'])) {
                        $preview[] = [
                            'type' => 'checkbox',
                            'label' => $text,
                            'key' => Str::slug($text, '_'),
                            'placeholder' => '',
                            'required' => false,
                            'options' => explode(',', str_replace(';', ',', $text)),
                            'validation' => [],
                        ];
                        continue;
                    }

                    $preview[] = [
                        'type' => 'text',
                        'label' => $text,
                        'key' => Str::slug($text, '_'),
                        'placeholder' => '',
                        'required' => false,
                        'options' => [],
                        'validation' => [],
                    ];
                }
            }
        }

        return $preview;
    }

    public function parseXlsx(UploadedFile $file): array
    {
        $spreadsheet = SpreadsheetIOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);
        $preview = [];

        if (count($rows) < 2) {
            return $preview;
        }

        $header = array_map(fn($label) => Str::lower(trim((string) $label)), $rows[array_key_first($rows)]);
        $hasSchema = in_array('label', $header, true) && in_array('type', $header, true);

        foreach ($rows as $index => $row) {
            if ($index === array_key_first($rows)) {
                continue;
            }

            if ($hasSchema) {
                $data = array_combine($header, array_map(fn($cell) => trim((string) $cell), $row));
                if (empty($data['label'])) {
                    continue;
                }
                $preview[] = [
                    'type' => $data['type'] ?: 'text',
                    'label' => $data['label'],
                    'key' => $data['key'] ?: Str::slug($data['label'], '_'),
                    'placeholder' => $data['placeholder'] ?? '',
                    'required' => filter_var($data['required'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'options' => $data['options'] ? explode('|', $data['options']) : [],
                    'validation' => [],
                ];
            } else {
                $label = trim((string) $row['A']);
                if ($label === '') {
                    continue;
                }
                $preview[] = [
                    'type' => 'text',
                    'label' => $label,
                    'key' => Str::slug($label, '_'),
                    'placeholder' => '',
                    'required' => false,
                    'options' => [],
                    'validation' => [],
                ];
            }
        }

        return $preview;
    }
}
