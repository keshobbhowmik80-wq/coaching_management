<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Support\InertiaPagination;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $student = $request->user()->student;
        $payments = $student?->payments()->latest()->get() ?? collect();

        return Inertia::render('Student/Payments', [
            'payments' => $student
                ? InertiaPagination::format($student->payments()->latest()->paginate(10)->withQueryString())
                : ['data' => [], 'links' => [], 'meta' => []],
            'summary' => [
                'due' => $payments->sum(fn ($payment) => $payment->amount_due - $payment->amount_paid),
                'paid' => $payments->sum('amount_paid'),
            ],
        ]);
    }
}
