<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function create(Course $course): View
    {
        return view('lecturer.assignments.create', compact('course'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_score' => 'required|integer|min:1|max:1000',
            'due_date' => 'required|date|after:now',
            'attachment' => 'nullable|file|max:20480',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('assignments', 'public');
        }

        $course->assignments()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'max_score' => $validated['max_score'],
            'due_date' => $validated['due_date'],
            'attachment_path' => $attachmentPath,
        ]);

        return redirect()->route('lecturer.courses.show', $course)->with('success', 'Assignment created.');
    }

    public function show(Assignment $assignment): View
    {
        $assignment->load(['course.students', 'submissions.student', 'submissions.grader']);
        return view('lecturer.assignments.show', compact('assignment'));
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $course = $assignment->course;

        if ($assignment->attachment_path && Storage::disk('public')->exists($assignment->attachment_path)) {
            Storage::disk('public')->delete($assignment->attachment_path);
        }

        $assignment->delete();

        return redirect()->route('lecturer.courses.show', $course)->with('success', 'Assignment deleted.');
    }
}
