<?php

namespace App\Http\Controllers\Locale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    protected array $supported = ['uz', 'ru', 'en'];

    public function set(Request $request)
    {
        $locale = $request->input('lang');

        if (!in_array($locale, $this->supported)) {
            abort(422, 'Unsupported locale');
        }

        session(['locale' => $locale]);

        return back();
    }
}
