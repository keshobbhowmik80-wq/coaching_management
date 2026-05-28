<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    public function index(Request $request): Response
    {
        $teachers = Teacher::with('user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                        ->orWhere('employee_id', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Teachers', [
            'teachers' => InertiaPagination::format($teachers),
            'filters' => (object) $request->only(['search']),
            'allTeachers' => Teacher::with('user')
                ->select('teachers.id', 'employee_id', 'users.name')
                ->join('users', 'users.id', '=', 'teachers.user_id')
                ->orderBy('employee_id')
                ->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Teachers/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'string', 'min:8'],
            'employee_id' => ['required', 'string', 'max:50', Rule::unique('teachers')],
            'phone' => ['nullable', 'string', 'max:50'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'joined_at' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated): void {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'teacher',
            ]);

            Teacher::create(collect($validated)->except(['name', 'email', 'password'])->put('user_id', $user->id)->all());
        });

        return back()->with('success', 'Teacher created.');
    }

    public function edit(Teacher $teacher): Response
    {
        return Inertia::render('Admin/Teachers/Edit', [
            'teacher' => $teacher->load('user'),
        ]);
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($teacher->user_id)],
            'password' => ['nullable', 'string', 'min:8'],
            'employee_id' => ['required', 'string', 'max:50', Rule::unique('teachers')->ignore($teacher)],
            'phone' => ['nullable', 'string', 'max:50'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'joined_at' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($teacher, $validated): void {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => 'teacher',
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $teacher->user->update($userData);
            $teacher->update(collect($validated)->except(['name', 'email', 'password'])->all());
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $teacher->user->delete();

        return back()->with('success', 'Teacher deleted.');
    }
}
