<?php

namespace App\Http\Controllers;

use App\Http\Requests\TapeCollectionRequest;
use App\Http\Requests\UpdateActionStatusRequest;
use App\Models\InitialCondition;
use App\Models\RestorationAction;
use App\Models\TapeCollection;
use App\Services\TapeCollectionService;
use Illuminate\Http\Request;

class TapeCollectionController extends Controller
{
    public function __construct(private TapeCollectionService $service)
    {
    }

    public function index(Request $request)
    {
        $koleksi = TapeCollection::with('condition')
            ->withCount('actions')
            ->search($request->q)
            ->format($request->media_format)
            ->condition($request->physical_condition)
            ->actionStatus($request->action_status)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Permintaan AJAX (pencarian langsung) hanya butuh potongan tabelnya saja
        if ($request->ajax()) {
            return view('koleksi._table', compact('koleksi'));
        }

        $stats = [
            'total'    => TapeCollection::count(),
            'critical' => InitialCondition::where('physical_condition', 'kritis')->count(),
            'progress' => RestorationAction::where('status', 'proses')->count(),
            'done'     => RestorationAction::where('status', 'selesai')->count(),
        ];

        return view('koleksi.index', compact('koleksi', 'stats'));
    }

    public function create()
    {
        return view('koleksi.create');
    }

    public function store(TapeCollectionRequest $request)
    {
        $koleksi = $this->service->create($request->validated());

        return redirect()->route('koleksi.show', $koleksi)
            ->with('success', 'Koleksi berhasil ditambahkan.');
    }

    public function show(TapeCollection $koleksi)
    {
        $koleksi->load(['condition', 'actions']);

        return view('koleksi.show', compact('koleksi'));
    }

    public function edit(TapeCollection $koleksi)
    {
        $koleksi->load(['condition', 'actions']);

        return view('koleksi.edit', compact('koleksi'));
    }

    public function update(TapeCollectionRequest $request, TapeCollection $koleksi)
    {
        $this->service->update($koleksi, $request->validated());

        return redirect()->route('koleksi.show', $koleksi)
            ->with('success', 'Koleksi berhasil diperbarui.');
    }

    public function destroy(TapeCollection $koleksi)
    {
        $this->service->delete($koleksi);

        return redirect()->route('koleksi.index')
            ->with('success', 'Koleksi berhasil dihapus.');
    }

    /** Endpoint JSON: ubah status tindakan tanpa reload halaman. */
    public function updateActionStatus(
        UpdateActionStatusRequest $request,
        TapeCollection $koleksi,
        RestorationAction $action
    ) {
        $this->service->updateActionStatus($action, $request->validated('status'));

        $total = $koleksi->actions()->count();
        $done  = $koleksi->actions()->where('status', 'selesai')->count();

        return response()->json([
            'message' => 'Status "' . $action->action_name . '" diubah menjadi ' . RestorationAction::STATUSES[$action->status] . '.',
            'done'    => $done,
            'total'   => $total,
            'percent' => $total ? (int) round($done / $total * 100) : 0,
        ]);
    }
}
