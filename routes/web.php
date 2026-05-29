<?php

use App\Http\Controllers\Admin\CoachingClassController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\MarkController as AdminMarkController;
use App\Http\Controllers\Admin\NoticeController as AdminNoticeController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReportCardController;
use App\Http\Controllers\Admin\RoutineController as AdminRoutineController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\RoleRedirectController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\NoticeController as StudentNoticeController;
use App\Http\Controllers\Student\PaymentController as StudentPaymentController;
use App\Http\Controllers\Student\PerformanceController as StudentPerformanceController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\ResultController;
use App\Http\Controllers\Student\RoutineController as StudentRoutineController;
use App\Http\Controllers\Teacher\ClassController as TeacherClassController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ExamController as TeacherExamController;
use App\Http\Controllers\Teacher\MarkController as TeacherMarkController;
use App\Http\Controllers\Teacher\NoticeController as TeacherNoticeController;
use App\Http\Controllers\Teacher\PaymentSummaryController;
use App\Http\Controllers\Teacher\PerformanceController as TeacherPerformanceController;
use App\Http\Controllers\UserSettingController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware('auth')->group(function (): void {
    Route::put('user/settings/theme', [UserSettingController::class, 'updateTheme'])->name('user.settings.theme.update');
});

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('dashboard', RoleRedirectController::class)->name('dashboard');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function (): void {
        Route::get('dashboard', AdminDashboardController::class)->name('dashboard');
        Route::get('/students/create', [AdminStudentController::class, 'create'])->name('admin.students.create');
        Route::get('students/{student}/edit', [AdminStudentController::class, 'edit'])->name('students.edit');
        Route::resource('students', AdminStudentController::class)->only(['index', 'store', 'update', 'destroy']);

        //teachers
        Route::get('teachers/create', [AdminTeacherController::class, 'create'])->name('teachers.create');
        Route::get('teachers/{teacher}/edit', [AdminTeacherController::class, 'edit'])->name('teachers.edit');
        Route::resource('teachers', AdminTeacherController::class)->only(['index', 'store', 'update', 'destroy']);

        //coaching class
        Route::get('classes/create', [CoachingClassController::class, 'create'])->name('classes.create');
        Route::get('classes/{class}/edit', [CoachingClassController::class, 'edit'])->name('classes.edit');
        Route::resource('classes', CoachingClassController::class)->parameters(['classes' => 'class'])->only(['index', 'store', 'update', 'destroy']);

        //section
        Route::get('sections/create', [SectionController::class, 'create'])->name('sections.create');
        Route::get('sections/{section}/edit', [SectionController::class, 'edit'])->name('sections.edit');
        Route::resource('sections', SectionController::class)->only(['index', 'store', 'update', 'destroy']);
        //subject
        Route::get('subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
        Route::get('subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
        Route::resource('subjects', SubjectController::class)->only(['index', 'store', 'update', 'destroy']);
        //exam
        Route::get('exams/create', [AdminExamController::class, 'create'])->name('exams.create');
        Route::get('exams/{exam}/edit', [AdminExamController::class, 'edit'])->name('exams.edit');
        Route::resource('exams', AdminExamController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('marks', AdminMarkController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('payments', AdminPaymentController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('expenses', ExpenseController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('notices', AdminNoticeController::class)->only(['index', 'store', 'update', 'destroy']);
        //routines
        Route::get('routines/create', [AdminRoutineController::class, 'create'])->name('routines.create');
        Route::get('routines/{routine}', [AdminRoutineController::class, 'show'])->name('routines.show');
        Route::get('routines/{routine}/edit', [AdminRoutineController::class, 'edit'])->name('routines.edit');
        Route::resource('routines', AdminRoutineController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('students/{student}/exams/{exam}/marksheet', ReportCardController::class)->name('students.marksheet');
    });

    Route::prefix('teacher')->name('teacher.')->middleware('role:teacher')->group(function (): void {
        Route::get('dashboard', TeacherDashboardController::class)->name('dashboard');
        Route::get('classes', TeacherClassController::class)->name('classes.index');
        Route::resource('marks', TeacherMarkController::class)->only(['index', 'store', 'update']);
        Route::resource('exams', TeacherExamController::class)->only(['index', 'store', 'update']);
        Route::get('performance', TeacherPerformanceController::class)->name('performance.index');
        Route::resource('notices', TeacherNoticeController::class)->only(['index', 'store']);
        Route::get('payments', PaymentSummaryController::class)->name('payments.index');
    });

    Route::prefix('student')->name('student.')->middleware('role:student')->group(function (): void {
        Route::get('dashboard', StudentDashboardController::class)->name('dashboard');
        Route::get('profile', [StudentProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [StudentProfileController::class, 'update'])->name('profile.update');
        Route::get('results', ResultController::class)->name('results.index');
        Route::get('performance', StudentPerformanceController::class)->name('performance.index');
        Route::get('payments', StudentPaymentController::class)->name('payments.index');
        Route::get('notices', StudentNoticeController::class)->name('notices.index');
        Route::get('routine', StudentRoutineController::class)->name('routine.index');
    });
});

require __DIR__ . '/settings.php';
