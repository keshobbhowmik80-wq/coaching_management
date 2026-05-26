<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachingClass;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CoachingClassController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Classes', [
            'classes' => InertiaPagination::format(
                CoachingClass::withCount(['sections', 'students', 'subjects'])->latest()->paginate(10)->withQueryString()
            ),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        CoachingClass::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('classes')],
            'description' => ['nullable', 'string'],
        ]));

        return back()->with('success', 'Class created.');
    }

    public function update(Request $request, CoachingClass $class): RedirectResponse
    {
        $class->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('classes')->ignore($class)],
            'description' => ['nullable', 'string'],
        ]));

        return back()->with('success', 'Class updated.');
    }

    public function destroy(CoachingClass $class): RedirectResponse
    {
        $class->delete();

        return back()->with('success', 'Class deleted.');
    }
}
