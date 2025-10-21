<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorRequest extends FormRequest
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
        if ($this->isMethod('put')) {
            // PUT: updating, fields optional
            return [
                'name' => 'sometimes|string|max:255',
                'nationality' => 'sometimes|string|max:255',
                'age' => 'sometimes|integer|min:0',
                'gender' => 'sometimes|string|max:50'
            ];
        }

        // POST: creating, all fields required
        return [
            'name' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'gender' => 'required|string|max:50'
        ];
    }

}

