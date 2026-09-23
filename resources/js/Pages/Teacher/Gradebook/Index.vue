<template>
    <Head title="Official Electronic Class Record - Salawag LMS" />

    <div class="min-h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300">

        <!-- Sticky Sidebar Navigation -->
        <div class="sticky top-0 h-screen z-40 shrink-0 shadow-lg">
            <Sidebart />
        </div>

        <!-- Main Workspace Canvas -->
        <main :class="['flex-1 relative overflow-y-auto min-h-screen flex flex-col', `text-scale-${fontSizeMode}`]">

            <!-- Reusable Top Navigation Component -->
            <navbartop searchPlaceholder="Search grade records, student LRN, competencies.."
                @font-size-changed="(size) => fontSizeMode = size" />

            <!-- MAIN PAGE CONTENT -->
            <div class="relative z-10 px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2">

                <!-- GRADEBOOK E-CLASS RECORD HERO BANNER -->
                <div v-observe
                    class="relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[300px] flex flex-col justify-center anim-fade-down">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2000&auto=format&fit=crop"
                        alt="Gradebook Background"
                        class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

                    <!-- Animated Green Overlay -->
                    <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply">
                    </div>

                    <!-- Inner Content Container -->
                    <div class="relative z-10 p-6 sm:p-8 md:p-10 flex flex-col justify-center space-y-6">

                        <!-- Top Badges -->
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span
                                class="bg-[#F9C20C] text-[#2C3E2D] text-[11px] md:text-xs font-black uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-sm flex items-center gap-1">
                                <span>🏛️</span> DEPED REGION IV-A • SDO DASMARIÑAS
                            </span>
                            <span
                                class="bg-white/15 backdrop-blur-md text-white border border-white/20 text-[11px] md:text-xs font-semibold px-3.5 py-1.5 rounded-full shadow-sm">
                                🛡️ DepEd Order No. 8, s. 2015 Compliant
                            </span>
                            <span
                                class="bg-white/15 backdrop-blur-md text-white border border-white/20 text-[11px] md:text-xs font-semibold px-3.5 py-1.5 rounded-full shadow-sm">
                                🎓 SHS Senior High School STEM Track
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <div class="space-y-3 max-w-2xl">
                            <h2
                                class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                                Official Electronic Class Record (E-Class Record)
                            </h2>
                            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                                Automated computation for Senior High School STEM Strand. Live score transmutation,
                                continuous transmuted MPS analytics, and direct export to DepEd SF-9 (Report Card) and
                                SF-10.
                            </p>
                        </div>

                    </div>
                </div>

                <!-- FOUR-CARD GRADING COMPONENTS GRID -->
                <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                    <!-- 1. COMPONENT A: WRITTEN WORKS (WW) -->
                    <div v-observe
                        class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6" style="animation-delay: 100ms;">
                        <div class="space-y-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span
                                        class="text-[11px] font-black uppercase tracking-wider text-[#005506] dark:text-[#86EFAC]">Component
                                        A</span>
                                    <h4 class="text-base font-black text-slate-900 dark:text-white mt-0.5">Written Works
                                        (WW)</h4>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex items-baseline gap-3">
                                <span class="text-4xl font-black text-slate-900 dark:text-white">25%</span>
                                <span
                                    class="text-[11px] font-bold bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] px-3 py-1 rounded-full border border-emerald-200/60 dark:border-emerald-900/40">
                                    DepEd STEM DO 8
                                </span>
                            </div>

                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                                4 Quizzes & Problem Sets (25 pts each)
                            </p>
                        </div>

                        <div
                            class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400">Highest Possible Score:</span>
                            <span class="font-extrabold text-[#005506] dark:text-[#86EFAC]">100 pts</span>
                        </div>
                    </div>

                    <!-- 2. COMPONENT B: PERFORMANCE TASKS (PT) -->
                    <div v-observe
                        class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6" style="animation-delay: 200ms;">
                        <div class="space-y-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span
                                        class="text-[11px] font-black uppercase tracking-wider text-amber-700 dark:text-amber-400">Component
                                        B</span>
                                    <h4 class="text-base font-black text-slate-900 dark:text-white mt-0.5">Performance
                                        Tasks (PT)</h4>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex items-baseline gap-3">
                                <span class="text-4xl font-black text-slate-900 dark:text-white">45%</span>
                                <span
                                    class="text-[11px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 px-3 py-1 rounded-full border border-amber-200/60 dark:border-amber-900/30">
                                    Major Practical Weight
                                </span>
                            </div>

                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                                3 Lab Experiments & STEM Project (40 pts each)
                            </p>
                        </div>

                        <div
                            class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400">Highest Possible Score:</span>
                            <span class="font-extrabold text-amber-700 dark:text-amber-400">120 pts</span>
                        </div>
                    </div>

                    <!-- 3. COMPONENT C: QUARTERLY EXAM (QA) -->
                    <div v-observe
                        class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6" style="animation-delay: 300ms;">
                        <div class="space-y-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span
                                        class="text-[11px] font-black uppercase tracking-wider text-rose-600 dark:text-rose-400">Component
                                        C</span>
                                    <h4 class="text-base font-black text-slate-900 dark:text-white mt-0.5">Quarterly
                                        Exam (QA)</h4>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex items-baseline gap-3">
                                <span class="text-4xl font-black text-slate-900 dark:text-white">30%</span>
                                <span
                                    class="text-[11px] font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 px-3 py-1 rounded-full border border-rose-200/60 dark:border-rose-900/40">
                                    Periodical Assessment
                                </span>
                            </div>

                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                                60 Multiple Choice Items (Physics 2 Core)
                            </p>
                        </div>

                        <div
                            class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400">Highest Possible Score:</span>
                            <span class="font-extrabold text-rose-600 dark:text-rose-400">60 pts</span>
                        </div>
                    </div>

                    <!-- 4. COHORT PERFORMANCE (Transmuted MPS) -->
                    <div v-observe
                        class="anim-slide-up bg-gradient-to-br from-[#005506] via-[#004105] to-[#162D1A] dark:from-[#152B1C] dark:via-[#0F2014] dark:to-[#0A160E] rounded-3xl p-6 shadow-lg border border-emerald-600/30 text-white flex flex-col justify-between space-y-6 relative overflow-hidden" style="animation-delay: 400ms;">
                        <div class="space-y-4 relative z-10">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-[11px] font-black uppercase tracking-wider text-[#F9C20C]">Cohort
                                        Performance</span>
                                    <h4 class="text-base font-black text-white mt-0.5">Transmuted MPS</h4>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-2xl bg-[#F9C20C] text-[#2C3E2D] flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                </div>
                            </div>

                            <div class="flex items-baseline gap-3">
                                <span class="text-4xl font-black text-white">92.4%</span>
                                <span
                                    class="text-[11px] font-bold bg-white/20 text-white px-3 py-1 rounded-full backdrop-blur-md">
                                    100% Passed
                                </span>
                            </div>

                            <!-- Progress bar -->
                            <div class="w-full h-2 rounded-full bg-black/25 overflow-hidden">
                                <div class="h-full bg-[#F9C20C] rounded-full w-[92.4%]"></div>
                            </div>
                        </div>

                        <div
                            class="pt-4 border-t border-white/15 flex items-center justify-between text-xs relative z-10">
                            <div>
                                <span class="text-white/70 block text-[11px]">Roster:</span>
                                <span class="font-extrabold text-white">45/45 Recorded</span>
                            </div>
                            <div class="text-right">
                                <span class="font-black text-[#F9C20C] text-sm">18 With Honors</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- MASTER E-CLASS SPREADSHEET SECTION -->
                <div v-observe
                    class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-6" style="animation-delay: 500ms;">

                    <!-- Spreadsheet Header & Status Controls -->
                    <div
                        class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-100 dark:border-[#3F4F43] pb-6">
                        <div class="space-y-1">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-[#005506] text-white dark:bg-[#86EFAC] dark:text-[#232D26] flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base sm:text-lg font-black text-slate-800 dark:text-white">
                                            Master E-Class Spreadsheet</h3>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                        Formula engine automatically maps raw weighted sums to the DepEd Transmutation
                                        Table (DO 8, s. 2015).
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Bar & Tabulator Filter Pills -->
                    <div
                        class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 bg-[#F9F7F1] dark:bg-[#232D26] p-4 rounded-2xl border border-slate-200/60 dark:border-[#3F4F43]">
                        <!-- Search input -->
                        <div class="relative w-full md:w-80">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" placeholder="Search by student name, LRN.."
                                class="w-full pl-9 pr-4 py-2 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#006907] text-xs text-slate-700 dark:text-slate-200 shadow-sm" />
                        </div>

                        <!-- Tabulator Filter Buttons -->
                        <div class="flex flex-wrap items-center gap-1.5 overflow-x-auto">
                            <button
                                class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
                                All (45)
                            </button>
                            <button
                                class="bg-white dark:bg-[#2D3A31] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-slate-200 dark:border-[#3F4F43]">
                                Male (23)
                            </button>
                            <button
                                class="bg-white dark:bg-[#2D3A31] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-slate-200 dark:border-[#3F4F43]">
                                Female (22)
                            </button>
                            <button
                                class="bg-white dark:bg-[#2D3A31] text-amber-700 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/40 px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-amber-200 dark:border-amber-900/30 flex items-center gap-1">
                                <span>★</span> With Honors (14)
                            </button>
                            <button
                                class="bg-white dark:bg-[#2D3A31] text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 px-4 py-2 rounded-xl text-xs font-semibold transition-all border border-red-200 dark:border-red-900/30 flex items-center gap-1">
                                <span>⚠️</span> At Risk (1)
                            </button>
                        </div>
                    </div>

                    <!-- Spreadsheet Table Container -->
                    <div class="overflow-x-auto rounded-2xl border border-slate-200/60 dark:border-[#3F4F43]">
                        <table class="w-full text-left border-collapse text-xs font-medium">
                            <thead>
                                <!-- Top Super Headers -->
                                <tr
                                    class="bg-[#EAE7DF]/70 dark:bg-[#232D26] text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-300 border-b border-slate-200/60 dark:border-[#3F4F43]">
                                    <th class="py-3 px-3 text-center border-r border-slate-200/60 dark:border-[#3F4F43]"
                                        rowspan="2">#</th>
                                    <th class="py-3 px-4 border-r border-slate-200/60 dark:border-[#3F4F43] min-w-[220px]"
                                        rowspan="2">Learner Identification</th>
                                    <th class="py-3 px-3 text-center border-r border-slate-200/60 dark:border-[#3F4F43]"
                                        rowspan="2">Sex</th>
                                    <th class="py-3 px-4 text-center bg-emerald-100/60 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] border-r border-slate-200/60 dark:border-[#3F4F43]"
                                        colspan="6">
                                        ✍️ WRITTEN WORKS (25% WEIGHT) • MAX 100
                                    </th>
                                    <th class="py-3 px-4 text-center bg-amber-50/80 dark:bg-amber-950/40 text-amber-800 dark:text-amber-400 border-r border-slate-200/60 dark:border-[#3F4F43]"
                                        colspan="4">
                                        🔬 PERFORMANCE TASKS (45% WEIGHT) • MAX 120
                                    </th>
                                </tr>
                                <!-- Sub Headers -->
                                <tr
                                    class="bg-[#F9F7F1] dark:bg-[#1E241E] text-[10px] font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-[#3F4F43] text-center">
                                    <!-- Written Works Subheaders -->
                                    <th class="py-2.5 px-2 border-r border-slate-200 dark:border-[#3F4F43]">WW1 (25)
                                    </th>
                                    <th class="py-2.5 px-2 border-r border-slate-200 dark:border-[#3F4F43]">WW2 (25)
                                    </th>
                                    <th class="py-2.5 px-2 border-r border-slate-200 dark:border-[#3F4F43]">WW3 (25)
                                    </th>
                                    <th class="py-2.5 px-2 border-r border-slate-200 dark:border-[#3F4F43]">WW4 (25)
                                    </th>
                                    <th
                                        class="py-2.5 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-50/50 dark:bg-emerald-950/30 text-[#005506] dark:text-[#86EFAC] font-black">
                                        Total (100)</th>
                                    <th
                                        class="py-2.5 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] font-black">
                                        WS (25%)</th>
                                    <!-- Performance Tasks Subheaders -->
                                    <th class="py-2.5 px-2 border-r border-slate-200 dark:border-[#3F4F43]">PT1 (40)
                                    </th>
                                    <th class="py-2.5 px-2 border-r border-slate-200 dark:border-[#3F4F43]">PT2 (40)
                                    </th>
                                    <th class="py-2.5 px-2 border-r border-slate-200 dark:border-[#3F4F43]">PT3 (40)
                                    </th>
                                    <th
                                        class="py-2.5 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-amber-50/50 dark:bg-amber-950/30 text-amber-800 dark:text-amber-400 font-black">
                                        Total (120)</th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-slate-200/60 dark:divide-[#3F4F43] text-slate-700 dark:text-slate-200 text-center">

                                <!-- Row 1 -->
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                                    <td class="py-3 px-3 font-bold border-r border-slate-200/60 dark:border-[#3F4F43]">1
                                    </td>
                                    <td class="py-3 px-4 text-left border-r border-slate-200/60 dark:border-[#3F4F43]">
                                        <p class="font-bold text-slate-900 dark:text-white">Dela Cruz, Juan Carlos M.
                                        </p>
                                        <p class="text-[11px] text-slate-400 font-mono">LRN: 109283746501</p>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200/60 dark:border-[#3F4F43]">
                                        <span
                                            class="bg-slate-100 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 font-bold px-2 py-0.5 rounded text-[10px]">M</span>
                                    </td>
                                    <!-- WW Scores -->
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">24</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">25</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">23</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">25</td>
                                    <td
                                        class="py-3 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-50/40 dark:bg-emerald-950/30 font-black text-[#005506] dark:text-[#86EFAC]">
                                        97</td>
                                    <td
                                        class="py-3 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/40 dark:bg-emerald-950/60 font-black text-[#005506] dark:text-[#86EFAC]">
                                        24.25</td>
                                    <!-- PT Scores -->
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">38</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">39</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">40</td>
                                    <td
                                        class="py-3 px-3 font-black text-amber-700 dark:text-amber-400 bg-amber-50/40 dark:bg-amber-950/30">
                                        117</td>
                                </tr>

                                <!-- Row 2 -->
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                                    <td class="py-3 px-3 font-bold border-r border-slate-200/60 dark:border-[#3F4F43]">2
                                    </td>
                                    <td class="py-3 px-4 text-left border-r border-slate-200/60 dark:border-[#3F4F43]">
                                        <p class="font-bold text-slate-900 dark:text-white">Bautista, Miguel Lorenzo R.
                                        </p>
                                        <p class="text-[11px] text-slate-400 font-mono">LRN: 109283746502</p>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200/60 dark:border-[#3F4F43]">
                                        <span
                                            class="bg-slate-100 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 font-bold px-2 py-0.5 rounded text-[10px]">M</span>
                                    </td>
                                    <!-- WW Scores -->
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">22</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">23</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">21</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">24</td>
                                    <td
                                        class="py-3 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-50/40 dark:bg-emerald-950/30 font-black text-[#005506] dark:text-[#86EFAC]">
                                        90</td>
                                    <td
                                        class="py-3 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/40 dark:bg-emerald-950/60 font-black text-[#005506] dark:text-[#86EFAC]">
                                        22.50</td>
                                    <!-- PT Scores -->
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">36</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">37</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">38</td>
                                    <td
                                        class="py-3 px-3 font-black text-amber-700 dark:text-amber-400 bg-amber-50/40 dark:bg-amber-950/30">
                                        111</td>
                                </tr>

                                <!-- Row 3 -->
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                                    <td class="py-3 px-3 font-bold border-r border-slate-200/60 dark:border-[#3F4F43]">3
                                    </td>
                                    <td class="py-3 px-4 text-left border-r border-slate-200/60 dark:border-[#3F4F43]">
                                        <p class="font-bold text-slate-900 dark:text-white">Santos, Clarissa Mae V.</p>
                                        <p class="text-[11px] text-slate-400 font-mono">LRN: 109283746503</p>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200/60 dark:border-[#3F4F43]">
                                        <span
                                            class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 font-bold px-2 py-0.5 rounded text-[10px]">F</span>
                                    </td>
                                    <!-- WW Scores -->
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">25</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">25</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">24</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">25</td>
                                    <td
                                        class="py-3 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-50/40 dark:bg-emerald-950/30 font-black text-[#005506] dark:text-[#86EFAC]">
                                        99</td>
                                    <td
                                        class="py-3 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/40 dark:bg-emerald-950/60 font-black text-[#005506] dark:text-[#86EFAC]">
                                        24.75</td>
                                    <!-- PT Scores -->
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">40</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">39</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">40</td>
                                    <td
                                        class="py-3 px-3 font-black text-amber-700 dark:text-amber-400 bg-amber-50/40 dark:bg-amber-950/30">
                                        119</td>
                                </tr>

                                <!-- Row 4 -->
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                                    <td class="py-3 px-3 font-bold border-r border-slate-200/60 dark:border-[#3F4F43]">4
                                    </td>
                                    <td class="py-3 px-4 text-left border-r border-slate-200/60 dark:border-[#3F4F43]">
                                        <p class="font-bold text-slate-900 dark:text-white">Alcantara, Kenneth D.</p>
                                        <p class="text-[11px] text-slate-400 font-mono">LRN: 109283746504</p>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200/60 dark:border-[#3F4F43]">
                                        <span
                                            class="bg-slate-100 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 font-bold px-2 py-0.5 rounded text-[10px]">M</span>
                                    </td>
                                    <!-- WW Scores -->
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">20</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">21</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">22</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">21</td>
                                    <td
                                        class="py-3 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-50/40 dark:bg-emerald-950/30 font-black text-[#005506] dark:text-[#86EFAC]">
                                        84</td>
                                    <td
                                        class="py-3 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/40 dark:bg-emerald-950/60 font-black text-[#005506] dark:text-[#86EFAC]">
                                        21.00</td>
                                    <!-- PT Scores -->
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">34</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">35</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">36</td>
                                    <td
                                        class="py-3 px-3 font-black text-amber-700 dark:text-amber-400 bg-amber-50/40 dark:bg-amber-950/30">
                                        105</td>
                                </tr>

                                <!-- Row 5 -->
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40 transition-colors">
                                    <td class="py-3 px-3 font-bold border-r border-slate-200/60 dark:border-[#3F4F43]">5
                                    </td>
                                    <td class="py-3 px-4 text-left border-r border-slate-200/60 dark:border-[#3F4F43]">
                                        <p class="font-bold text-slate-900 dark:text-white">Reyes, Bea Alyssa T.</p>
                                        <p class="text-[11px] text-slate-400 font-mono">LRN: 109283746505</p>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200/60 dark:border-[#3F4F43]">
                                        <span
                                            class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 font-bold px-2 py-0.5 rounded text-[10px]">F</span>
                                    </td>
                                    <!-- WW Scores -->
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">23</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">24</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">24</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">23</td>
                                    <td
                                        class="py-3 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-50/40 dark:bg-emerald-950/30 font-black text-[#005506] dark:text-[#86EFAC]">
                                        94</td>
                                    <td
                                        class="py-3 px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/40 dark:bg-emerald-950/60 font-black text-[#005506] dark:text-[#86EFAC]">
                                        23.50</td>
                                    <!-- PT Scores -->
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">37</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">38</td>
                                    <td class="py-3 px-2 border-r border-slate-200 dark:border-[#3F4F43]">38</td>
                                    <td
                                        class="py-3 px-3 font-black text-amber-700 dark:text-amber-400 bg-amber-50/40 dark:bg-amber-950/30">
                                        113</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination & Sorting Footer -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                            Showing <span class="font-bold text-slate-700 dark:text-slate-200">5 of 45</span> students
                            in Grade 12 STEM – Rizal •
                            <span class="text-slate-400">Sorted: Surname (A–Z)</span>
                        </p>

                        <div class="flex items-center gap-1.5">
                            <button
                                class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-[#3F4F43] text-xs font-bold text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-[#232D26] cursor-not-allowed">
                                Prev
                            </button>
                            <button
                                class="w-9 h-9 rounded-xl bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold flex items-center justify-center shadow-sm">
                                1
                            </button>
                            <button
                                class="w-9 h-9 rounded-xl hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 text-xs font-bold flex items-center justify-center transition-colors">
                                2
                            </button>
                            <button
                                class="w-9 h-9 rounded-xl hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 text-xs font-bold flex items-center justify-center transition-colors">
                                3
                            </button>
                            <button
                                class="w-9 h-9 rounded-xl hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 text-xs font-bold flex items-center justify-center transition-colors">
                                4
                            </button>
                            <button
                                class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-[#3F4F43] text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-[#2D3A31] hover:bg-slate-50 transition-colors">
                                Next
                            </button>
                        </div>
                    </div>

                </div>

                <!-- TWO-COLUMN ANALYTICS & TRANSMUTATION TABLE SECTION -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <!-- LEFT CARD: COHORT DISTRIBUTION -->
                    <div v-observe
                        class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6" style="animation-delay: 600ms;">

                        <div class="space-y-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between gap-4">
                                <div class="space-y-1">
                                    <span
                                        class="text-[11px] font-black uppercase tracking-wider text-[#005506] dark:text-[#86EFAC]">Cohort
                                        Distribution</span>
                                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">Quarter 3
                                        Grade Breakdown</h3>
                                </div>
                                <div
                                    class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border border-emerald-200 dark:border-emerald-900/40 px-3.5 py-2 rounded-2xl text-xs font-extrabold shrink-0 shadow-sm text-center">
                                    N = 45 <br><span class="text-[10px] font-medium">Learners</span>
                                </div>
                            </div>

                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                                Transmuted distributions based on DepEd DO 8, s. 2015 standards. 0 learners at academic
                                risk.
                            </p>

                            <!-- Progress Bars List -->
                            <div class="space-y-4 pt-2">

                                <!-- Range 1: 95 - 100 -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs font-bold">
                                        <span class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                            <span class="w-2.5 h-2.5 rounded-full bg-[#005506]"></span> 95 – 100 (High /
                                            Highest Honors)
                                        </span>
                                        <span class="text-[#005506] dark:text-[#86EFAC]">14 learners (31%)</span>
                                    </div>
                                    <div class="w-full h-3 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                                        <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full w-[31%]"></div>
                                    </div>
                                </div>

                                <!-- Range 2: 90 - 94 -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs font-bold">
                                        <span class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                            <span class="w-2.5 h-2.5 rounded-full bg-[#F9C20C]"></span> 90 – 94 (With
                                            Honors)
                                        </span>
                                        <span class="text-amber-700 dark:text-amber-400">22 learners (49%)</span>
                                    </div>
                                    <div class="w-full h-3 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                                        <div class="h-full bg-[#F9C20C] rounded-full w-[49%]"></div>
                                    </div>
                                </div>

                                <!-- Range 3: 85 - 89 -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs font-bold">
                                        <span class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span> 85 – 89 (Very
                                            Satisfactory)
                                        </span>
                                        <span class="text-emerald-700 dark:text-emerald-400">7 learners (15%)</span>
                                    </div>
                                    <div class="w-full h-3 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                                        <div class="h-full bg-emerald-600 rounded-full w-[15%]"></div>
                                    </div>
                                </div>

                                <!-- Range 4: 80 - 84 -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs font-bold">
                                        <span class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                            <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span> 80 – 84
                                            (Satisfactory)
                                        </span>
                                        <span class="text-slate-600 dark:text-slate-400">2 learners (4%)</span>
                                    </div>
                                    <div class="w-full h-3 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                                        <div class="h-full bg-slate-400 rounded-full w-[4%]"></div>
                                    </div>
                                </div>

                                <!-- Range 5: Below 75 -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs font-bold">
                                        <span class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                            <span class="w-2.5 h-2.5 rounded-full bg-red-600"></span> Below 75 (Did Not
                                            Meet)
                                        </span>
                                        <span class="text-red-600 dark:text-red-400">0 learners (0%)</span>
                                    </div>
                                    <div class="w-full h-3 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                                        <div class="h-full bg-red-600 rounded-full w-[0%]"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Footer Status -->
                        <div
                            class="pt-6 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-700 dark:text-slate-300">
                                Class Mastery Status: <span class="text-[#005506] dark:text-[#86EFAC] font-black">Level
                                    4 (Mastered)</span>
                            </span>
                            <button
                                class="flex items-center gap-1.5 text-xs font-bold text-[#005506] dark:text-[#86EFAC] hover:underline">
                                <span>📈</span> Item Analysis
                            </button>
                        </div>

                    </div>

                    <!-- RIGHT CARD: OFFICIAL TRANSMUTATION TABLE -->
                    <div v-observe
                        class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6" style="animation-delay: 700ms;">

                        <div class="space-y-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between gap-4">
                                <div class="space-y-1">
                                    <span
                                        class="text-[11px] font-black uppercase tracking-wider text-amber-700 dark:text-amber-400">Official
                                        Matrix</span>
                                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">DepEd
                                        Transmutation Table</h3>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-2xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] text-slate-700 dark:text-slate-300 flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                </div>
                            </div>

                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                                Standard Philippine DepEd Conversion scale from Initial Grade to Transmuted Quarterly
                                Grade.
                            </p>

                            <!-- Transmutation Table -->
                            <div class="overflow-x-auto rounded-2xl border border-slate-200/60 dark:border-[#3F4F43]">
                                <table class="w-full text-left border-collapse text-xs font-medium">
                                    <thead>
                                        <tr
                                            class="bg-[#F9F7F1] dark:bg-[#232D26] text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-300 border-b border-slate-200/60 dark:border-[#3F4F43]">
                                            <th class="py-3 px-4">Initial Score Range</th>
                                            <th class="py-3 px-4 text-right">Transmuted Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-slate-700 dark:text-slate-200 font-semibold">
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                                            <td class="py-3 px-4 font-mono font-bold text-slate-900 dark:text-white">
                                                100.00</td>
                                            <td
                                                class="py-3 px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                                                100</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                                            <td class="py-3 px-4 font-mono">98.40 – 99.99</td>
                                            <td
                                                class="py-3 px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                                                99</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                                            <td class="py-3 px-4 font-mono">96.80 – 98.39</td>
                                            <td
                                                class="py-3 px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                                                98</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                                            <td class="py-3 px-4 font-mono">95.20 – 96.79</td>
                                            <td
                                                class="py-3 px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                                                97</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                                            <td class="py-3 px-4 font-mono">93.60 – 95.19</td>
                                            <td
                                                class="py-3 px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                                                96</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                                            <td class="py-3 px-4 font-mono">92.00 – 93.59</td>
                                            <td
                                                class="py-3 px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                                                95</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Footer Info -->
                        <div
                            class="pt-6 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400">
                                Formula: Base 60 Transmutation
                            </span>
                            <a href="#" class="font-extrabold text-[#005506] dark:text-[#86EFAC] hover:underline">
                                Full 4–Page Matrix →
                            </a>
                        </div>

                    </div>

                </div>

            </div>

        </main>

    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import Sidebart from '@/Components/Sidebart.vue'
import navbartop from '@/Components/navbartop.vue'

// Font Scaling State Managed Locally via navbartop event
const fontSizeMode = ref('base')

// Custom directive to re-trigger slide-up/fade-down animations on scroll & revisit
const vObserve = {
  mounted(el) {
    el.classList.add('not-visible')
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) {
        el.classList.add('is-animated')
      } else {
        el.classList.remove('is-animated') // Reverts so it re-triggers when scrolling back
      }
    }, { threshold: 0.1 })
    observer.observe(el)
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800;900&display=swap');

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
    line-height: 2.0rem !important;
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