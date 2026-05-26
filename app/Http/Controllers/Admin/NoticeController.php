<?php

namespace App\Http\Controllers\Admin;

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
        return Inertia::render('Admin/Notices', [
            'notices' => InertiaPagination::format(
                Notice::with('creator:id,name')->latest()->paginate(10)->withQueryString()
            ),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Notice::create($this->validated($request) + ['created_by' => $request->user()->id]);

        return back()->with('success', 'Notice posted.');
    }

    public function update(Request $request, Notice $notice): RedirectResponse
    {
        $notice->update($this->validated($request));

        return back()->with('success', 'Notice updated.');
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        $notice->delete();

        return back()->with('success', 'Notice deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'audience' => ['required', Rule::in(['all', 'admin', 'teacher', 'student'])],
            'published_on' => ['nullable', 'date'],
        ]);
    }
}
