{{-- Potongan tabel: dirender penuh saat load pertama dan sebagai respons AJAX saat cari/filter --}}
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
            <tr class="text-secondary small text-uppercase">
                <th>Kode</th><th>Koleksi</th><th>Format</th><th>Kondisi Awal</th>
                <th class="text-center">Tindakan</th><th class="text-end">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($koleksi as $item)
                <tr>
                    <td><span class="code-pill">{{ $item->code }}</span></td>
                    <td>
                        <a href="{{ route('koleksi.show', $item) }}" class="fw-semibold text-decoration-none">{{ $item->name }}</a>
                        @if ($item->description)
                            <div class="small text-secondary text-truncate" style="max-width:280px">{{ $item->description }}</div>
                        @endif
                    </td>
                    <td class="small">{{ $item->format_label }}</td>
                    <td>
                        @if ($item->condition)
                            <span class="dot-badge cond-{{ $item->condition->physical_condition }}">{{ $item->condition->condition_label }}</span>
                        @else <span class="text-secondary">-</span> @endif
                    </td>
                    <td class="text-center"><span class="badge rounded-pill text-bg-secondary">{{ $item->actions_count }}</span></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('koleksi.show', $item) }}" class="btn btn-sm btn-outline-secondary" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('koleksi.edit', $item) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                        <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                data-action="{{ route('koleksi.destroy', $item) }}"
                                data-name="{{ $item->code }} — {{ $item->name }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">
                    <div class="empty-state"><i class="bi bi-inbox"></i>Tidak ada koleksi yang cocok dengan pencarian.</div>
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($koleksi->hasPages())
        <div class="card-footer bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-secondary">Menampilkan {{ $koleksi->firstItem() }}–{{ $koleksi->lastItem() }} dari {{ $koleksi->total() }}</small>
            {{ $koleksi->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="card-footer bg-transparent"><small class="text-secondary">{{ $koleksi->total() }} koleksi</small></div>
    @endif
</div>
