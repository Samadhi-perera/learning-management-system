<x-lms-layout title="{{ $course->code }} - Course Manager" header="{{ $course->code }}: {{ $course->title }}">
    <div class="space-y-6">
        <!-- Course Header Card -->
        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white p-6 sm:p-8 rounded-3xl shadow-lg border border-slate-800">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                            {{ $course->code }}
                        </span>
                        <span class="text-xs text-slate-300">
                            {{ $course->credits }} Academic Credits
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight mb-2">{{ $course->title }}</h2>
                    <p class="text-sm text-slate-300 max-w-2xl">{{ $course->description ?? 'No course description provided yet.' }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" onclick="document.getElementById('zoom-scheduler-panel').classList.toggle('hidden')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        + Schedule Zoom Meeting
                    </button>
                    <a href="{{ route('lecturer.assignments.create', $course) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow transition">
                        + Create Assignment
                    </a>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-800/80 flex flex-wrap gap-6 text-xs text-slate-400">
                <div>Department: <span class="font-semibold text-slate-200">{{ $course->department->name ?? 'N/A' }}</span></div>
                <div>Faculty: <span class="font-semibold text-slate-200">{{ $course->department->faculty->name ?? 'N/A' }}</span></div>
                <div>Enrolled Students: <span class="font-semibold text-slate-200">{{ $course->students->count() }}</span></div>
                <div>Academic Term: <span class="font-semibold text-slate-200">{{ $course->semester->name ?? 'Default' }}</span></div>
            </div>
        </div>

        <!-- Zoom Meeting Scheduler Form (Expandable) -->
        <div id="zoom-scheduler-panel" class="hidden bg-white p-6 rounded-2xl border-2 border-blue-500/30 shadow-md">
            <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Publish New Zoom Meeting</h4>
                        <p class="text-xs text-slate-400">Enrolled students will see this in their classroom with automated attendance logging.</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('zoom-scheduler-panel').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    &times;
                </button>
            </div>

            <form action="{{ route('lecturer.zoom.store', $course) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Session Topic / Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" placeholder="e.g. Live Lecture 04: Advanced Query Optimization & Indexing" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Date & Start Time <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="start_time" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Duration (Minutes) <span class="text-rose-500">*</span></label>
                        <input type="number" name="duration_minutes" value="60" min="15" max="480" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Zoom Meeting URL <span class="text-rose-500">*</span></label>
                        <input type="url" name="meeting_url" placeholder="https://us05web.zoom.us/j/81234567890?pwd=..." required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Meeting ID (Optional)</label>
                        <input type="text" name="meeting_id" placeholder="812 3456 7890" class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Passcode (Optional)</label>
                        <input type="text" name="passcode" placeholder="e.g. SMD2026" class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Session Overview / Agenda</label>
                        <textarea name="description" rows="2" placeholder="Topics covered, prerequisite readings, or student instructions..." class="w-full rounded-xl border-slate-200 text-sm px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('zoom-scheduler-panel').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition shadow-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Publish Zoom Session
                    </button>
                </div>
            </form>
        </div>

        <!-- Course Sections & Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Area: Zoom Meetings & Curriculum Sections -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Live Zoom Sessions Section -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-5 bg-gradient-to-r from-blue-50/70 to-indigo-50/50 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-base">Live Zoom Lectures & Virtual Classrooms</h3>
                                <p class="text-xs text-slate-500">Real-time online classes with automatic student attendance tracking</p>
                            </div>
                        </div>
                        <button type="button" onclick="document.getElementById('zoom-scheduler-panel').classList.toggle('hidden')" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                            + Schedule Meeting
                        </button>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($course->zoomMeetings as $meeting)
                            <div class="p-5 hover:bg-slate-50/60 transition space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-slate-900 text-sm">{{ $meeting->title }}</h4>
                                        @if($meeting->isLive())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500 text-white animate-pulse">
                                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                                Live Now
                                            </span>
                                        @elseif($meeting->isUpcoming())
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                                Upcoming
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600">
                                                {{ ucfirst($meeting->status) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-xs text-slate-500 flex items-center gap-2">
                                        <span class="font-medium text-slate-700">{{ $meeting->start_time->format('M d, Y @ h:i A') }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $meeting->duration_minutes }} mins</span>
                                    </div>
                                </div>

                                @if($meeting->description)
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $meeting->description }}</p>
                                @endif

                                <!-- Meeting Info Badges -->
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    @if($meeting->meeting_id)
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-mono text-[11px]">
                                            ID: {{ $meeting->meeting_id }}
                                        </span>
                                    @endif
                                    @if($meeting->passcode)
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-mono text-[11px]">
                                            Passcode: {{ $meeting->passcode }}
                                        </span>
                                    @endif
                                    <a href="{{ route('lecturer.zoom.roster', $meeting) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-semibold hover:bg-indigo-100 transition text-[11px]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        {{ $meeting->attendances->count() }} Students Attended
                                    </a>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ $meeting->meeting_url }}" target="_blank" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1 shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            Start Meeting (Host)
                                        </a>

                                        <!-- Status Switchers -->
                                        @if($meeting->status !== 'live')
                                            <form action="{{ route('lecturer.zoom.status', $meeting) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="live">
                                                <button type="submit" class="px-3 py-1.5 rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold transition">
                                                    Mark Live Now
                                                </button>
                                            </form>
                                        @endif

                                        @if($meeting->status !== 'completed')
                                            <form action="{{ route('lecturer.zoom.status', $meeting) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-semibold transition">
                                                    End Session
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <form action="{{ route('lecturer.zoom.destroy', $meeting) }}" method="POST" onsubmit="return confirm('Cancel and delete this Zoom session?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-slate-400 hover:text-rose-600 transition">
                                            Delete Meeting
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-slate-400 text-xs">
                                No Zoom meetings published for this course yet. Click "+ Schedule Meeting" above to set up a live virtual lecture.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Section Header & Add Form -->
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-lg">Curriculum & Course Modules</h3>
                </div>

                <!-- Add Section Card -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <h4 class="font-bold text-slate-800 text-sm mb-3">+ Add New Course Module / Section</h4>
                    <form action="{{ route('lecturer.courses.sections.store', $course) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <input type="text" name="title" placeholder="Module Title (e.g. Week 1: Foundations of Database Systems)" required class="w-full rounded-xl border-slate-200 text-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <textarea name="description" rows="2" placeholder="Brief section overview or learning objectives (optional)..." class="w-full rounded-xl border-slate-200 text-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-semibold hover:bg-slate-700 transition">
                            Add Module
                        </button>
                    </form>
                </div>

                <!-- Existing Sections -->
                @forelse($course->sections as $section)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                        <div class="p-5 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-start gap-3">
                                <span class="w-7 h-7 rounded-lg bg-indigo-600/10 text-indigo-600 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                                    {{ $loop->iteration }}
                                </span>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-base">{{ $section->title }}</h4>
                                    @if($section->description)
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $section->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="document.getElementById('edit-section-{{ $section->id }}').classList.toggle('hidden')" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                                    Edit
                                </button>
                                <span class="text-slate-300">&bull;</span>
                                <form action="{{ route('lecturer.sections.destroy', $section) }}" method="POST" onsubmit="return confirm('Delete this module and all its materials?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Inline Edit Section Form -->
                        <div id="edit-section-{{ $section->id }}" class="hidden p-4 bg-indigo-50/40 border-b border-indigo-100">
                            <form action="{{ route('lecturer.sections.update', $section) }}" method="POST" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-700 uppercase mb-1">Module Title</label>
                                    <input type="text" name="title" value="{{ $section->title }}" required class="w-full rounded-xl border-slate-200 text-sm px-3.5 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-700 uppercase mb-1">Module Overview / Description</label>
                                    <textarea name="description" rows="2" class="w-full rounded-xl border-slate-200 text-sm px-3.5 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ $section->description }}</textarea>
                                </div>
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick="document.getElementById('edit-section-{{ $section->id }}').classList.add('hidden')" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-white">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Materials List -->
                        <div class="divide-y divide-slate-100">
                            @forelse($section->materials as $material)
                                <div class="p-4 flex items-center justify-between hover:bg-slate-50/60 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            @if($material->type === 'pdf') PDF
                                            @elseif($material->type === 'video') VID
                                            @elseif($material->type === 'link') URL
                                            @else DOC
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="font-semibold text-sm text-slate-800">{{ $material->title }}</h5>
                                            @if($material->content)
                                                <p class="text-xs text-slate-500 line-clamp-1">{{ $material->content }}</p>
                                            @endif
                                            @if($material->file_path)
                                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="text-xs font-semibold text-indigo-600 hover:underline">
                                                    Download Attachment &darr;
                                                </a>
                                            @elseif($material->external_url)
                                                <a href="{{ $material->external_url }}" target="_blank" class="text-xs font-semibold text-indigo-600 hover:underline">
                                                    Open External Resource &rarr;
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <form action="{{ route('lecturer.materials.destroy', $material) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-slate-400 hover:text-rose-600">Delete</button>
                                    </form>
                                </div>
                            @empty
                                <p class="p-4 text-xs text-slate-400 italic">No materials uploaded in this section yet.</p>
                            @endforelse
                        </div>

                        <!-- Add Material Form -->
                        <div class="p-4 bg-slate-50/40 border-t border-slate-100">
                            <details class="group">
                                <summary class="cursor-pointer text-xs font-bold text-indigo-600 hover:text-indigo-800 list-none flex items-center gap-1">
                                    <span>+ Upload / Add Learning Material to {{ $section->title }}</span>
                                </summary>
                                <form action="{{ route('lecturer.materials.store', $section) }}" method="POST" enctype="multipart/form-data" class="mt-3 space-y-3 bg-white p-4 rounded-xl border border-slate-200">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Material Title</label>
                                            <input type="text" name="title" placeholder="e.g. Lecture 01 Slides" required class="w-full rounded-lg border-slate-200 text-xs px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Type</label>
                                            <select name="type" class="w-full rounded-lg border-slate-200 text-xs px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="pdf">PDF Document</option>
                                                <option value="document">Document / Slide</option>
                                                <option value="video">Video Lecture</option>
                                                <option value="link">Web Link</option>
                                                <option value="text">Notes / Reading</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Upload File (Optional, max 20MB)</label>
                                        <input type="file" name="file" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">External Resource URL (Optional)</label>
                                        <input type="url" name="external_url" placeholder="https://..." class="w-full rounded-lg border-slate-200 text-xs px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Notes / Description</label>
                                        <textarea name="content" rows="2" placeholder="Brief notes or reading instructions..." class="w-full rounded-lg border-slate-200 text-xs px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                    </div>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 transition">
                                        Upload Material
                                    </button>
                                </form>
                            </details>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400 text-sm">
                        No sections created yet. Use the form above to add your first module!
                    </div>
                @endforelse
            </div>

            <!-- Sidebar: Assignments, Announcements, Students -->
            <div class="space-y-6">
                <!-- Course Assignments -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                        <h4 class="font-bold text-slate-800 text-sm">Course Assignments</h4>
                        <a href="{{ route('lecturer.assignments.create', $course) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">+ New</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($course->assignments as $assignment)
                            <div class="p-4 hover:bg-slate-50/60 transition">
                                <div class="flex items-center justify-between mb-1">
                                    <h5 class="font-semibold text-sm text-slate-800">{{ $assignment->title }}</h5>
                                    <span class="text-xs font-bold text-indigo-600">{{ $assignment->max_score }} pts</span>
                                </div>
                                <p class="text-xs text-slate-400 mb-2">Due {{ $assignment->due_date->format('M d, Y') }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-500">
                                        {{ $assignment->submissions->count() }} Submissions
                                    </span>
                                    <a href="{{ route('lecturer.assignments.show', $assignment) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                                        View Submissions &rarr;
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="p-4 text-xs text-slate-400">No assignments created yet.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Post Announcement -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <h4 class="font-bold text-slate-800 text-sm mb-3">Broadcast Course Announcement</h4>
                    <form action="{{ route('lecturer.announcements.store', $course) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <input type="text" name="title" placeholder="Announcement Title" required class="w-full rounded-xl border-slate-200 text-xs px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <textarea name="content" rows="3" placeholder="Message content for enrolled students..." required class="w-full rounded-xl border-slate-200 text-xs px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="is_pinned" name="is_pinned" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="is_pinned" class="text-xs text-slate-600">Pin to top of course page</label>
                        </div>
                        <button type="submit" class="w-full py-2 bg-slate-800 text-white rounded-xl text-xs font-semibold hover:bg-slate-700 transition">
                            Post Announcement
                        </button>
                    </form>
                </div>

                <!-- Enrolled Students Roster -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-100">
                        <h4 class="font-bold text-slate-800 text-sm">Enrolled Students ({{ $course->students->count() }})</h4>
                    </div>
                    <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
                        @forelse($course->students as $student)
                            <div class="p-3 flex items-center justify-between hover:bg-slate-50/60">
                                <div>
                                    <p class="text-xs font-semibold text-slate-800">{{ $student->name }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $student->identifier ?? $student->email }}</p>
                                </div>
                                <span class="text-[10px] text-slate-400">{{ $student->pivot->enrolled_at ? \Carbon\Carbon::parse($student->pivot->enrolled_at)->format('M d') : '' }}</span>
                            </div>
                        @empty
                            <p class="p-4 text-xs text-slate-400">No students enrolled yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-lms-layout>
