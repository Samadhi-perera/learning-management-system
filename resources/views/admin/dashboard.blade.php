<x-lms-layout title="Admin Dashboard" header="University System Overview">
    <div class="space-y-6">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Students -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Students</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_students'] }}</h3>
                </div>
            </div>

            <!-- Lecturers -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Faculty / Lecturers</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_lecturers'] }}</h3>
                </div>
            </div>

            <!-- Courses -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Active Courses</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['active_courses'] }} / {{ $stats['total_courses'] }}</h3>
                </div>
            </div>

            <!-- Enrollments -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Course Enrollments</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_enrollments'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Quick Action Bar -->
        <div class="flex flex-wrap items-center gap-3 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2">Quick Actions:</span>
            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Course
            </a>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold hover:bg-slate-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Add Student / Lecturer
            </a>
            <a href="{{ route('admin.departments.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold hover:bg-slate-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                Manage Departments
            </a>
        </div>

        <!-- Tables Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Courses -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-base">Recently Added Courses</h3>
                    <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View All &rarr;</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentCourses as $course)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/80 transition">
                            <div>
                                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold bg-indigo-50 text-indigo-700 mb-1">
                                    {{ $course->code }}
                                </span>
                                <h4 class="font-semibold text-sm text-slate-800">{{ $course->title }}</h4>
                                <p class="text-xs text-slate-400">
                                    Dept: {{ $course->department->name ?? 'N/A' }} • Lecturer: {{ $course->instructor->name ?? 'Unassigned' }}
                                </p>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $course->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $course->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    @empty
                        <p class="p-6 text-center text-sm text-slate-400">No courses created yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Enrollments -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-base">Recent Student Enrollments</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentEnrollments as $enrollment)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/80 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-xs">
                                    {{ substr($enrollment->student->name ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-sm text-slate-800">{{ $enrollment->student->name ?? 'Unknown Student' }}</h4>
                                    <p class="text-xs text-slate-400">
                                        Enrolled in <span class="font-medium text-slate-600">{{ $enrollment->course->code ?? '' }}</span> - {{ $enrollment->course->title ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <span class="text-xs text-slate-400">{{ $enrollment->enrolled_at ? $enrollment->enrolled_at->diffForHumans() : '' }}</span>
                        </div>
                    @empty
                        <p class="p-6 text-center text-sm text-slate-400">No enrollments recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-lms-layout>
