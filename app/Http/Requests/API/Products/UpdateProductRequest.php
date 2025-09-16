<?php

namespace App\Http\Requests\API\Products;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'category' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'description' => [
                'sometimes',
                'string',
                'max:100',
            ],
            'image' => [
                'sometimes',
                'active_url',
                'starts_with:http://,https://',
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
