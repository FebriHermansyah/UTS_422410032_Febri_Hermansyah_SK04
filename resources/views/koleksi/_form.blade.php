@php
    use App\Models\TapeCollection;
    use App\Models\InitialCondition;

    $cond = $koleksi?->condition;
    $condIcons = ['baik' => 'emoji-smile', 'cukup' => 'emoji-neutral', 'rusak' => 'emoji-frown', 'kritis' => 'exclamation-octagon'];

    // Baris tindakan: input lama (jika validasi gagal) -> data database -> kosong
    $rows = old('actions', $koleksi
        ? $koleksi->actions->map(fn ($a) => [
            'id' => $a->id, 'action_name' => $a->action_name, 'priority' => $a->priority,
            'status' => $a->status, 'planned_date' => $a->planned_date?->format('Y-m-d'),
            'notes' => $a->notes,
        ])->all()
        : []);

    $presets = [
        ['name' => 'Baking pita',              'priority' => 'tinggi'],
        ['name' => 'Pembersihan jalur pita',   'priority' => 'sedang'],
        ['name' => 'Splicing pita putus',      'priority' => 'tinggi'],
        ['name' => 'Ganti casing / reel',      'priority' => 'rendah'],
        ['name' => 'Digitalisasi',             'priority' => 'sedang'],
    ];
@endphp

@if ($errors->any())
    <div class="alert alert-danger d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div>Terdapat <strong>{{ $errors->count() }}</strong> kesalahan. Periksa isian yang ditandai merah.</div>
    </div>
@endif

{{-- ===== 1. Data Koleksi ===== --}}
<div class="card mb-4">
    <div class="card-header fw-bold"><span class="step-no">1</span>Data Koleksi</div>
    <div class="card-body row g-3">
        <div class="col-md-4">
            <label class="form-label">Kode Koleksi <span class="text-danger">*</span></label>
            <input type="text" name="code" id="code" value="{{ old('code', $koleksi?->code) }}" placeholder="PTA-006"
                   class="form-control text-uppercase @error('code') is-invalid @enderror">
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-8">
            <label class="form-label">Nama Koleksi <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ old('name', $koleksi?->name) }}"
                   class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Format Media <span class="text-danger">*</span></label>
            <select name="media_format" class="form-select @error('media_format') is-invalid @enderror">
                <option value="">-- pilih format --</option>
                @foreach (TapeCollection::FORMATS as $k => $v)
                    <option value="{{ $k }}" @selected(old('media_format', $koleksi?->media_format) === $k)>{{ $v }}</option>
                @endforeach
            </select>
            @error('media_format') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-8">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" rows="2" maxlength="1000" data-counter
                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $koleksi?->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- ===== 2. Kondisi Awal (1 koleksi : 1 kondisi) ===== --}}
<div class="card mb-4">
    <div class="card-header fw-bold"><span class="step-no">2</span>Kondisi Awal</div>
    <div class="card-body row g-3">
        <div class="col-12">
            <label class="form-label d-block">Kondisi Fisik <span class="text-danger">*</span></label>
            <div class="d-flex flex-wrap gap-2">
                @foreach (InitialCondition::CONDITIONS as $k => $v)
                    <div class="cond-{{ $k }}">
                        <input type="radio" class="btn-check" name="condition[physical_condition]" id="cond-{{ $k }}"
                               value="{{ $k }}" @checked(old('condition.physical_condition', $cond?->physical_condition) === $k)>
                        <label class="btn cond-opt" for="cond-{{ $k }}"><i class="bi bi-{{ $condIcons[$k] }} me-1"></i>{{ $v }}</label>
                    </div>
                @endforeach
            </div>
            @error('condition.physical_condition') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Tanggal Penilaian</label>
            <input type="date" name="condition[assessed_at]" max="{{ now()->toDateString() }}"
                   value="{{ old('condition.assessed_at', $cond?->assessed_at?->format('Y-m-d')) }}"
                   class="form-control @error('condition.assessed_at') is-invalid @enderror">
            @error('condition.assessed_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-8">
            <label class="form-label d-block">Indikasi Kerusakan</label>
            <input type="hidden" name="condition[has_mold]" value="0">
            <input type="hidden" name="condition[has_sticky_shed]" value="0">
            <div class="form-check form-switch form-check-inline">
                <input type="checkbox" class="form-check-input" id="has_mold" name="condition[has_mold]" value="1"
                       @checked(old('condition.has_mold', $cond?->has_mold))>
                <label class="form-check-label" for="has_mold">Terdapat jamur</label>
            </div>
            <div class="form-check form-switch form-check-inline">
                <input type="checkbox" class="form-check-input" id="has_sticky" name="condition[has_sticky_shed]" value="1"
                       @checked(old('condition.has_sticky_shed', $cond?->has_sticky_shed))>
                <label class="form-check-label" for="has_sticky">Sticky-shed syndrome</label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Deskripsi Kerusakan</label>
            <textarea name="condition[damage_description]" rows="2" maxlength="2000" data-counter
                      class="form-control @error('condition.damage_description') is-invalid @enderror">{{ old('condition.damage_description', $cond?->damage_description) }}</textarea>
            @error('condition.damage_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- ===== 3. Rencana Tindakan (1 koleksi : N tindakan) ===== --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span class="fw-bold"><span class="step-no">3</span>Rencana Tindakan Restorasi
            <span class="badge rounded-pill text-bg-secondary ms-1" id="action-count">0</span></span>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-success" id="add-action"><i class="bi bi-plus-lg"></i> Tambah</button>
            <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">Tambah cepat</h6></li>
                @foreach ($presets as $p)
                    <li><button type="button" class="dropdown-item preset" data-name="{{ $p['name'] }}" data-priority="{{ $p['priority'] }}">{{ $p['name'] }}</button></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div id="action-empty" class="empty-state py-4 d-none">
            <i class="bi bi-tools"></i>Belum ada tindakan. Klik <strong>Tambah</strong> atau pilih tindakan cepat.
        </div>
        <div id="action-list">
            @foreach ($rows as $i => $row)
                @include('koleksi._action_row', ['i' => $i, 'a' => $row])
            @endforeach
        </div>
    </div>
</div>

<div class="sticky-actions">
    <div class="inner d-flex justify-content-between align-items-center">
        <small class="text-secondary d-none d-md-inline" id="dirty-hint"></small>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('koleksi.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-brand px-4" id="save-btn"><i class="bi bi-check2-circle me-1"></i> Simpan</button>
        </div>
    </div>
</div>

<template id="action-template">
    @include('koleksi._action_row', ['i' => '__INDEX__', 'a' => ['priority' => 'sedang', 'status' => 'rencana']])
</template>

@push('scripts')
<script>
(() => {
    const form  = document.querySelector('form[data-tape-form]');
    const list  = document.getElementById('action-list');
    const tpl   = document.getElementById('action-template');
    const empty = document.getElementById('action-empty');
    const count = document.getElementById('action-count');
    let index = {{ count($rows) ? max(array_keys($rows)) + 1 : 0 }};
    let dirty = false;

    const markDirty = () => {
        dirty = true;
        document.getElementById('dirty-hint').textContent = 'Ada perubahan yang belum disimpan';
    };

    const refresh = () => {
        const n = list.querySelectorAll('.action-row:not(.row-leave)').length;
        count.textContent = n;
        empty.classList.toggle('d-none', n > 0);
    };

    const setPriority = (row, value) => {
        row.classList.remove('prio-rendah', 'prio-sedang', 'prio-tinggi');
        row.classList.add('prio-' + value);
    };

    function addRow(preset = {}) {
        list.insertAdjacentHTML('beforeend', tpl.innerHTML.replaceAll('__INDEX__', index++));
        const row = list.lastElementChild;
        row.classList.add('row-enter');
        if (preset.name) row.querySelector('[name$="[action_name]"]').value = preset.name;
        if (preset.priority) {
            row.querySelector('[name$="[priority]"]').value = preset.priority;
            setPriority(row, preset.priority);
        }
        row.querySelector('[name$="[action_name]"]').focus();
        refresh(); markDirty();
    }

    document.getElementById('add-action').addEventListener('click', () => addRow());
    document.querySelectorAll('.preset').forEach(b =>
        b.addEventListener('click', () => addRow({ name: b.dataset.name, priority: b.dataset.priority })));

    list.addEventListener('click', (e) => {
        const btn = e.target.closest('.remove-action');
        if (!btn) return;
        const row = btn.closest('.action-row');
        row.classList.add('row-leave');
        row.addEventListener('animationend', () => { row.remove(); refresh(); }, { once: true });
        markDirty();
    });

    list.addEventListener('change', (e) => {
        if (e.target.matches('[name$="[priority]"]')) setPriority(e.target.closest('.action-row'), e.target.value);
    });

    // Kode otomatis huruf besar
    document.getElementById('code').addEventListener('input', (e) => {
        const pos = e.target.selectionStart;
        e.target.value = e.target.value.toUpperCase();
        e.target.setSelectionRange(pos, pos);
    });

    // Penghitung karakter pada textarea
    document.querySelectorAll('[data-counter]').forEach(el => {
        const max = el.getAttribute('maxlength');
        const hint = document.createElement('div');
        hint.className = 'form-text text-end';
        el.insertAdjacentElement('afterend', hint);
        const upd = () => hint.textContent = `${el.value.length}/${max}`;
        el.addEventListener('input', upd); upd();
    });

    // Peringatan jika meninggalkan halaman dengan perubahan belum tersimpan
    form.addEventListener('input', markDirty);
    form.addEventListener('submit', () => {
        dirty = false;
        const btn = document.getElementById('save-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    });
    window.addEventListener('beforeunload', (e) => { if (dirty) { e.preventDefault(); e.returnValue = ''; } });

    refresh();
})();
</script>
@endpush
