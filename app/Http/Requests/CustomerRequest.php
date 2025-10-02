<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'identification_type_id' => 'required|integer',
            'document_number' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'paternal_surname' => 'required|string|max:255',
            'maternal_surname' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'date_of_birth' => 'nullable|date|before:today',
            'ubigeo_cod' => 'nullable|string|max:10',
            'contact_type' => 'nullable|string|max:50',
            'contact_value' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'reference' => 'nullable|string|max:500',
        ];
    }
}
