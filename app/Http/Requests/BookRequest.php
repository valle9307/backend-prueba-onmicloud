<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
        $rules =  [
            'editorial_id' => 'required|exists:editorials,id',
            'title'        => 'required',
            'publish_at'   => 'required|date',
            'price'        => 'numeric|min:10',
            'authors'      => 'array|min:1'
        ];

        switch ($this->method()) {
            case 'POST':
                $rules['file'] = 'required|file|mimes:pdf';
                break;

            case 'PUT':
                $rules['file'] = 'nullable|file|mimes:pdf';
                break;
        }

        return $rules;
    }
}
