<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LecturerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $lecturer = $request->user();

        $courses = Course::where('instructor_id', $lecturer->id)
            ->with(['department.faculty', 'semester'])
            ->withCount('students')
            ->get();

        $courseIds = $courses->pluck('id');

        $totalStudents = $courses->sum('students_count');

        $pendingGrading = Submission::whereHas('assignment', function ($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })->whereNull('graded_at')->count();

        $recentSubmissions = Submission::with(['assignment.course', 'student'])
            ->whereHas('assignment', function ($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds);
            })
            ->latest('submitted_at')
            ->take(6)
            ->get();

        $upcomingAssignments = Assignment::whereIn('course_id', $courseIds)
            ->with('course')
            ->where('due_date', '>=', now())
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        return view('lecturer.dashboard', compact(
            'courses',
            'totalStudents',
            'pendingGrading',
            'recentSubmissions',
            'upcomingAssignments'
        ));
    }
}
