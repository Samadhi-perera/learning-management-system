<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradingController extends Controller
{
    public function index(Request $request): View
    {
        $lecturer = $request->user();

        $assignments = Assignment::whereHas('course', function ($q) use ($lecturer) {
            $q->where('instructor_id', $lecturer->id);
        })
        ->with(['course', 'submissions.student'])
        ->withCount(['submissions as ungraded_count' => function ($q) {
            $q->whereNull('graded_at');
        }])
        ->latest('due_date')
        ->paginate(10);

        return view('lecturer.grading.index', compact('assignments'));
    }

    public function show(Submission $submission): View
    {
        $submission->load(['assignment.course', 'student', 'grader']);
        return view('lecturer.grading.grade', compact('submission'));
    }

    public function grade(Request $request, Submission $submission): RedirectResponse
    {
        $maxScore = $submission->assignment->max_score;

        $validated = $request->validate([
            'score' => "required|numeric|min:0|max:{$maxScore}",
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'],
            'graded_by' => $request->user()->id,
            'graded_at' => now(),
        ]);

        return redirect()->route('lecturer.assignments.show', $submission->assignment_id)
            ->with('success', "Graded submission for {$submission->student->name}.");
    }
}
