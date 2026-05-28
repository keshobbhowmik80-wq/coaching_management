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
    public function index(Request $request): Response
    {
        $students = Student::with(['user', 'coachingClass', 'section'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                        ->orWhere('admission_no', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('class_id'), fn($q) => $q->where('class_id', $request->class_id))
            ->when($request->filled('section_id'), fn($q) => $q->where('section_id', $request->section_id))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Students', [
            'students' => InertiaPagination::format($students),
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
            'sections' => Section::orderBy('name')->get(['id', 'class_id', 'name']),
            'filters' => (object) $request->only(['search', 'class_id', 'section_id']),
            'allStudents' => Student::with('user')
                ->select('students.id', 'admission_no', 'users.name')
                ->join('users', 'users.id', '=', 'students.user_id')
                ->orderBy('admission_no')
                ->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Students/Create', [
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
            'sections' => Section::orderBy('name')->get(['id', 'class_id', 'name']),
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

    public function edit(Student $student): Response
    {
        return Inertia::render('Admin/Students/Edit', [
            'student' => $student->load('user'),
            'classes' => CoachingClass::orderBy('name')->get(['id', 'name']),
            'sections' => Section::orderBy('name')->get(['id', 'class_id', 'name']),
        ]);
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

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $student->user->update($userData);
            $student->update(collect($validated)->except(['name', 'email', 'password'])->all());
        });

        return redirect()->route('admin.students.index')->with('success', 'Student updated.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->user->delete();

        return back()->with('success', 'Student deleted.');
    }
}
