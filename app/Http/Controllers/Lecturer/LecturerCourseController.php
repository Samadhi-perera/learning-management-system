<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LecturerCourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = Course::where('instructor_id', $request->user()->id)
            ->with(['department', 'semester'])
            ->withCount(['students', 'assignments'])
            ->latest()
            ->get();

        return view('lecturer.courses.index', compact('courses'));
    }

    public function show(Request $request, Course $course): View|RedirectResponse
    {
        // Ensure this lecturer owns the course or is admin
        if ($course->instructor_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this course.');
        }

        $course->load([
            'department.faculty',
            'semester',
            'sections.materials',
            'assignments.submissions',
            'announcements.author',
            'students',
        ]);

        return view('lecturer.courses.show', compact('course'));
    }

    public function addSection(Request $request, Course $course): RedirectResponse
    {
        if ($course->instructor_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $maxOrder = $course->sections()->max('order') ?? 0;
        $course->sections()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Course section added.');
    }

    public function updateSection(Request $request, CourseSection $section): RedirectResponse
    {
        $course = $section->course;
        if ($course->instructor_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $section->update($validated);

        return back()->with('success', 'Course module updated successfully.');
    }

    public function deleteSection(CourseSection $section): RedirectResponse
    {
        $section->delete();
        return back()->with('success', 'Section deleted.');
    }

    public function addMaterial(Request $request, CourseSection $section): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:document,pdf,link,text,video',
            'external_url' => 'nullable|url',
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:20480', // 20MB max
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('course_materials', 'public');
        }

        $maxOrder = $section->materials()->max('order') ?? 0;
        $section->materials()->create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'file_path' => $filePath,
            'external_url' => $validated['external_url'] ?? null,
            'content' => $validated['content'] ?? null,
            'order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Learning material added to section.');
    }

    public function deleteMaterial(CourseMaterial $material): RedirectResponse
    {
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();
        return back()->with('success', 'Material removed.');
    }

    public function postAnnouncement(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_pinned' => 'boolean',
        ]);

        $course->announcements()->create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'is_pinned' => $request->has('is_pinned'),
        ]);

        return back()->with('success', 'Course announcement posted.');
    }
}
