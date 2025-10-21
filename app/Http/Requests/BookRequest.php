<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|min:3|max:255',
            'category_id' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'publication_date' => 'nullable|date', 
            'edition' => 'nullable|integer',
            'author_id' => 'nullable|integer',
            'isbn' => 'nullable|string|min:3|max:255',
            'cover' => 'nullable|string|min:3|max:255', 
        ];
    }
}
