<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Centralizes rupee math for personalized SKUs so storefront AJAX and checkout stay aligned.
 */
class PricingService
{
    /**
     * Canonical groups edited together by admins / mirrored on the Blade form.
     *
     * @var list<string>
     */
    protected array $singleSelectGroups = [
        'material',
        'size',
        'color',
        'font',
        'gift_wrap',
        'engraving',
    ];

    /**
     * Computes final unit price and auditable line-items for UI + receipts.
     *
     * @param  array<string, mixed>  $selections
     * @return array{unit_price: string, breakdown: array<int, array{label: string, amount: string}>}
     */
    public function quote(Product $product, array $selections): array
    {
        $product->loadMissing('customizationOptions');

        /** @var Collection<string, Collection<int, \App\Models\CustomizationOption>> $byGroup */
        $byGroup = $product->customizationOptions->groupBy('option_group');

        $breakdown = [];
        $base = (float) $product->base_price;
        $breakdown[] = [
            'label' => 'Base — '.$product->name,
            'amount' => number_format($base, 2, '.', ''),
        ];

        $total = $base;

        foreach ($this->singleSelectGroups as $group) {
            $key = $selections[$group] ?? null;
            if (! is_string($key) || $key === '') {
                continue;
            }

            $option = $byGroup->get($group)?->firstWhere('value_key', $key);
            if (! $option) {
                continue;
            }

            $adj = (float) $option->price_adjustment;
            if ($adj === 0.0) {
                continue;
            }

            $total += $adj;
            $breakdown[] = [
                'label' => $option->label,
                'amount' => number_format($adj, 2, '.', ''),
            ];
        }

        $addons = $selections['addons'] ?? [];
        if (is_array($addons)) {
            foreach ($addons as $addonKey) {
                if (! is_string($addonKey) || $addonKey === '') {
                    continue;
                }

                $option = $byGroup->get('addon')?->firstWhere('value_key', $addonKey);
                if (! $option) {
                    continue;
                }

                $adj = (float) $option->price_adjustment;
                $total += $adj;
                $breakdown[] = [
                    'label' => $option->label,
                    'amount' => number_format($adj, 2, '.', ''),
                ];
            }
        }

        $wantsUpload = filter_var($selections['has_image_upload'] ?? false, FILTER_VALIDATE_BOOLEAN);
        if ($wantsUpload) {
            $fee = $byGroup->get('image_upload')?->firstWhere('value_key', 'yes');
            if ($fee) {
                $adj = (float) $fee->price_adjustment;
                $total += $adj;
                $breakdown[] = [
                    'label' => $fee->label,
                    'amount' => number_format($adj, 2, '.', ''),
                ];
            }
        }

        return [
            'unit_price' => number_format($total, 2, '.', ''),
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Builds the persistable snapshot stored on cart/order rows (includes display strings).
     *
     * @param  array<string, mixed>  $selections
     * @return array<string, mixed>
     */
    public function snapshot(Product $product, array $selections, ?string $storedUploadPath = null): array
    {
        $quote = $this->quote($product, $selections);

        return [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'selections' => $selections,
            'upload_path' => $storedUploadPath,
            'unit_price' => $quote['unit_price'],
            'breakdown' => $quote['breakdown'],
        ];
    }
}
