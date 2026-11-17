<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TapeCollection extends Model
{
    public const FORMATS = [
        'open_reel'         => 'Open Reel (Reel-to-Reel)',
        'compact_cassette'  => 'Kaset Compact',
        'microcassette'     => 'Microcassette',
        'dat'               => 'DAT (Digital Audio Tape)',
        'cartridge_8track'  => '8-Track Cartridge',
    ];

    protected $fillable = ['code', 'name', 'media_format', 'description'];

    // 1 koleksi : 1 kondisi awal
    public function condition(): HasOne
    {
        return $this->hasOne(InitialCondition::class);
    }

    // 1 koleksi : N tindakan restorasi
    public function actions(): HasMany
    {
        return $this->hasMany(RestorationAction::class);
    }

    public function getFormatLabelAttribute(): string
    {
        return self::FORMATS[$this->media_format] ?? $this->media_format;
    }

    /* ---------- Query Scope untuk pencarian & filter ---------- */

    public function scopeSearch(Builder $q, ?string $keyword): Builder
    {
        return $q->when($keyword, fn ($q) => $q->where(function ($q) use ($keyword) {
            $q->where('code', 'like', "%{$keyword}%")
              ->orWhere('name', 'like', "%{$keyword}%");
        }));
    }

    public function scopeFormat(Builder $q, ?string $format): Builder
    {
        return $q->when($format, fn ($q) => $q->where('media_format', $format));
    }

    public function scopeCondition(Builder $q, ?string $condition): Builder
    {
        return $q->when($condition, fn ($q) =>
            $q->whereHas('condition', fn ($c) => $c->where('physical_condition', $condition)));
    }

    public function scopeActionStatus(Builder $q, ?string $status): Builder
    {
        return $q->when($status, fn ($q) =>
            $q->whereHas('actions', fn ($a) => $a->where('status', $status)));
    }
}
