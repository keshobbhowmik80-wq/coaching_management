<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Grade;
use App\Support\InertiaPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MarkController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Marks', [
            'marks' => InertiaPagination::format(
                Mark::with(['exam', 'subject', 'student.user', 'teacher.user'])->latest()->paginate(10)->withQueryString()
            ),
            'exams' => Exam::orderByDesc('id')->get(['id', 'name']),
            'subjects' => Subject::orderBy('name')->get(['id', 'name', 'full_marks']),
            'students' => Student::with('user:id,name')->orderBy('admission_no')->get(['id', 'user_id', 'admission_no']),
            'teachers' => Teacher::with('user:id,name')->orderBy('employee_id')->get(['id', 'user_id', 'employee_id']),
        ]);
    }

    public function entry(Request $request): Response
    {
        $examId = $request->input('exam_id');
        $subjectId = $request->input('subject_id');

        $exams = Exam::orderByDesc('id')->get(['id', 'name', 'class_id']);
        $subjects = collect();
        $students = collect();

        if ($examId) {
            $subjects = Subject::whereHas('marks', fn($q) => $q->where('exam_id', $examId))
                ->get(['id', 'name', 'full_marks', 'pass_marks']);
        }

        if ($examId && $subjectId) {
            $students = Mark::with('student.user')
                ->where('exam_id', $examId)
                ->where('subject_id', $subjectId)
                ->where('status', 'present')
                ->get()
                ->map(fn($mark) => [
                    'mark_id' => $mark->id,
                    'student_id' => $mark->student_id,
                    'name' => $mark->student->user->name,
                    'admission_no' => $mark->student->admission_no,
                    'marks_obtained' => $mark->marks_obtained,
                    'status' => $mark->status,
                ]);
        }

        return Inertia::render('Admin/Marks/Entry', [
            'exams' => $exams,
            'subjects' => $subjects,
            'students' => $students,
            'filters' => [
                'exam_id' => $examId ? (int) $examId : null,
                'subject_id' => $subjectId ? (int) $subjectId : null,
            ],
        ]);
    }

    public function bulkSave(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'marks' => ['required', 'array'],
            'marks.*.mark_id' => ['required', 'exists:marks,id'],
            'marks.*.marks_obtained' => ['nullable', 'numeric', 'min:0'],
        ]);

        foreach ($validated['marks'] as $entry) {
            Mark::where('id', $entry['mark_id'])
                ->update(['marks_obtained' => $entry['marks_obtained']]);
        }

        return back()->with('success', 'Marks saved.');
    }

    public function marksheet(Request $request): Response
    {
        $examId = $request->input('exam_id');
        $exams = Exam::orderByDesc('id')->get(['id', 'name', 'class_id']);

        $subjects = collect();
        $studentsData = collect();

        if ($examId) {
            $exam = Exam::with('coachingClass')->find($examId);

            // Get unique subjects for this exam from marks
            $subjects = Subject::whereIn('id', function ($query) use ($examId) {
                $query->select('subject_id')->from('marks')->where('exam_id', $examId)->distinct();
            })->get(['id', 'name', 'full_marks', 'pass_marks']);

            // Get all students in the exam's class
            $students = Student::with('user')
                ->where('class_id', $exam->class_id)
                ->orderBy('admission_no')
                ->get();

            // Get all marks for this exam
            $marks = Mark::where('exam_id', $examId)
                ->get()
                ->groupBy(function ($mark) {
                    return $mark->student_id . '-' . $mark->subject_id;
                });

            $grades = Grade::orderBy('min_percent')->get();

            $studentsData = $students->map(function ($student) use ($subjects, $marks, $grades) {
                $row = [
                    'student_id' => $student->id,
                    'name' => $student->user->name,
                    'admission_no' => $student->admission_no,
                    'subjects' => [],
                    'total_obtained' => 0,
                    'total_full' => 0,
                ];

                foreach ($subjects as $subject) {
                    $key = $student->id . '-' . $subject->id;
                    $mark = $marks->get($key);

                    if ($mark && $mark->first()->status === 'absent') {
                        $row['subjects'][$subject->id] = [
                            'marks' => null,
                            'full_marks' => $subject->full_marks,
                            'status' => 'absent',
                            'grade' => 'Absent',
                        ];
                    } elseif ($mark && $mark->first()->marks_obtained !== null) {
                        $obtained = (float) $mark->first()->marks_obtained;
                        $percent = ($obtained / $subject->full_marks) * 100;
                        $grade = Grade::forPercent($percent);
                        $row['subjects'][$subject->id] = [
                            'marks' => $obtained,
                            'full_marks' => $subject->full_marks,
                            'status' => 'present',
                            'grade' => $grade?->grade ?? 'N/A',
                        ];
                        $row['total_obtained'] += $obtained;
                        $row['total_full'] += $subject->full_marks;
                    } else {
                        $row['subjects'][$subject->id] = [
                            'marks' => null,
                            'full_marks' => $subject->full_marks,
                            'status' => 'pending',
                            'grade' => '—',
                        ];
                    }
                }

                if ($row['total_full'] > 0) {
                    $totalPercent = ($row['total_obtained'] / $row['total_full']) * 100;
                    $totalGrade = Grade::forPercent($totalPercent);
                    $row['total_grade'] = $totalGrade?->grade ?? 'N/A';
                } else {
                    $row['total_grade'] = '—';
                }

                return $row;
            });
        }

        return Inertia::render('Admin/Marks/Marksheet', [
            'exams' => $exams,
            'subjects' => $subjects,
            'students' => $studentsData,
            'filters' => ['exam_id' => $examId ? (int) $examId : null],
        ]);
    }

    public function marksheetPdf(Request $request)
    {
        $examId = $request->input('exam_id');
        $exam = Exam::with('coachingClass')->findOrFail($examId);

        $subjects = Subject::whereIn('id', function ($query) use ($examId) {
            $query->select('subject_id')->from('marks')->where('exam_id', $examId)->distinct();
        })->get(['id', 'name', 'full_marks']);

        $students = Student::with('user')
            ->where('class_id', $exam->class_id)
            ->orderBy('admission_no')
            ->get();

        $marks = Mark::where('exam_id', $examId)->get()
            ->groupBy(fn($m) => $m->student_id . '-' . $m->subject_id);

        $grades = Grade::orderBy('min_percent')->get();

        $studentsData = $students->map(function ($student) use ($subjects, $marks, $grades) {
            $row = [
                'name' => $student->user->name,
                'admission_no' => $student->admission_no,
                'subjects' => [],
                'total_obtained' => 0,
                'total_full' => 0,
                'total_grade' => '—',
            ];

            foreach ($subjects as $subject) {
                $key = $student->id . '-' . $subject->id;
                $mark = $marks->get($key);

                if ($mark && $mark->first()->status === 'absent') {
                    $row['subjects'][$subject->id] = ['display' => 'Absent', 'grade' => ''];
                } elseif ($mark && $mark->first()->marks_obtained !== null) {
                    $obtained = (float) $mark->first()->marks_obtained;
                    $percent = ($obtained / $subject->full_marks) * 100;
                    $grade = Grade::forPercent($percent);
                    $row['subjects'][$subject->id] = [
                        'display' => $obtained,
                        'grade' => $grade?->grade ?? '',
                    ];
                    $row['total_obtained'] += $obtained;
                    $row['total_full'] += $subject->full_marks;
                } else {
                    $row['subjects'][$subject->id] = ['display' => '—', 'grade' => ''];
                }
            }

            if ($row['total_full'] > 0) {
                $totalPercent = ($row['total_obtained'] / $row['total_full']) * 100;
                $totalGrade = Grade::forPercent($totalPercent);
                $row['total_grade'] = $totalGrade?->grade ?? '';
            }

            return $row;
        });

        $pdf = Pdf::loadView('pdf.marksheet-bulk', [
            'exam' => $exam,
            'subjects' => $subjects,
            'students' => $studentsData,
            'coachingName' => config('app.name'),
        ]);

        return $pdf->download("marksheet-{$exam->name}.pdf");
    }

    public function store(Request $request): RedirectResponse
    {
        Mark::create($this->validated($request));

        return back()->with('success', 'Mark saved.');
    }

    public function update(Request $request, Mark $mark): RedirectResponse
    {
        $mark->update($this->validated($request, $mark));

        return back()->with('success', 'Mark updated.');
    }

    public function destroy(Mark $mark): RedirectResponse
    {
        $mark->delete();

        return back()->with('success', 'Mark deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Mark $mark = null): array
    {
        return $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'student_id' => [
                'required',
                'exists:students,id',
                Rule::unique('marks')->where('exam_id', $request->integer('exam_id'))->where('subject_id', $request->integer('subject_id'))->ignore($mark),
            ],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'marks_obtained' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ]);
    }
}
