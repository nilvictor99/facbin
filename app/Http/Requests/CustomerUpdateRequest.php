<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
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
            'identification_type_id' => 'required|exists:identification_types,id',
            'document_number' => 'required|string|max:20',
            'name' => 'required|string|max:100',
            'paternal_surname' => 'required|string|max:100',
            'maternal_surname' => 'required|string|max:100',
            'gender' => 'required|in:M,F',
            'date_of_birth' => 'required|date',
            'ubigeo_cod' => 'required|string|size:6',
            'contact_type' => 'required',
            'contact_value' => 'required|string|max:100',
            'address' => 'nullable|string|max:200',
            'reference' => 'nullable|string|max:200',
        ];
    }
}
