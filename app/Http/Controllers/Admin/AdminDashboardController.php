<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_students' => User::where('role', User::ROLE_STUDENT)->count(),
            'total_lecturers' => User::where('role', User::ROLE_LECTURER)->count(),
            'total_courses' => Course::count(),
            'active_courses' => Course::where('is_active', true)->count(),
            'total_faculties' => Faculty::count(),
            'total_departments' => Department::count(),
            'total_enrollments' => Enrollment::count(),
        ];

        $recentEnrollments = Enrollment::with(['student', 'course'])
            ->latest('enrolled_at')
            ->take(6)
            ->get();

        $recentCourses = Course::with(['department.faculty', 'instructor'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentEnrollments', 'recentCourses'));
    }
}
