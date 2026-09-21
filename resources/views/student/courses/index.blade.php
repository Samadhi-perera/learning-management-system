<x-lms-layout title="My Courses" header="Enrolled Courses">
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
                        <p class="text-xs text-slate-500 mb-2">{{ $course->department->name ?? 'Department' }}</p>
                        <p class="text-xs text-slate-400 mb-4">Instructor: {{ $course->instructor->name ?? 'Faculty' }}</p>

                        <div class="flex items-center gap-4 text-xs text-slate-500 bg-slate-50 p-3 rounded-xl">
                            <div>
                                <span class="font-bold text-slate-800">{{ $course->sections_count }}</span> Modules
                            </div>
                            <div>•</div>
                            <div>
                                <span class="font-bold text-slate-800">{{ $course->assignments_count }}</span> Tasks
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('student.courses.show', $course) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                            Go to Classroom &rarr;
                        </a>
                        <form action="{{ route('student.courses.drop', $course) }}" method="POST" onsubmit="return confirm('Are you sure you want to drop this course?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-slate-400 hover:text-rose-600">
                                Drop
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white p-12 rounded-2xl border border-slate-200 text-center">
                    <h3 class="font-bold text-slate-700 text-base mb-1">No courses enrolled</h3>
                    <p class="text-xs text-slate-500 mb-4">You have not registered for any courses in this academic term.</p>
                    <a href="{{ route('student.catalog.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition">
                        Browse Course Catalog &rarr;
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-lms-layout>
