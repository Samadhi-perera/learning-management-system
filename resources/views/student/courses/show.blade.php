<x-lms-layout title="{{ $course->code }} Classroom" header="{{ $course->code }}: {{ $course->title }}">
    <div class="space-y-6">
        <!-- Classroom Banner -->
        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white p-6 sm:p-8 rounded-3xl shadow-lg border border-slate-800">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                            {{ $course->code }}
                        </span>
                        <span class="text-xs text-slate-300">
                            {{ $course->credits }} Credits • {{ $course->semester->name ?? 'Current Term' }}
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight mb-2">{{ $course->title }}</h2>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">{{ $course->description ?? 'Welcome to this university course.' }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur p-4 rounded-2xl border border-white/10 text-xs">
                    <span class="text-slate-400 block uppercase font-bold text-[10px] mb-1">Course Lecturer</span>
                    <span class="font-bold text-white block text-sm">{{ $course->instructor->name ?? 'Faculty Staff' }}</span>
                    <span class="text-slate-300 block text-[11px]">{{ $course->instructor->email ?? '' }}</span>
                </div>
            </div>
        </div>

        <!-- Pinned Announcements -->
        @if($course->announcements->isNotEmpty())
            <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm p-5 space-y-3">
                <div class="flex items-center gap-2 text-xs font-bold text-indigo-700 uppercase tracking-wider">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Instructor Announcements
                </div>
                <div class="space-y-3">
                    @foreach($course->announcements as $announcement)
                        <div class="p-4 rounded-xl {{ $announcement->is_pinned ? 'bg-amber-50/70 border border-amber-200' : 'bg-slate-50 border border-slate-100' }}">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-bold text-sm text-slate-800">{{ $announcement->title }}</h4>
                                <span class="text-[11px] text-slate-400">{{ $announcement->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $announcement->content }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Classroom Layout: Modules on Left, Tasks on Right -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Learning Modules -->
            <div class="lg:col-span-2 space-y-5">
                <h3 class="font-bold text-slate-800 text-lg">Course Materials & Lectures</h3>

                @forelse($course->sections as $section)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                        <div class="p-5 bg-slate-50/80 border-b border-slate-100">
                            <h4 class="font-bold text-slate-900 text-base">{{ $section->title }}</h4>
                            @if($section->description)
                                <p class="text-xs text-slate-500 mt-1">{{ $section->description }}</p>
                            @endif
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse($section->materials as $material)
                                <div class="p-4 flex items-center justify-between hover:bg-slate-50/60 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
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
                                        </div>
                                    </div>

                                    <div>
                                        @if($material->file_path)
                                            <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 text-xs font-semibold hover:bg-indigo-100 transition inline-flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                Download
                                            </a>
                                        @elseif($material->external_url)
                                            <a href="{{ $material->external_url }}" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 text-xs font-semibold hover:bg-indigo-100 transition inline-flex items-center gap-1">
                                                Visit Link &rarr;
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="p-4 text-xs text-slate-400 italic">No files or notes in this module yet.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400 text-sm">
                        The instructor has not uploaded any learning materials for this course yet.
                    </div>
                @endforelse
            </div>

            <!-- Assignments & Tasks Column -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100">
                        <h4 class="font-bold text-slate-800 text-base">Course Tasks & Assignments</h4>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($course->assignments as $assignment)
                            @php
                                $submission = $assignment->submissions->first();
                            @endphp
                            <div class="p-4 hover:bg-slate-50/60 transition">
                                <div class="flex items-center justify-between mb-1">
                                    <h5 class="font-semibold text-sm text-slate-800">{{ $assignment->title }}</h5>
                                    <span class="text-xs font-bold text-indigo-600">{{ $assignment->max_score }} pts</span>
                                </div>
                                <span class="text-xs text-slate-400 block mb-3">Due: {{ $assignment->due_date->format('M d, Y H:i') }}</span>

                                <div class="flex items-center justify-between">
                                    @if($submission && $submission->isGraded())
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Score: {{ $submission->score }} / {{ $assignment->max_score }}
                                        </span>
                                    @elseif($submission)
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-indigo-50 text-indigo-700">
                                            Submitted (Pending Review)
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-50 text-rose-700">
                                            Not Submitted
                                        </span>
                                    @endif

                                    <a href="{{ route('student.assignments.show', $assignment) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                                        {{ $submission ? 'View Work' : 'Submit' }} &rarr;
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="p-6 text-center text-xs text-slate-400">No assignments posted for this course.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-lms-layout>
