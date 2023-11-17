<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjecrCreateRequest extends FormRequest
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
            'name' => 'required|string|max:30',
            'description' => 'required|string|max:800',
            'collectibles' => 'required|string|max:100',
            'goal' => 'required|string|max:100',
            'facilitator.name' => 'required|string|max:30',
            'facilitator.firstPerson' => 'required|string|max:10',
            'facilitator.honorificTitle' => 'required|string|max:10',
            'facilitator.character' => 'required|string|max:255',
            'facilitator.endOfSentence' => 'required|string|max:10',
            'facilitator.favouritePhrase' => 'required|string|max:20',
            'cross_start' => 'required|integer|min:3|max:255',
            'vote_start' => 'required|integer|gte:cross_start|max:255',
            'reflection_start' => 'required|integer|min:50|max:100',
        ];
    }
}
