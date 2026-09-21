<x-lms-layout title="Assignment Submissions" header="{{ $assignment->course->code }}: {{ $assignment->title }}">
    <div class="space-y-6">
        <!-- Assignment Overview Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-indigo-50 text-indigo-700">
                        {{ $assignment->course->code }}
                    </span>
                    <span class="text-xs font-semibold text-rose-600">
                        Due: {{ $assignment->due_date->format('M d, Y H:i') }} ({{ $assignment->due_date->diffForHumans() }})
                    </span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $assignment->title }}</h3>
                <p class="text-xs text-slate-500 max-w-2xl">{{ $assignment->description ?? 'No extra instructions.' }}</p>
                @if($assignment->attachment_path)
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $assignment->attachment_path) }}" target="_blank" class="text-xs font-semibold text-indigo-600 hover:underline inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download Guideline File
                        </a>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4 text-center">
                <div class="bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Submissions</span>
                    <span class="text-lg font-bold text-slate-800">{{ $assignment->submissions->count() }} / {{ $assignment->course->students->count() }}</span>
                </div>
                <div class="bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Max Score</span>
                    <span class="text-lg font-bold text-indigo-600">{{ $assignment->max_score }} pts</span>
                </div>
            </div>
        </div>

        <!-- Student Submissions Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h4 class="font-bold text-slate-800 text-base">Student Submissions</h4>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Submitted At</th>
                            <th class="px-6 py-4">Attached Work</th>
                            <th class="px-6 py-4">Grade / Feedback</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($assignment->submissions as $submission)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $submission->student->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $submission->student->identifier ?? $submission->student->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-slate-700 font-medium block">{{ $submission->submitted_at->format('M d, Y H:i') }}</span>
                                    @if($submission->submitted_at->gt($assignment->due_date))
                                        <span class="text-[10px] font-bold text-rose-600">Late Submission</span>
                                    @else
                                        <span class="text-[10px] font-bold text-emerald-600">On Time</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($submission->file_path)
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:underline">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Download File
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">No file</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($submission->isGraded())
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700">
                                            {{ $submission->score }} / {{ $assignment->max_score }}
                                        </span>
                                        @if($submission->feedback)
                                            <p class="text-[11px] text-slate-500 mt-1 italic">"{{ Str::limit($submission->feedback, 30) }}"</p>
                                        @endif
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700">
                                            Needs Grading
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('lecturer.grading.show', $submission) }}" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">
                                        {{ $submission->isGraded() ? 'Edit Grade' : 'Grade' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">
                                    No student submissions have been turned in yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-lms-layout>
