<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Error') — Salawag Senior High School</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,700,800,900|anton:400" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#E8FFE8] font-['Inter'] text-slate-800 flex flex-col items-center justify-center px-6 py-16">

    <div class="w-full max-w-2xl text-center space-y-8">

        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl text-white shadow-lg @yield('icon_bg', 'bg-[#005506]')">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                @yield('icon')
            </svg>
        </div>

        <div class="space-y-3">
            <p class="text-[11px] font-black uppercase tracking-[0.3em] text-[#005506]/70">@yield('code')</p>
            <h1 class="font-['Anton'] text-5xl sm:text-6xl md:text-7xl text-[#005506] uppercase tracking-wide leading-none">
                @yield('heading')
            </h1>
            <p class="text-sm sm:text-base text-slate-600 font-medium leading-relaxed max-w-lg mx-auto">
                @yield('message')
            </p>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
            @hasSection('actions')
                @yield('actions')
            @else
                <a href="/"
                    class="inline-flex items-center gap-2 bg-[#005506] hover:bg-[#004105] text-white font-black text-xs sm:text-sm px-6 py-3 rounded-full shadow-md transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Back to Home
                </a>
                <button onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '{{ url('/') }}'; }"
                    class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-800 border border-slate-200 font-bold text-xs sm:text-sm px-6 py-3 rounded-full shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Go Back
                </button>
            @endif
        </div>

        <p class="text-[11px] text-slate-500 font-medium pt-4">
            @yield('footer')
        </p>

    </div>
</body>
</html>