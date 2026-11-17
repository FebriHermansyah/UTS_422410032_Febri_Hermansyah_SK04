<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestorationAction extends Model
{
    public const PRIORITIES = ['rendah' => 'Rendah', 'sedang' => 'Sedang', 'tinggi' => 'Tinggi'];
    public const STATUSES   = ['rencana' => 'Rencana', 'proses' => 'Proses', 'selesai' => 'Selesai'];

    protected $fillable = [
        'tape_collection_id', 'action_name', 'priority', 'status', 'planned_date', 'notes',
    ];

    protected function casts(): array
    {
        return ['planned_date' => 'date'];
    }

    public function tapeCollection(): BelongsTo
    {
        return $this->belongsTo(TapeCollection::class);
    }
}
