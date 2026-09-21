<x-lms-layout title="Grading Center" header="Grading Center">
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-base">Assignments Requiring Evaluation</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Assignment</th>
                            <th class="px-6 py-4">Course</th>
                            <th class="px-6 py-4">Due Date</th>
                            <th class="px-6 py-4">Submissions Received</th>
                            <th class="px-6 py-4">Pending Grading</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($assignments as $assignment)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $assignment->title }}</div>
                                    <div class="text-xs text-slate-400">Max Score: {{ $assignment->max_score }} pts</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2.5 py-0.5 rounded text-xs font-bold bg-indigo-50 text-indigo-700">
                                        {{ $assignment->course->code }}
                                    </span>
                                    <div class="text-xs text-slate-600 mt-0.5">{{ $assignment->course->title }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium">
                                    {{ $assignment->due_date->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $assignment->submissions->count() }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($assignment->ungraded_count > 0)
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ $assignment->ungraded_count }} Ungraded
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700">
                                            All Graded
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('lecturer.assignments.show', $assignment) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">
                                        Open Submissions &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">
                                    No assignments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($assignments->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $assignments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-lms-layout>
