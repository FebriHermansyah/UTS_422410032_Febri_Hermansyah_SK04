<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InitialCondition extends Model
{
    public const CONDITIONS = [
        'baik'   => 'Baik',
        'cukup'  => 'Cukup',
        'rusak'  => 'Rusak',
        'kritis' => 'Kritis',
    ];

    protected $fillable = [
        'tape_collection_id', 'physical_condition', 'damage_description',
        'has_mold', 'has_sticky_shed', 'assessed_at',
    ];

    protected function casts(): array
    {
        return [
            'has_mold'        => 'boolean',
            'has_sticky_shed' => 'boolean',
            'assessed_at'     => 'date',
        ];
    }

    public function tapeCollection(): BelongsTo
    {
        return $this->belongsTo(TapeCollection::class);
    }

    public function getConditionLabelAttribute(): string
    {
        return self::CONDITIONS[$this->physical_condition] ?? $this->physical_condition;
    }
}
