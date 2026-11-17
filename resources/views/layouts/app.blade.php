<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Restorasi Pita Analog')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <script>
        // Terapkan tema tersimpan sebelum render agar tidak berkedip
        document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('theme') || 'light');
    </script>

    <style>
        :root {
            --brand: #d97706; --brand-dark: #92400e;
            --c-baik: #16a34a; --c-cukup: #0891b2; --c-rusak: #d97706; --c-kritis: #dc2626;
            --p-rendah: #94a3b8; --p-sedang: #f59e0b; --p-tinggi: #ef4444;
            --app-bg: #f4f1ec; --card-shadow: 0 1px 3px rgba(0,0,0,.06), 0 8px 24px rgba(0,0,0,.05);
        }
        [data-bs-theme="dark"] { --app-bg: #14110f; --card-shadow: 0 1px 3px rgba(0,0,0,.5); }

        body { font-family: 'Inter', system-ui, sans-serif; background: var(--app-bg); }
        .app-nav { background: linear-gradient(135deg, #1c1917, #44260d); }
        .app-nav .navbar-brand { font-weight: 700; letter-spacing: .2px; }
        .app-nav .brand-icon { color: var(--brand); }

        .card { border: 0; border-radius: 1rem; box-shadow: var(--card-shadow); }
        .card-header { background: transparent; border-bottom: 1px solid var(--bs-border-color); padding: 1rem 1.25rem; }
        .btn { border-radius: .6rem; font-weight: 500; }
        .btn-brand { background: var(--brand); border-color: var(--brand); color: #fff; }
        .btn-brand:hover { background: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .form-control, .form-select { border-radius: .6rem; }
        .form-control:focus, .form-select:focus { border-color: var(--brand); box-shadow: 0 0 0 .2rem rgba(217,119,6,.18); }

        /* Kartu statistik */
        .stat-card { display: flex; gap: 1rem; align-items: center; padding: 1.1rem 1.25rem; text-decoration: none;
                     color: inherit; transition: transform .15s, box-shadow .15s; cursor: pointer; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(0,0,0,.12); color: inherit; }
        .stat-icon { width: 3rem; height: 3rem; border-radius: .9rem; display: grid; place-items: center; font-size: 1.4rem; color: #fff; }
        .stat-value { font-size: 1.6rem; font-weight: 700; line-height: 1; }

        /* Tabel */
        #table-wrapper { transition: opacity .15s; }
        #table-wrapper.loading { opacity: .45; pointer-events: none; }
        .table > :not(caption) > * > * { padding: .85rem 1rem; }
        .table-hover tbody tr { transition: background .12s; }
        .code-pill { font-family: ui-monospace, monospace; font-weight: 600; background: rgba(217,119,6,.12);
                     color: var(--brand); padding: .2rem .55rem; border-radius: .5rem; font-size: .85rem; }

        /* Badge kondisi bertitik */
        .dot-badge { display: inline-flex; align-items: center; gap: .4rem; font-size: .8rem; font-weight: 600;
                     padding: .25rem .65rem; border-radius: 2rem; background: color-mix(in srgb, var(--c) 14%, transparent); color: var(--c); }
        .dot-badge::before { content: ''; width: .5rem; height: .5rem; border-radius: 50%; background: var(--c); }
        .cond-baik { --c: var(--c-baik); } .cond-cukup { --c: var(--c-cukup); }
        .cond-rusak { --c: var(--c-rusak); } .cond-kritis { --c: var(--c-kritis); }

        /* Pilihan kondisi (radio berwarna) */
        .cond-opt { border: 1.5px solid var(--c); color: var(--c); background: transparent; padding: .5rem 1rem; }
        .btn-check:checked + .cond-opt, .cond-opt:hover { background: var(--c); color: #fff; border-color: var(--c); }
        .btn-check:focus-visible + .cond-opt { box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--c) 30%, transparent); }

        /* Baris tindakan */
        .action-row { border-left: 5px solid var(--p-sedang); border-radius: .75rem; background: var(--bs-tertiary-bg); }
        .prio-rendah { border-left-color: var(--p-rendah) !important; }
        .prio-sedang { border-left-color: var(--p-sedang) !important; }
        .prio-tinggi { border-left-color: var(--p-tinggi) !important; }
        .row-enter { animation: rowIn .25s ease-out; }
        .row-leave { animation: rowOut .2s ease-in forwards; }
        @keyframes rowIn  { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: none; } }
        @keyframes rowOut { to { opacity: 0; transform: translateX(30px); height: 0; margin: 0; padding: 0; } }

        .step-no { width: 1.9rem; height: 1.9rem; border-radius: 50%; background: var(--brand); color: #fff;
                   display: inline-grid; place-items: center; font-weight: 700; font-size: .9rem; margin-right: .5rem; }
        .sticky-actions { position: sticky; bottom: 1rem; z-index: 10; }
        .sticky-actions .inner { background: var(--bs-body-bg); border-radius: 1rem; box-shadow: 0 8px 30px rgba(0,0,0,.18); padding: .75rem 1rem; }

        /* Select status inline */
        .status-select { border-radius: 2rem; font-weight: 600; font-size: .85rem; width: auto; padding-right: 2rem; }
        .status-select[data-status="rencana"] { background-color: #e2e8f0; color: #334155; }
        .status-select[data-status="proses"]  { background-color: #dbeafe; color: #1d4ed8; }
        .status-select[data-status="selesai"] { background-color: #dcfce7; color: #15803d; }

        .detail-hero { background: linear-gradient(135deg, #1c1917, #78350f); color: #fff; border-radius: 1rem; }
        .empty-state { padding: 3rem 1rem; text-align: center; color: var(--bs-secondary-color); }
        .empty-state i { font-size: 2.5rem; display: block; margin-bottom: .5rem; opacity: .6; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark app-nav mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('koleksi.index') }}">
            <i class="bi bi-cassette-fill brand-icon me-1"></i> Restorasi Pita Analog
        </a>
        <button class="btn btn-sm btn-outline-light" id="theme-toggle" type="button" title="Ganti tema">
            <i class="bi bi-moon-stars-fill"></i>
        </button>
    </div>
</nav>

<main class="container pb-5">
    @yield('content')
</main>

{{-- Modal konfirmasi hapus (dipakai bersama oleh semua tombol hapus) --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="delete-form" class="modal-content" style="border-radius:1rem">
            @csrf @method('DELETE')
            <div class="modal-body text-center p-4">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:3rem"></i>
                <h5 class="mt-2">Hapus koleksi?</h5>
                <p class="text-secondary mb-0">
                    <strong id="delete-name"></strong> beserta kondisi awal dan seluruh tindakan restorasinya akan dihapus permanen.
                </p>
            </div>
            <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, hapus</button>
            </div>
        </form>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3" id="toast-area"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ---- Toast global ----
    window.showToast = (message, type = 'success') => {
        const icon = type === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill';
        const el = document.createElement('div');
        el.className = `toast align-items-center text-bg-${type} border-0`;
        el.innerHTML = `<div class="d-flex"><div class="toast-body"><i class="bi bi-${icon} me-2"></i>${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
        document.getElementById('toast-area').appendChild(el);
        const t = new bootstrap.Toast(el, { delay: 3500 });
        el.addEventListener('hidden.bs.toast', () => el.remove());
        t.show();
    };

    // ---- Toggle tema gelap/terang ----
    const themeBtn = document.getElementById('theme-toggle');
    const syncThemeIcon = () => {
        const dark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        themeBtn.innerHTML = dark ? '<i class="bi bi-sun-fill"></i>' : '<i class="bi bi-moon-stars-fill"></i>';
    };
    themeBtn.addEventListener('click', () => {
        const next = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-bs-theme', next);
        localStorage.setItem('theme', next);
        syncThemeIcon();
    });
    syncThemeIcon();

    // ---- Modal hapus: isi action & nama dari tombol pemicu ----
    document.getElementById('deleteModal').addEventListener('show.bs.modal', (e) => {
        document.getElementById('delete-form').action = e.relatedTarget.dataset.action;
        document.getElementById('delete-name').textContent = e.relatedTarget.dataset.name;
    });

    // ---- Flash message dari session ----
    @if (session('success'))
        showToast(@json(session('success')), 'success');
    @endif
</script>
@stack('scripts')
</body>
</html>
