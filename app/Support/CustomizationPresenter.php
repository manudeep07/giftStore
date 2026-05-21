<?php

namespace App\Support;

use App\Models\Product;

class CustomizationPresenter
{
    /**
     * @param  array<string, mixed>|null  $snapshot
     */
    public static function resumeUrl(Product $product, ?array $snapshot): string
    {
        if ($snapshot === null || $snapshot === []) {
            return route('products.show', $product);
        }

        $selections = $snapshot['selections'] ?? [];
        $params = [];

        foreach (['material', 'size', 'color', 'font', 'gift_wrap', 'engraving'] as $key) {
            if (! empty($selections[$key])) {
                $params[$key] = $selections[$key];
            }
        }

        if (! empty($selections['custom_text'])) {
            $params['custom_text'] = $selections['custom_text'];
        }

        if (! empty($selections['addons']) && is_array($selections['addons'])) {
            foreach ($selections['addons'] as $addon) {
                $params['addons'][] = $addon;
            }
        }

        if (! empty($selections['has_image_upload']) || ! empty($snapshot['upload_path'])) {
            $params['has_image_upload'] = '1';
        }

        if ($params === []) {
            return route('products.show', $product);
        }

        return route('products.show', $product).'?'.http_build_query($params);
    }

    /**
     * @param  array<string, mixed>|null  $snapshot
     * @return array<string, mixed>
     */
    public static function selections(?array $snapshot): array
    {
        if ($snapshot === null) {
            return [];
        }

        return is_array($snapshot['selections'] ?? null) ? $snapshot['selections'] : [];
    }
}
