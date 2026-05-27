<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachingClass;
use App\Models\Section;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SectionController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Sections', [
            'sections' => InertiaPagination::format(
                Section::with('coachingClass')->latest()->paginate(10)->withQueryString()
            ),
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Sections/Create', [
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function edit(Section $section): Response
    {
        return Inertia::render('Admin/Sections/Edit', [
            'section' => $section,
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Section::create($request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('sections')->where('class_id', $request->integer('class_id'))],
            'capacity' => ['nullable', 'string', 'max:50'],
        ]));

        return back()->with('success', 'Section created.');
    }

    public function update(Request $request, Section $section): RedirectResponse
    {
        $section->update($request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('sections')->where('class_id', $request->integer('class_id'))->ignore($section)],
            'capacity' => ['nullable', 'string', 'max:50'],
        ]));

        return redirect()->route('admin.sections.index')->with('success', 'Section updated.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();

        return back()->with('success', 'Section deleted.');
    }
}
