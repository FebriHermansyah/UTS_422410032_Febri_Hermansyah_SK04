@php
    use App\Models\RestorationAction;
    $p = "actions.$i";
@endphp
<div class="action-row prio-{{ $a['priority'] ?? 'sedang' }} p-3 mb-3">
    <input type="hidden" name="actions[{{ $i }}][id]" value="{{ $a['id'] ?? '' }}">
    <div class="row g-2">
        <div class="col-md-5">
            <label class="form-label small mb-1">Nama Tindakan <span class="text-danger">*</span></label>
            <input type="text" name="actions[{{ $i }}][action_name]" value="{{ $a['action_name'] ?? '' }}"
                   class="form-control @error($p.'.action_name') is-invalid @enderror">
            @error($p.'.action_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label small mb-1">Prioritas</label>
            <select name="actions[{{ $i }}][priority]" class="form-select @error($p.'.priority') is-invalid @enderror">
                @foreach (RestorationAction::PRIORITIES as $k => $v)
                    <option value="{{ $k }}" @selected(($a['priority'] ?? '') === $k)>{{ $v }}</option>
                @endforeach
            </select>
            @error($p.'.priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label small mb-1">Status</label>
            <select name="actions[{{ $i }}][status]" class="form-select @error($p.'.status') is-invalid @enderror">
                @foreach (RestorationAction::STATUSES as $k => $v)
                    <option value="{{ $k }}" @selected(($a['status'] ?? '') === $k)>{{ $v }}</option>
                @endforeach
            </select>
            @error($p.'.status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Tanggal Rencana</label>
            <input type="date" name="actions[{{ $i }}][planned_date]" value="{{ $a['planned_date'] ?? '' }}"
                   class="form-control @error($p.'.planned_date') is-invalid @enderror">
            @error($p.'.planned_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-10 col-md-11">
            <input type="text" name="actions[{{ $i }}][notes]" value="{{ $a['notes'] ?? '' }}" class="form-control form-control-sm" placeholder="Catatan (opsional)">
        </div>
        <div class="col-2 col-md-1">
            <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-action" title="Hapus tindakan"><i class="bi bi-trash"></i></button>
        </div>
    </div>
</div>
