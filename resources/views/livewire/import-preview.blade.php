<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <h2 class="text-lg font-semibold text-slate-900">Import preview</h2>
    <p class="mt-1 text-sm text-slate-600">Review parsed fields, update labels and field types, then save the imported form.</p>

    <form wire:submit.prevent="save" class="space-y-6 mt-6">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">Form title</label>
                <input wire:model.defer="formTitle" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Form description</label>
                <input wire:model.defer="formDescription" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
            </div>
        </div>

        @forelse($fields as $index => $field)
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="font-semibold text-slate-900">Field {{ $index + 1 }}</p>
                        <p class="text-sm text-slate-600">Current guess: {{ Str::title(str_replace('_', ' ', $field['type'])) }}</p>
                    </div>
                    <div class="w-full max-w-xs">
                        <label class="block text-sm font-medium text-slate-700">Field type</label>
                        <select wire:model.defer="fields.{{ $index }}.type" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none">
                            @foreach($fieldTypes as $type)
                                <option value="{{ $type }}">{{ Str::title(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Label</label>
                        <input wire:model.defer="fields.{{ $index }}.label" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Key</label>
                        <input wire:model.defer="fields.{{ $index }}.key" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Placeholder</label>
                        <input wire:model.defer="fields.{{ $index }}.placeholder" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Required</label>
                        <select wire:model.defer="fields.{{ $index }}.required" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-slate-900 focus:border-slate-500 focus:outline-none">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Help text</label>
                        <input wire:model.defer="fields.{{ $index }}.help_text" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Options (comma separated)</label>
                        <input wire:model.defer="fields.{{ $index }}.options" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-center text-slate-500">
                No preview fields were detected. Please import a Word or Excel file with recognizable questions.
            </div>
        @endforelse

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Commit imported form</button>
            <a href="{{ route('forms.import') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Back</a>
        </div>
    </form>
</div>
