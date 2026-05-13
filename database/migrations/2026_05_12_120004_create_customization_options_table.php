<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-defined choices (material, size, etc.) with rupee adjustments.
 * PricingService sums matching option rows for the active selection payload.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customization_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            /** Logical grouping presented together in the UI (material, size, …). */
            $table->string('option_group', 64);
            /** Stable key POSTed from the storefront (e.g. premium_wood). */
            $table->string('value_key', 128);
            $table->string('label');
            /** Added on top of base_price when this choice is selected. */
            $table->decimal('price_adjustment', 12, 2)->default(0);
            /** Arbitrary JSON: hex codes, max engraving chars, helper copy, etc. */
            $table->json('meta')->nullable();
            $table->boolean('is_default')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'option_group', 'value_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customization_options');
    }
};
