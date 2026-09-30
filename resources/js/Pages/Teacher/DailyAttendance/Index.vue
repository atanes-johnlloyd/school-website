<template>

    <Head title="Teacher Daily Attendance - Salawag LMS" />

    <AuthenticatedLayout searchPlaceholder="Search student attendance logs, LRN, remarks..">

        <!-- MAIN PAGE CONTENT -->
        <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

            <!-- DAILY ATTENDANCE HERO BANNER -->
            <div v-observe
                class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center anim-fade-down"
                style="animation-delay: 50ms;">

                <!-- Hero Image bound to Local Import -->
                <img :src="heroImage" alt="Daily Attendance Background"
                    class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

                <!-- Animated Green Overlay -->
                <div
                    class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply">
                </div>

                <!-- Inner Content Container -->
                <div class="relative z-10 p-4 sm:p-8 md:p-10 flex flex-col justify-center space-y-4 sm:space-y-6">

                    <!-- Top Badges -->
                    <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                        <span
                            class="bg-[#005506] text-white border border-emerald-400/30 text-[10px] sm:text-[11px] md:text-xs font-bold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 fill-current text-emerald-300" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                            </svg>
                            DepEd Cavite • Live Gate RFID Sync
                        </span>
                        <span
                            class="bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-[11px] md:text-xs font-black uppercase tracking-wider px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 fill-current text-[#2C3E2D]" viewBox="0 0 24 24">
                                <path
                                    d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                            </svg>
                            SY 2025–2026 • 2nd Semester
                        </span>
                    </div>

                    <!-- Title & Description -->
                    <div class="space-y-2 sm:space-y-3 max-w-3xl">
                        <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                            Teacher Daily Attendance & RFID Log Tracker
                        </h2>
                        <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                            Real-time student gate entry logs, automated advisory tracking, and daily presence
                            verification for senior high school classes.
                        </p>
                    </div>

                    <!-- Bottom Meta Details -->
                    <div
                        class="flex flex-wrap items-center gap-y-2 gap-x-3 sm:gap-x-6 text-white/90 text-[11px] sm:text-sm font-semibold pt-2 border-t border-white/15">
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <svg class="w-4 h-4 fill-current text-amber-300" viewBox="0 0 24 24">
                                <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z" />
                            </svg>
                            Grade 12 STEM – Rizal
                        </div>
                        <div class="hidden sm:inline text-white/40">•</div>
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <svg class="w-4 h-4 fill-current text-emerald-300" viewBox="0 0 24 24">
                                <path
                                    d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z" />
                            </svg>
                            General Physics 2
                        </div>
                        <div class="hidden sm:inline text-white/40">•</div>
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <svg class="w-4 h-4 fill-current text-amber-300" viewBox="0 0 24 24">
                                <path
                                    d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                            </svg>
                            Tuesday, March 10, 2026
                        </div>
                        <div class="hidden sm:inline text-white/40">•</div>
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <svg class="w-4 h-4 fill-current text-emerald-300" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                            </svg>
                            Building B • Science Lab 3
                        </div>
                    </div>

                </div>
            </div>

            <!-- 5-CARD ATTENDANCE METRICS GRID -->
            <div class="relative z-10 grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-5">

                <!-- 1. ENROLLED ROSTER -->
                <div v-observe
                    class="anim-slide-up col-span-1 bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-6"
                    style="animation-delay: 100ms;">
                    <div class="space-y-2 sm:space-y-4">
                        <div class="flex items-start justify-between">
                            <span
                                class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Enrolled<br>Roster</span>
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-700">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-baseline gap-1.5 sm:gap-2">
                            <span class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white">45</span>
                            <span
                                class="text-[10px] sm:text-xs font-medium text-slate-500 dark:text-slate-400">Learners</span>
                        </div>
                    </div>

                    <div
                        class="pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex flex-wrap items-center justify-between text-[10px] sm:text-xs gap-1">
                        <span class="text-slate-500 dark:text-slate-400">Section STEM-1</span>
                        <span class="font-bold text-slate-800 dark:text-white">23B / 22G</span>
                    </div>
                </div>

                <!-- 2. PRESENT (VERIFIED) -->
                <div v-observe
                    class="anim-slide-up col-span-1 bg-[#F2FCF4] dark:bg-emerald-950/30 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-emerald-200/80 dark:border-emerald-900/40 flex flex-col justify-between space-y-4 sm:space-y-6"
                    style="animation-delay: 200ms;">
                    <div class="space-y-2 sm:space-y-4">
                        <div class="flex items-start justify-between">
                            <span
                                class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-[#005506] dark:text-[#86EFAC]">Present<br>(Verified)</span>
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-200 dark:border-emerald-800">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-baseline gap-2 sm:gap-3">
                            <span class="text-2xl sm:text-4xl font-black text-[#005506] dark:text-[#86EFAC]">43</span>
                            <span
                                class="text-[10px] sm:text-[11px] font-extrabold bg-emerald-200/60 dark:bg-emerald-900/60 text-[#005506] dark:text-[#86EFAC] px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full">
                                95.5%
                            </span>
                        </div>
                    </div>

                    <div
                        class="pt-3 sm:pt-4 border-t border-emerald-200/60 dark:border-emerald-900/40 flex items-center justify-between text-[10px] sm:text-xs font-bold">
                        <span class="text-[#005506] dark:text-[#86EFAC]">In-Seat</span>
                        <span class="text-emerald-800 dark:text-[#86EFAC]">Complete</span>
                    </div>
                </div>

                <!-- 3. TARDY / LATE -->
                <div v-observe
                    class="anim-slide-up col-span-1 bg-[#FFFDF4] dark:bg-amber-950/20 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-amber-200/80 dark:border-amber-900/40 flex flex-col justify-between space-y-4 sm:space-y-6"
                    style="animation-delay: 300ms;">
                    <div class="space-y-2 sm:space-y-4">
                        <div class="flex items-start justify-between">
                            <span
                                class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-amber-700 dark:text-amber-400">Tardy
                                /<br>Late</span>
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-200 dark:border-amber-800">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-baseline gap-2 sm:gap-3">
                            <span class="text-2xl sm:text-4xl font-black text-amber-600 dark:text-amber-400">1</span>
                            <span
                                class="text-[9px] sm:text-[10px] font-black bg-amber-200/60 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full uppercase tracking-wider">
                                Grace
                            </span>
                        </div>
                    </div>

                    <div
                        class="pt-3 sm:pt-4 border-t border-amber-200/60 dark:border-amber-900/40 flex items-center justify-between text-[10px] sm:text-xs font-bold">
                        <span class="text-amber-800 dark:text-amber-400">07:18 AM</span>
                        <span class="text-orange-600 dark:text-orange-400">SMS Sent</span>
                    </div>
                </div>

                <!-- 4. EXCUSED SLIP -->
                <div v-observe
                    class="anim-slide-up col-span-1 bg-[#F4F8FE] dark:bg-sky-950/20 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-sky-200/80 dark:border-sky-900/40 flex flex-col justify-between space-y-4 sm:space-y-6"
                    style="animation-delay: 400ms;">
                    <div class="space-y-2 sm:space-y-4">
                        <div class="flex items-start justify-between">
                            <span
                                class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-sky-700 dark:text-sky-400">Excused<br>Slip</span>
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-sky-100 dark:bg-sky-900/50 text-sky-700 dark:text-sky-400 flex items-center justify-center shrink-0 border border-sky-200 dark:border-sky-800">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-baseline gap-2 sm:gap-3">
                            <span class="text-2xl sm:text-4xl font-black text-sky-700 dark:text-sky-400">1</span>
                            <span
                                class="text-[9px] sm:text-[10px] font-bold bg-sky-100 dark:bg-sky-900/60 text-sky-800 dark:text-sky-300 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full">
                                Clinic
                            </span>
                        </div>
                    </div>

                    <div
                        class="pt-3 sm:pt-4 border-t border-sky-200/60 dark:border-sky-900/40 flex items-center justify-between text-[10px] sm:text-xs font-bold">
                        <span class="text-sky-800 dark:text-sky-300">CLN</span>
                        <span class="text-sky-900 dark:text-sky-200 font-extrabold">Bed Rest</span>
                    </div>
                </div>

                <!-- 5. UNEXCUSED -->
                <div v-observe
                    class="anim-slide-up col-span-2 sm:col-span-2 lg:col-span-1 bg-[#FEF2F2] dark:bg-rose-950/20 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-rose-200/80 dark:border-rose-900/40 flex flex-col justify-between space-y-4 sm:space-y-6"
                    style="animation-delay: 500ms;">
                    <div class="space-y-2 sm:space-y-4">
                        <div class="flex items-start justify-between">
                            <span
                                class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-rose-700 dark:text-rose-400">Unexcused</span>
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-200 dark:border-rose-800">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-baseline gap-2 sm:gap-3">
                            <span class="text-2xl sm:text-4xl font-black text-rose-700 dark:text-rose-400">0</span>
                            <span
                                class="text-[10px] sm:text-[11px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full">
                                0.0% Truancy
                            </span>
                        </div>
                    </div>

                    <div
                        class="pt-3 sm:pt-4 border-t border-rose-200/60 dark:border-rose-900/40 flex items-center justify-between text-[10px] sm:text-xs font-bold">
                        <span class="text-slate-600 dark:text-slate-400">Zero Cutting</span>
                        <span class="text-[#005506] dark:text-[#86EFAC]">Perfect Class</span>
                    </div>
                </div>

            </div>

            <!-- LEARNERS DAILY SF-2 ROSTER SECTION -->
            <div v-observe
                class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4 sm:space-y-6"
                style="animation-delay: 600ms;">

                <!-- Top Filter & Search Bar Header -->
                <div
                    class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 sm:gap-4 bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-4 rounded-2xl border border-slate-200/60 dark:border-[#3F4F43]">
                    <!-- Search Input -->
                    <div class="relative w-full md:w-96">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" placeholder="Search learner by name, LRN, seat, or RFID code..."
                            class="w-full pl-10 pr-4 py-2 sm:py-2.5 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#006907] text-xs text-slate-700 dark:text-slate-200 shadow-sm" />
                    </div>

                    <!-- Filter Pills & Config Button -->
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                        <button
                            class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-bold shadow-sm transition-all">
                            All (45)
                        </button>
                        <button
                            class="bg-white dark:bg-[#2D3A31] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-semibold transition-all border border-slate-200 dark:border-[#3F4F43]">
                            Flags (2)
                        </button>
                        <button
                            class="bg-white dark:bg-[#2D3A31] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-semibold transition-all border border-slate-200 dark:border-[#3F4F43]">
                            Boys (23)
                        </button>
                        <button
                            class="bg-white dark:bg-[#2D3A31] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-semibold transition-all border border-slate-200 dark:border-[#3F4F43]">
                            Girls (22)
                        </button>
                        <button
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Section Title Bar & 1-Tap Switch Legend -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 border-b border-slate-100 dark:border-[#3F4F43] pb-3 sm:pb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-lg font-black text-slate-800 dark:text-white">Learners
                                Daily SF–2 Roster</h3>
                            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">DepEd
                                SF-2 Sequence</p>
                        </div>
                    </div>

                    <div
                        class="self-start sm:self-auto bg-[#FEF9E7] dark:bg-amber-950/40 border border-amber-200/60 dark:border-amber-900/40 px-3 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-bold text-amber-900 dark:text-amber-300 flex items-center gap-2">
                        <span class="font-black text-amber-600 dark:text-amber-400">1–Tap Switch:</span>
                        <span class="bg-white dark:bg-amber-900 px-2 py-0.5 rounded shadow-sm">P • L • A • E</span>
                    </div>
                </div>

                <!-- Responsive Scrollable Table Container -->
                <div
                    class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl sm:rounded-2xl border border-slate-200/60 dark:border-[#3F4F43]">
                    <table class="w-full min-w-[640px] text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-[#F9F7F1] dark:bg-[#232D26] text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-[#3F4F43]">
                                <th class="py-3 px-3 sm:px-4">Learner & LRN</th>
                                <th class="py-3 px-3 sm:px-4">Gate Turnstile Sync</th>
                                <th class="py-3 px-3 sm:px-4">Period Status</th>
                                <th class="py-3 px-3 sm:px-4">Advisory & Endorsement</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-xs font-medium text-slate-700 dark:text-slate-200">

                            <!-- Row 1: Dela Cruz -->
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-2.5 sm:gap-3">
                                        <span aria-hidden="true"
                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 text-[9px] sm:text-[10px] font-bold flex items-center justify-center shrink-0 border border-slate-200 dark:border-[#3F4F43]">JDC</span>
                                        <div>
                                            <div class="flex items-center gap-1.5 sm:gap-2">
                                                <p class="font-bold text-slate-900 dark:text-white text-xs">Dela
                                                    Cruz, Juan Carlos M.</p>
                                                <span
                                                    class="bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 text-[9px] font-extrabold px-1.5 sm:px-2 py-0.5 rounded-full border border-amber-200">✨
                                                    +16</span>
                                            </div>
                                            <p class="text-[10px] sm:text-[11px] text-slate-500">LRN: 136582120041 •
                                                Seat #01</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div
                                        class="flex items-start gap-1.5 text-emerald-700 dark:text-emerald-400 font-bold">
                                        <span class="text-xs sm:text-sm">🛜</span>
                                        <div>
                                            <span class="block text-xs">Gate 1: 07:08 AM</span>
                                            <span
                                                class="text-[10px] sm:text-[11px] font-normal text-slate-500 dark:text-slate-400 block">Early
                                                Bird Turnstile</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-1 font-bold">
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#005506] text-white flex items-center justify-center text-[10px] sm:text-[11px] shadow-sm">P</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">L</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">A</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">E</button>
                                    </div>
                                </td>
                                <td
                                    class="py-3 sm:py-4 px-3 sm:px-4 text-slate-600 dark:text-slate-400 text-[10px] sm:text-[11px]">
                                    <p class="font-semibold text-slate-800 dark:text-slate-200">Class President •
                                        Physics Lab Custodian</p>
                                </td>
                            </tr>

                            <!-- Row 2: Bautista -->
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-2.5 sm:gap-3">
                                        <span aria-hidden="true"
                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 text-[9px] sm:text-[10px] font-bold flex items-center justify-center shrink-0 border border-slate-200 dark:border-[#3F4F43]">MLB</span>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-xs">Bautista,
                                                Miguel Lorenzo R.</p>
                                            <p class="text-[10px] sm:text-[11px] text-slate-500">LRN: 136582120042 •
                                                Seat #02</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div
                                        class="flex items-start gap-1.5 text-emerald-700 dark:text-emerald-400 font-bold">
                                        <span class="text-xs sm:text-sm">🛜</span>
                                        <div>
                                            <span class="block text-xs">Gate 1: 07:12 AM</span>
                                            <span
                                                class="text-[10px] sm:text-[11px] font-normal text-slate-500 dark:text-slate-400 block">Standard
                                                Arrival</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-1 font-bold">
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#005506] text-white flex items-center justify-center text-[10px] sm:text-[11px] shadow-sm">P</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">L</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">A</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">E</button>
                                    </div>
                                </td>
                                <td
                                    class="py-3 sm:py-4 px-3 sm:px-4 text-slate-600 dark:text-slate-400 text-[10px] sm:text-[11px]">
                                    <p class="font-semibold text-slate-800 dark:text-slate-200">Standard profile</p>
                                </td>
                            </tr>

                            <!-- Row 3: Santos -->
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-2.5 sm:gap-3">
                                        <span aria-hidden="true"
                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 text-[9px] sm:text-[10px] font-bold flex items-center justify-center shrink-0 border border-slate-200 dark:border-[#3F4F43]">CMS</span>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-xs">Santos,
                                                Clarissa Mae V.</p>
                                            <p class="text-[10px] sm:text-[11px] text-slate-500">LRN: 136582120043 •
                                                Seat #03</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div
                                        class="flex items-start gap-1.5 text-emerald-700 dark:text-emerald-400 font-bold">
                                        <span class="text-xs sm:text-sm">🛜</span>
                                        <div>
                                            <span class="block text-xs">Gate 1: 07:04 AM</span>
                                            <span
                                                class="text-[10px] sm:text-[11px] font-normal text-slate-500 dark:text-slate-400 block">Early
                                                Bird Turnstile</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-1 font-bold">
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#005506] text-white flex items-center justify-center text-[10px] sm:text-[11px] shadow-sm">P</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">L</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">A</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">E</button>
                                    </div>
                                </td>
                                <td
                                    class="py-3 sm:py-4 px-3 sm:px-4 text-slate-600 dark:text-slate-400 text-[10px] sm:text-[11px]">
                                    <p class="font-semibold text-amber-700 dark:text-amber-400">Science Olympiad
                                        Lead</p>
                                </td>
                            </tr>

                            <!-- Row 4: Alcantara (Tardy) -->
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-2.5 sm:gap-3">
                                        <span aria-hidden="true"
                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 text-[9px] sm:text-[10px] font-bold flex items-center justify-center shrink-0 border border-slate-200 dark:border-[#3F4F43]">KA</span>
                                        <div>
                                            <div class="flex items-center gap-1.5 sm:gap-2">
                                                <p class="font-bold text-slate-900 dark:text-white text-xs">
                                                    Alcantara, Kenneth</p>
                                                <span
                                                    class="bg-amber-100 text-amber-800 text-[9px] font-extrabold px-1.5 sm:px-2 py-0.5 rounded-full">Tardy
                                                    (3m)</span>
                                            </div>
                                            <p class="text-[10px] sm:text-[11px] text-slate-500">LRN: 136582120044 •
                                                Seat #04</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-start gap-1.5 text-amber-600 dark:text-amber-400 font-bold">
                                        <span class="text-xs sm:text-sm">⏰</span>
                                        <div>
                                            <span class="block text-xs">Gate 1: 07:18 AM</span>
                                            <span
                                                class="text-[10px] sm:text-[11px] font-normal text-rose-600 block">Past
                                                Cutoff</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-1 font-bold">
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">P</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-orange-600 text-white flex items-center justify-center text-[10px] sm:text-[11px] shadow-sm">L</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">A</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">E</button>
                                    </div>
                                </td>
                                <td
                                    class="py-3 sm:py-4 px-3 sm:px-4 text-slate-600 dark:text-slate-400 text-[10px] sm:text-[11px]">
                                    <p class="font-semibold text-rose-600">Parent Dispatched (07:19 AM)</p>
                                    <p class="text-[9px] sm:text-[10px]">Transit delay along McArthur</p>
                                </td>
                            </tr>

                            <!-- Row 5: Reyes -->
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-2.5 sm:gap-3">
                                        <span aria-hidden="true"
                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 text-[9px] sm:text-[10px] font-bold flex items-center justify-center shrink-0 border border-slate-200 dark:border-[#3F4F43]">BAR</span>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-xs">Reyes, Bea
                                                Alyssa T.</p>
                                            <p class="text-[10px] sm:text-[11px] text-slate-500">LRN: 136582120045 •
                                                Seat #05</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div
                                        class="flex items-start gap-1.5 text-emerald-700 dark:text-emerald-400 font-bold">
                                        <span class="text-xs sm:text-sm">🛜</span>
                                        <div>
                                            <span class="block text-xs">Gate 1: 07:10 AM</span>
                                            <span
                                                class="text-[10px] sm:text-[11px] font-normal text-slate-500 dark:text-slate-400 block">Standard
                                                Arrival</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-1 font-bold">
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#005506] text-white flex items-center justify-center text-[10px] sm:text-[11px] shadow-sm">P</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">L</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">A</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">E</button>
                                    </div>
                                </td>
                                <td
                                    class="py-3 sm:py-4 px-3 sm:px-4 text-slate-600 dark:text-slate-400 text-[10px] sm:text-[11px]">
                                    <p class="font-semibold text-slate-800 dark:text-slate-200">Peer Tutor •
                                        Research Team</p>
                                </td>
                            </tr>

                            <!-- Row 6: Mendoza (Medical) -->
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-2.5 sm:gap-3">
                                        <span aria-hidden="true"
                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 text-[9px] sm:text-[10px] font-bold flex items-center justify-center shrink-0 border border-slate-200 dark:border-[#3F4F43]">JM</span>
                                        <div>
                                            <div class="flex items-center gap-1.5 sm:gap-2">
                                                <p class="font-bold text-slate-900 dark:text-white text-xs">Mendoza,
                                                    Joshua P.</p>
                                                <span
                                                    class="bg-sky-100 text-sky-800 text-[9px] font-extrabold px-1.5 sm:px-2 py-0.5 rounded-full">Medical</span>
                                            </div>
                                            <p class="text-[10px] sm:text-[11px] text-slate-500">LRN: 136582120046 •
                                                Seat #06</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-start gap-1.5 text-slate-400 font-medium">
                                        <span class="text-xs sm:text-sm">🚫</span>
                                        <div>
                                            <span class="block text-xs text-slate-600 dark:text-slate-300">No
                                                Scan</span>
                                            <span
                                                class="text-[10px] sm:text-[11px] text-sky-600 dark:text-sky-400 font-semibold block">Home
                                                Isolation</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-1 font-bold">
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">P</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">L</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">A</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] sm:text-[11px] shadow-sm">E</button>
                                    </div>
                                </td>
                                <td
                                    class="py-3 sm:py-4 px-3 sm:px-4 text-sky-700 dark:text-sky-400 text-[10px] sm:text-[11px]">
                                    <p class="font-bold flex items-center gap-1"><span>🏥</span> Clinic #CLN-882</p>
                                    <p class="text-[9px] sm:text-[10px] text-slate-500">Endorsed by Guardian</p>
                                </td>
                            </tr>

                            <!-- Row 7: Villafuerte -->
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-2.5 sm:gap-3">
                                        <span aria-hidden="true"
                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 text-[9px] sm:text-[10px] font-bold flex items-center justify-center shrink-0 border border-slate-200 dark:border-[#3F4F43]">HSV</span>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-xs">Villafuerte,
                                                Hannah Sofia C.</p>
                                            <p class="text-[10px] sm:text-[11px] text-slate-500">LRN: 136582120047 •
                                                Seat #07</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div
                                        class="flex items-start gap-1.5 text-emerald-700 dark:text-emerald-400 font-bold">
                                        <span class="text-xs sm:text-sm">🛜</span>
                                        <div>
                                            <span class="block text-xs">Gate 3: 07:09 AM</span>
                                            <span
                                                class="text-[10px] sm:text-[11px] font-normal text-slate-500 dark:text-slate-400 block">West
                                                Annex</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 sm:py-4 px-3 sm:px-4">
                                    <div class="flex items-center gap-1 font-bold">
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#005506] text-white flex items-center justify-center text-[10px] sm:text-[11px] shadow-sm">P</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">L</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">A</button>
                                        <button
                                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-slate-100 dark:bg-[#3F4F43] text-slate-400 flex items-center justify-center text-[10px] sm:text-[11px]">E</button>
                                    </div>
                                </td>
                                <td
                                    class="py-3 sm:py-4 px-3 sm:px-4 text-slate-600 dark:text-slate-400 text-[10px] sm:text-[11px]">
                                    <p class="font-semibold text-slate-800 dark:text-slate-200">SSG Auditor •
                                        Science Lead</p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <!-- Pagination & Status Footer -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 pt-2">
                    <p
                        class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium text-center sm:text-left">
                        Showing 7 of 45 learners • <span
                            class="text-emerald-700 dark:text-emerald-400 font-bold inline-flex items-center gap-1">✓
                            100% Biometric Matched</span>
                    </p>

                    <div class="flex items-center gap-1 sm:gap-1.5">
                        <button
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold flex items-center justify-center shadow-sm">
                            1
                        </button>
                        <button
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 text-xs font-bold flex items-center justify-center transition-colors">
                            2
                        </button>
                        <button
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 text-xs font-bold flex items-center justify-center transition-colors">
                            3
                        </button>
                        <button
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 text-xs font-bold flex items-center justify-center transition-colors">
                            4
                        </button>
                    </div>
                </div>

            </div>

            <!-- TWO-COLUMN SECTION FOR SF-2 SYNC & Q3 ATTENDANCE GRAPH -->
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

                <!-- CARD 1: DEPED FORM SF-2 SYNC -->
                <div v-observe
                    class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-6"
                    style="animation-delay: 700ms;">
                    <div class="space-y-3 sm:space-y-4">
                        <!-- Header Badge -->
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm sm:text-base font-black text-slate-800 dark:text-white">DepEd
                                        Form SF–2
                                        Sync</h3>
                                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                                        Daily Track</p>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <p
                            class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                            Daily Attendance Report of Senior High School Learners under DepEd Cavite Attendance
                            Management.
                        </p>

                        <!-- March Track Container -->
                        <div
                            class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-xl sm:rounded-2xl p-3 sm:p-4 border border-slate-200/80 dark:border-[#3F4F43] space-y-3 sm:space-y-4">
                            <div class="flex items-center justify-between text-[11px] sm:text-xs font-black">
                                <span class="text-slate-700 dark:text-slate-300">March 2026 Daily Track</span>
                                <span class="text-[#005506] dark:text-[#86EFAC]">Day 7 of 21</span>
                            </div>

                            <!-- Day Badges Sequence -->
                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                                <div
                                    class="w-8 h-10 sm:w-9 sm:h-11 rounded-lg sm:rounded-xl bg-white dark:bg-[#2D3A31] border border-emerald-200 dark:border-emerald-900/50 flex flex-col items-center justify-center text-[9px] sm:text-[10px] font-bold text-[#005506] dark:text-[#86EFAC] shadow-sm">
                                    <span>M2</span>
                                    <span class="text-[10px] sm:text-xs">✓</span>
                                </div>
                                <div
                                    class="w-8 h-10 sm:w-9 sm:h-11 rounded-lg sm:rounded-xl bg-white dark:bg-[#2D3A31] border border-emerald-200 dark:border-emerald-900/50 flex flex-col items-center justify-center text-[9px] sm:text-[10px] font-bold text-[#005506] dark:text-[#86EFAC] shadow-sm">
                                    <span>T3</span>
                                    <span class="text-[10px] sm:text-xs">✓</span>
                                </div>
                                <div
                                    class="w-8 h-10 sm:w-9 sm:h-11 rounded-lg sm:rounded-xl bg-white dark:bg-[#2D3A31] border border-emerald-200 dark:border-emerald-900/50 flex flex-col items-center justify-center text-[9px] sm:text-[10px] font-bold text-[#005506] dark:text-[#86EFAC] shadow-sm">
                                    <span>W4</span>
                                    <span class="text-[10px] sm:text-xs">✓</span>
                                </div>
                                <div
                                    class="w-8 h-10 sm:w-9 sm:h-11 rounded-lg sm:rounded-xl bg-white dark:bg-[#2D3A31] border border-emerald-200 dark:border-emerald-900/50 flex flex-col items-center justify-center text-[9px] sm:text-[10px] font-bold text-[#005506] dark:text-[#86EFAC] shadow-sm">
                                    <span>T5</span>
                                    <span class="text-[10px] sm:text-xs">✓</span>
                                </div>
                                <div
                                    class="w-8 h-10 sm:w-9 sm:h-11 rounded-lg sm:rounded-xl bg-white dark:bg-[#2D3A31] border border-emerald-200 dark:border-emerald-900/50 flex flex-col items-center justify-center text-[9px] sm:text-[10px] font-bold text-[#005506] dark:text-[#86EFAC] shadow-sm">
                                    <span>F6</span>
                                    <span class="text-[10px] sm:text-xs">✓</span>
                                </div>
                                <div
                                    class="w-8 h-10 sm:w-9 sm:h-11 rounded-lg sm:rounded-xl bg-white dark:bg-[#2D3A31] border border-emerald-200 dark:border-emerald-900/50 flex flex-col items-center justify-center text-[9px] sm:text-[10px] font-bold text-[#005506] dark:text-[#86EFAC] shadow-sm">
                                    <span>M9</span>
                                    <span class="text-[10px] sm:text-xs">✓</span>
                                </div>
                                <div
                                    class="w-9 h-10 sm:w-10 sm:h-11 rounded-lg sm:rounded-xl bg-[#F9C20C] text-[#2C3E2D] border border-amber-300 flex flex-col items-center justify-center text-[9px] sm:text-[10px] font-black shadow-md">
                                    <span>T10</span>
                                    <span class="text-[10px] sm:text-xs">📅</span>
                                </div>
                            </div>

                            <!-- Month-to-Date ADA -->
                            <div
                                class="flex items-center justify-between pt-2 border-t border-slate-200/60 dark:border-[#3F4F43]">
                                <span
                                    class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">Month-to-Date
                                    ADA:</span>
                                <span
                                    class="text-sm sm:text-base font-black text-[#005506] dark:text-[#86EFAC]">98.4%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Actions -->
                    <div
                        class="flex items-center gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
                        <button
                            class="flex-1 bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-800 dark:text-slate-200 border border-slate-200/80 dark:border-[#3F4F43] py-2 sm:py-2.5 px-3 sm:px-4 rounded-xl text-[11px] sm:text-xs font-bold transition-colors text-center">
                            Preview SF–2 Matrix
                        </button>
                        <button
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-800 dark:text-slate-200 border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- CARD 2: SECTION RIZAL Q3 ATTENDANCE GRAPH -->
                <div v-observe
                    class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-6"
                    style="animation-delay: 800ms;">
                    <div class="space-y-3 sm:space-y-4">
                        <!-- Header & Average Badge -->
                        <div class="flex items-start justify-between gap-3 sm:gap-4">
                            <div>
                                <h3
                                    class="text-sm sm:text-base font-black text-slate-800 dark:text-white leading-tight">
                                    Section Rizal Q3 Attendance</h3>
                                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                                    Division target: 95.0%</p>
                            </div>

                            <div
                                class="bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border border-emerald-200/60 dark:border-emerald-900/40 px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-xl sm:rounded-2xl text-center shrink-0 shadow-sm">
                                <span class="text-xs sm:text-sm font-black block">98.6%</span>
                                <span
                                    class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider block">Avg</span>
                            </div>
                        </div>

                        <!-- SVG Graph Visualizer -->
                        <div class="relative w-full h-28 sm:h-36 pt-2">
                            <svg class="w-full h-full overflow-visible" viewBox="0 0 300 100"
                                preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="attendanceGradient" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#005506" stop-opacity="0.25" />
                                        <stop offset="100%" stop-color="#005506" stop-opacity="0.0" />
                                    </linearGradient>
                                </defs>

                                <!-- Gridlines -->
                                <line x1="0" y1="30" x2="300" y2="30" stroke="currentColor"
                                    class="text-slate-200 dark:text-[#3F4F43]" stroke-dasharray="3 3"
                                    stroke-width="1" />
                                <line x1="0" y1="65" x2="300" y2="65" stroke="currentColor"
                                    class="text-slate-200 dark:text-[#3F4F43]" stroke-dasharray="3 3"
                                    stroke-width="1" />

                                <!-- Gradient Fill -->
                                <polygon points="0,75 40,68 90,52 140,58 190,45 240,48 290,38 290,100 0,100"
                                    fill="url(#attendanceGradient)" />

                                <!-- Line -->
                                <polyline fill="none" stroke="#005506" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round" points="0,75 40,68 90,52 140,58 190,45 240,48 290,38" />

                                <!-- Final Peak Highlight -->
                                <circle cx="290" cy="38" r="4" fill="#F9C20C" stroke="#005506" stroke-width="2" />
                            </svg>
                        </div>
                    </div>

                    <!-- Weekly Metrics Footer -->
                    <div
                        class="grid grid-cols-4 gap-1.5 sm:gap-2 pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43] text-center">
                        <div class="space-y-0.5">
                            <span
                                class="text-[9px] sm:text-[10px] font-bold text-slate-400 dark:text-slate-500 block">Wk
                                1</span>
                            <span
                                class="text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 block">96.8%</span>
                        </div>
                        <div class="space-y-0.5">
                            <span
                                class="text-[9px] sm:text-[10px] font-bold text-slate-400 dark:text-slate-500 block">Wk
                                2</span>
                            <span
                                class="text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 block">97.9%</span>
                        </div>
                        <div class="space-y-0.5">
                            <span
                                class="text-[9px] sm:text-[10px] font-bold text-slate-400 dark:text-slate-500 block">Wk
                                3</span>
                            <span
                                class="text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 block">98.6%</span>
                        </div>
                        <div
                            class="space-y-0.5 bg-emerald-50 dark:bg-emerald-950/40 rounded-lg sm:rounded-xl p-1 border border-emerald-100 dark:border-emerald-900/30">
                            <span
                                class="text-[9px] sm:text-[10px] font-bold text-[#005506] dark:text-[#86EFAC] block">Wk
                                4</span>
                            <span
                                class="text-[11px] sm:text-xs font-black text-[#005506] dark:text-[#86EFAC] block">99.1%</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </AuthenticatedLayout>

</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import heroImage from '../../../../assets/img/local/attendace.png'

const vObserve = {
    mounted(el) {
        el.classList.add('not-visible')
        const observer = new IntersectionObserver(([entry]) => {
            if (entry.isIntersecting) {
                el.classList.add('is-animated')
            } else {
                el.classList.remove('is-animated')
            }
        }, { threshold: 0.1 })
        observer.observe(el)
    }
}
</script>

<style scoped>
/* Breathing opacity animation for the green background layer */
@keyframes pulse-opacity {

    0%,
    100% {
        opacity: 0.88;
    }

    50% {
        opacity: 0.65;
    }
}

.animate-overlay {
    animation: pulse-opacity 6s infinite ease-in-out;
}

/* Replayable Entrance Animations */
@keyframes slideUpFade {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.not-visible {
    opacity: 0;
}

.is-animated.anim-fade-down {
    animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.is-animated.anim-slide-up {
    animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* TEXT SCALING OVERRIDES */
.text-scale-sm :deep(.text-xs) {
    font-size: 0.65rem !important;
    line-height: 0.85rem !important;
}

.text-scale-sm :deep(.text-sm) {
    font-size: 0.75rem !important;
    line-height: 1rem !important;
}

.text-scale-sm :deep(.text-base) {
    font-size: 0.875rem !important;
    line-height: 1.25rem !important;
}

.text-scale-sm :deep(.text-lg) {
    font-size: 1rem !important;
    line-height: 1.5rem !important;
}

.text-scale-sm :deep(.text-xl) {
    font-size: 1.125rem !important;
    line-height: 1.75rem !important;
}

.text-scale-sm :deep(.text-3xl) {
    font-size: 1.5rem !important;
    line-height: 2rem !important;
}

.text-scale-sm :deep(.sm\:text-4xl) {
    font-size: 1.875rem !important;
    line-height: 2.25rem !important;
}

.text-scale-sm :deep(.md\:text-5xl) {
    font-size: 2.25rem !important;
    line-height: 1 !important;
}

.text-scale-lg :deep(.text-xs) {
    font-size: 0.875rem !important;
    line-height: 1.25rem !important;
}

.text-scale-lg :deep(.text-sm) {
    font-size: 1rem !important;
    line-height: 1.5rem !important;
}

.text-scale-lg :deep(.text-base) {
    font-size: 1.125rem !important;
    line-height: 1.75rem !important;
}

.text-scale-lg :deep(.text-lg) {
    font-size: 1.25rem !important;
    line-height: 1.75rem !important;
}

.text-scale-lg :deep(.text-xl) {
    font-size: 1.5rem !important;
    line-height: 2rem !important;
}

.text-scale-lg :deep(.text-3xl) {
    font-size: 2.25rem !important;
    line-height: 2.5rem !important;
}

.text-scale-lg :deep(.sm\:text-4xl) {
    font-size: 2.75rem !important;
    line-height: 1 !important;
}

.text-scale-lg :deep(.md\:text-5xl) {
    font-size: 3.5rem !important;
    line-height: 1 !important;
}
</style>