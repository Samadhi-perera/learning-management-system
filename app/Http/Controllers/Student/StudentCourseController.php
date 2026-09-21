<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentCourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->enrolledCourses()
            ->with(['department.faculty', 'instructor', 'semester'])
            ->withCount(['assignments', 'sections'])
            ->get();

        return view('student.courses.index', compact('courses'));
    }

    public function show(Request $request, Course $course): View
    {
        $student = $request->user();

        // Check enrollment
        $isEnrolled = $student->enrolledCourses()->where('courses.id', $course->id)->exists();
        if (! $isEnrolled && ! $student->isAdmin()) {
            return redirect()->route('student.catalog.index')
                ->with('error', 'You must enroll in this course first to view classroom materials.');
        }

        $course->load([
            'department.faculty',
            'instructor',
            'semester',
            'sections.materials',
            'assignments.submissions' => function ($q) use ($student) {
                $q->where('student_id', $student->id);
            },
            'announcements.author',
        ]);

        return view('student.courses.show', compact('course'));
    }
}
