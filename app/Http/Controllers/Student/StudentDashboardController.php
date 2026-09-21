<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user();

        $enrolledCourses = $student->enrolledCourses()
            ->with(['department.faculty', 'instructor', 'semester'])
            ->withCount(['assignments', 'sections'])
            ->get();

        $courseIds = $enrolledCourses->pluck('id');

        // Upcoming assignments from enrolled courses
        $upcomingAssignments = Assignment::whereIn('course_id', $courseIds)
            ->where('due_date', '>=', now())
            ->with('course')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // Recent graded submissions
        $recentGrades = Submission::where('student_id', $student->id)
            ->whereNotNull('graded_at')
            ->with(['assignment.course', 'grader'])
            ->latest('graded_at')
            ->take(5)
            ->get();

        // Announcements from enrolled courses
        $announcements = Announcement::whereIn('course_id', $courseIds)
            ->with(['course', 'author'])
            ->orderByDesc('is_pinned')
            ->latest()
            ->take(6)
            ->get();

        return view('student.dashboard', compact(
            'enrolledCourses',
            'upcomingAssignments',
            'recentGrades',
            'announcements'
        ));
    }
}
