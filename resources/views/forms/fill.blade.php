@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold text-slate-900">{{ $form->title }}</h1>
        <p class="mt-2 text-sm text-slate-600">{{ $form->description }}</p>
    </div>

    <form method="POST" action="{{ route('forms.submit', $form->public_uuid) }}" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf

        @foreach($form->schema['fields'] as $field)
            @if(($field['type'] ?? '') === 'heading')
                <div class="rounded-xl bg-slate-50 p-4">
                    <h2 class="text-lg font-semibold">{{ $field['label'] ?? 'Heading' }}</h2>
                </div>
                @continue
            @endif

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-700">{{ $field['label'] ?? 'Field' }}@if(! empty($field['required'] ?? false)) <span class="text-red-500">*</span>@endif</label>
                @if(in_array($field['type'] ?? '', ['text', 'email', 'number', 'date', 'phone', 'url'], true))
                    <input
                        type="{{ ($field['type'] ?? 'text') === 'phone' ? 'tel' : (($field['type'] ?? 'text') === 'url' ? 'url' : ($field['type'] ?? 'text')) }}"
                        name="{{ $field['key'] ?? '' }}"
                        value="{{ old($field['key'] ?? '', $field['default'] ?? '') }}"
                        placeholder="{{ $field['placeholder'] ?? '' }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none"
                    />
                @elseif(($field['type'] ?? '') === 'textarea')
                    <textarea name="{{ $field['key'] ?? '' }}" placeholder="{{ $field['placeholder'] ?? '' }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none">{{ old($field['key'] ?? '', $field['default'] ?? '') }}</textarea>
                @elseif(($field['type'] ?? '') === 'dropdown')
                    <select name="{{ $field['key'] ?? '' }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none">
                        <option value="">Select an option</option>
                        @foreach((array) ($field['options'] ?? []) as $option)
                            <option value="{{ $option }}" @selected(old($field['key'] ?? '') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                @elseif(($field['type'] ?? '') === 'radio')
                    <div class="space-y-2">
                        @foreach((array) ($field['options'] ?? []) as $option)
                            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                <input type="radio" name="{{ $field['key'] ?? '' }}" value="{{ $option }}" @checked(old($field['key'] ?? '') === $option) />
                                {{ $option }}
                            </label>
                        @endforeach
                    </div>
                @elseif(($field['type'] ?? '') === 'checkbox')
                    <div class="space-y-2">
                        @foreach((array) ($field['options'] ?? []) as $option)
                            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                <input type="checkbox" name="{{ $field['key'] ?? '' }}[]" value="{{ $option }}" @checked(is_array(old($field['key'] ?? [])) && in_array($option, old($field['key'] ?? []))) />
                                {{ $option }}
                            </label>
                        @endforeach
                    </div>
                @elseif(($field['type'] ?? '') === 'file')
                    <input type="file" name="{{ $field['key'] ?? '' }}" class="w-full text-sm text-slate-700" />
                @elseif(($field['type'] ?? '') === 'rating')
                    <select name="{{ $field['key'] ?? '' }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none">
                        <option value="">Choose rating</option>
                        @foreach(range(1, 5) as $value)
                            <option value="{{ $value }}" @selected(old($field['key'] ?? '') == $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                @endif

                @if(! empty($field['help_text'] ?? ''))
                    <p class="text-sm text-slate-500">{{ $field['help_text'] ?? '' }}</p>
                @endif

                @error($field['key'] ?? '')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        @endforeach

        <button type="submit" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Submit</button>
    </form>
</div>
@endsection
