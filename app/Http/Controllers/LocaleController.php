<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale)
    {
        if (! in_array($locale, SetLocale::LOCALES, true)) {
            abort(404);
        }

        session(['locale' => $locale]);

        return redirect()->back();
    }
}