<?php

namespace App\Http\Requests;

use App\Enums\LangEnum;
use App\Models\Entry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TranslateSaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $id = $this->route()->originalParameter('entry');

        $rules = [
            'key' => [
                'required',
                'alpha_dash',
                'min:6',
                'max:100',
                Rule::unique(Entry::class)->whereNull('deleted_at')->ignore($id),
            ],
            'tags' => 'array',
            'tags.*' => 'alpha_dash|min:6|max:100',
        ];

        if (empty($id)) {
            $rules['lang'] = [
                'required',
                Rule::in(LangEnum::getValues()),
            ];
            $rules['content'] = 'required|max:300';
        }

        return $rules;
    }
}
