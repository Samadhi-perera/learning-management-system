<x-lms-layout title="Manage Courses" header="Course Management">
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Filter & Search -->
            <form method="GET" action="{{ route('admin.courses.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by code or title..." class="rounded-xl border-slate-200 text-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm w-64">
                <select name="department_id" class="rounded-xl border-slate-200 text-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }} ({{ $dept->code }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-sm font-semibold hover:bg-slate-700 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'department_id']))
                    <a href="{{ route('admin.courses.index') }}" class="text-xs text-slate-500 hover:text-slate-700 underline">Clear</a>
                @endif
            </form>

            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition shadow-sm self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create New Course
            </a>
        </div>

        <!-- Course Cards Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Course</th>
                            <th class="px-6 py-4">Department & Faculty</th>
                            <th class="px-6 py-4">Instructor</th>
                            <th class="px-6 py-4">Credits</th>
                            <th class="px-6 py-4">Students</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($courses as $course)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold bg-indigo-50 text-indigo-700 mb-1">
                                        {{ $course->code }}
                                    </span>
                                    <a href="{{ route('lecturer.courses.show', $course) }}" class="font-semibold text-slate-800 hover:text-indigo-600 block transition">
                                        {{ $course->title }} &rarr;
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-700">{{ $course->department->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-400">{{ $course->department->faculty->name ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($course->instructor)
                                        <div class="font-medium text-slate-700">{{ $course->instructor->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $course->instructor->email }}</div>
                                    @else
                                        <span class="text-xs text-amber-600 font-medium">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium">{{ $course->credits }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                        {{ $course->students_count }} enrolled
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $course->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $course->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('lecturer.courses.show', $course) }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg border border-indigo-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        Modules
                                    </a>
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-800">Edit</a>
                                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this course?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    No courses found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($courses->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-lms-layout>
