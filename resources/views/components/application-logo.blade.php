@php
    $appName = config('app.name', 'SMD University');
    $words = preg_split('/\s+/', trim($appName));
    $initials = count($words) > 1
        ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1) . (isset($words[2]) ? substr($words[2], 0, 1) : ''))
        : strtoupper(substr($appName, 0, 3));
@endphp
<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center gap-2']) }}>
    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-500/30">
        {{ $initials }}
    </div>
    <span class="text-lg font-bold text-white brand-font tracking-tight">{{ $appName }}</span>
</div>
