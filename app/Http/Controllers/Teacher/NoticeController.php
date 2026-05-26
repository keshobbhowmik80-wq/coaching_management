<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class NoticeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Teacher/Notices', [
            'notices' => InertiaPagination::format(
                Notice::whereIn('audience', ['all', 'teacher', 'student'])->latest()->paginate(10)->withQueryString()
            ),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Notice::create($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'audience' => ['required', Rule::in(['all', 'teacher', 'student'])],
            'published_on' => ['nullable', 'date'],
        ]) + ['created_by' => $request->user()->id]);

        return back()->with('success', 'Notice posted.');
    }
}
