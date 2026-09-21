<x-lms-layout title="Grade Submission" header="Grade Submission: {{ $submission->student->name }}">
    <div class="max-w-3xl space-y-6">
        <!-- Breadcrumb / Back link -->
        <div>
            <a href="{{ route('lecturer.assignments.show', $submission->assignment_id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                &larr; Back to {{ $submission->assignment->title }}
            </a>
        </div>

        <!-- Student Submission Details -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-900 text-lg">{{ $submission->student->name }}</h3>
                    <p class="text-xs text-slate-400">{{ $submission->student->identifier ?? $submission->student->email }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-500 block font-medium">Submitted on {{ $submission->submitted_at->format('M d, Y \a\t H:i') }}</span>
                    @if($submission->submitted_at->gt($submission->assignment->due_date))
                        <span class="text-[11px] font-bold text-rose-600">Late Submission</span>
                    @else
                        <span class="text-[11px] font-bold text-emerald-600">On Time</span>
                    @endif
                </div>
            </div>

            @if($submission->comments)
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Student Notes / Remarks</label>
                    <div class="bg-slate-50 p-4 rounded-xl text-sm text-slate-700 border border-slate-100">
                        {{ $submission->comments }}
                    </div>
                </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Submitted File Attachment</label>
                @if($submission->file_path)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-indigo-50/50 border border-indigo-100">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-sm font-semibold text-slate-800">Student Submission File</span>
                        </div>
                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="px-4 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">
                            Download & Review &darr;
                        </a>
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic">No file attached.</p>
                @endif
            </div>
        </div>

        <!-- Grade Evaluation Form -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
            <h4 class="font-bold text-slate-900 text-base mb-4">Evaluation & Marks</h4>

            <form action="{{ route('lecturer.grading.store', $submission) }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Awarded Score (Max: {{ $submission->assignment->max_score }} Points)
                    </label>
                    <div class="relative w-48">
                        <input type="number" step="0.1" name="score" value="{{ old('score', $submission->score) }}" min="0" max="{{ $submission->assignment->max_score }}" required class="w-full rounded-xl border-slate-200 text-base font-bold px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Instructor Feedback & Comments
                    </label>
                    <textarea name="feedback" rows="4" placeholder="Provide constructive feedback, strengths, and areas for improvement..." class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">{{ old('feedback', $submission->feedback) }}</textarea>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition shadow-sm">
                        Submit Final Grade
                    </button>
                    <a href="{{ route('lecturer.assignments.show', $submission->assignment_id) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-lms-layout>
