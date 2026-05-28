<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachingClass;
use App\Models\Subject;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SubjectController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Subjects', [
            'subjects' => InertiaPagination::format(
                Subject::with('coachingClass')->latest()->paginate(10)->withQueryString()
            ),
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Subjects/Create', [
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function edit(Subject $subject): Response
    {
        return Inertia::render('Admin/Subjects/Edit', [
            'subject' => $subject,
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Subject::create($request->validate([
            'class_id' => ['nullable', 'exists:classes,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('subjects')],
            'full_marks' => ['required', 'integer', 'min:1'],
            'pass_marks' => ['required', 'integer', 'min:0', 'lte:full_marks'],
        ]));

        return back()->with('success', 'Subject created.');
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $subject->update($request->validate([
            'class_id' => ['nullable', 'exists:classes,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('subjects')->ignore($subject)],
            'full_marks' => ['required', 'integer', 'min:1'],
            'pass_marks' => ['required', 'integer', 'min:0', 'lte:full_marks'],
        ]));

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return back()->with('success', 'Subject deleted.');
    }
}
