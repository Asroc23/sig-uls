<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        $request->validate([
            'locale' => 'required|in:en,es',
        ], [
            'locale' => $request->locale ?? 'en',
        ]);

        $locale = $request->route('locale');

        if (! in_array($locale, ['en', 'es'])) {
            $locale = 'en';
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        return back();
    }
}
