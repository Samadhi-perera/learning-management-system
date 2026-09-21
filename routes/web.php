<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CourseManagementController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Lecturer\AssignmentController as LecturerAssignmentController;
use App\Http\Controllers\Lecturer\GradingController;
use App\Http\Controllers\Lecturer\LecturerCourseController;
use App\Http\Controllers\Lecturer\LecturerDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\CourseCatalogController;
use App\Http\Controllers\Student\StudentCourseController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\SubmissionController as StudentSubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Smart dashboard redirection based on role
Route::get('/dashboard', function () {
    $user = auth()->user();
    if (! $user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'lecturer' => redirect()->route('lecturer.dashboard'),
        default => redirect()->route('student.dashboard'),
    };
})->middleware(['auth'])->name('dashboard');

// Common profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -------------------------------------------------------------
// ADMIN PORTAL
// -------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Faculties & Departments
    Route::resource('faculties', FacultyController::class)->except(['create', 'edit', 'show']);
    Route::resource('departments', DepartmentController::class)->except(['create', 'edit', 'show']);

    // Roles & Permissions
    Route::resource('roles', \App\Http\Controllers\Admin\RolePermissionController::class)->except(['create', 'show']);

    // Courses
    Route::resource('courses', CourseManagementController::class);

    // Users
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle', [UserManagementController::class, 'toggleStatus'])->name('users.toggle');
});

// -------------------------------------------------------------
// LECTURER PORTAL
// -------------------------------------------------------------
Route::middleware(['auth', 'role:lecturer,admin'])->prefix('lecturer')->name('lecturer.')->group(function () {
    Route::get('/dashboard', [LecturerDashboardController::class, 'index'])->name('dashboard');

    // My Courses & Course Builder
    Route::get('/courses', [LecturerCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [LecturerCourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/sections', [LecturerCourseController::class, 'addSection'])->name('courses.sections.store');
    Route::put('/sections/{section}', [LecturerCourseController::class, 'updateSection'])->name('sections.update');
    Route::delete('/sections/{section}', [LecturerCourseController::class, 'deleteSection'])->name('sections.destroy');

    // Materials
    Route::post('/sections/{section}/materials', [LecturerCourseController::class, 'addMaterial'])->name('materials.store');
    Route::delete('/materials/{material}', [LecturerCourseController::class, 'deleteMaterial'])->name('materials.destroy');

    // Announcements
    Route::post('/courses/{course}/announcements', [LecturerCourseController::class, 'postAnnouncement'])->name('announcements.store');

    // Assignments & Grading
    Route::get('/courses/{course}/assignments/create', [LecturerAssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/courses/{course}/assignments', [LecturerAssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}', [LecturerAssignmentController::class, 'show'])->name('assignments.show');
    Route::delete('/assignments/{assignment}', [LecturerAssignmentController::class, 'destroy'])->name('assignments.destroy');

    Route::get('/grading', [GradingController::class, 'index'])->name('grading.index');
    Route::get('/submissions/{submission}/grade', [GradingController::class, 'show'])->name('grading.show');
    Route::post('/submissions/{submission}/grade', [GradingController::class, 'grade'])->name('grading.store');

    // Zoom Virtual Classrooms
    Route::post('/courses/{course}/zoom-meetings', [\App\Http\Controllers\ZoomMeetingController::class, 'store'])->name('zoom.store');
    Route::post('/zoom-meetings/{meeting}/status', [\App\Http\Controllers\ZoomMeetingController::class, 'updateStatus'])->name('zoom.status');
    Route::delete('/zoom-meetings/{meeting}', [\App\Http\Controllers\ZoomMeetingController::class, 'destroy'])->name('zoom.destroy');
    Route::get('/zoom-meetings/{meeting}/roster', [\App\Http\Controllers\ZoomMeetingController::class, 'roster'])->name('zoom.roster');
});

// -------------------------------------------------------------
// STUDENT PORTAL
// -------------------------------------------------------------
Route::middleware(['auth', 'role:student,admin'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    // My Enrolled Courses
    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');

    // Course Catalog & Self-Enrollment
    Route::get('/catalog', [CourseCatalogController::class, 'index'])->name('catalog.index');
    Route::post('/courses/{course}/enroll', [CourseCatalogController::class, 'enroll'])->name('courses.enroll');
    Route::delete('/courses/{course}/drop', [CourseCatalogController::class, 'drop'])->name('courses.drop');

    // Assignment Submissions
    Route::get('/assignments/{assignment}', [StudentSubmissionController::class, 'show'])->name('assignments.show');
    Route::post('/assignments/{assignment}/submit', [StudentSubmissionController::class, 'submit'])->name('assignments.submit');

    // Zoom Attendance & Join
    Route::get('/zoom-meetings/{meeting}/attend', [\App\Http\Controllers\ZoomMeetingController::class, 'attend'])->name('zoom.attend');
});

require __DIR__.'/auth.php';
