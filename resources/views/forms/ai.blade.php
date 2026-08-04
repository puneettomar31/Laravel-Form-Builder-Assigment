@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold text-slate-900">AI Form Generation</h1>
        <p class="mt-1 text-sm text-slate-600">Generate or update a form using natural language prompts.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr_420px]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form action="{{ route('forms.ai.generate') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700">Prompt</label>
                    <textarea name="prompt" rows="4" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Existing form (optional)</label>
                    <select name="form_id" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none">
                        <option value="">Create new form</option>
                        @foreach($forms as $form)
                            <option value="{{ $form->id }}">{{ $form->title }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Queue AI generation</button>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Recent AI tasks</h2>
            <div class="mt-4 space-y-3">
                @foreach($tasks as $task)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <p class="font-semibold text-slate-900">{{ Str::limit($task->prompt, 60) }}</p>
                                <p class="text-sm text-slate-600">Action: {{ $task->action }} · Status: {{ $task->status }}</p>
                            </div>
                            <span class="rounded-full bg-slate-200 px-3 py-1 text-xs uppercase tracking-wide text-slate-700">{{ $task->status }}</span>
                        </div>
                        @if($task->error)
                            <p class="mt-3 text-sm text-rose-600">Error: {{ $task->error }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $tasks->links() }}</div>
        </div>
    </div>
</div>
@endsection
