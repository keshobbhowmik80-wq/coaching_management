<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Payments', [
            'payments' => InertiaPagination::format(
                Payment::with('student.user')->latest()->paginate(10)->withQueryString()
            ),
            'students' => Student::with('user:id,name')->orderBy('admission_no')->get(['id', 'user_id', 'admission_no']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Payment::create($this->validated($request));

        return back()->with('success', 'Payment record created.');
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $payment->update($this->validated($request));

        return back()->with('success', 'Payment record updated.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return back()->with('success', 'Payment record deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'fee_type' => ['required', 'string', 'max:255'],
            'amount_due' => ['required', 'numeric', 'min:0'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['due', 'partial', 'paid'])],
            'method' => ['nullable', 'string', 'max:100'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
