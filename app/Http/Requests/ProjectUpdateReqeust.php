<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateReqeust extends FormRequest
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
            'name' => 'required|string|max:20',
            'description' => 'required|string|max:1000',
            'facilitator' => 'required|string|max:1000',
            'cross_start' => 'required|integer|min:3|max:255',
            'vote_start' => 'required|integer|gte:cross_start|max:255',
            'reflection_start' => 'required|integer|min:1|max:100',
        ];
    }
}
