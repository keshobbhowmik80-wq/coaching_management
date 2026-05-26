<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Marksheet</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 12px; }
        h1 { font-size: 22px; margin-bottom: 4px; }
        h2 { font-size: 16px; margin-top: 0; color: #374151; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
        .grid { width: 100%; margin-top: 14px; }
        .grid td { border: 0; padding: 3px 0; }
        .summary { margin-top: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Coaching Management System</h1>
    <h2>{{ $exam->name }} Marksheet</h2>

    <table class="grid">
        <tr><td>Student</td><td>{{ $student->user->name }}</td></tr>
        <tr><td>Admission No</td><td>{{ $student->admission_no }}</td></tr>
        <tr><td>Class</td><td>{{ $student->coachingClass?->name ?? 'Unassigned' }}</td></tr>
        <tr><td>Section</td><td>{{ $student->section?->name ?? 'Unassigned' }}</td></tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Full Marks</th>
                <th>Pass Marks</th>
                <th>Marks Obtained</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($marks as $mark)
                <tr>
                    <td>{{ $mark->subject->name }}</td>
                    <td>{{ $mark->subject->full_marks }}</td>
                    <td>{{ $mark->subject->pass_marks }}</td>
                    <td>{{ $mark->marks_obtained }}</td>
                    <td>{{ $mark->remarks }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No marks recorded for this exam.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="summary">Total: {{ $total }} | Average: {{ $average }}</p>
</body>
</html>
