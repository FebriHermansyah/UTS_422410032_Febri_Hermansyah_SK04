<?php

namespace App\Http\Requests;

use App\Models\InitialCondition;
use App\Models\RestorationAction;
use App\Models\TapeCollection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TapeCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Pada update, route param 'koleksi' berisi model -> abaikan id sendiri saat cek unique
        $current = $this->route('koleksi');

        return [
            'code' => ['required', 'string', 'max:30',
                Rule::unique('tape_collections', 'code')->ignore($current?->id)],
            'name'         => ['required', 'string', 'max:150'],
            'media_format' => ['required', Rule::in(array_keys(TapeCollection::FORMATS))],
            'description'  => ['nullable', 'string', 'max:1000'],

            'condition.physical_condition' => ['required', Rule::in(array_keys(InitialCondition::CONDITIONS))],
            'condition.damage_description' => ['nullable', 'string', 'max:2000'],
            'condition.has_mold'           => ['nullable', 'boolean'],
            'condition.has_sticky_shed'    => ['nullable', 'boolean'],
            'condition.assessed_at'        => ['nullable', 'date', 'before_or_equal:today'],

            'actions'                => ['nullable', 'array'],
            'actions.*.id'           => ['nullable', 'integer'],
            'actions.*.action_name'  => ['required', 'string', 'max:150'],
            'actions.*.priority'     => ['required', Rule::in(array_keys(RestorationAction::PRIORITIES))],
            'actions.*.status'       => ['required', Rule::in(array_keys(RestorationAction::STATUSES))],
            'actions.*.planned_date' => ['nullable', 'date'],
            'actions.*.notes'        => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode koleksi',
            'name' => 'nama koleksi',
            'media_format' => 'format media',
            'description' => 'deskripsi',
            'condition.physical_condition' => 'kondisi fisik',
            'condition.assessed_at' => 'tanggal penilaian',
            'actions.*.action_name' => 'nama tindakan',
            'actions.*.priority' => 'prioritas',
            'actions.*.status' => 'status',
            'actions.*.planned_date' => 'tanggal rencana',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'unique'   => ':attribute sudah dipakai koleksi lain.',
            'max'      => ':attribute maksimal :max karakter.',
            'in'       => ':attribute tidak valid.',
            'date'     => ':attribute harus berupa tanggal.',
            'before_or_equal' => ':attribute tidak boleh melebihi hari ini.',
        ];
    }
}
