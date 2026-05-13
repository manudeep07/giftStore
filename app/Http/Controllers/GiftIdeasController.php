<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Lightweight narrative placeholder for future ML-powered gifting prompts.
 */
class GiftIdeasController extends Controller
{
    public function __invoke(): View
    {
        return view('gift-ideas');
    }
}
