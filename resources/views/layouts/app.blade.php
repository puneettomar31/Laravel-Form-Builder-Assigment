<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'AI Form Builder') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="min-h-screen bg-slate-50">
        <header class="border-b border-slate-200 bg-white/80 backdrop-blur-sm sticky top-0 z-50">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div>
                    <a href="{{ route('forms.index') }}" class="text-xl font-semibold text-slate-900">AI Form Builder</a>
                </div>
                <nav class="flex items-center gap-4 text-sm text-slate-700">
                    <a href="{{ route('forms.index') }}" class="hover:text-slate-900">Forms</a>
                    <a href="{{ route('forms.create') }}" class="hover:text-slate-900">Create Form</a>
                    <a href="{{ route('forms.import') }}" class="hover:text-slate-900">Import</a>
                    <a href="{{ route('forms.ai') }}" class="hover:text-slate-900">AI</a>
                </nav>
            </div>
        </header>
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if(session('message'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 shadow-sm">
                    {{ session('message') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    @livewireScripts
</body>
</html>
