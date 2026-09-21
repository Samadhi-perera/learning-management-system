<x-lms-layout title="Roles & Permissions" header="Roles & Permissions (RBAC)">
    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Roles Overview Table -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Defined University Roles</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Control granular access privileges per role across the portal</p>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($roles as $role)
                            <div class="p-5 hover:bg-slate-50/60 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-bold text-slate-900 text-base">{{ $role->name }}</h4>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-slate-100 text-slate-600">
                                            {{ $role->slug }}
                                        </span>
                                        @if($role->is_system)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                System Default
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 mb-2">{{ $role->description ?? 'No description provided.' }}</p>

                                    <div class="flex items-center gap-4 text-xs text-slate-500">
                                        <span class="font-semibold text-slate-700">{{ $role->users_count }} Assigned Users</span>
                                        <span>•</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-emerald-50 text-emerald-700">
                                            {{ $role->permissions->count() }} Active Permissions
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">
                                        Configure Permissions &rarr;
                                    </a>
                                    @if(! $role->is_system)
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Delete this custom role?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="p-8 text-center text-slate-400 text-sm">No roles registered in database.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Create Custom Role Card -->
            <div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm sticky top-6">
                    <h3 class="font-bold text-slate-800 text-base mb-1">Create Custom Role</h3>
                    <p class="text-xs text-slate-400 mb-4">Define specialized academic or administrative roles (e.g. Teaching Assistant, Dean)</p>

                    <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Role Name</label>
                            <input type="text" name="name" placeholder="e.g. Teaching Assistant" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Role Slug (Optional)</label>
                            <input type="text" name="slug" placeholder="e.g. teaching_assistant" class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Description</label>
                            <textarea name="description" rows="3" placeholder="Duties and scope for this role..." class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                            Create Role
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-lms-layout>
