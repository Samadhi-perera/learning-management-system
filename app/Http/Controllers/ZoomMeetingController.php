<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\MeetingAttendance;
use App\Models\ZoomMeeting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ZoomMeetingController extends Controller
{
    /**
     * Store a newly created Zoom meeting for a course.
     */
    public function store(Request $request, Course $course): RedirectResponse
    {
        if ($course->instructor_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403, 'Unauthorized to publish Zoom meetings for this course.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_url' => 'required|url',
            'meeting_id' => 'nullable|string|max:100',
            'passcode' => 'nullable|string|max:50',
            'start_time' => 'required|date',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'status' => 'nullable|in:scheduled,live,completed',
        ]);

        $course->zoomMeetings()->create([
            'instructor_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'meeting_url' => $validated['meeting_url'],
            'meeting_id' => $validated['meeting_id'] ?? null,
            'passcode' => $validated['passcode'] ?? null,
            'start_time' => $validated['start_time'],
            'duration_minutes' => $validated['duration_minutes'],
            'status' => $validated['status'] ?? 'scheduled',
        ]);

        return back()->with('success', 'Zoom meeting scheduled and published successfully.');
    }

    /**
     * Update the status of a Zoom meeting (e.g. mark Live or Completed).
     */
    public function updateStatus(Request $request, ZoomMeeting $meeting): RedirectResponse
    {
        if ($meeting->course->instructor_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:scheduled,live,completed,cancelled',
        ]);

        $meeting->update(['status' => $validated['status']]);

        $statusLabel = match ($validated['status']) {
            'live' => 'marked as LIVE NOW',
            'completed' => 'marked as Completed',
            'cancelled' => 'cancelled',
            default => 'scheduled',
        };

        return back()->with('success', "Zoom session {$statusLabel}.");
    }

    /**
     * Delete a Zoom meeting.
     */
    public function destroy(Request $request, ZoomMeeting $meeting): RedirectResponse
    {
        if ($meeting->course->instructor_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }

        $meeting->delete();

        return back()->with('success', 'Zoom meeting removed.');
    }

    /**
     * Student attends a Zoom meeting: logs attendance and redirects to Zoom URL.
     */
    public function attend(Request $request, ZoomMeeting $meeting): RedirectResponse
    {
        $user = $request->user();
        $isEnrolled = $meeting->course->students()->where('users.id', $user->id)->exists();

        if (! $isEnrolled && ! $user->isAdmin() && $meeting->course->instructor_id !== $user->id) {
            abort(403, 'You must be enrolled in this course to attend this Zoom lecture.');
        }

        // Record attendance if not already recorded
        MeetingAttendance::firstOrCreate(
            [
                'zoom_meeting_id' => $meeting->id,
                'user_id' => $user->id,
            ],
            [
                'joined_at' => now(),
            ]
        );

        return redirect()->away($meeting->meeting_url);
    }

    /**
     * View attendance roster for a meeting.
     */
    public function roster(Request $request, ZoomMeeting $meeting): View
    {
        if ($meeting->course->instructor_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }

        $meeting->load(['course', 'attendances.user.department']);

        return view('lecturer.zoom.roster', compact('meeting'));
    }
}
