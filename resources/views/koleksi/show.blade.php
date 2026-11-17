@extends('layouts.app')
@section('title', 'Detail Koleksi')

@section('content')
@php
    use App\Models\RestorationAction;
    $c = $koleksi->condition;
    $total = $koleksi->actions->count();
    $done = $koleksi->actions->where('status', 'selesai')->count();
    $percent = $total ? (int) round($done / $total * 100) : 0;
@endphp

<a href="{{ route('koleksi.index') }}" class="text-decoration-none text-secondary"><i class="bi bi-arrow-left"></i> Kembali ke daftar</a>

<div class="detail-hero p-4 my-3">
    <div class="d-flex justify-content-between flex-wrap gap-3">
        <div>
            <span class="code-pill">{{ $koleksi->code }}</span>
            <h2 class="fw-bold mt-2 mb-1">{{ $koleksi->name }}</h2>
            <div class="opacity-75"><i class="bi bi-disc me-1"></i>{{ $koleksi->format_label }}</div>
            @if ($koleksi->description) <p class="mt-2 mb-0 opacity-75">{{ $koleksi->description }}</p> @endif
        </div>
        <div class="d-flex gap-2 align-items-start">
            <a href="{{ route('koleksi.edit', $koleksi) }}" class="btn btn-light"><i class="bi bi-pencil me-1"></i>Edit</a>
            <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#deleteModal"
                    data-action="{{ route('koleksi.destroy', $koleksi) }}" data-name="{{ $koleksi->code }} — {{ $koleksi->name }}">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
    <div class="mt-4">
        <div class="d-flex justify-content-between small mb-1">
            <span>Progres restorasi</span><span id="progress-text">{{ $done }} dari {{ $total }} tindakan selesai ({{ $percent }}%)</span>
        </div>
        <div class="progress" style="height:.7rem;background:rgba(255,255,255,.2)">
            <div class="progress-bar bg-success" id="progress-bar" style="width: {{ $percent }}%; transition: width .5s"></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header fw-bold"><i class="bi bi-clipboard2-pulse me-1"></i> Kondisi Awal</div>
            <div class="card-body">
                @if ($c)
                    <span class="dot-badge cond-{{ $c->physical_condition }} fs-6">{{ $c->condition_label }}</span>
                    <ul class="list-unstyled mt-3 mb-0">
                        <li class="mb-2"><i class="bi bi-{{ $c->has_mold ? 'x-circle-fill text-danger' : 'check-circle-fill text-success' }} me-2"></i>{{ $c->has_mold ? 'Terdapat jamur' : 'Tidak ada jamur' }}</li>
                        <li class="mb-2"><i class="bi bi-{{ $c->has_sticky_shed ? 'x-circle-fill text-danger' : 'check-circle-fill text-success' }} me-2"></i>{{ $c->has_sticky_shed ? 'Sticky-shed terdeteksi' : 'Tidak ada sticky-shed' }}</li>
                        <li class="mb-2"><i class="bi bi-calendar-event me-2"></i>Dinilai: {{ $c->assessed_at?->translatedFormat('d F Y') ?? '-' }}</li>
                    </ul>
                    <hr>
                    <div class="small text-secondary">Deskripsi kerusakan</div>
                    <p class="mb-0">{{ $c->damage_description ?? '-' }}</p>
                @else
                    <span class="text-secondary">Belum ada data kondisi.</span>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header fw-bold"><i class="bi bi-tools me-1"></i> Rencana Tindakan Restorasi ({{ $total }})</div>
            <div class="card-body">
                @forelse ($koleksi->actions as $a)
                    <div class="action-row prio-{{ $a->priority }} p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <div class="fw-semibold">{{ $a->action_name }}</div>
                                <div class="small text-secondary">
                                    <i class="bi bi-flag-fill" style="color: var(--p-{{ $a->priority }})"></i> Prioritas {{ $a->priority }}
                                    · <i class="bi bi-calendar3"></i> {{ $a->planned_date?->format('d/m/Y') ?? 'tanggal belum ditentukan' }}
                                </div>
                                @if ($a->notes) <div class="small mt-1">{{ $a->notes }}</div> @endif
                            </div>
                            <select class="form-select form-select-sm status-select" data-status="{{ $a->status }}"
                                    data-url="{{ route('koleksi.actions.status', [$koleksi, $a]) }}" title="Ubah status">
                                @foreach (RestorationAction::STATUSES as $k => $v)
                                    <option value="{{ $k }}" @selected($a->status === $k)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @empty
                    <div class="empty-state"><i class="bi bi-tools"></i>Belum ada tindakan restorasi.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.status-select').forEach(sel => {
    sel.dataset.prev = sel.value;
    sel.addEventListener('change', async () => {
        sel.disabled = true;
        try {
            const res = await fetch(sel.dataset.url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({ status: sel.value }),
            });
            if (!res.ok) throw new Error();
            const d = await res.json();
            sel.dataset.status = sel.dataset.prev = sel.value;
            document.getElementById('progress-bar').style.width = d.percent + '%';
            document.getElementById('progress-text').textContent = `${d.done} dari ${d.total} tindakan selesai (${d.percent}%)`;
            showToast(d.message, 'success');
        } catch (e) {
            sel.value = sel.dataset.prev;          // kembalikan jika gagal
            showToast('Gagal memperbarui status.', 'danger');
        }
        sel.disabled = false;
    });
});
</script>
@endpush
