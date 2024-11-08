<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() : array
    {
        return [
            //
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['required', 'image', 'mimes:png, jpg, jpeg'],
            'about' => ['required', 'string', 'max:65535'],
        ];
    }
}
