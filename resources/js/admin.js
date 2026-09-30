// Admin panel behaviour: confirmations, previews, rich-text editor, drag-and-drop ordering, charts.
import '@fontsource-variable/plus-jakarta-sans';
import '@fontsource-variable/playfair-display';
import 'bootstrap-icons/font/bootstrap-icons.min.css';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

document.addEventListener('DOMContentLoaded', () => {
    initToasts();
    initConfirmations();
    initImagePreviews();
    initFileLabels();
    initSortable();
    initEditors();
    initCharts();
    initTooltips();
});

function initToasts() {
    document.querySelectorAll('.toast').forEach((el) => bootstrap.Toast.getOrCreateInstance(el, { delay: 5000 }).show());
}

function initTooltips() {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => new bootstrap.Tooltip(el));
}

// Forms marked data-confirm="Message" ask for confirmation in a modal before submitting.
function initConfirmations() {
    const modalEl = document.getElementById('confirmModal');
    if (!modalEl) return;

    const modal = new bootstrap.Modal(modalEl);
    const message = modalEl.querySelector('[data-confirm-message]');
    const title = modalEl.querySelector('[data-confirm-title]');
    const confirmButton = modalEl.querySelector('[data-confirm-accept]');
    let pendingForm = null;

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('form[data-confirm]');
        if (!form || form.dataset.confirmed === '1') return;

        event.preventDefault();
        pendingForm = form;
        message.textContent = form.dataset.confirm;
        title.textContent = form.dataset.confirmTitle || 'Are you sure?';
        confirmButton.textContent = form.dataset.confirmButton || 'Yes, delete';
        confirmButton.className = 'btn ' + (form.dataset.confirmVariant || 'btn-danger');
        modal.show();
    });

    confirmButton.addEventListener('click', () => {
        if (!pendingForm) return;
        pendingForm.dataset.confirmed = '1';
        confirmButton.disabled = true;
        pendingForm.submit();
    });

    modalEl.addEventListener('hidden.bs.modal', () => {
        pendingForm = null;
        confirmButton.disabled = false;
    });
}

// <input type="file" data-preview="#target"> shows the chosen image(s) before upload.
function initImagePreviews() {
    document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
        const target = document.querySelector(input.dataset.preview);
        if (!target) return;

        input.addEventListener('change', () => {
            const files = Array.from(input.files || []).filter((f) => f.type.startsWith('image/'));
            if (!files.length) return;

            target.innerHTML = '';
            if (input.multiple) {
                const grid = document.createElement('div');
                grid.className = 'preview-grid w-100 p-2';
                files.slice(0, 40).forEach((file) => grid.appendChild(imageFor(file)));
                target.appendChild(grid);
            } else {
                target.appendChild(imageFor(files[0]));
            }
        });
    });

    function imageFor(file) {
        const img = document.createElement('img');
        img.alt = file.name;
        img.src = URL.createObjectURL(file);
        img.onload = () => URL.revokeObjectURL(img.src);
        return img;
    }
}

// Shows the selected document's name and size under file inputs marked data-file-info.
function initFileLabels() {
    document.querySelectorAll('input[type="file"][data-file-info]').forEach((input) => {
        const target = document.querySelector(input.dataset.fileInfo);
        input.addEventListener('change', () => {
            const file = input.files?.[0];
            if (!file || !target) return;
            const size = file.size > 1048576 ? (file.size / 1048576).toFixed(1) + ' MB' : Math.ceil(file.size / 1024) + ' KB';
            target.textContent = `Selected: ${file.name} (${size})`;
        });
    });
}

// <tbody data-sortable="/admin/x/reorder"> rows with data-id can be dragged to reorder.
function initSortable() {
    document.querySelectorAll('[data-sortable]').forEach((container) => {
        let dragged = null;

        container.querySelectorAll('[data-id]').forEach((row) => {
            const handle = row.querySelector('.drag-handle');
            if (handle) {
                handle.addEventListener('mousedown', () => (row.draggable = true));
                handle.addEventListener('touchstart', () => (row.draggable = true), { passive: true });
            }

            row.addEventListener('dragstart', (e) => {
                dragged = row;
                row.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
            });

            row.addEventListener('dragend', () => {
                row.classList.remove('dragging');
                row.draggable = false;
                container.querySelectorAll('.drag-over').forEach((r) => r.classList.remove('drag-over'));
                save(container);
            });

            row.addEventListener('dragover', (e) => {
                e.preventDefault();
                if (!dragged || dragged === row) return;
                const rect = row.getBoundingClientRect();
                const after = e.clientY > rect.top + rect.height / 2;
                row.parentNode.insertBefore(dragged, after ? row.nextSibling : row);
            });
        });
    });

    async function save(container) {
        const ids = Array.from(container.querySelectorAll('[data-id]')).map((r) => r.dataset.id);
        try {
            const response = await fetch(container.dataset.sortable, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ ids }),
            });
            if (!response.ok) throw new Error();
            showToast('Order saved.', 'success');
        } catch {
            showToast('Could not save the new order. Please refresh and try again.', 'danger');
        }
    }
}

export function showToast(message, variant = 'success') {
    const container = document.querySelector('.toast-container');
    if (!container) return;
    const icon = variant === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
    const el = document.createElement('div');
    el.className = `toast align-items-center text-bg-${variant}`;
    el.setAttribute('role', 'status');
    el.setAttribute('aria-live', 'polite');
    el.innerHTML = `<div class="d-flex"><div class="toast-body"><i class="bi ${icon} me-2"></i><span></span></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>`;
    el.querySelector('span').textContent = message;
    container.appendChild(el);
    bootstrap.Toast.getOrCreateInstance(el, { delay: 3500 }).show();
    el.addEventListener('hidden.bs.toast', () => el.remove());
}

// CKEditor 5 is loaded only on pages with a <textarea data-editor>.
async function initEditors() {
    const areas = document.querySelectorAll('textarea[data-editor]');
    if (!areas.length) return;

    const [ck] = await Promise.all([import('ckeditor5'), import('ckeditor5/ckeditor5.css')]);
    const {
        ClassicEditor, Essentials, Paragraph, Heading, Bold, Italic, Underline, Link, List, BlockQuote,
        Table, TableToolbar, HorizontalLine, Indent, Autoformat, RemoveFormat, PasteFromOffice, AutoLink,
    } = ck;

    areas.forEach((textarea) => {
        ClassicEditor.create(textarea, {
            licenseKey: 'GPL',
            plugins: [Essentials, Paragraph, Heading, Bold, Italic, Underline, Link, AutoLink, List, BlockQuote,
                Table, TableToolbar, HorizontalLine, Indent, Autoformat, RemoveFormat, PasteFromOffice],
            toolbar: {
                items: ['heading', '|', 'bold', 'italic', 'underline', 'link', '|', 'bulletedList', 'numberedList',
                    'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'horizontalLine', '|', 'removeFormat', 'undo', 'redo'],
                shouldNotGroupWhenFull: false,
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading2', view: 'h2', title: 'Heading', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Subheading', class: 'ck-heading_heading3' },
                ],
            },
            link: { defaultProtocol: 'https://', addTargetToExternalLinks: true },
            table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] },
        }).then((editor) => {
            // Keep the textarea in sync so server-side validation errors retain the content.
            editor.model.document.on('change:data', () => (textarea.value = editor.getData()));
        }).catch((error) => console.error('Editor failed to load', error));
    });
}

// <canvas data-chart='{"labels":[],"data":[]}'> renders a simple bar chart.
async function initCharts() {
    const canvases = document.querySelectorAll('canvas[data-chart]');
    if (!canvases.length) return;

    const { Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip } = await import('chart.js');
    Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip);

    canvases.forEach((canvas) => {
        const config = JSON.parse(canvas.dataset.chart);
        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: config.labels,
                datasets: [{
                    label: config.label || 'Total',
                    data: config.data,
                    backgroundColor: '#0b2447',
                    hoverBackgroundColor: '#f2b705',
                    borderRadius: 6,
                    maxBarThickness: 36,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#eef1f6' }, border: { display: false } },
                    x: { grid: { display: false }, border: { display: false } },
                },
            },
        });
    });
}
