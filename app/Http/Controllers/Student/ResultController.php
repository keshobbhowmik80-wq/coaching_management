<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Support\InertiaPagination;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ResultController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Student/Results', [
            'marks' => $request->user()->student
                ? InertiaPagination::format($request->user()->student->marks()->with(['exam', 'subject'])->latest()->paginate(10)->withQueryString())
                : ['data' => [], 'links' => [], 'meta' => []],
        ]);
    }
}
