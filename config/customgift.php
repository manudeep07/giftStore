<?php

/**
 * Storefront commerce defaults for CustomGift (tax, shipping, text limits).
 */
return [

    /** GST-style tax applied after discounts on subtotal (decimal fraction). */
    'tax_rate' => (float) env('CUSTOMGIFT_TAX_RATE', 0.18),

    /** Flat domestic shipping placeholder until carrier integrations ship. */
    'shipping_flat' => (float) env('CUSTOMGIFT_SHIPPING_FLAT', 49),

    'custom_text_max' => 500,

    'upload_max_kb' => 2048,

    /** Allowed bespoke upload mime types for personalization previews. */
    'upload_mimes' => ['jpeg', 'jpg', 'png', 'webp'],
];
