@extends('layouts.app')
@section('title', 'Daftar Koleksi')

@section('content')
@php
    use App\Models\TapeCollection;
    use App\Models\InitialCondition;
    use App\Models\RestorationAction;
@endphp

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold mb-0">Koleksi Pita Analog</h3>
        <span class="text-secondary">Pendataan kondisi dan rencana restorasi arsip rekaman</span>
    </div>
    <a href="{{ route('koleksi.create') }}" class="btn btn-brand btn-lg"><i class="bi bi-plus-lg me-1"></i> Tambah Koleksi</a>
</div>

{{-- Kartu statistik: klik untuk memfilter --}}
<div class="row g-3 mb-4">
    @foreach ([
        ['Total Koleksi',      $stats['total'],    'collection-fill', '#475569', '', ''],
        ['Kondisi Kritis',     $stats['critical'], 'exclamation-triangle-fill', 'var(--c-kritis)', 'physical_condition', 'kritis'],
        ['Tindakan Berjalan',  $stats['progress'], 'gear-fill', '#2563eb', 'action_status', 'proses'],
        ['Tindakan Selesai',   $stats['done'],     'check2-circle', 'var(--c-baik)', 'action_status', 'selesai'],
    ] as [$label, $value, $icon, $color, $key, $val])
        <div class="col-6 col-lg-3">
            <a class="card stat-card" data-filter-key="{{ $key }}" data-filter-value="{{ $val }}">
                <div class="stat-icon" style="background: {{ $color }}"><i class="bi bi-{{ $icon }}"></i></div>
                <div>
                    <div class="stat-value">{{ $value }}</div>
                    <div class="small text-secondary">{{ $label }}</div>
                </div>
            </a>
        </div>
    @endforeach
</div>

{{-- Pencarian & filter: hasil diperbarui otomatis tanpa reload --}}
<form method="GET" action="{{ route('koleksi.index') }}" id="filter-form" class="card card-body mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-lg-4">
            <div class="input-group">
                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari kode atau nama..." autocomplete="off">
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <select name="media_format" class="form-select">
                <option value="">Semua format</option>
                @foreach (TapeCollection::FORMATS as $k => $v)
                    <option value="{{ $k }}" @selected(request('media_format') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-lg-2">
            <select name="physical_condition" class="form-select">
                <option value="">Semua kondisi</option>
                @foreach (InitialCondition::CONDITIONS as $k => $v)
                    <option value="{{ $k }}" @selected(request('physical_condition') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-lg-2">
            <select name="action_status" class="form-select">
                <option value="">Status tindakan</option>
                @foreach (RestorationAction::STATUSES as $k => $v)
                    <option value="{{ $k }}" @selected(request('action_status') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-lg-2 d-grid">
            <button type="button" id="reset-filter" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</button>
        </div>
    </div>
</form>

<div id="table-wrapper">
    @include('koleksi._table')
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('filter-form');
    const wrap = document.getElementById('table-wrapper');
    let timer, controller;

    const buildUrl = () => {
        const params = new URLSearchParams(new FormData(form));
        [...params].forEach(([k, v]) => { if (!v) params.delete(k); });
        const qs = params.toString();
        return form.action + (qs ? '?' + qs : '');
    };

    async function load(url) {
        controller?.abort();                       // batalkan permintaan sebelumnya
        controller = new AbortController();
        wrap.classList.add('loading');
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: controller.signal });
            wrap.innerHTML = await res.text();
            history.replaceState(null, '', url);   // URL tetap bisa di-bookmark
        } catch (e) {
            if (e.name !== 'AbortError') showToast('Gagal memuat data.', 'danger');
        }
        wrap.classList.remove('loading');
    }

    // Ketik -> tunggu 350 ms setelah berhenti mengetik (debounce)
    form.q.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(() => load(buildUrl()), 350); });
    form.addEventListener('submit', (e) => { e.preventDefault(); load(buildUrl()); });
    form.querySelectorAll('select').forEach(s => s.addEventListener('change', () => load(buildUrl())));

    const clearAll = () => form.querySelectorAll('input, select').forEach(el => el.value = '');

    document.getElementById('reset-filter').addEventListener('click', () => { clearAll(); load(buildUrl()); });

    // Kartu statistik = pintasan filter
    document.querySelectorAll('.stat-card').forEach(card => card.addEventListener('click', () => {
        clearAll();
        if (card.dataset.filterKey) form.elements[card.dataset.filterKey].value = card.dataset.filterValue;
        load(buildUrl());
    }));

    // Pagination via AJAX (tabel diganti, jadi pakai event delegation)
    wrap.addEventListener('click', (e) => {
        const a = e.target.closest('.pagination a');
        if (a) { e.preventDefault(); load(a.href); }
    });
})();
</script>
@endpush
