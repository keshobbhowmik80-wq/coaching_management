<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Support\InertiaPagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ExpenseController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Expenses', [
            'expenses' => InertiaPagination::format(
                Expense::with('creator:id,name')->latest()->paginate(10)->withQueryString()
            ),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Expense::create($this->validated($request) + ['created_by' => $request->user()->id]);

        return back()->with('success', 'Expense created.');
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $expense->update($this->validated($request));

        return back()->with('success', 'Expense updated.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return back()->with('success', 'Expense deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'spent_on' => ['required', 'date'],
        ]);
    }
}
