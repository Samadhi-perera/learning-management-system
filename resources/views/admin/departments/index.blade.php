<x-lms-layout title="Manage Departments" header="Academic Departments">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Department List -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base">Departments Overview</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($departments as $dept)
                        <div class="p-5 hover:bg-slate-50/60 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-purple-50 text-purple-700">
                                        {{ $dept->code }}
                                    </span>
                                    <h4 class="font-bold text-slate-800 text-base">{{ $dept->name }}</h4>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">
                                    Faculty: <span class="font-medium text-slate-700">{{ $dept->faculty->name ?? 'N/A' }}</span>
                                    • {{ $dept->courses_count }} Courses Offered
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" onsubmit="return confirm('Deleting this department will delete associated courses!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-slate-400 text-sm">No departments created yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Add Department Form -->
        <div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm sticky top-6">
                <h3 class="font-bold text-slate-800 text-base mb-4">Add Department</h3>
                <form action="{{ route('admin.departments.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Parent Faculty</label>
                        <select name="faculty_id" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Faculty</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }} ({{ $faculty->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Department Name</label>
                        <input type="text" name="name" placeholder="e.g. Software Engineering" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Department Code</label>
                        <input type="text" name="code" placeholder="e.g. SE" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Description</label>
                        <textarea name="description" rows="3" placeholder="Department overview..." class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                        Create Department
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-lms-layout>
