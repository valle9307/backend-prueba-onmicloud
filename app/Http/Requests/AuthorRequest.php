<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorRequest extends FormRequest
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
        $rules  = [
            'name'      => 'required',
            'last_name' => 'required',
            'email'     => 'required|email',
        ];

        switch ($this->method()) {
            case 'POST':
                $rules['file'] = 'required|image|mimes:png,jpg,jpeg';
                break;

            case 'PUT':
                $rules['file'] = 'nullable|image|mimes:png,jpg,jpeg';
                break;
        }

        return $rules;
    }
}
