<?php

namespace App\Http\Requests;

use App\Models\Entry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SyncAnchorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            '*.entry_key' => [
                'required',
                'alpha_dash',
                'min:6',
                'max:100',
                Rule::exists(Entry::class, 'key')->whereNull('deleted_at'),
            ],
            '*.key' => [
                'required',
                'alpha_dash',
                'min:6',
                'max:200',
            ],
            '*.url' => 'string|url|max:500',
            '*.remark' => 'nullable|string|max:200',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
