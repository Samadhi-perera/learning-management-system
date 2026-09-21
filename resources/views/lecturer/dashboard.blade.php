<x-lms-layout title="Lecturer Portal" header="Faculty Dashboard">
    <div class="space-y-6">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">My Courses</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $courses->count() }}</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Enrolled Students</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $totalStudents }}</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending Grading</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $pendingGrading }}</h3>
                </div>
            </div>
        </div>

        <!-- My Courses Grid -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800 text-lg">Assigned Teaching Courses</h3>
                <a href="{{ route('lecturer.courses.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">All Courses &rarr;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($courses as $course)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-indigo-50 text-indigo-700">
                                    {{ $course->code }}
                                </span>
                                <span class="text-xs font-medium text-slate-400">
                                    {{ $course->credits }} Credits
                                </span>
                            </div>
                            <h4 class="font-bold text-slate-900 text-base mb-1">{{ $course->title }}</h4>
                            <p class="text-xs text-slate-500 mb-3">{{ $course->department->name ?? 'Department' }}</p>
                            <div class="text-xs text-slate-400 flex items-center gap-3">
                                <span>{{ $course->students_count }} Students</span>
                                <span>•</span>
                                <span>{{ $course->semester->name ?? 'Term 1' }}</span>
                            </div>
                        </div>
                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('lecturer.courses.show', $course) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                Manage Course Content &rarr;
                            </a>
                            <a href="{{ route('lecturer.assignments.create', $course) }}" class="text-xs font-medium text-slate-600 hover:text-slate-900">
                                + Assignment
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400">
                        No courses currently assigned to you. Contact university administration.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Submissions & Deadlines Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Submissions Needing Review -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-base">Recent Submissions for Grading</h3>
                    <a href="{{ route('lecturer.grading.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Grading Center &rarr;</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentSubmissions as $submission)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/60 transition">
                            <div>
                                <h4 class="font-semibold text-sm text-slate-800">{{ $submission->student->name }}</h4>
                                <p class="text-xs text-slate-500">
                                    {{ $submission->assignment->title }} ({{ $submission->assignment->course->code }})
                                </p>
                                <span class="text-[11px] text-slate-400">Submitted {{ $submission->submitted_at->diffForHumans() }}</span>
                            </div>
                            <div>
                                @if($submission->isGraded())
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700">
                                        Score: {{ $submission->score }}/{{ $submission->assignment->max_score }}
                                    </span>
                                @else
                                    <a href="{{ route('lecturer.grading.show', $submission) }}" class="px-3 py-1.5 rounded-lg bg-amber-500 text-white text-xs font-semibold hover:bg-amber-600 transition shadow-sm">
                                        Grade
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="p-6 text-center text-slate-400 text-sm">No recent student submissions.</p>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base">Upcoming Assignment Due Dates</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($upcomingAssignments as $assignment)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/60 transition">
                            <div>
                                <span class="text-[11px] font-bold text-indigo-600 uppercase">{{ $assignment->course->code }}</span>
                                <h4 class="font-semibold text-sm text-slate-800">{{ $assignment->title }}</h4>
                                <p class="text-xs text-slate-400">Max Score: {{ $assignment->max_score }} points</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-rose-600 block">{{ $assignment->due_date->format('M d, Y H:i') }}</span>
                                <span class="text-[11px] text-slate-400">Due {{ $assignment->due_date->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="p-6 text-center text-slate-400 text-sm">No upcoming deadlines.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-lms-layout>
