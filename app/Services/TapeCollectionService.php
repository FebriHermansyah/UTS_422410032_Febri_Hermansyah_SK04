<?php

namespace App\Services;

use App\Models\RestorationAction;
use App\Models\TapeCollection;
use Illuminate\Support\Facades\DB;

/**
 * Service layer: seluruh logika bisnis & transaksi database ada di sini,
 * sehingga controller tetap tipis (hanya HTTP in/out).
 */
class TapeCollectionService
{
    public function create(array $data): TapeCollection
    {
        // Tiga tabel ditulis sekaligus; gagal satu => rollback semua.
        return DB::transaction(function () use ($data) {
            $koleksi = TapeCollection::create($this->collectionFields($data));

            $koleksi->condition()->create($this->conditionFields($data['condition']));

            foreach ($data['actions'] ?? [] as $action) {
                $koleksi->actions()->create($this->actionFields($action));
            }

            return $koleksi;
        });
    }

    public function update(TapeCollection $koleksi, array $data): TapeCollection
    {
        return DB::transaction(function () use ($koleksi, $data) {
            $koleksi->update($this->collectionFields($data));

            // hasOne: update jika sudah ada, buat jika belum
            $koleksi->condition()->updateOrCreate([], $this->conditionFields($data['condition']));

            // Sinkronisasi hasMany: hapus yang dibuang, update yang ada, buat yang baru
            $actions = collect($data['actions'] ?? []);
            $keepIds = $actions->pluck('id')->filter()->all();

            $koleksi->actions()->whereNotIn('id', $keepIds)->delete();

            foreach ($actions as $action) {
                if (! empty($action['id'])) {
                    $koleksi->actions()->whereKey($action['id'])->update($this->actionFields($action));
                } else {
                    $koleksi->actions()->create($this->actionFields($action));
                }
            }

            return $koleksi->refresh();
        });
    }

    public function delete(TapeCollection $koleksi): void
    {
        DB::transaction(function () use ($koleksi) {
            $koleksi->actions()->delete();
            $koleksi->condition()->delete();
            $koleksi->delete();
        });
    }

    public function updateActionStatus(RestorationAction $action, string $status): RestorationAction
    {
        $action->update(['status' => $status]);

        return $action;
    }

    private function collectionFields(array $d): array
    {
        return [
            'code'         => strtoupper(trim($d['code'])),
            'name'         => $d['name'],
            'media_format' => $d['media_format'],
            'description'  => $d['description'] ?? null,
        ];
    }

    private function conditionFields(array $c): array
    {
        return [
            'physical_condition' => $c['physical_condition'],
            'damage_description' => $c['damage_description'] ?? null,
            'has_mold'           => (bool) ($c['has_mold'] ?? false),
            'has_sticky_shed'    => (bool) ($c['has_sticky_shed'] ?? false),
            'assessed_at'        => $c['assessed_at'] ?? null,
        ];
    }

    private function actionFields(array $a): array
    {
        return [
            'action_name'  => $a['action_name'],
            'priority'     => $a['priority'],
            'status'       => $a['status'],
            'planned_date' => $a['planned_date'] ?? null,
            'notes'        => $a['notes'] ?? null,
        ];
    }
}
