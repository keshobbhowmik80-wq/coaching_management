<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Support\InertiaPagination;
use Inertia\Inertia;
use Inertia\Response;

class PaymentSummaryController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Teacher/Payments', [
            'payments' => InertiaPagination::format(
                Payment::with('student.user')->latest()->paginate(10)->withQueryString()
            ),
            'summary' => [
                'due' => Payment::query()->selectRaw('COALESCE(SUM(amount_due - amount_paid), 0) as due')->value('due'),
                'paid' => Payment::sum('amount_paid'),
            ],
        ]);
    }
}
