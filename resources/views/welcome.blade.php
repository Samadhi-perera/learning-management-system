<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $appName = config('app.name', 'SMD University');
        $words = preg_split('/\s+/', trim($appName));
        $initials = count($words) > 1
            ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1) . (isset($words[2]) ? substr($words[2], 0, 1) : ''))
            : strtoupper(substr($appName, 0, 3));
    @endphp
    <title>{{ $appName }} - Learning Management System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .brand-font { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="min-h-full bg-slate-950 text-slate-100 selection:bg-indigo-500 selection:text-white">
    <!-- Navbar -->
    <header class="border-b border-slate-800/80 backdrop-blur bg-slate-950/80 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18C5 19.94 8.13 22 12 22C15.87 22 19 19.94 19 17.18V13.18L12 17.09L5 13.18Z"/>
                    </svg>
                </div>
                <div>
                    <span class="font-bold text-xl tracking-tight text-white brand-font block leading-none">{{ $appName }}</span>
                    <span class="text-[10px] uppercase font-bold tracking-widest text-indigo-400">Academic LMS Portal</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-lg shadow-indigo-600/30">
                        Go to Dashboard &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-lg shadow-indigo-600/30">
                        Sign In to Portal
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="max-w-7xl mx-auto px-6 py-16 lg:py-24 space-y-20">
        <div class="text-center space-y-6 max-w-3xl mx-auto">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                <span class="w-2 h-2 rounded-full bg-indigo-400 mr-2 animate-pulse"></span>
                Higher Education Digital Ecosystem
            </span>
            <h1 class="text-4xl sm:text-6xl font-extrabold text-white tracking-tight leading-tight brand-font">
                Empowering University Education & Research
            </h1>
            <p class="text-slate-400 text-base sm:text-lg leading-relaxed">
                A unified learning ecosystem designed for modern higher education. Seamlessly connecting academic administrators, faculty lecturers, and students through rich digital classrooms, curriculum delivery, and virtual sessions.
            </p>
            <div class="flex flex-wrap justify-center gap-4 pt-2">
                <a href="{{ route('login') }}" class="px-8 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-xl shadow-indigo-600/25 transition">
                    Access University Portal &rarr;
                </a>
            </div>
        </div>

        <!-- Academic Portals Overview -->
        <div class="bg-slate-900/60 border border-slate-800 rounded-3xl p-8 sm:p-10 space-y-8">
            <div class="text-center max-w-xl mx-auto space-y-2">
                <h3 class="text-2xl font-bold text-white brand-font">University Role Portals</h3>
                <p class="text-xs text-slate-400">
                    Tailored environments for university administrators, academic faculty, and enrolled students.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Admin Card -->
                <div class="bg-slate-950/80 border border-slate-800 rounded-2xl p-6 space-y-4 hover:border-rose-500/50 transition flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                Administrator
                            </span>
                            <span class="text-xs text-slate-500">Governance & Control</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-lg">System Administration</h4>
                            <p class="text-xs text-slate-400 mt-1">Institutional oversight across all faculties, departments, courses, and security matrices.</p>
                        </div>
                        <ul class="text-xs text-slate-400 space-y-1.5 border-t border-slate-900 pt-3">
                            <li class="flex items-center gap-2">✓ Manage Faculties & Departments</li>
                            <li class="flex items-center gap-2">✓ Create & Assign Courses</li>
                            <li class="flex items-center gap-2">✓ User Directory & Status Control</li>
                        </ul>
                    </div>
                    <a href="{{ route('login') }}" class="block text-center py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition mt-4">
                        Sign In as Admin &rarr;
                    </a>
                </div>

                <!-- Lecturer Card -->
                <div class="bg-slate-950/80 border border-slate-800 rounded-2xl p-6 space-y-4 hover:border-amber-500/50 transition flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                Faculty / Lecturer
                            </span>
                            <span class="text-xs text-slate-500">Academic Delivery</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-lg">Faculty Instruction</h4>
                            <p class="text-xs text-slate-400 mt-1">Deliver course syllabus, upload lecture slides, publish live Zoom sessions, and evaluate assignments.</p>
                        </div>
                        <ul class="text-xs text-slate-400 space-y-1.5 border-t border-slate-900 pt-3">
                            <li class="flex items-center gap-2">✓ Modular Weekly Course Builder</li>
                            <li class="flex items-center gap-2">✓ Live Zoom Virtual Classrooms</li>
                            <li class="flex items-center gap-2">✓ Grade Submissions & Give Feedback</li>
                        </ul>
                    </div>
                    <a href="{{ route('login') }}" class="block text-center py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition mt-4">
                        Sign In as Faculty &rarr;
                    </a>
                </div>

                <!-- Student Card -->
                <div class="bg-slate-950/80 border border-slate-800 rounded-2xl p-6 space-y-4 hover:border-emerald-500/50 transition flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                Enrolled Student
                            </span>
                            <span class="text-xs text-slate-500">Digital Campus</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-lg">Student Learning</h4>
                            <p class="text-xs text-slate-400 mt-1">Access lecture notes, download course slides, attend live lectures, and submit coursework.</p>
                        </div>
                        <ul class="text-xs text-slate-400 space-y-1.5 border-t border-slate-900 pt-3">
                            <li class="flex items-center gap-2">✓ Digital Classroom & Syllabus</li>
                            <li class="flex items-center gap-2">✓ Attend Live Zoom Lectures</li>
                            <li class="flex items-center gap-2">✓ Turn in Homework & Track Marks</li>
                        </ul>
                    </div>
                    <a href="{{ route('login') }}" class="block text-center py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition mt-4">
                        Sign In as Student &rarr;
                    </a>
                </div>
            </div>

            @if(app()->environment('local'))
                <!-- Development Only Helper (Collapsible) -->
                <details class="text-center pt-2">
                    <summary class="text-xs text-slate-500 hover:text-slate-400 cursor-pointer select-none">
                        Developer Test Credentials (Local Environment Only)
                    </summary>
                    <div class="mt-3 p-4 bg-slate-950/90 rounded-2xl border border-slate-800 text-xs text-slate-400 inline-block text-left font-mono space-y-1.5">
                        <div class="text-indigo-400 font-bold mb-1">Seeded Accounts (Password: <span class="text-white">password</span>):</div>
                        <div>&bull; Admin: <span class="text-slate-200">admin@university.edu</span></div>
                        <div>&bull; Lecturer: <span class="text-slate-200">dr.smith@university.edu</span></div>
                        <div>&bull; Student: <span class="text-slate-200">student1@university.edu</span></div>
                    </div>
                </details>
            @endif
        </div>

        <!-- Academic Features Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-slate-300">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                </div>
                <h4 class="text-lg font-bold text-white brand-font">Faculty & Department Structure</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Designed around university governance. Map academic faculties, departments, terms, and course credits with complete administrative oversight.
                </p>
            </div>

            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h4 class="text-lg font-bold text-white brand-font">Curriculum & Course Builder</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Lecturers organize lessons into modular weekly topics with direct file uploads, video links, and rich lecture notes.
                </p>
            </div>

            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="text-lg font-bold text-white brand-font">Assignment Submissions & Grading</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Students turn in deliverables with automatic deadline tracking. Instructors evaluate files, assign marks, and issue personalized feedback.
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 py-8 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} {{ $appName }} Learning Management System.
    </footer>
</body>
</html>
