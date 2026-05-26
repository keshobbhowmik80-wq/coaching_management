<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachingClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Students', [
            'students' => InertiaPagination::format(
                Student::with(['user', 'coachingClass', 'section'])->latest()->paginate(10)->withQueryString()
            ),
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
            'sections' => Section::with('coachingClass:id,name')->orderBy('name')->get(['id', 'class_id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'string', 'min:8'],
            'admission_no' => ['required', 'string', 'max:50', Rule::unique('students')],
            'class_id' => ['nullable', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'admitted_at' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($validated): void {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
            ]);

            Student::create(collect($validated)->except(['name', 'email', 'password'])->put('user_id', $user->id)->all());
        });

        return back()->with('success', 'Student created.');
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($student->user_id)],
            'password' => ['nullable', 'string', 'min:8'],
            'admission_no' => ['required', 'string', 'max:50', Rule::unique('students')->ignore($student)],
            'class_id' => ['nullable', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'admitted_at' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($student, $validated): void {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => 'student',
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $student->user->update($userData);
            $student->update(collect($validated)->except(['name', 'email', 'password'])->all());
        });

        return back()->with('success', 'Student updated.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->user->delete();

        return back()->with('success', 'Student deleted.');
    }
}
