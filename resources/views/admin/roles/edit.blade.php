<x-lms-layout title="Configure Permissions" header="Permissions Matrix: {{ $role->name }}">
    <div class="max-w-5xl space-y-6">
        <div>
            <a href="{{ route('admin.roles.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                &larr; Back to Roles Directory
            </a>
        </div>

        <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Role Details Header -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Role Name</label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Role Description</label>
                    <input type="text" name="description" value="{{ old('description', $role->description) }}" class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <!-- Permission Groups Matrix -->
            <div class="space-y-6">
                @foreach($permissionGroups as $group => $permissions)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                        <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                            <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                {{ $group }} Module
                            </h4>
                            <span class="text-xs text-slate-400 font-medium">
                                {{ $permissions->count() }} Privileges Available
                            </span>
                        </div>

                        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($permissions as $permission)
                                @php
                                    $isChecked = in_array($permission->id, $activePermissionIds, true);
                                @endphp
                                <label class="relative flex items-start p-3.5 rounded-xl border {{ $isChecked ? 'border-indigo-200 bg-indigo-50/30' : 'border-slate-200/80 bg-white hover:bg-slate-50/80' }} cursor-pointer transition">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ $isChecked ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-3 text-xs">
                                        <span class="font-bold text-slate-800 block">{{ $permission->name }}</span>
                                        <span class="text-[11px] font-mono text-slate-400 block mb-0.5">{{ $permission->slug }}</span>
                                        @if($permission->description)
                                            <span class="text-[11px] text-slate-500 leading-tight block">{{ $permission->description }}</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Floating Save Actions -->
            <div class="sticky bottom-6 bg-slate-900/90 backdrop-blur text-white p-4 rounded-2xl shadow-xl flex items-center justify-between z-20 border border-slate-800">
                <div class="text-xs text-slate-300">
                    Editing privileges for <strong class="text-white">{{ $role->name }}</strong> (Slug: <code class="text-indigo-400 font-mono">{{ $role->slug }}</code>)
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-300 hover:text-white transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition shadow-md">
                        Save Permission Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-lms-layout>
