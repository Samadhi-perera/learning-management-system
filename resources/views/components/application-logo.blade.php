@php
    $appName = config('app.name', 'SMD University');
@endphp
<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center gap-3']) }}>
    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-xl shadow-indigo-500/30">
        <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18C5 19.94 8.13 22 12 22C15.87 22 19 19.94 19 17.18V13.18L12 17.09L5 13.18Z"/>
        </svg>
    </div>
    <div class="text-center">
        <span class="text-xl font-bold text-white brand-font tracking-tight block leading-tight">{{ $appName }}</span>
        <span class="text-[10px] uppercase font-bold tracking-widest text-indigo-400">Academic LMS Portal</span>
    </div>
</div>
