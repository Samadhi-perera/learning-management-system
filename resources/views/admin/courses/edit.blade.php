<x-lms-layout title="Edit Course" header="Edit Course: {{ $course->code }}">
    <div class="max-w-3xl">
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
            <form action="{{ route('admin.courses.update', $course) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Course Code</label>
                        <input type="text" name="code" value="{{ old('code', $course->code) }}" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Course Title</label>
                        <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Department</label>
                        <select name="department_id" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $course->department_id) == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }} ({{ $dept->faculty->name ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Assigned Lecturer</label>
                        <select name="instructor_id" class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Unassigned</option>
                            @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}" {{ old('instructor_id', $course->instructor_id) == $lecturer->id ? 'selected' : '' }}>
                                    {{ $lecturer->name }} ({{ $lecturer->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Semester / Term</label>
                        <select name="semester_id" class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Semester</option>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem->id }}" {{ old('semester_id', $course->semester_id) == $sem->id ? 'selected' : '' }}>
                                    {{ $sem->name }} ({{ $sem->academic_year }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Credits</label>
                        <input type="number" name="credits" value="{{ old('credits', $course->credits) }}" min="1" max="10" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Course Description & Objectives</label>
                    <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $course->description) }}</textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_active" class="text-sm font-medium text-slate-700">Course is Active & Available for Enrollment</label>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                        Update Course
                    </button>
                    <a href="{{ route('admin.courses.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-lms-layout>
