<x-lms-layout title="Submit Assignment" header="{{ $assignment->course->code }}: {{ $assignment->title }}">
    <div class="max-w-3xl space-y-6">
        <div>
            <a href="{{ route('student.courses.show', $assignment->course_id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                &larr; Back to {{ $assignment->course->title }}
            </a>
        </div>

        <!-- Assignment Details Card -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                <div>
                    <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-indigo-50 text-indigo-700 mb-1 inline-block">
                        {{ $assignment->course->code }}
                    </span>
                    <h2 class="text-2xl font-bold text-slate-900">{{ $assignment->title }}</h2>
                </div>
                <div class="sm:text-right">
                    <span class="text-xs font-bold text-rose-600 block">Due: {{ $assignment->due_date->format('M d, Y H:i') }}</span>
                    <span class="text-xs text-slate-400">Max Score: {{ $assignment->max_score }} Points</span>
                </div>
            </div>

            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Instructions</h4>
                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50/60 p-4 rounded-xl border border-slate-100">
                    {{ $assignment->description ?? 'Follow the instructions provided during lectures.' }}
                </div>
            </div>

            @if($assignment->attachment_path)
                <div class="pt-2">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Attached Specification</h4>
                    <a href="{{ asset('storage/' . $assignment->attachment_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-semibold hover:bg-indigo-100 transition border border-indigo-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download Assignment Specification Document &darr;
                    </a>
                </div>
            @endif
        </div>

        <!-- Grade & Feedback Card (if graded) -->
        @if($submission && $submission->isGraded())
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 p-6 rounded-2xl shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">
                            ✓
                        </div>
                        <h3 class="font-bold text-emerald-950 text-base">Graded by Lecturer</h3>
                    </div>
                    <span class="text-xl font-extrabold text-emerald-700">
                        {{ $submission->score }} / {{ $assignment->max_score }} Points
                    </span>
                </div>

                @if($submission->feedback)
                    <div class="text-xs text-emerald-900 bg-white/80 p-4 rounded-xl border border-emerald-100">
                        <span class="font-bold uppercase text-[10px] text-emerald-700 block mb-1">Feedback Remarks:</span>
                        {{ $submission->feedback }}
                    </div>
                @endif
                <span class="text-[11px] text-emerald-600 block">Evaluation completed on {{ $submission->graded_at->format('M d, Y') }}</span>
            </div>
        @endif

        <!-- Submission Status / Upload Form -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-5">
            <h3 class="font-bold text-slate-900 text-lg">
                {{ $submission ? 'Your Submitted Work' : 'Turn In Your Submission' }}
            </h3>

            @if($submission)
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-slate-800 block">Submitted on {{ $submission->submitted_at->format('M d, Y \a\t H:i') }}</span>
                            @if($submission->submitted_at->gt($assignment->due_date))
                                <span class="text-[11px] font-bold text-rose-600">Late Submission</span>
                            @else
                                <span class="text-[11px] font-bold text-emerald-600">Submitted on time</span>
                            @endif
                        </div>
                        @if($submission->file_path)
                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="px-3.5 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm inline-flex items-center gap-1">
                                Download My Submission &darr;
                            </a>
                        @endif
                    </div>

                    @if($submission->comments)
                        <div class="text-xs text-slate-600 border-t border-slate-200/60 pt-2">
                            <span class="font-semibold text-slate-700">Your comments:</span> {{ $submission->comments }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Submit Form (Allows submitting or resubmitting before deadline) -->
            <div>
                <h4 class="font-semibold text-sm text-slate-800 mb-3">
                    {{ $submission ? 'Re-upload / Update Submission' : 'Upload Submission File' }}
                </h4>
                <form action="{{ route('student.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Select File (PDF, ZIP, DOCX, Code - Max 25MB)
                        </label>
                        <input type="file" name="file" required class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Submission Notes / Comments (Optional)
                        </label>
                        <textarea name="comments" rows="3" placeholder="Add any comments or notes for your lecturer..." class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">{{ old('comments') }}</textarea>
                    </div>

                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                        {{ $submission ? 'Submit Updated Work' : 'Submit Assignment' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-lms-layout>
