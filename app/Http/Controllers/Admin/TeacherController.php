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
    public function index(): Response
    {
        return Inertia::render('Admin/Teachers', [
            'teachers' => InertiaPagination::format(
                Teacher::with('user')->latest()->paginate(10)->withQueryString()
            ),
        ]);
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

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $teacher->user->update($userData);
            $teacher->update(collect($validated)->except(['name', 'email', 'password'])->all());
        });

        return back()->with('success', 'Teacher updated.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $teacher->user->delete();

        return back()->with('success', 'Teacher deleted.');
    }
}
