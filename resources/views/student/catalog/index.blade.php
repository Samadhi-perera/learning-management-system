<x-lms-layout title="Course Catalog" header="University Course Catalog">
    <div class="space-y-6">
        <!-- Search & Filter Bar -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <form method="GET" action="{{ route('student.catalog.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="sm:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search courses by title, code or keyword..." class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <select name="faculty_id" class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Faculties</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                        Find Courses
                    </button>
                    @if(request()->hasAny(['search', 'faculty_id', 'department_id']))
                        <a href="{{ route('student.catalog.index') }}" class="px-3 py-2.5 text-xs text-slate-500 hover:text-slate-700 font-medium">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Course Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                @php
                    $isEnrolled = in_array($course->id, $enrolledCourseIds, true);
                @endphp
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
                        <h3 class="font-bold text-slate-900 text-lg mb-1">{{ $course->title }}</h3>
                        <p class="text-xs text-slate-500 mb-2">
                            {{ $course->department->name ?? 'Department' }} • {{ $course->department->faculty->name ?? '' }}
                        </p>
                        <p class="text-xs text-slate-600 line-clamp-3 mb-4">
                            {{ $course->description ?? 'No description provided.' }}
                        </p>

                        <div class="flex items-center gap-2 text-xs text-slate-400 border-t border-slate-100 pt-3">
                            <span>Lecturer: <strong class="text-slate-700 font-medium">{{ $course->instructor->name ?? 'Unassigned' }}</strong></span>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500">{{ $course->students_count }} students enrolled</span>

                        @if($isEnrolled)
                            <a href="{{ route('student.courses.show', $course) }}" class="px-4 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 hover:bg-emerald-100 transition">
                                Enrolled &rarr;
                            </a>
                        @else
                            <form action="{{ route('student.courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-1.5 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">
                                    Enroll in Course
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white p-12 rounded-2xl border border-slate-200 text-center text-slate-400">
                    No active courses found matching your search.
                </div>
            @endforelse
        </div>

        @if($courses->hasPages())
            <div class="p-4">
                {{ $courses->links() }}
            </div>
        @endif
    </div>
</x-lms-layout>
