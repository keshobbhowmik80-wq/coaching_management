<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Support\InertiaPagination;
use Inertia\Inertia;
use Inertia\Response;

class NoticeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Student/Notices', [
            'notices' => InertiaPagination::format(
                Notice::whereIn('audience', ['all', 'student'])->latest()->paginate(10)->withQueryString()
            ),
        ]);
    }
}
