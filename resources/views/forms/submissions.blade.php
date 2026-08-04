@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold text-slate-900">Submissions for {{ $form->title }}</h1>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 px-6 py-4">
            <form action="" method="GET" class="flex flex-wrap gap-3">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search submissions" class="rounded-xl border border-slate-300 px-4 py-2 text-slate-900 focus:border-slate-500 focus:outline-none" />
                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Search</button>
            </form>
            <a href="{{ route('forms.submissions.export', $form) }}" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Export CSV</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-6 py-3 font-medium">ID</th>
                        <th class="px-6 py-3 font-medium">Submitted at</th>
                        <th class="px-6 py-3 font-medium">Summary</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach($submissions as $submission)
                        <tr>
                            <td class="px-6 py-4">{{ $submission->id }}</td>
                            <td class="px-6 py-4">{{ $submission->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ Str::limit(json_encode($submission->submission_data), 120) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">{{ $submissions->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
