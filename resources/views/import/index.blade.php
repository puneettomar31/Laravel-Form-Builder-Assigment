@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold text-slate-900">Import forms</h1>
        <p class="mt-2 text-sm text-slate-600">Upload a Word or Excel file to convert it into an editable form schema.</p>
    </div>

    <form action="{{ route('forms.import.preview') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf

        <div class="grid gap-4 sm:grid-cols-2">
            <label class="block text-sm font-medium text-slate-700">Import file</label>
            <input type="file" name="file" accept=".docx,.xlsx" class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
        </div>

        <div class="mt-4 space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                <p class="font-semibold text-slate-900">Import guidelines</p>
                <p class="mt-2">For Excel imports, use a header row in the first sheet with columns:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-slate-600">
                    <li><strong>label</strong> — field label</li>
                    <li><strong>type</strong> — text, textarea, number, email, phone, date, dropdown, radio, checkbox, file, heading, rating</li>
                    <li><strong>key</strong> — unique field name, optional</li>
                    <li><strong>placeholder</strong> — optional placeholder text</li>
                    <li><strong>required</strong> — true/false or yes/no</li>
                    <li><strong>options</strong> — comma-separated list for dropdown, radio, checkbox</li>
                </ul>
            </div>
            <button type="submit" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Preview import</button>
        </div>
    </form>
</div>
@endsection
