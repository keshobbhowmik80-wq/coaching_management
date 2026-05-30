<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class GradeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Grades', [
            'grades' => Grade::orderBy('min_percent')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'min_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'max_percent' => ['required', 'integer', 'min:0', 'max:100', 'gte:min_percent'],
            'grade' => ['required', 'string', 'max:10', Rule::unique('grades')],
            'gpa' => ['required', 'numeric', 'min:0', 'max:5'],
        ]);

        Grade::create($validated);

        return back()->with('success', 'Grade added.');
    }

    public function update(Request $request, Grade $grade): RedirectResponse
    {
        $validated = $request->validate([
            'min_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'max_percent' => ['required', 'integer', 'min:0', 'max:100', 'gte:min_percent'],
            'grade' => ['required', 'string', 'max:10', Rule::unique('grades')->ignore($grade)],
            'gpa' => ['required', 'numeric', 'min:0', 'max:5'],
        ]);

        $grade->update($validated);

        return back()->with('success', 'Grade updated.');
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        $grade->delete();

        return back()->with('success', 'Grade deleted.');
    }
}
