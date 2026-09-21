<x-lms-layout title="Manage Faculties" header="University Faculties">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Faculty List -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-base">Faculties Overview</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($faculties as $faculty)
                        <div class="p-5 hover:bg-slate-50/60 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-indigo-50 text-indigo-700">
                                        {{ $faculty->code }}
                                    </span>
                                    <h4 class="font-bold text-slate-800 text-base">{{ $faculty->name }}</h4>
                                </div>
                                @if($faculty->description)
                                    <p class="text-xs text-slate-500 mt-1">{{ $faculty->description }}</p>
                                @endif
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-400">
                                        {{ $faculty->departments_count }} Departments:
                                    </span>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($faculty->departments as $dept)
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700">
                                                {{ $dept->name }} ({{ $dept->code }})
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.faculties.destroy', $faculty) }}" method="POST" onsubmit="return confirm('Deleting this faculty will also remove its departments!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-slate-400 text-sm">No faculties added yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Add Faculty Form -->
        <div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm sticky top-6">
                <h3 class="font-bold text-slate-800 text-base mb-4">Add New Faculty</h3>
                <form action="{{ route('admin.faculties.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Faculty Name</label>
                        <input type="text" name="name" placeholder="e.g. Faculty of Computing" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Faculty Code</label>
                        <input type="text" name="code" placeholder="e.g. FOC" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Description</label>
                        <textarea name="description" rows="3" placeholder="Brief description..." class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                        Create Faculty
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-lms-layout>
