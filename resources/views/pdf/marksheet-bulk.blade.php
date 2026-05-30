<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Marksheet — {{ $exam->name }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
        }

        .header h2 {
            font-size: 16px;
            margin: 5px 0;
        }

        .header p {
            font-size: 13px;
            color: #555;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: center;
            font-size: 11px;
        }

        th {
            background: #f0f0f0;
            font-weight: 600;
        }

        .absent {
            color: #d00;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $coachingName }}</h1>
        <h2>Marksheet — {{ $exam->name }}</h2>
        <p>Class: {{ $exam->coachingClass?->name }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>ID</th>
                @foreach($subjects as $subject)
                <th>{{ $subject->name }} ({{ $subject->full_marks }})</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student['name'] }}</td>
                <td>{{ $student['admission_no'] }}</td>
                @foreach($subjects as $subject)
                <td>
                    @if($student['subjects'][$subject->id]['display'] === 'Absent')
                    <span class="absent">Absent</span>
                    @else
                    {{ $student['subjects'][$subject->id]['display'] }}
                    @if($student['subjects'][$subject->id]['grade'])
                    <small>({{ $student['subjects'][$subject->id]['grade'] }})</small>
                    @endif
                    @endif
                </td>
                @endforeach
                <td>
                    {{ $student['total_full'] > 0 ? $student['total_obtained'] : '—' }}
                    @if($student['total_grade'] !== '—')
                    <small>({{ $student['total_grade'] }})</small>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>