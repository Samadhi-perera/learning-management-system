<x-lms-layout title="User Directory" header="User & Role Management">
    <div class="space-y-6">
        <!-- Top bar with Filter and Modal trigger -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, ID..." class="rounded-xl border-slate-200 text-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm w-64">
                <select name="role" class="rounded-xl border-slate-200 text-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    <option value="">All Roles</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Students</option>
                    <option value="lecturer" {{ request('role') == 'lecturer' ? 'selected' : '' }}>Lecturers</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-sm font-semibold hover:bg-slate-700 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-slate-500 hover:text-slate-700 underline">Clear</a>
                @endif
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Table -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50/80 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4">Role</th>
                                <th class="px-6 py-4">Identifier / Department</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-bold rounded-full uppercase tracking-wider
                                            {{ $user->isAdmin() ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}
                                            {{ $user->isLecturer() ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                            {{ $user->isStudent() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-700">{{ $user->identifier ?? 'None' }}</div>
                                        <div class="text-xs text-slate-400">{{ $user->department->name ?? 'No Dept' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-900 transition">
                                            Edit
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs font-semibold {{ $user->status === 'active' ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }}">
                                                    {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">Current User</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

            <!-- Create User Form -->
            <div>
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm sticky top-6">
                    <h3 class="font-bold text-slate-800 text-base mb-4">Add University Account</h3>
                    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
                            <input type="text" name="name" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">University Email</label>
                            <input type="email" name="email" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Account Role</label>
                            <select name="role" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="student">Student</option>
                                <option value="lecturer">Lecturer / Faculty</option>
                                <option value="admin">System Administrator</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Student / Staff ID</label>
                            <input type="text" name="identifier" placeholder="e.g. STU2026001 or EMP105" class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Department</label>
                            <select name="department_id" class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">None / General</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Temporary Password</label>
                            <input type="password" name="password" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                            Create Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-lms-layout>
