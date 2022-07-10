<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StreamStoreRequest extends FormRequest
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
            'name' => ['bail', 'required', 'min:3', 'max:255'],
            'description' => ['bail', 'required', 'min:3', 'max:255'],
            'preview' => ['required', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ];
    }
}
