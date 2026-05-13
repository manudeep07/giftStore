<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Validates AJAX payloads mirrored from the live customization form. */
class QuotePriceRequest extends FormRequest
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

        return [
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
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function selections(): array
    {
        return $this->validated();
    }
}
