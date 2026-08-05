<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4 lg:grid-cols-[280px_minmax(0,1fr)]">
            <div class="space-y-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <h2 class="text-lg font-semibold">Field types</h2>
                    <p class="mt-1 text-sm text-slate-600">Click to add a field.</p>
                </div>

                <div class="grid gap-2">
                    @foreach(['text', 'textarea', 'number', 'email', 'phone', 'url', 'date', 'dropdown', 'radio', 'checkbox', 'file', 'heading', 'rating'] as $type)
                        <button wire:click.prevent="addField('{{ $type }}')" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-medium text-slate-900 shadow-sm hover:bg-slate-50">{{ Str::title(str_replace('_', ' ', $type)) }}</button>
                    @endforeach
                </div>
            </div>

            <div class="space-y-4">
                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="grid gap-4 lg:grid-cols-3">
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Title</label>
                            <input wire:model.defer="title" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                            @error('title') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Status</label>
                            <select wire:model="status" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                            @error('status') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Description</label>
                        <textarea wire:model.defer="description" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" rows="3"></textarea>
                        @error('description') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-base font-semibold">Field canvas</h3>
                                <p class="text-sm text-slate-500">You can drag fields to reorder them, or use the buttons.</p>
                            </div>
                        </div>
                        <div id="field-canvas" class="space-y-4">
                            @forelse($schema['fields'] as $index => $field)
                                <div wire:key="field-{{ $index }}-{{ $field['key'] }}" draggable="true" data-field-index="{{ $index }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-slate-400">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $field['label'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $field['type'] }} field</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <button wire:click.prevent="moveField({{ $index }}, -1)" class="rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-700 hover:bg-slate-50">Up</button>
                                        <button wire:click.prevent="moveField({{ $index }}, 1)" class="rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-700 hover:bg-slate-50">Down</button>
                                        <button wire:click.prevent="duplicateField({{ $index }})" class="rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-700 hover:bg-slate-50">Duplicate</button>
                                        <button wire:click.prevent="removeField({{ $index }})" class="rounded-full border border-slate-200 px-3 py-1 text-xs text-rose-600 hover:bg-rose-50">Remove</button>
                                    </div>
                                </div>

                                <div class="grid gap-4 md:grid-cols-2 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Label</label>
                                        <input wire:model.defer="schema.fields.{{ $index }}.label" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Key</label>
                                        <input wire:model.defer="schema.fields.{{ $index }}.key" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Placeholder</label>
                                        <input wire:model.defer="schema.fields.{{ $index }}.placeholder" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Help text</label>
                                        <input wire:model.defer="schema.fields.{{ $index }}.help_text" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Default value</label>
                                        <input wire:model.defer="schema.fields.{{ $index }}.default" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Required</label>
                                        <select wire:model.defer="schema.fields.{{ $index }}.required" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Field type</label>
                                        <select wire:model.defer="schema.fields.{{ $index }}.type" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none">
                                            @foreach(['text','textarea','number','email','phone','url','date','dropdown','radio','checkbox','file','heading','rating'] as $type)
                                                <option value="{{ $type }}">{{ Str::title(str_replace('_', ' ', $type)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="grid gap-4 md:grid-cols-2 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Min / max length</label>
                                        <div class="mt-1 grid gap-2 sm:grid-cols-2">
                                            <input wire:model.defer="schema.fields.{{ $index }}.validation.min_length" type="number" placeholder="Min" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                                            <input wire:model.defer="schema.fields.{{ $index }}.validation.max_length" type="number" placeholder="Max" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Min / max value</label>
                                        <div class="mt-1 grid gap-2 sm:grid-cols-2">
                                            <input wire:model.defer="schema.fields.{{ $index }}.validation.min" type="number" placeholder="Min" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                                            <input wire:model.defer="schema.fields.{{ $index }}.validation.max" type="number" placeholder="Max" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-4 md:grid-cols-2 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">Regex validation</label>
                                        <input wire:model.defer="schema.fields.{{ $index }}.validation.regex" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" placeholder="e.g. ^[A-Za-z ]+$" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700">File type / max size</label>
                                        <div class="mt-1 grid gap-2 sm:grid-cols-2">
                                            <input wire:model.defer="schema.fields.{{ $index }}.validation.file_type" placeholder="jpg,png,pdf" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                                            <input wire:model.defer="schema.fields.{{ $index }}.validation.file_size" type="number" placeholder="KB" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-slate-700">Options (comma separated)</label>
                                    <input wire:model.defer="schema.fields.{{ $index }}.options" class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-slate-500 focus:outline-none" />
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-center text-slate-500">
                                Add fields from the left panel to begin building your form.
                            </div>
                        @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <h3 class="text-base font-semibold">Schema editor</h3>
                        <textarea wire:model.debounce.500ms="schemaJson" class="mt-2 h-72 w-full rounded-xl border border-slate-300 bg-white p-4 font-mono text-sm text-slate-900 focus:border-slate-500 focus:outline-none"></textarea>
                        @error('schemaJson') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Save form</button>
                        <a href="{{ route('forms.index') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        let draggedIndex = null;

        function findCard(element) {
            return element.closest('[data-field-index]');
        }

        document.addEventListener('dragstart', function (event) {
            const card = findCard(event.target);
            if (!card) {
                return;
            }
            draggedIndex = Number(card.dataset.fieldIndex);
            event.dataTransfer.effectAllowed = 'move';
        });

        document.addEventListener('dragover', function (event) {
            if (findCard(event.target)) {
                event.preventDefault();
            }
        });

        document.addEventListener('drop', function (event) {
            event.preventDefault();
            const card = findCard(event.target);
            if (!card || draggedIndex === null) {
                return;
            }
            const targetIndex = Number(card.dataset.fieldIndex);
            if (draggedIndex !== targetIndex) {
                Livewire.emit('fieldReordered', draggedIndex, targetIndex);
            }
            draggedIndex = null;
        });
    });
</script>
