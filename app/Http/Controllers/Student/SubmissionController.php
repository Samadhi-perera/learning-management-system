<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function show(Request $request, Assignment $assignment): View
    {
        $student = $request->user();

        // Check if student is enrolled in course
        if (! $student->enrolledCourses()->where('courses.id', $assignment->course_id)->exists() && ! $student->isAdmin()) {
            abort(403, 'You are not enrolled in this course.');
        }

        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        $assignment->load(['course.instructor']);

        return view('student.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment): RedirectResponse
    {
        $student = $request->user();

        $validated = $request->validate([
            'comments' => 'nullable|string',
            'file' => 'required|file|max:25600', // 25MB max
        ]);

        $filePath = $request->file('file')->store('submissions', 'public');

        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
            ],
            [
                'file_path' => $filePath,
                'comments' => $validated['comments'] ?? null,
                'submitted_at' => now(),
            ]
        );

        return back()->with('success', 'Assignment submitted successfully!');
    }
}
