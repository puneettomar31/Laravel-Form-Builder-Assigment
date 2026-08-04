document.addEventListener('livewire:load', function () {
    const container = document.getElementById('field-canvas');
    if (! container) {
        return;
    }

    let draggedIndex = null;

    container.addEventListener('dragstart', function (event) {
        const item = event.target.closest('[data-field-index]');
        if (! item) {
            return;
        }

        draggedIndex = Number(item.dataset.fieldIndex);
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', String(draggedIndex));
        item.classList.add('opacity-50');
    });

    container.addEventListener('dragend', function (event) {
        const item = event.target.closest('[data-field-index]');
        if (item) {
            item.classList.remove('opacity-50');
        }
    });

    container.addEventListener('dragover', function (event) {
        event.preventDefault();
        const overItem = event.target.closest('[data-field-index]');
        container.querySelectorAll('[data-field-index]').forEach(el => el.classList.remove('border-slate-900'));
        if (overItem) {
            overItem.classList.add('border-slate-900');
        }
    });

    container.addEventListener('dragleave', function (event) {
        const item = event.target.closest('[data-field-index]');
        if (item) {
            item.classList.remove('border-slate-900');
        }
    });

    container.addEventListener('drop', function (event) {
        event.preventDefault();
        const target = event.target.closest('[data-field-index]');
        if (! target || draggedIndex === null) {
            return;
        }

        const targetIndex = Number(target.dataset.fieldIndex);
        if (targetIndex !== draggedIndex) {
            window.livewire.emit('fieldReordered', draggedIndex, targetIndex);
        }

        container.querySelectorAll('[data-field-index]').forEach(el => el.classList.remove('border-slate-900'));
        draggedIndex = null;
    });
});

