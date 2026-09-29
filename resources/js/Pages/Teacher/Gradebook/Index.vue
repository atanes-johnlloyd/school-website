<template>

    <Head title="Official Electronic Class Record - Salawag LMS" />

    <div
        class="min-h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300">

        <!-- Sticky Sidebar Navigation -->
        <div class="hidden md:block sticky top-0 h-screen z-40 shrink-0 shadow-lg">
            <Sidebart />
        </div>

        <!-- Main Workspace Canvas -->
        <main
            :class="['flex-1 relative overflow-y-auto min-h-screen flex flex-col w-full min-w-0', `text-scale-${fontSizeMode}`]">

            <!-- Reusable Top Navigation Component -->
            <navbartop searchPlaceholder="Search grade records, student LRN, competencies.."
                @font-size-changed="(size) => fontSizeMode = size" />

            <!-- MAIN PAGE CONTENT -->
            <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

                <!-- GRADEBOOK E-CLASS RECORD HERO BANNER -->
                <div v-observe
                    class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[280px] flex flex-col justify-center anim-fade-down"
                    style="animation-delay: 50ms;">

                    <!-- Hero Image bound to Local Import -->
                    <img :src="heroImage" alt="Gradebook Background"
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
                                class="bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-[11px] md:text-xs font-black uppercase tracking-wider px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1">
                                <span>🏛️</span> DEPED REGION IV-A • SDO DASMARIÑAS
                            </span>
                            <span
                                class="bg-white/15 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm">
                                🛡️ DepEd Order No. 8, s. 2015 Compliant
                            </span>
                            <span
                                class="bg-white/15 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm">
                                🎓 SHS Senior High School STEM Track
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <div class="space-y-2 sm:space-y-3 max-w-2xl">
                            <h2
                                class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
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

                <!-- FOUR-CARD METRIC GRID (ANIMATED & MOBILE RESPONSIVE) -->
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">

                    <!-- CARD 1: CLASS WEIGHTED MEAN -->
                    <div v-observe
                        class="anim-slide-up col-span-1 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3 sm:space-y-4"
                        style="animation-delay: 100ms;">
                        <div class="flex items-start justify-between">
                            <span
                                class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[130px] leading-tight">
                                CLASS WEIGHTED MEAN
                            </span>
                            <div
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-emerald-100/70 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z" />
                                </svg>
                            </div>
                        </div>

                        <div class="space-y-1 sm:space-y-2">
                            <div class="flex items-baseline gap-2">
                                <span
                                    class="text-2xl sm:text-4xl font-black text-[#005506] dark:text-[#86EFAC]">89.2%</span>
                            </div>
                            <div>
                                <span
                                    class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full inline-block">
                                    Very Satisfactory
                                </span>
                            </div>
                        </div>

                        <div
                            class="flex flex-wrap items-center justify-between text-[10px] sm:text-xs font-bold pt-2 border-t border-slate-100 dark:border-[#3F4F43] gap-1">
                            <span class="text-slate-500 dark:text-slate-400">Target: 85.0%</span>
                            <span class="text-[#005506] dark:text-[#86EFAC] font-black">+4.2%</span>
                        </div>
                    </div>

                    <!-- CARD 2: SUBMISSIONS COMPLETE -->
                    <div v-observe
                        class="anim-slide-up col-span-1 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3 sm:space-y-4"
                        style="animation-delay: 200ms;">
                        <div class="flex items-start justify-between">
                            <span
                                class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[130px] leading-tight">
                                SUBMISSIONS COMPLETE
                            </span>
                            <div
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-amber-100/70 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-2 14l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="flex items-baseline">
                                <span class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white">34</span>
                                <span
                                    class="text-xs sm:text-base font-bold text-slate-500 dark:text-slate-400">/38</span>
                            </div>
                            <span
                                class="bg-[#FEF9E7] dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] sm:text-xs font-bold px-2 sm:px-3 py-1 rounded-xl sm:rounded-2xl border border-amber-200/60 dark:border-amber-900/40 text-center leading-tight">
                                89.5%<br><span class="font-normal text-[9px] sm:text-[10px]">Logged</span>
                            </span>
                        </div>

                        <!-- Yellow Progress Bar -->
                        <div class="w-full h-2 sm:h-2.5 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
                            <div class="h-full bg-[#F9C20C] rounded-full" style="width: 89.5%;"></div>
                        </div>
                    </div>

                    <!-- CARD 3: AT-RISK LEARNERS -->
                    <div v-observe
                        class="anim-slide-up col-span-1 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3 sm:space-y-4"
                        style="animation-delay: 300ms;">
                        <div class="flex items-start justify-between">
                            <span
                                class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[130px] leading-tight">
                                AT-RISK LEARNERS
                            </span>
                            <div
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-rose-100/70 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <span class="text-2xl sm:text-4xl font-black text-rose-600 dark:text-rose-400">1</span>
                            <span
                                class="bg-rose-100/80 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10px] sm:text-xs font-bold px-2 sm:px-3 py-0.5 sm:py-1 rounded-full border border-rose-200/60 dark:border-rose-900/40">
                                Intervention
                            </span>
                        </div>

                        <div
                            class="flex flex-wrap items-center justify-between text-[10px] sm:text-xs font-bold pt-2 border-t border-slate-100 dark:border-[#3F4F43] gap-1">
                            <span class="text-slate-500 dark:text-slate-400">Lab 1 Remedial</span>
                            <span class="text-rose-600 dark:text-rose-400 font-black">Priority</span>
                        </div>
                    </div>

                    <!-- CARD 4: SCORE SYNC STATUS -->
                    <div v-observe
                        class="anim-slide-up col-span-1 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3 sm:space-y-4"
                        style="animation-delay: 400ms;">
                        <div class="flex items-start justify-between">
                            <span
                                class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[130px] leading-tight">
                                SCORE SYNC STATUS
                            </span>
                            <div
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-emerald-100/70 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-1.5 sm:gap-2">
                            <span
                                class="text-lg sm:text-2xl font-extrabold text-slate-900 dark:text-white leading-tight">
                                Auto-<br>saved
                            </span>
                            <span
                                class="bg-[#005506] text-white text-[10px] sm:text-[11px] font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full shadow-sm">
                                Secure
                            </span>
                        </div>

                        <div
                            class="flex flex-wrap items-center justify-between text-[10px] sm:text-xs font-bold pt-2 border-t border-slate-100 dark:border-[#3F4F43] gap-1">
                            <span class="text-slate-500 dark:text-slate-400">Synced 2m ago</span>
                            <div class="text-right">
                                <span class="text-slate-900 dark:text-white font-black block">Node #24</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ACADEMIC HORIZON & WEIGHT SCHEME HEADER PANEL (ANIMATED) -->
                <div v-observe
                    class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-6"
                    style="animation-delay: 450ms;">

                    <!-- Select Dropdowns -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 flex-1">
                        <div>
                            <label
                                class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Academic
                                Section</label>
                            <div class="relative">
                                <select
                                    class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-extrabold px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer">
                                    <option>Grade 12 STEM–2 Curie – Active</option>
                                    <option>Grade 12 STEM–1 Rizal</option>
                                    <option>Grade 11 ICT–1 Turing</option>
                                </select>
                                <svg class="w-4 h-4 fill-current text-slate-500 absolute right-3 top-2.5 sm:top-3 pointer-events-none"
                                    viewBox="0 0 24 24">
                                    <path d="M7 10l5 5 5-5z" />
                                </svg>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Subject
                                Assignment</label>
                            <div class="relative">
                                <select
                                    class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-extrabold px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer">
                                    <option>General Physics 1</option>
                                    <option>General Biology 2</option>
                                    <option>Practical Research 2</option>
                                </select>
                                <svg class="w-4 h-4 fill-current text-slate-500 absolute right-3 top-2.5 sm:top-3 pointer-events-none"
                                    viewBox="0 0 24 24">
                                    <path d="M7 10l5 5 5-5z" />
                                </svg>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Term
                                Horizon</label>
                            <div class="relative">
                                <select
                                    class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-extrabold px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer">
                                    <option>1st Quarter Midterm</option>
                                    <option>2nd Quarter Finals</option>
                                </select>
                                <svg class="w-4 h-4 fill-current text-slate-500 absolute right-3 top-2.5 sm:top-3 pointer-events-none"
                                    viewBox="0 0 24 24">
                                    <path d="M7 10l5 5 5-5z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Weight Scheme Legend Box -->
                    <div
                        class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-1.5 sm:space-y-2 shrink-0">
                        <span
                            class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">WEIGHT
                            SCHEME</span>
                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                            <span
                                class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[11px] sm:text-xs font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full border border-emerald-200/60 dark:border-emerald-900/40 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-[#005506] dark:bg-[#86EFAC]"></span> Written (25%)
                            </span>
                            <span
                                class="bg-amber-100/80 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[11px] sm:text-xs font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full border border-amber-200/60 dark:border-amber-900/40 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-600"></span> Performance (50%)
                            </span>
                            <span
                                class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[11px] sm:text-xs font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full border border-emerald-200/60 dark:border-emerald-900/40 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-[#005506] dark:bg-[#86EFAC]"></span> Exam (25%)
                            </span>
                        </div>
                    </div>

                </div>

                <!-- MASTER E-CLASS RECORD SPREADSHEET TOOLBAR & TABLE (ANIMATED) -->
                <div v-observe
                    class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4 sm:space-y-6"
                    style="animation-delay: 500ms;">

                    <!-- Search & Action Toolbar Alignment -->
                    <div
                        class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">

                        <!-- Search input -->
                        <div class="relative flex-1 w-full min-w-0">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                                </svg>
                            </span>
                            <input type="text" placeholder="Search student by name, LRN or ID..."
                                class="w-full pl-10 pr-4 py-2 sm:py-2.5 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-sm" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto shrink-0 pt-1 md:pt-0">
                            <button
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center transition-colors shadow-sm shrink-0"
                                title="Filter Records">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z" />
                                </svg>
                            </button>
                            <button
                                class="bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-extrabold px-2.5 sm:px-3.5 py-2 sm:py-2.5 rounded-xl border border-slate-200 dark:border-[#3F4F43] flex items-center gap-1.5 transition-colors shadow-sm shrink-0">
                                <svg class="w-3.5 h-3.5 fill-current text-slate-500" viewBox="0 0 24 24">
                                    <path d="M9 16h6v-6h4l-7-7-7 7h4v6zm-4 2h14v2H5v-2z" />
                                </svg>
                                Import CSV
                            </button>
                            <button
                                class="bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-extrabold px-2.5 sm:px-3.5 py-2 sm:py-2.5 rounded-xl border border-slate-200 dark:border-[#3F4F43] flex items-center gap-1.5 transition-colors shadow-sm shrink-0">
                                <svg class="w-3.5 h-3.5 fill-current text-slate-500" viewBox="0 0 24 24">
                                    <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z" />
                                </svg>
                                Export
                            </button>
                            <button
                                class="bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-extrabold px-2.5 sm:px-3.5 py-2 sm:py-2.5 rounded-xl border border-slate-200 dark:border-[#3F4F43] flex items-center gap-1.5 transition-colors shadow-sm shrink-0">
                                <svg class="w-3.5 h-3.5 fill-current text-slate-500" viewBox="0 0 24 24">
                                    <path
                                        d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z" />
                                </svg>
                                Print
                            </button>
                        </div>

                    </div>

                    <!-- COMPLETE E-CLASS SPREADSHEET TABLE WITH RESPONSIVE SCROLL -->
                    <div
                        class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
                        <table class="w-full min-w-[780px] text-center border-collapse text-xs font-medium">
                            <thead>
                                <!-- SUPER HEADERS -->
                                <tr
                                    class="bg-[#EAE7DF]/70 dark:bg-[#232D26] text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-300 border-b border-slate-200/80 dark:border-[#3F4F43]">
                                    <th class="py-2.5 sm:py-3 px-2 sm:px-3 border-r border-slate-200/80 dark:border-[#3F4F43]"
                                        rowspan="2">No.</th>
                                    <th class="py-2.5 sm:py-3 px-3 sm:px-4 text-left border-r border-slate-200/80 dark:border-[#3F4F43] min-w-[180px]"
                                        rowspan="2">Learner Details</th>
                                    <th class="py-2.5 sm:py-3 px-3 sm:px-4 bg-emerald-100/60 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] border-r border-slate-200/80 dark:border-[#3F4F43]"
                                        colspan="5">
                                        Written Works (25%)
                                    </th>
                                    <th class="py-2.5 sm:py-3 px-3 sm:px-4 bg-amber-50/80 dark:bg-amber-950/40 text-amber-900 dark:text-amber-300 border-r border-slate-200/80 dark:border-[#3F4F43]"
                                        colspan="5">
                                        Performance Tasks (50%)
                                    </th>
                                    <th class="py-2.5 sm:py-3 px-3 sm:px-4 bg-emerald-100/60 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] border-r border-slate-200/80 dark:border-[#3F4F43]"
                                        colspan="2">
                                        Quarterly Exam (25%)
                                    </th>
                                    <th class="py-2.5 sm:py-3 px-2 sm:px-3 bg-[#EAE7DF] dark:bg-[#1E241E] border-r border-slate-200/80 dark:border-[#3F4F43]"
                                        rowspan="2">
                                        Initial<br><span
                                            class="text-[9px] sm:text-[10px] font-normal text-slate-500">(100%)</span>
                                    </th>
                                    <th class="py-2.5 sm:py-3 px-3 sm:px-4 bg-[#005506] text-white dark:bg-[#86EFAC] dark:text-[#232D26] border-r border-slate-200/80 dark:border-[#3F4F43]"
                                        rowspan="2">
                                        Transmuted<br>Grade
                                    </th>
                                    <th class="py-2.5 sm:py-3 px-3 sm:px-4 border-r border-slate-200/80 dark:border-[#3F4F43]"
                                        rowspan="2">
                                        Standing
                                    </th>
                                </tr>

                                <!-- SUB HEADERS -->
                                <tr
                                    class="bg-[#F9F7F1] dark:bg-[#1E241E] text-[9px] sm:text-[10px] font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-[#3F4F43]">
                                    <!-- Written Works -->
                                    <th class="py-2 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">
                                        WW1<br><span class="font-normal text-slate-400">/20</span></th>
                                    <th class="py-2 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">
                                        WW2<br><span class="font-normal text-slate-400">/25</span></th>
                                    <th class="py-2 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">
                                        WW3<br><span class="font-normal text-slate-400">/30</span></th>
                                    <th
                                        class="py-2 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-50/50 text-[#005506] dark:text-[#86EFAC] font-black">
                                        Tot<br><span class="font-normal">/75</span></th>
                                    <th
                                        class="py-2 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/50 text-[#005506] dark:text-[#86EFAC] font-black">
                                        25%</th>

                                    <!-- Performance Tasks -->
                                    <th class="py-2 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">
                                        Lab1<br><span class="font-normal text-slate-400">/50</span></th>
                                    <th class="py-2 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">
                                        Proj<br><span class="font-normal text-slate-400">/50</span></th>
                                    <th class="py-2 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">
                                        Prct<br><span class="font-normal text-slate-400">/50</span></th>
                                    <th
                                        class="py-2 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-amber-50/50 text-amber-900 dark:text-amber-300 font-black">
                                        Tot<br><span class="font-normal">/150</span></th>
                                    <th
                                        class="py-2 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-amber-100/50 text-amber-900 dark:text-amber-300 font-black">
                                        50%</th>

                                    <!-- Quarterly Exam -->
                                    <th class="py-2 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">
                                        QA<br><span class="font-normal text-slate-400">/100</span></th>
                                    <th
                                        class="py-2 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/50 text-[#005506] dark:text-[#86EFAC] font-black">
                                        25%</th>
                                </tr>
                            </thead>

                            <tbody
                                class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-slate-700 dark:text-slate-200">

                                <!-- ROW 1 -->
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                                    <td
                                        class="py-3 px-2 sm:px-3 font-bold border-r border-slate-200/80 dark:border-[#3F4F43]">
                                        01</td>
                                    <td
                                        class="py-3 px-3 sm:px-4 text-left border-r border-slate-200/80 dark:border-[#3F4F43]">
                                        <p class="font-extrabold text-slate-900 dark:text-white">Domenic Roob</p>
                                        <p class="text-[10px] sm:text-[11px] font-mono text-slate-400">SSH-2026-4401</p>
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">19
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">24
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">28
                                    </td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-50/40 font-black text-[#005506] dark:text-[#86EFAC]">
                                        71</td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/40 font-black text-[#005506] dark:text-[#86EFAC]">
                                        23.7</td>

                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">48
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">47
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">49
                                    </td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-amber-50/40 font-black text-amber-900 dark:text-amber-300">
                                        144</td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-amber-100/40 font-black text-amber-900 dark:text-amber-300">
                                        48.0</td>

                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">94
                                    </td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/40 font-black text-[#005506] dark:text-[#86EFAC]">
                                        23.5</td>

                                    <td
                                        class="py-3 px-2 sm:px-3 font-bold border-r border-slate-200/80 dark:border-[#3F4F43] bg-slate-50 dark:bg-[#232D26]">
                                        95.2</td>
                                    <td
                                        class="py-3 px-3 sm:px-4 font-black text-base sm:text-lg text-[#005506] dark:text-[#86EFAC] bg-emerald-50 dark:bg-emerald-950/60 border-r border-slate-200/80 dark:border-[#3F4F43]">
                                        97</td>
                                    <td class="py-3 px-3 sm:px-4">
                                        <span
                                            class="bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border border-emerald-200 dark:border-emerald-900/40 text-[10px] sm:text-[11px] font-extrabold px-2 sm:px-3 py-0.5 sm:py-1 rounded-full inline-flex items-center gap-1">
                                            ✔ High Mastery
                                        </span>
                                    </td>
                                </tr>

                                <!-- ROW 2 -->
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                                    <td
                                        class="py-3 px-2 sm:px-3 font-bold border-r border-slate-200/80 dark:border-[#3F4F43]">
                                        02</td>
                                    <td
                                        class="py-3 px-3 sm:px-4 text-left border-r border-slate-200/80 dark:border-[#3F4F43]">
                                        <p class="font-extrabold text-slate-900 dark:text-white">Bernie Mann</p>
                                        <p class="text-[10px] sm:text-[11px] font-mono text-slate-400">SSH-2026-4402</p>
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">17
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">21
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">25
                                    </td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-50/40 font-black text-[#005506] dark:text-[#86EFAC]">
                                        63</td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/40 font-black text-[#005506] dark:text-[#86EFAC]">
                                        21.0</td>

                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">42
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">44
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">40
                                    </td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-amber-50/40 font-black text-amber-900 dark:text-amber-300">
                                        126</td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-amber-100/40 font-black text-amber-900 dark:text-amber-300">
                                        42.0</td>

                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">82
                                    </td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/40 font-black text-[#005506] dark:text-[#86EFAC]">
                                        20.5</td>

                                    <td
                                        class="py-3 px-2 sm:px-3 font-bold border-r border-slate-200/80 dark:border-[#3F4F43] bg-slate-50 dark:bg-[#232D26]">
                                        83.5</td>
                                    <td
                                        class="py-3 px-3 sm:px-4 font-black text-base sm:text-lg text-amber-800 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 border-r border-slate-200/80 dark:border-[#3F4F43]">
                                        89</td>
                                    <td class="py-3 px-3 sm:px-4">
                                        <span
                                            class="bg-amber-100 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 border border-amber-200 dark:border-amber-900/40 text-[10px] sm:text-[11px] font-extrabold px-2 sm:px-3 py-0.5 sm:py-1 rounded-full inline-flex items-center gap-1">
                                            ✔ Good
                                        </span>
                                    </td>
                                </tr>

                                <!-- ROW 3 -->
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                                    <td
                                        class="py-3 px-2 sm:px-3 font-bold border-r border-slate-200/80 dark:border-[#3F4F43]">
                                        03</td>
                                    <td
                                        class="py-3 px-3 sm:px-4 text-left border-r border-slate-200/80 dark:border-[#3F4F43]">
                                        <p class="font-extrabold text-slate-900 dark:text-white">Mack Howell</p>
                                        <p class="text-[10px] sm:text-[11px] font-mono text-slate-400">SSH-2026-4403</p>
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">18
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">23
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">27
                                    </td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-50/40 font-black text-[#005506] dark:text-[#86EFAC]">
                                        68</td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/40 font-black text-[#005506] dark:text-[#86EFAC]">
                                        22.7</td>

                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">46
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">45
                                    </td>
                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">48
                                    </td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-amber-50/40 font-black text-amber-900 dark:text-amber-300">
                                        139</td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-amber-100/40 font-black text-amber-900 dark:text-amber-300">
                                        46.3</td>

                                    <td class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">90
                                    </td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-emerald-100/40 font-black text-[#005506] dark:text-[#86EFAC]">
                                        22.5</td>

                                    <td
                                        class="py-3 px-2 sm:px-3 font-bold border-r border-slate-200/80 dark:border-[#3F4F43] bg-slate-50 dark:bg-[#232D26]">
                                        91.5</td>
                                    <td
                                        class="py-3 px-3 sm:px-4 font-black text-base sm:text-lg text-[#005506] dark:text-[#86EFAC] bg-emerald-50 dark:bg-emerald-950/60 border-r border-slate-200/80 dark:border-[#3F4F43]">
                                        94</td>
                                    <td class="py-3 px-3 sm:px-4">
                                        <span
                                            class="bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border border-emerald-200 dark:border-emerald-900/40 text-[10px] sm:text-[11px] font-extrabold px-2 sm:px-3 py-0.5 sm:py-1 rounded-full inline-flex items-center gap-1">
                                            ✔ High Mastery
                                        </span>
                                    </td>
                                </tr>

                                <!-- ROW 4 (FLAGGED AT RISK) -->
                                <tr class="bg-rose-50/40 dark:bg-rose-950/20 hover:bg-rose-50/70 transition-colors">
                                    <td
                                        class="py-3 px-2 sm:px-3 font-bold text-rose-700 dark:text-rose-400 border-r border-slate-200/80 dark:border-[#3F4F43]">
                                        04</td>
                                    <td
                                        class="py-3 px-3 sm:px-4 text-left border-r border-slate-200/80 dark:border-[#3F4F43]">
                                        <p class="font-black text-rose-700 dark:text-rose-400">Priscilla Beahan</p>
                                        <p
                                            class="text-[10px] sm:text-[11px] font-extrabold text-rose-600 dark:text-rose-400">
                                            SSH-2026-4404 • Flagged</p>
                                    </td>
                                    <td
                                        class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43] text-rose-700 font-bold">
                                        11</td>
                                    <td
                                        class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43] text-rose-700 font-bold">
                                        14</td>
                                    <td
                                        class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43] text-rose-700 font-bold">
                                        16</td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-rose-100/50 font-black text-rose-800">
                                        41</td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-rose-100/80 font-black text-rose-800">
                                        13.7</td>

                                    <td
                                        class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43] text-rose-700 font-bold">
                                        24</td>
                                    <td
                                        class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43] text-rose-700 font-bold">
                                        28</td>
                                    <td
                                        class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43] text-rose-700 font-bold">
                                        22</td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-rose-100/50 font-black text-rose-800">
                                        74</td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-rose-100/80 font-black text-rose-800">
                                        24.7</td>

                                    <td
                                        class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43] text-rose-700 font-bold">
                                        62</td>
                                    <td
                                        class="py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] bg-rose-100/80 font-black text-rose-800">
                                        15.5</td>

                                    <td
                                        class="py-3 px-2 sm:px-3 font-bold border-r border-slate-200/80 dark:border-[#3F4F43] bg-rose-100/40 text-rose-900">
                                        53.9</td>
                                    <td
                                        class="py-3 px-3 sm:px-4 font-black text-base sm:text-lg text-rose-700 dark:text-rose-400 bg-rose-100 border-r border-slate-200/80 dark:border-[#3F4F43]">
                                        72</td>
                                    <td class="py-3 px-3 sm:px-4">
                                        <span
                                            class="bg-rose-100 dark:bg-rose-950/80 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-900/40 text-[10px] sm:text-[11px] font-extrabold px-2 sm:px-3 py-0.5 sm:py-1 rounded-full inline-flex items-center gap-1">
                                            ⚠️ Intervention
                                        </span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <!-- ALIGNED PAGINATION FOOTER -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 pt-2">
                        <p
                            class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium text-center sm:text-left">
                            Displaying <strong class="text-slate-800 dark:text-white">8 of 38</strong> enrolled learners
                            • Section Curie
                        </p>

                        <div class="flex items-center gap-1 sm:gap-1.5">
                            <button
                                class="px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-[#2D3A31] hover:bg-slate-50 transition-colors">
                                Prev
                            </button>
                            <button
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-black flex items-center justify-center shadow-sm">
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
                                class="px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-[#2D3A31] hover:bg-slate-50 transition-colors">
                                Next
                            </button>
                        </div>
                    </div>

                </div>

                <!-- TWO-COLUMN ANALYTICS & TRANSMUTATION TABLE SECTION -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8">

                    <!-- LEFT CARD: COHORT DISTRIBUTION -->
                    <div v-observe
                        class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-6"
                        style="animation-delay: 600ms;">

                        <div class="space-y-4 sm:space-y-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between gap-3 sm:gap-4">
                                <div class="space-y-0.5 sm:space-y-1">
                                    <span
                                        class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-[#005506] dark:text-[#86EFAC]">Cohort
                                        Distribution</span>
                                    <h3 class="text-lg sm:text-2xl font-black text-slate-900 dark:text-white">Quarter 3
                                        Breakdown</h3>
                                </div>
                                <div
                                    class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border border-emerald-200 dark:border-emerald-900/40 px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-xl sm:rounded-2xl text-[11px] sm:text-xs font-extrabold shrink-0 shadow-sm text-center">
                                    N = 45 <br><span class="text-[9px] sm:text-[10px] font-medium">Learners</span>
                                </div>
                            </div>

                            <p
                                class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                                Transmuted distributions based on DepEd DO 8, s. 2015 standards.
                            </p>

                            <!-- Progress Bars List -->
                            <div class="space-y-3 sm:space-y-4 pt-1 sm:pt-2">

                                <!-- Range 1: 95 - 100 -->
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-[11px] sm:text-xs font-bold">
                                        <span class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                            <span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-[#005506]"></span> 95
                                            – 100 (Highest Honors)
                                        </span>
                                        <span class="text-[#005506] dark:text-[#86EFAC]">14 (31%)</span>
                                    </div>
                                    <div
                                        class="w-full h-2.5 sm:h-3 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                                        <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full w-[31%]"></div>
                                    </div>
                                </div>

                                <!-- Range 2: 90 - 94 -->
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-[11px] sm:text-xs font-bold">
                                        <span class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                            <span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-[#F9C20C]"></span> 90
                                            – 94 (With Honors)
                                        </span>
                                        <span class="text-amber-700 dark:text-amber-400">22 (49%)</span>
                                    </div>
                                    <div
                                        class="w-full h-2.5 sm:h-3 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                                        <div class="h-full bg-[#F9C20C] rounded-full w-[49%]"></div>
                                    </div>
                                </div>

                                <!-- Range 3: 85 - 89 -->
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-[11px] sm:text-xs font-bold">
                                        <span class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                            <span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-emerald-600"></span>
                                            85 – 89 (Very Satisfactory)
                                        </span>
                                        <span class="text-emerald-700 dark:text-emerald-400">7 (15%)</span>
                                    </div>
                                    <div
                                        class="w-full h-2.5 sm:h-3 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                                        <div class="h-full bg-emerald-600 rounded-full w-[15%]"></div>
                                    </div>
                                </div>

                                <!-- Range 4: 80 - 84 -->
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-[11px] sm:text-xs font-bold">
                                        <span class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                            <span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-slate-400"></span> 80
                                            – 84 (Satisfactory)
                                        </span>
                                        <span class="text-slate-600 dark:text-slate-400">2 (4%)</span>
                                    </div>
                                    <div
                                        class="w-full h-2.5 sm:h-3 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                                        <div class="h-full bg-slate-400 rounded-full w-[4%]"></div>
                                    </div>
                                </div>

                                <!-- Range 5: Below 75 -->
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-[11px] sm:text-xs font-bold">
                                        <span class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                            <span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-red-600"></span>
                                            Below 75 (Did Not Meet)
                                        </span>
                                        <span class="text-red-600 dark:text-red-400">0 (0%)</span>
                                    </div>
                                    <div
                                        class="w-full h-2.5 sm:h-3 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                                        <div class="h-full bg-red-600 rounded-full w-[0%]"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Footer Status -->
                        <div
                            class="pt-4 sm:pt-6 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[11px] sm:text-xs">
                            <span class="font-bold text-slate-700 dark:text-slate-300">
                                Mastery: <span class="text-[#005506] dark:text-[#86EFAC] font-black">Level 4</span>
                            </span>
                            <button
                                class="flex items-center gap-1 font-bold text-[#005506] dark:text-[#86EFAC] hover:underline">
                                <span>📈</span> Item Analysis
                            </button>
                        </div>

                    </div>

                    <!-- RIGHT CARD: OFFICIAL TRANSMUTATION TABLE -->
                    <div v-observe
                        class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-6"
                        style="animation-delay: 700ms;">

                        <div class="space-y-4 sm:space-y-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between gap-4">
                                <div class="space-y-0.5 sm:space-y-1">
                                    <span
                                        class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-amber-700 dark:text-amber-400">Official
                                        Matrix</span>
                                    <h3 class="text-lg sm:text-2xl font-black text-slate-900 dark:text-white">DepEd
                                        Transmutation Table</h3>
                                </div>
                                <div
                                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] text-slate-700 dark:text-slate-300 flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                </div>
                            </div>

                            <p
                                class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                                Standard DepEd Conversion scale from Initial Grade to Transmuted Quarterly Grade.
                            </p>

                            <!-- Transmutation Table -->
                            <div
                                class="overflow-x-auto rounded-xl sm:rounded-2xl border border-slate-200/60 dark:border-[#3F4F43]">
                                <table class="w-full text-left border-collapse text-xs font-medium">
                                    <thead>
                                        <tr
                                            class="bg-[#F9F7F1] dark:bg-[#232D26] text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-300 border-b border-slate-200/60 dark:border-[#3F4F43]">
                                            <th class="py-2.5 sm:py-3 px-3 sm:px-4">Initial Score Range</th>
                                            <th class="py-2.5 sm:py-3 px-3 sm:px-4 text-right">Transmuted Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-slate-700 dark:text-slate-200 font-semibold text-[11px] sm:text-xs">
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                                            <td
                                                class="py-2.5 sm:py-3 px-3 sm:px-4 font-mono font-bold text-slate-900 dark:text-white">
                                                100.00</td>
                                            <td
                                                class="py-2.5 sm:py-3 px-3 sm:px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                                                100</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 font-mono">98.40 – 99.99</td>
                                            <td
                                                class="py-2.5 sm:py-3 px-3 sm:px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                                                99</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 font-mono">96.80 – 98.39</td>
                                            <td
                                                class="py-2.5 sm:py-3 px-3 sm:px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                                                98</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 font-mono">95.20 – 96.79</td>
                                            <td
                                                class="py-2.5 sm:py-3 px-3 sm:px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                                                97</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 font-mono">93.60 – 95.19</td>
                                            <td
                                                class="py-2.5 sm:py-3 px-3 sm:px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                                                96</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 font-mono">92.00 – 93.59</td>
                                            <td
                                                class="py-2.5 sm:py-3 px-3 sm:px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                                                95</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Footer Info -->
                        <div
                            class="pt-4 sm:pt-6 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[11px] sm:text-xs">
                            <span class="text-slate-500 dark:text-slate-400">
                                Base 60 Transmutation
                            </span>
                            <a href="#" class="font-extrabold text-[#005506] dark:text-[#86EFAC] hover:underline">
                                Full Matrix →
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
import heroImage from '../../../../assets/img/local/gradebook.png'

const fontSizeMode = ref('base')

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