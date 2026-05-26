<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        return redirect(match ($request->user()->role) {
            'admin' => route('admin.dashboard'),
            'teacher' => route('teacher.dashboard'),
            default => route('student.dashboard'),
        });
    }
}
