<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Mark;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $income = Payment::sum('amount_paid');
        $expenses = Expense::sum('amount');

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'students' => Student::count(),
                'teachers' => Teacher::count(),
                'users' => User::count(),
                'marks' => Mark::count(),
                'income' => $income,
                'expenses' => $expenses,
                'profit' => $income - $expenses,
                'dues' => Payment::query()->selectRaw('COALESCE(SUM(amount_due - amount_paid), 0) as due')->value('due'),
            ],
        ]);
    }
}
