<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::with(['department.faculty', 'instructor', 'semester'])
            ->withCount('students');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $courses = $query->latest()->paginate(12)->withQueryString();
        $departments = Department::orderBy('name')->get();
        $lecturers = User::where('role', User::ROLE_LECTURER)->orderBy('name')->get();
        $semesters = Semester::latest()->get();

        return view('admin.courses.index', compact('courses', 'departments', 'lecturers', 'semesters'));
    }

    public function create(): View
    {
        $departments = Department::with('faculty')->orderBy('name')->get();
        $lecturers = User::where('role', User::ROLE_LECTURER)->orderBy('name')->get();
        $semesters = Semester::latest()->get();

        return view('admin.courses.create', compact('departments', 'lecturers', 'semesters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:courses,code',
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'instructor_id' => 'nullable|exists:users,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'credits' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Course::create($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course): View
    {
        $departments = Department::with('faculty')->orderBy('name')->get();
        $lecturers = User::where('role', User::ROLE_LECTURER)->orderBy('name')->get();
        $semesters = Semester::latest()->get();

        return view('admin.courses.edit', compact('course', 'departments', 'lecturers', 'semesters'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:courses,code,' . $course->id,
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'instructor_id' => 'nullable|exists:users,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'credits' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
