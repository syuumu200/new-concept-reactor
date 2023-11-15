<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'project_id' => 'required|ulid|exists:projects,id',
            'suggestion' => 'required|string',
            'sources' => 'array',
            'sources.*.id' => 'exists:materials,id',
            'targets' => 'required|array',
            'targets.*.body' => 'string|min:1|max:100',
            'targets.*.isCommand' => 'boolean',
        ];
    }
}
