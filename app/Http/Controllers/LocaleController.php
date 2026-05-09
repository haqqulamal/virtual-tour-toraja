<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    /**
     * Switch the application locale
     */
    public function switchLocale(string $locale): RedirectResponse
    {
        // Validate locale is supported
        $supported = ['id', 'en'];
        if (!in_array($locale, $supported)) {
            abort(400, 'Unsupported locale');
        }

        // Store in session
        session(['locale' => $locale]);

        // Update app locale
        app()->setLocale($locale);

        return back();
    }
}
