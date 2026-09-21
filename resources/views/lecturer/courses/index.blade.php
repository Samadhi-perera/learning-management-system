<x-lms-layout title="My Courses" header="My Teaching Courses">
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
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
                        <p class="text-xs text-slate-500 mb-4">{{ $course->department->name ?? 'Department' }}</p>

                        <div class="grid grid-cols-2 gap-2 text-xs text-slate-600 bg-slate-50 p-3 rounded-xl mb-4">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Students</span>
                                <span class="font-bold text-slate-800 text-sm">{{ $course->students_count }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Assignments</span>
                                <span class="font-bold text-slate-800 text-sm">{{ $course->assignments_count }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('lecturer.courses.show', $course) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                            Course Content & Builder &rarr;
                        </a>
                        <a href="{{ route('lecturer.assignments.create', $course) }}" class="text-xs font-medium text-slate-600 hover:text-slate-900">
                            + Assignment
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white p-12 rounded-2xl border border-slate-200 text-center text-slate-400">
                    No courses currently assigned to you.
                </div>
            @endforelse
        </div>
    </div>
</x-lms-layout>
