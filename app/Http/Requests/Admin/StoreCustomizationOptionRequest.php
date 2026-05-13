<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomizationOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'option_group' => ['required', 'string', 'max:64'],
            'value_key' => ['required', 'string', 'max:128'],
            'label' => ['required', 'string', 'max:255'],
            'price_adjustment' => ['required', 'numeric'],
            'meta' => ['nullable', 'array'],
            'is_default' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
