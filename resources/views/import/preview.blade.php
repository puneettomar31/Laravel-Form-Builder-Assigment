@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold text-slate-900">Import preview</h1>
        <p class="mt-1 text-sm text-slate-600">Review parsed fields and adjust field types before importing.</p>
    </div>

    @livewire('import-preview', ['preview' => $preview])
</div>
@endsection
