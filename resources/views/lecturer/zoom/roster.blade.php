<x-lms-layout title="Zoom Attendance Roster - {{ $meeting->title }}" header="Virtual Classroom Attendance">
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Breadcrumb & Nav -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('lecturer.courses.show', $meeting->course) }}" class="hover:text-indigo-600 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to {{ $meeting->course->code }}
                </a>
                <span>/</span>
                <span class="text-slate-700">Zoom Attendance Roster</span>
            </div>

            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider
                {{ $meeting->isLive() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 animate-pulse' : '' }}
                {{ $meeting->isUpcoming() ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : '' }}
                {{ $meeting->isCompleted() ? 'bg-slate-100 text-slate-700 border border-slate-200' : '' }}">
                {{ $meeting->status }}
            </span>
        </div>

        <!-- Meeting Summary Header -->
        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white p-6 sm:p-8 rounded-3xl shadow-lg border border-slate-800">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="px-3 py-1 rounded-lg text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        {{ $meeting->course->code }} &bull; Virtual Session
                    </span>
                    <h2 class="text-2xl font-bold tracking-tight mt-2">{{ $meeting->title }}</h2>
                    <p class="text-xs text-slate-300 mt-1 max-w-2xl">{{ $meeting->description ?? 'Live Zoom lecture for enrolled students.' }}</p>
                </div>
                
                <div class="bg-white/10 backdrop-blur p-4 rounded-2xl border border-white/10 text-xs space-y-1">
                    <div><span class="text-slate-400">Scheduled:</span> <span class="font-semibold text-white">{{ $meeting->start_time->format('M d, Y h:i A') }}</span></div>
                    <div><span class="text-slate-400">Duration:</span> <span class="font-semibold text-white">{{ $meeting->duration_minutes }} Minutes</span></div>
                    @if($meeting->meeting_id)
                        <div><span class="text-slate-400">Meeting ID:</span> <span class="font-mono text-white">{{ $meeting->meeting_id }}</span></div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Attendance Stats Cards -->
        @php
            $totalEnrolled = $meeting->course->students->count();
            $totalAttended = $meeting->attendances->count();
            $attendanceRate = $totalEnrolled > 0 ? round(($totalAttended / $totalEnrolled) * 100) : 0;
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Total Enrolled</span>
                <span class="text-2xl font-bold text-slate-800">{{ $totalEnrolled }} Students</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Attended Session</span>
                <span class="text-2xl font-bold text-emerald-600">{{ $totalAttended }} Students</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Turnout Rate</span>
                <span class="text-2xl font-bold text-indigo-600">{{ $attendanceRate }}%</span>
            </div>
        </div>

        <!-- Attendee Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Verified Student Attendees</h3>
                    <p class="text-xs text-slate-400">Students who attended and joined through the LMS Zoom portal.</p>
                </div>
                <button onclick="window.print()" class="px-3 py-1.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Roster
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Student Name</th>
                            <th class="px-6 py-4">Student ID</th>
                            <th class="px-6 py-4">Department</th>
                            <th class="px-6 py-4 text-right">Joined Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($meeting->attendances as $attendance)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4 text-xs text-slate-400 font-mono">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $attendance->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $attendance->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-semibold text-slate-700">{{ $attendance->user->identifier ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    {{ $attendance->user->department->name ?? 'General' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ $attendance->joined_at->format('h:i:s A') }} ({{ $attendance->joined_at->format('M d') }})
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">
                                    No students have attended or joined this session yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-lms-layout>
