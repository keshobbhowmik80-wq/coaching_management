<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class ReportCardController extends Controller
{
    public function __invoke(Student $student, Exam $exam): Response
    {
        $student->load(['user', 'coachingClass', 'section']);
        $marks = $student->marks()
            ->with(['subject', 'exam'])
            ->where('exam_id', $exam->id)
            ->get();

        $pdf = Pdf::loadView('pdf.marksheet', [
            'student' => $student,
            'exam' => $exam,
            'marks' => $marks,
            'total' => $marks->sum('marks_obtained'),
            'average' => $marks->isNotEmpty() ? round($marks->avg('marks_obtained'), 2) : 0,
        ]);

        return $pdf->download("marksheet-{$student->admission_no}-{$exam->id}.pdf");
    }
}
