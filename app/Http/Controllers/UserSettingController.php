<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserSettingController extends Controller
{
    public function updateTheme(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => ['required', Rule::in(['light', 'dark'])],
        ]);

        $request->user()->settings()->updateOrCreate(
            ['user_id' => $request->user()->id],
            ['theme' => $validated['theme']],
        );

        return back()->with('theme', $validated['theme']);
    }
}
