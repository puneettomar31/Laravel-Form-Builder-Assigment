@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Forms</h1>
            <p class="text-sm text-slate-600">Create, edit, publish, and review generated forms.</p>
        </div>
        <a href="{{ route('forms.create') }}" class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-700">New form</a>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        @foreach($forms as $form)
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">{{ $form->title }}</h2>
                        <p class="mt-1 text-sm text-slate-600">{{ $form->description }}</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700">{{ $form->status }}</span>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('forms.edit', $form) }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Edit</a>
                    <a href="{{ route('forms.fill', $form->public_uuid) }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Public fill link</a>
                    <a href="{{ route('forms.submissions', $form) }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Submissions</a>
                    <span class="rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-700 break-all">{{ url('/forms/'.$form->public_uuid) }}</span>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $forms->links() }}
    </div>
</div>
@endsection
