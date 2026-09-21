<x-lms-layout title="Create Assignment" header="Create Assignment for {{ $course->code }}">
    <div class="max-w-2xl">
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
            <form action="{{ route('lecturer.assignments.store', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Assignment Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Lab Project 1: Relational Schema Design" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Detailed Instructions / Description</label>
                    <textarea name="description" rows="5" placeholder="Specify grading rubric, requirements, report format..." class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Max Score (Points)</label>
                        <input type="number" name="max_score" value="{{ old('max_score', 100) }}" min="1" max="1000" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Due Date & Time</label>
                        <input type="datetime-local" name="due_date" value="{{ old('due_date') }}" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Attach Guideline File / Specification PDF (Optional)</label>
                    <input type="file" name="attachment" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                        Publish Assignment
                    </button>
                    <a href="{{ route('lecturer.courses.show', $course) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-lms-layout>
