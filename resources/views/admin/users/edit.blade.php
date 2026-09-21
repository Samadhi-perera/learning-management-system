<x-lms-layout title="Edit User - {{ $user->name }}" header="User Management">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.users.index') }}" class="hover:text-indigo-600 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to User Directory
                </a>
                <span>/</span>
                <span class="text-slate-700">Edit User</span>
            </div>
            
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider
                {{ $user->isAdmin() ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}
                {{ $user->isLecturer() ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                {{ $user->isStudent() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}">
                {{ $user->role }}
            </span>
        </div>

        <!-- Main Edit Form Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center gap-4 bg-slate-50/50">
                <div class="w-14 h-14 rounded-2xl bg-indigo-600/10 border border-indigo-500/20 text-indigo-600 flex items-center justify-center font-bold text-xl">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-400">{{ $user->email }} &bull; Member since {{ $user->created_at->format('M Y') }}</p>
                </div>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                @if (isset($errors) && $errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
                        <div class="font-bold">Please correct the following errors:</div>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            University Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Account Role <span class="text-rose-500">*</span>
                        </label>
                        <select name="role" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="lecturer" {{ old('role', $user->role) === 'lecturer' ? 'selected' : '' }}>Lecturer / Faculty</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>System Administrator</option>
                        </select>
                        @error('role') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Identifier / Staff or Student ID -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Student / Staff ID
                        </label>
                        <input type="text" name="identifier" value="{{ old('identifier', $user->identifier) }}" placeholder="e.g. STU2026001 or EMP105"
                            class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('identifier') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Department
                        </label>
                        <select name="department_id" class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">None / General</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }} ({{ $dept->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('department_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Phone Number
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+94 77 123 4567"
                            class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('phone') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                            Account Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Password Reset Section -->
                <div class="pt-6 border-t border-slate-100">
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Reset Password</h3>
                    <p class="text-xs text-slate-400 mb-4">Leave both fields empty if you do not want to change the user's current password.</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                                New Password
                            </label>
                            <input type="password" name="password" autocomplete="new-password" placeholder="Minimum 8 characters"
                                class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                                Confirm New Password
                            </label>
                            <input type="password" name="password_confirmation" autocomplete="new-password" placeholder="Repeat new password"
                                class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        @if($user->id !== auth()->id())
            <div class="bg-rose-50/50 rounded-2xl border border-rose-200/80 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-rose-900">Delete Account</h3>
                    <p class="text-xs text-rose-600/80">Permanently delete this user, their enrollments, submissions, and permissions.</p>
                </div>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete user {{ $user->name }}? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-rose-600 text-white text-xs font-bold rounded-xl hover:bg-rose-700 transition shadow-sm">
                        Delete User
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-lms-layout>
