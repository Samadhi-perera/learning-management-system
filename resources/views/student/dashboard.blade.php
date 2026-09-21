<x-lms-layout title="Student Portal" header="Student Dashboard">
    <div class="space-y-6">
        <!-- Student Greeting Banner -->
        <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-indigo-200 backdrop-blur mb-2">
                    {{ auth()->user()->identifier ?? 'Student ID: STU2026' }} • Active Semester
                </span>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight mb-2">
                    Welcome back, {{ auth()->user()->name }}!
                </h2>
                <p class="text-xs sm:text-sm text-indigo-200 max-w-xl">
                    You are enrolled in {{ $enrolledCourses->count() }} courses this semester. Keep track of your lecture materials and upcoming assignment deadlines below.
                </p>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Enrolled Courses</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $enrolledCourses->count() }}</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Due Assignments</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $upcomingAssignments->count() }}</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Graded Tasks</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $recentGrades->count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Enrolled Courses Section -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800 text-lg">My Enrolled Courses</h3>
                <a href="{{ route('student.catalog.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                    + Explore Course Catalog &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($enrolledCourses as $course)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-indigo-50 text-indigo-700">
                                    {{ $course->code }}
                                </span>
                                <span class="text-xs font-semibold text-slate-400">
                                    {{ $course->credits }} Credits
                                </span>
                            </div>
                            <h4 class="font-bold text-slate-900 text-base mb-1">{{ $course->title }}</h4>
                            <p class="text-xs text-slate-500 mb-4">
                                Instructor: <span class="font-medium text-slate-700">{{ $course->instructor->name ?? 'Faculty Staff' }}</span>
                            </p>

                            <div class="flex items-center gap-4 text-xs text-slate-400 border-t border-slate-100 pt-3">
                                <span>{{ $course->sections_count }} Learning Modules</span>
                                <span>•</span>
                                <span>{{ $course->assignments_count }} Assignments</span>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 border-t border-slate-100">
                            <a href="{{ route('student.courses.show', $course) }}" class="w-full inline-flex items-center justify-center py-2 px-4 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">
                                Enter Classroom &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-12 rounded-2xl border border-slate-200 text-center">
                        <h4 class="font-bold text-slate-700 mb-1">You are not enrolled in any courses yet</h4>
                        <p class="text-xs text-slate-500 mb-4">Browse our university courses catalog to register and start learning.</p>
                        <a href="{{ route('student.catalog.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition">
                            Browse Course Catalog
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Deadlines & Grades Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Upcoming Deadlines -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base">Upcoming Deadlines</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($upcomingAssignments as $assignment)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/60 transition">
                            <div>
                                <span class="text-[11px] font-bold text-indigo-600 uppercase">{{ $assignment->course->code }}</span>
                                <h4 class="font-semibold text-sm text-slate-800">{{ $assignment->title }}</h4>
                                <span class="text-xs text-rose-600 font-semibold block">Due: {{ $assignment->due_date->format('M d, Y H:i') }}</span>
                            </div>
                            <a href="{{ route('student.assignments.show', $assignment) }}" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">
                                Submit Work
                            </a>
                        </div>
                    @empty
                        <p class="p-6 text-center text-slate-400 text-sm">No upcoming deadlines.</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Grades & Feedback -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base">Recent Grades & Feedback</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentGrades as $grade)
                        <div class="p-4 hover:bg-slate-50/60 transition">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-semibold text-sm text-slate-800">{{ $grade->assignment->title }}</h4>
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $grade->score }} / {{ $grade->assignment->max_score }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mb-2">{{ $grade->assignment->course->code }} • Graded {{ $grade->graded_at->diffForHumans() }}</p>
                            @if($grade->feedback)
                                <div class="text-xs bg-slate-50 p-2.5 rounded-lg text-slate-600 border border-slate-100 italic">
                                    "{{ $grade->feedback }}"
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="p-6 text-center text-slate-400 text-sm">No graded assignments yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Announcements Feed -->
        @if($announcements->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base">Course Announcements</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($announcements as $announcement)
                        <div class="p-5 hover:bg-slate-50/60 transition">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-indigo-50 text-indigo-700">
                                    {{ $announcement->course->code ?? 'NOTICE' }}
                                </span>
                                @if($announcement->is_pinned)
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-amber-50 text-amber-700">
                                        PINNED
                                    </span>
                                @endif
                                <span class="text-xs text-slate-400">• {{ $announcement->created_at->diffForHumans() }}</span>
                            </div>
                            <h4 class="font-bold text-sm text-slate-900 mb-1">{{ $announcement->title }}</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $announcement->content }}</p>
                            <span class="text-[11px] text-slate-400 mt-2 block">Posted by {{ $announcement->author->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-lms-layout>
