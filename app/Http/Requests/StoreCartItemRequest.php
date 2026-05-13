<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $max = config('customgift.custom_text_max', 500);
        $maxKb = config('customgift.upload_max_kb', 2048);

        return [
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'material' => ['nullable', 'string', 'max:128'],
            'size' => ['nullable', 'string', 'max:128'],
            'color' => ['nullable', 'string', 'max:128'],
            'font' => ['nullable', 'string', 'max:128'],
            'gift_wrap' => ['nullable', 'string', 'max:128'],
            'engraving' => ['nullable', 'string', 'max:128'],
            'addons' => ['nullable', 'array'],
            'addons.*' => ['string', 'max:128'],
            'has_image_upload' => ['nullable', 'boolean'],
            'custom_text' => ['nullable', 'string', 'max:'.$max],
            'upload' => ['nullable', 'file', 'max:'.($maxKb * 1024), 'mimes:jpeg,jpg,png,webp'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function selections(): array
    {
        $data = $this->validated();
        unset($data['quantity'], $data['upload']);

        return $data;
    }
}
