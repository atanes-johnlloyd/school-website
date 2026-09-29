<template>

    <Head title="Quiz Hub & Exam Bank - Salawag LMS" />

    <div
        class="h-screen w-full flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300 overflow-hidden">

        <!-- Sticky Sidebar Navigation -->
        <aside class="hidden md:block sticky top-0 h-screen z-40 shrink-0 shadow-lg">
            <Sidebart />
        </aside>

        <!-- Main Workspace Canvas -->
        <main :class="['flex-1 min-w-0 relative overflow-y-auto h-screen flex flex-col', `text-scale-${fontSizeMode}`]">

            <!-- Reusable Top Navigation Component -->
            <navbartop searchPlaceholder="Search question sets, Bloom's taxonomy nodes, quiz banks.."
                @font-size-changed="(size) => fontSizeMode = size" />

            <!-- MAIN PAGE CONTENT -->
            <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2 max-w-full">

                <!-- MATCHED QUIZ HUB HERO BANNER -->
                <div v-observe
                    class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[260px] flex flex-col justify-center">
                    <!-- Background Image properly aligned and fitted -->
                    <img :src="heroImage" alt="Quiz Hub Background"
                        class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

                    <!-- Animated Green Overlay -->
                    <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply">
                    </div>

                    <!-- Content Container -->
                    <div class="relative z-10 p-4 sm:p-8 md:p-10 max-w-3xl space-y-3 sm:space-y-4">

                        <!-- Quarter Badge -->
                        <div
                            class="inline-flex items-center gap-1.5 sm:gap-2 bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm max-w-full truncate">
                            <span class="shrink-0">📅</span> <span class="truncate">SY 2025–2026 • Quarter 2 Assessment
                                Hub</span>
                        </div>

                        <!-- Main Title & Description -->
                        <div class="space-y-2 sm:space-y-3">
                            <h2
                                class="text-xl sm:text-3xl md:text-5xl font-black text-white tracking-tight leading-tight">
                                Quiz Hub Builder & Question Bank Manager
                            </h2>
                            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                                Design gamified assessments, calibrate three-tiered non-shaming hints, and manage
                                student learning nodes across Salawag Senior High's interactive curriculum map.
                            </p>
                        </div>

                    </div>
                </div>

                <!-- METRICS OVERVIEW & SUBJECT BANK FILTERS SECTION -->
                <div class="space-y-6">

                    <!-- 4-CARD METRICS GRID -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

                        <!-- 1. INTERACTIVE NODES -->
                        <div v-observe style="animation-delay: 100ms;"
                            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">
                            <div class="flex items-start justify-between">
                                <span
                                    class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">
                                    INTERACTIVE NODES
                                </span>
                                <div
                                    class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-[#006907] dark:text-[#86EFAC] flex items-center justify-center shrink-0">
                                    🗺️
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-2xl font-black text-slate-900 dark:text-white">8</span>
                                    <span class="text-sm font-black text-[#006907] dark:text-[#86EFAC]">Zones
                                        Active</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                                    Live learning nodes populated on student maps
                                </p>
                            </div>

                            <div class="w-full h-1.5 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
                                <div class="h-full bg-[#006907] dark:bg-[#86EFAC] rounded-full w-full"></div>
                            </div>
                        </div>

                        <!-- 2. REPOSITORY SCALE -->
                        <div v-observe style="animation-delay: 200ms;"
                            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">
                            <div class="flex items-start justify-between">
                                <span
                                    class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">
                                    REPOSITORY SCALE
                                </span>
                                <div
                                    class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 flex items-center justify-center shrink-0">
                                    📒
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-2xl font-black text-slate-900 dark:text-white">184</span>
                                    <span class="text-sm font-black text-amber-800 dark:text-amber-400">Categorized
                                        Items</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                                    Spanning STEM General Physics & Calculus
                                </p>
                            </div>

                            <div class="w-full h-1.5 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
                                <div class="h-full bg-[#F9C20C] rounded-full w-3/4"></div>
                            </div>
                        </div>

                        <!-- 3. GAMIFICATION ECONOMY -->
                        <div v-observe style="animation-delay: 300ms;"
                            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">
                            <div class="flex items-start justify-between">
                                <span
                                    class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">
                                    GAMIFICATION ECONOMY
                                </span>
                                <div
                                    class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 flex items-center justify-center shrink-0">
                                    ⚡
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-2xl font-black text-slate-900 dark:text-white">14,250</span>
                                    <span class="text-sm font-black text-amber-800 dark:text-amber-400">Sparks</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                                    Distributed to learners this grading cycle
                                </p>
                            </div>

                            <div class="w-full h-1.5 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
                                <div class="h-full bg-[#F9C20C] rounded-full w-4/5"></div>
                            </div>
                        </div>

                        <!-- 4. NON-SHAMING HINT RATE -->
                        <div v-observe style="animation-delay: 400ms;"
                            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">
                            <div class="flex items-start justify-between">
                                <span
                                    class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">
                                    NON-SHAMING HINT RATE
                                </span>
                                <div
                                    class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-[#006907] dark:text-[#86EFAC] flex items-center justify-center shrink-0">
                                    🧠
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-2xl font-black text-slate-900 dark:text-white">34%</span>
                                    <span class="text-sm font-black text-orange-700 dark:text-orange-400">Flux
                                        Modules</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                                    Used 'Clear the Fog' hint to overcome roadblocks
                                </p>
                            </div>

                            <div class="w-full h-1.5 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
                                <div class="h-full bg-orange-600 rounded-full w-1/3"></div>
                            </div>
                        </div>

                    </div>

                    <!-- SUBJECT BANK & DIFFICULTY BREAKDOWN BAR -->
                    <div v-observe style="animation-delay: 500ms;"
                        class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 sm:gap-6">

                        <!-- Left Filter Pills -->
                        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                            <span
                                class="text-xs font-bold text-slate-600 dark:text-slate-400 mr-1 w-full sm:w-auto">Subject
                                Bank:</span>

                            <div
                                class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 w-full sm:w-auto scrollbar-none">
                                <button
                                    class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] px-4 py-2 rounded-full text-xs font-bold shadow-sm whitespace-nowrap">
                                    All Subjects (184)
                                </button>

                                <button
                                    class="bg-[#F2EFE9] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-[#3F4F43] px-4 py-2 rounded-full text-xs font-semibold transition-colors whitespace-nowrap">
                                    General Physics 2 (82 questions)
                                </button>

                                <button
                                    class="bg-[#F2EFE9] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-[#3F4F43] px-4 py-2 rounded-full text-xs font-semibold transition-colors whitespace-nowrap">
                                    Basic Calculus (54 questions)
                                </button>

                                <button
                                    class="bg-[#F2EFE9] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-[#3F4F43] px-4 py-2 rounded-full text-xs font-semibold transition-colors whitespace-nowrap">
                                    TechPro Electronics (48 questions)
                                </button>
                            </div>
                        </div>

                        <!-- Right Difficulty Pills -->
                        <div
                            class="flex flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-3 shrink-0 pt-3 lg:pt-0 border-t lg:border-t-0 lg:border-l border-slate-100 dark:border-[#3F4F43] lg:pl-6 w-full lg:w-auto overflow-x-auto">

                            <div
                                class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-900/40 text-emerald-900 dark:text-emerald-300 px-3 sm:px-4 py-2 rounded-full text-xs font-extrabold flex items-center gap-1 whitespace-nowrap">
                                <span>Easy: Recall (42)</span>
                            </div>

                            <div
                                class="bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-900/40 text-amber-900 dark:text-amber-300 px-3 sm:px-4 py-2 rounded-full text-xs font-extrabold flex items-center gap-1 whitespace-nowrap">
                                <span>Moderate: Application (94)</span>
                            </div>

                            <div
                                class="bg-rose-100 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900/40 text-rose-900 dark:text-rose-300 px-3 sm:px-4 py-2 rounded-full text-xs font-extrabold flex items-center gap-1 whitespace-nowrap">
                                <span>Challenging: Derivation (48)</span>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- QUESTION ITEM EDITOR & GAMIFIED HINT CONFIGURATOR GRID -->
                <div v-observe style="animation-delay: 100ms;"
                    class="anim-slide-up grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

                    <!-- LEFT COLUMN: QUESTION ITEM EDITOR (7 COLS) -->
                    <div
                        class="lg:col-span-7 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-5 sm:space-y-6">

                        <!-- Header Badge & Taxonomy Pill -->
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 sm:gap-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#005506] text-white font-black text-xs flex items-center justify-center shrink-0">
                                    14
                                </div>
                                <div>
                                    <h3
                                        class="text-xs font-black uppercase tracking-wider text-amber-800 dark:text-amber-400">
                                        GENERAL PHYSICS 2 • MODULE 4 (ELECTROMAGNETISM)
                                    </h3>
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Magnetic Force on a Moving Charge (Lorentz Equation)
                                    </p>
                                </div>
                            </div>

                            <div
                                class="bg-amber-100 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-black px-3 py-1.5 rounded-full border border-amber-200 dark:border-amber-900/40 text-left sm:text-center shrink-0 self-start sm:self-auto">
                                Taxonomy: Derivation / Solving
                            </div>
                        </div>

                        <!-- Assessment Type Selector -->
                        <div class="space-y-2">
                            <label
                                class="text-xs font-black uppercase tracking-wider text-slate-600 dark:text-slate-400 block">
                                Assessment Type
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <button
                                    class="bg-emerald-50 dark:bg-emerald-950/60 border-2 border-[#005506] dark:border-[#86EFAC] text-[#005506] dark:text-[#86EFAC] py-2 px-2 sm:px-3 rounded-xl text-xs font-black flex items-center justify-center gap-1.5 shadow-sm text-center">
                                    <span>🔘</span> Multiple Choice
                                </button>
                                <button
                                    class="bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] text-slate-600 dark:text-slate-300 py-2 px-2 sm:px-3 rounded-xl text-xs font-bold hover:bg-slate-100 flex items-center justify-center gap-1.5 text-center">
                                    <span>📝</span> Problem Solving
                                </button>
                                <button
                                    class="bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] text-slate-600 dark:text-slate-300 py-2 px-2 sm:px-3 rounded-xl text-xs font-bold hover:bg-slate-100 flex items-center justify-center gap-1.5 text-center">
                                    <span>🌱</span> Matching Type
                                </button>
                                <button
                                    class="bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] text-slate-600 dark:text-slate-300 py-2 px-2 sm:px-3 rounded-xl text-xs font-bold hover:bg-slate-100 flex items-center justify-center gap-1.5 text-center">
                                    <span>💬</span> True / False
                                </button>
                            </div>
                        </div>

                        <!-- Question Prompt Field -->
                        <div class="space-y-2">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                <label
                                    class="text-xs font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                    Item Stem / Question Prompt
                                </label>
                                <span class="text-[10px] font-semibold text-slate-400">Supports LaTeX syntax & DepEd
                                    diagrams</span>
                            </div>
                            <textarea rows="4"
                                class="w-full bg-[#FEF9E7] dark:bg-[#232D26] p-3 sm:p-4 rounded-2xl border border-amber-200 dark:border-[#3F4F43] text-xs font-medium text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#006907] resize-none leading-relaxed"
                                placeholder="Type question prompt...">A proton ($q = 1.6 \times 10^{-19}\text{ C}$) travels eastward with a velocity of $4.5 \times 10^5\text{ m/s}$ directly perpendicular into a uniform magnetic field of $0.65\text{ T}$ oriented vertically downward. Calculate the magnitude and spatial direction of the...</textarea>
                        </div>

                        <!-- Asset Attachment Box -->
                        <div
                            class="bg-[#FEF9E7]/60 dark:bg-[#232D26] p-3 sm:p-4 rounded-2xl border border-amber-200/80 dark:border-[#3F4F43] flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                            <div
                                class="w-16 h-12 sm:w-20 sm:h-16 bg-white dark:bg-[#2D3A31] rounded-xl border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center shrink-0 p-1">
                                <span class="text-xl">📊</span>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-xs font-black text-slate-900 dark:text-white">Vector Orientation Diagram
                                    (Figure 4.2–A)</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">High-contrast SVG
                                    loaded for colorblind learner accessibility.</p>
                                <div
                                    class="flex items-center gap-3 text-xs font-bold text-[#005506] dark:text-[#86EFAC] pt-0.5">
                                    <button class="hover:underline">Replace Asset</button>
                                    <span>•</span>
                                    <button class="hover:underline">Add Alt-Description</button>
                                </div>
                            </div>
                        </div>

                        <!-- Options & Distractors (Single Answer Key) -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label
                                    class="text-xs font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">
                                    Options & Distractors
                                </label>
                                <span class="text-[10px] font-black text-[#005506] dark:text-[#86EFAC]">Single Answer
                                    Selected</span>
                            </div>

                            <!-- Option A -->
                            <div
                                class="flex flex-col sm:flex-row sm:items-center gap-2 bg-[#FEF9E7]/40 dark:bg-[#232D26]/40 p-2 sm:p-0 rounded-xl sm:rounded-none">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="answer_key"
                                        class="w-4 h-4 text-[#005506] focus:ring-[#005506]">
                                    <span class="text-xs font-extrabold text-slate-500 w-4">A</span>
                                </div>
                                <input type="text" value="2.93 × 10⁻¹⁴ N, directed North"
                                    class="flex-1 bg-[#FEF9E7] dark:bg-[#232D26] px-3 sm:px-4 py-2.5 rounded-xl border border-amber-200 dark:border-[#3F4F43] text-xs font-bold text-slate-800 dark:text-slate-200 focus:outline-none w-full" />
                                <span
                                    class="bg-[#FEF9E7] dark:bg-[#232D26] text-slate-500 text-[10px] font-bold px-3 py-2 rounded-xl border border-amber-200 dark:border-[#3F4F43] shrink-0 self-start sm:self-auto">Distractor
                                    1</span>
                            </div>

                            <!-- Option B (Correct Answer Key) -->
                            <div
                                class="flex flex-col sm:flex-row sm:items-center gap-2 bg-emerald-50/50 dark:bg-emerald-950/30 p-2.5 sm:p-1.5 rounded-2xl border border-emerald-200/60 dark:border-emerald-900/40">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="answer_key" checked
                                        class="w-4 h-4 text-[#005506] focus:ring-[#005506]">
                                    <span class="text-xs font-extrabold text-[#005506] dark:text-[#86EFAC] w-4">B</span>
                                </div>
                                <input type="text" value="4.68 × 10⁻¹⁴ N, directed North"
                                    class="flex-1 bg-white dark:bg-[#232D26] px-3 sm:px-4 py-2.5 rounded-xl border-2 border-[#005506] dark:border-[#86EFAC] text-xs font-black text-slate-900 dark:text-white focus:outline-none w-full" />
                                <span
                                    class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-[10px] font-black px-3 py-2 rounded-xl shrink-0 self-start sm:self-auto">Correct
                                    Answer Key</span>
                            </div>

                            <!-- Option C -->
                            <div
                                class="flex flex-col sm:flex-row sm:items-center gap-2 bg-[#FEF9E7]/40 dark:bg-[#232D26]/40 p-2 sm:p-0 rounded-xl sm:rounded-none">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="answer_key"
                                        class="w-4 h-4 text-[#005506] focus:ring-[#005506]">
                                    <span class="text-xs font-extrabold text-slate-500 w-4">C</span>
                                </div>
                                <input type="text" value="4.68 × 10⁻¹⁴ N, directed South"
                                    class="flex-1 bg-[#FEF9E7] dark:bg-[#232D26] px-3 sm:px-4 py-2.5 rounded-xl border border-amber-200 dark:border-[#3F4F43] text-xs font-bold text-slate-800 dark:text-slate-200 focus:outline-none w-full" />
                                <span
                                    class="bg-[#FEF9E7] dark:bg-[#232D26] text-slate-500 text-[10px] font-bold px-3 py-2 rounded-xl border border-amber-200 dark:border-[#3F4F43] shrink-0 self-start sm:self-auto">Distractor
                                    2</span>
                            </div>

                            <!-- Option D -->
                            <div
                                class="flex flex-col sm:flex-row sm:items-center gap-2 bg-[#FEF9E7]/40 dark:bg-[#232D26]/40 p-2 sm:p-0 rounded-xl sm:rounded-none">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="answer_key"
                                        class="w-4 h-4 text-[#005506] focus:ring-[#005506]">
                                    <span class="text-xs font-extrabold text-slate-500 w-4">D</span>
                                </div>
                                <input type="text" value="7.20 × 10⁻¹⁵ N, directed Upward"
                                    class="flex-1 bg-[#FEF9E7] dark:bg-[#232D26] px-3 sm:px-4 py-2.5 rounded-xl border border-amber-200 dark:border-[#3F4F43] text-xs font-bold text-slate-800 dark:text-slate-200 focus:outline-none w-full" />
                                <span
                                    class="bg-[#FEF9E7] dark:bg-[#232D26] text-slate-500 text-[10px] font-bold px-3 py-2 rounded-xl border border-amber-200 dark:border-[#3F4F43] shrink-0 self-start sm:self-auto">Distractor
                                    3</span>
                            </div>
                        </div>

                        <!-- Action Control Buttons -->
                        <div
                            class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
                            <button
                                class="text-rose-600 dark:text-rose-400 hover:text-rose-800 text-xs font-bold flex items-center justify-center gap-1.5 p-2">
                                <span>🗑️</span> Delete Question
                            </button>

                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                                <button
                                    class="bg-[#EFECE6] dark:bg-[#232D26] text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-[#3F4F43] px-5 py-2.5 rounded-full text-xs font-bold transition-all text-center">
                                    Duplicate to Set B
                                </button>
                                <button
                                    class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] hover:bg-[#004105] px-6 py-2.5 rounded-full text-xs font-black shadow-md transition-all text-center">
                                    Save Item #14
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT COLUMN: GAMIFIED HINT CONFIGURATOR (5 COLS) -->
                    <div
                        class="lg:col-span-5 bg-[#FEF9E7]/60 dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-amber-200/80 dark:border-[#3F4F43] space-y-4 sm:space-y-5">

                        <!-- Header & Item Status Pill -->
                        <div
                            class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 border-b border-amber-200/60 dark:border-[#3F4F43] pb-4">
                            <div class="flex items-center gap-2">
                                <span class="text-amber-700 dark:text-amber-400 text-lg">💡</span>
                                <div>
                                    <h3
                                        class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">
                                        Gamified Hint Configurator
                                    </h3>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                                        Document 3 Core Architecture (Three-Tiered)
                                    </p>
                                </div>
                            </div>

                            <span
                                class="bg-[#F9C20C] text-[#2C3E2D] text-[10px] font-black px-3 py-1 rounded-full shadow-sm shrink-0 self-start sm:self-auto">
                                Active for Item #14
                            </span>
                        </div>

                        <p class="text-xs text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
                            Configure adaptive supports that prevent learner frustration while encouraging autonomous
                            derivation during self-paced practice.
                        </p>

                        <!-- HINT 1: CLEAR THE FOG -->
                        <div
                            class="bg-white dark:bg-[#232D26] p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-[#005506] text-white text-[10px] font-black flex items-center justify-center shrink-0">1</span>
                                    <h4 class="text-xs font-black text-slate-900 dark:text-white">Hint 1: "Clear the
                                        Fog" (Elimination)</h4>
                                </div>
                                <span
                                    class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[9px] font-black px-2 py-0.5 rounded-full border border-emerald-200 shrink-0 self-start sm:self-auto">
                                    Reflex Mini-Game
                                </span>
                            </div>

                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                                Eliminates 2 distractors when student triggers reflex pop. Check distractors to
                                eliminate:
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <label
                                    class="bg-[#F9F7F1] dark:bg-[#2D3A31] p-2.5 rounded-xl border border-slate-200 dark:border-[#3F4F43] flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" checked
                                        class="rounded text-[#005506] focus:ring-[#005506] w-4 h-4 shrink-0">
                                    <span
                                        class="text-[11px] font-bold text-slate-800 dark:text-slate-200 truncate">Distractor
                                        A
                                        (2.93 × 10⁻¹⁴)</span>
                                </label>
                                <label
                                    class="bg-[#F9F7F1] dark:bg-[#2D3A31] p-2.5 rounded-xl border border-slate-200 dark:border-[#3F4F43] flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" checked
                                        class="rounded text-[#005506] focus:ring-[#005506] w-4 h-4 shrink-0">
                                    <span
                                        class="text-[11px] font-bold text-slate-800 dark:text-slate-200 truncate">Distractor
                                        D
                                        (7.20 × 10⁻¹⁵)</span>
                                </label>
                            </div>
                        </div>

                        <!-- HINT 2: ASK A CLUE -->
                        <div
                            class="bg-white dark:bg-[#232D26] p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-[#F9C20C] text-[#2C3E2D] text-[10px] font-black flex items-center justify-center shrink-0">2</span>
                                    <h4 class="text-xs font-black text-slate-900 dark:text-white">Hint 2: "Ask a Clue"
                                        (Mascot Riddle)</h4>
                                </div>
                                <span
                                    class="bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 text-[9px] font-black px-2 py-0.5 rounded-full border border-amber-200 shrink-0 self-start sm:self-auto">
                                    Sagisag Persona
                                </span>
                            </div>

                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                                Delivers a friendly pedagogical nudge written in Sagisag the Cat's encouraging campus
                                voice:
                            </p>

                            <textarea rows="3"
                                class="w-full bg-[#F9F7F1] dark:bg-[#2D3A31] p-3 rounded-xl border border-slate-200 dark:border-[#3F4F43] text-[11px] font-medium text-slate-800 dark:text-slate-200 focus:outline-none resize-none leading-relaxed"
                                placeholder="Type mascot riddle...">Remember our classroom right-hand rule exercise! Your thumb aligns with velocity, your fingers sweep into the...</textarea>
                        </div>

                        <!-- HINT 3: CRACK THE CODE -->
                        <div
                            class="bg-white dark:bg-[#232D26] p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-[#F9C20C] text-[#2C3E2D] text-[10px] font-black flex items-center justify-center shrink-0">3</span>
                                    <h4 class="text-xs font-black text-slate-900 dark:text-white">Hint 3: "Crack the
                                        Code" (Stepwise Reveal)</h4>
                                </div>
                                <span
                                    class="bg-slate-100 dark:bg-[#2D3A31] text-slate-700 dark:text-slate-300 text-[9px] font-black px-2 py-0.5 rounded-full border border-slate-200 shrink-0 self-start sm:self-auto">
                                    Formula Stepper
                                </span>
                            </div>

                            <div
                                class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 bg-[#FEF9E7] dark:bg-[#2D3A31] p-3 rounded-xl border border-amber-200 dark:border-[#3F4F43]">
                                <span class="text-xs font-black text-slate-800 dark:text-slate-200">Revealed
                                    Equation:</span>
                                <span
                                    class="font-mono text-xs font-black text-[#005506] dark:text-[#86EFAC] bg-white dark:bg-[#232D26] px-3 py-1 rounded-lg border border-slate-200 dark:border-[#3F4F43] self-stretch sm:self-auto text-center">
                                    F_B = |q| v B sin(θ)
                                </span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </main>

    </div>
</template>

<script setup>
import { ref } from 'vue'
import heroImage from '../../../../assets/img/local/quiz-hub-hero.jpg'
import { Head } from '@inertiajs/vue3'
import Sidebart from '@/Components/Sidebart.vue'
import navbartop from '@/Components/navbartop.vue'

// Font Scaling State Managed Locally via navbartop event
const fontSizeMode = ref('base')

// Mock Quiz Bank Data
const quizList = ref([
    {
        id: 1,
        subject: 'STEM • Gen Physics 2',
        itemsCount: 25,
        title: 'Electromagnetic Induction & Faraday\'s Law Concept Check',
        description: 'Tiered multiple-choice questions focusing on magnetic flux variations and Lenz\'s Law direction rule.',
        status: 'Ready for Deployment'
    },
    {
        id: 2,
        subject: 'STEM • Gen Physics 2',
        itemsCount: 30,
        title: 'DC Circuits & Kirchhoff\'s Loop Rule Assessment',
        description: 'Nodal equation calculations, resistor color codes, and tolerance calculation problems.',
        status: 'Active Bank'
    },
    {
        id: 3,
        subject: 'TVL-ICT • Robotics',
        itemsCount: 15,
        title: 'Arduino Sensor Array Breadboard Diagnostics',
        description: 'Ultrasonic rangefinder circuit analysis, IR pinouts, and code logic troubleshooting.',
        status: 'Drafting'
    }
])

// Custom Directive for Smooth Entrance Animations
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
/* Hide horizontal scrollbar for clean UI on mobile */
.scrollbar-none::-webkit-scrollbar {
    display: none;
}

.scrollbar-none {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Breathing Overlay Animation */
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
        transform: translateY(24px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-16px);
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
    animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.is-animated.anim-slide-up {
    animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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
</style>