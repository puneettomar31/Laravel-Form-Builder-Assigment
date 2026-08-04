@extends('layouts.app')

@section('content')
    <div class="space-y-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-semibold text-slate-900">Create form</h1>
            <p class="mt-1 text-sm text-slate-600">Build a schema-driven form and save it to MySQL.</p>
        </div>

        @livewire('form-builder')
    </div>
@endsection
