<?php

namespace App\Http\Requests\API\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'category' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
                'max:100',
            ],
            'image' => [
                'required',
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
