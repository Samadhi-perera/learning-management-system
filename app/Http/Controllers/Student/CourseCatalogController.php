<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user();

        $query = Course::where('is_active', true)
            ->with(['department.faculty', 'instructor', 'semester'])
            ->withCount('students');

        if ($request->filled('faculty_id')) {
            $query->whereHas('department', function ($q) use ($request) {
                $q->where('faculty_id', $request->faculty_id);
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $courses = $query->latest()->paginate(9)->withQueryString();
        $faculties = Faculty::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        $enrolledCourseIds = $student->enrolledCourses()->pluck('courses.id')->toArray();

        return view('student.catalog.index', compact('courses', 'faculties', 'departments', 'enrolledCourseIds'));
    }

    public function enroll(Request $request, Course $course): RedirectResponse
    {
        $student = $request->user();

        if ($student->enrolledCourses()->where('courses.id', $course->id)->exists()) {
            return back()->with('info', 'You are already enrolled in this course.');
        }

        $student->enrolledCourses()->attach($course->id, [
            'enrolled_at' => now(),
            'status' => 'enrolled',
        ]);

        return redirect()->route('student.courses.show', $course)
            ->with('success', "Enrolled in {$course->code} - {$course->title} successfully!");
    }

    public function drop(Request $request, Course $course): RedirectResponse
    {
        $student = $request->user();
        $student->enrolledCourses()->detach($course->id);

        return back()->with('success', "You have dropped {$course->code}.");
    }
}
