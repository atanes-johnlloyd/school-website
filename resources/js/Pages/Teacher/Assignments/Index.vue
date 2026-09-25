<template>

  <Head title="Assignment & Classwork Evaluation Manager - Salawag LMS" />

  <div class="min-h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300">

    <!-- Sticky Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-40 shrink-0 shadow-lg">
      <Sidebart />
    </div>

    <!-- Main Workspace Canvas -->
    <main :class="['flex-1 relative overflow-y-auto min-h-screen flex flex-col', `text-scale-${fontSizeMode}`]">

      <!-- Reusable Top Navigation Component -->
      <navbartop searchPlaceholder="Search assignments, rubric-grade tasks, student turn-ins.."
        @font-size-changed="(size) => fontSizeMode = size" />

      <!-- MAIN PAGE CONTENT -->
      <div class="relative z-10 px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2">

        <!-- ASSIGNMENTS HERO BANNER WITH ANIMATED OVERLAY & FADE DOWN -->
        <div v-observe
          class="anim-fade-down relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[260px] flex flex-col justify-center">
          <!-- Background Image properly aligned and fitted -->
          <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2000&auto=format&fit=crop"
            alt="Assignments Background" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <!-- Animated Green Overlay -->
          <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

          <!-- Content Container -->
          <div class="relative z-10 p-6 sm:p-8 md:p-10 max-w-3xl space-y-4">

            <!-- Quarter Badge -->
            <div
              class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md text-white border border-white/20 text-xs font-semibold px-4 py-1.5 rounded-full shadow-sm">
              <span>📅</span> SY 2025–2026 • Quarter 2 Evaluation
            </div>

            <!-- Main Title & Description -->
            <div class="space-y-3">
              <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                Assignments & Classwork Evaluation Manager
              </h2>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Review artifacts, rubric-grade DepEd performance tasks, track turn-ins across Senior High STEM & TVL
                sections, and grant gamified Salawag Spark bonuses.
              </p>
            </div>

          </div>
        </div>

        <!-- 4-CARD ASSIGNMENT METRICS GRID WITH STAGGERED ANIMATIONS -->
        <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

          <!-- 1. TOTAL ACTIVE TASKS -->
          <div v-observe style="animation-delay: 100ms;"
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="flex items-start justify-between">
                <span
                  class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">Total
                  Active Tasks</span>
                <div
                  class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                  </svg>
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-slate-900 dark:text-white">6</span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Published Tasks</span>
              </div>
            </div>

            <div
              class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center gap-2 text-xs font-bold text-[#005506] dark:text-[#86EFAC]">
              <span>📈</span>
              <span>2 Sections completing today</span>
            </div>
          </div>

          <!-- 2. AWAITING EVALUATION -->
          <div v-observe style="animation-delay: 200ms;"
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="flex items-start justify-between">
                <span
                  class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">Awaiting
                  Evaluation</span>
                <div
                  class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/30">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                  </svg>
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-orange-600 dark:text-orange-400">42</span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Submissions</span>
              </div>
            </div>

            <div
              class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-400">
              <span class="shrink-0">👥</span>
              <span class="truncate">STEM Rizal, Archimedes, TVL-Turing</span>
            </div>
          </div>

          <!-- 3. GRADED THIS WEEK -->
          <div v-observe style="animation-delay: 300ms;"
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="flex items-start justify-between">
                <span
                  class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">Graded
                  This Week</span>
                <div
                  class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-[#005506] dark:text-[#86EFAC]">89</span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Evaluated</span>
              </div>
            </div>

            <div
              class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center gap-2 text-xs font-bold text-[#005506] dark:text-[#86EFAC]">
              <span>⏱️</span>
              <span>Turnaround: 1.4d (Target ≤ 2.0d)</span>
            </div>
          </div>

          <!-- 4. MISSING / OVERDUE -->
          <div v-observe style="animation-delay: 400ms;"
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="flex items-start justify-between">
                <span
                  class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">Missing
                  / Overdue</span>
                <div
                  class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/30">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                  </svg>
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-rose-600 dark:text-rose-400">3</span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Learners</span>
              </div>
            </div>

            <div
              class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-xs font-bold">
              <span class="text-rose-600 dark:text-rose-400 flex items-center gap-1">
                <span>✉️</span> Dispatched
              </span>
              <a href="#" class="text-[#005506] dark:text-[#86EFAC] hover:underline">
                View 3 LRNs
              </a>
            </div>
          </div>

        </div>

        <!-- CLASSWORK PIPELINE & GRADING QUEUE WORKSPACE GRID -->
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

          <!-- LEFT COLUMN: CLASSWORK PIPELINE (5 COLS) -->
          <div class="lg:col-span-5 space-y-5">

            <!-- Pipeline Filter Header Panel -->
            <div v-observe
              class="anim-fade-down bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div
                    class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                      </path>
                    </svg>
                  </div>
                  <h3 class="text-base font-black text-slate-800 dark:text-white">Classwork Pipeline</h3>
                </div>
                <span
                  class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[11px] font-black px-3 py-1 rounded-full border border-emerald-200/60 dark:border-emerald-900/40">
                  SY 2025–26 • 2nd Sem
                </span>
              </div>

              <!-- Filter Pills -->
              <div class="flex flex-wrap items-center gap-1.5 pt-1">
                <button v-for="filter in pipelineFilters" :key="filter.id" @click="activeFilter = filter.id" :class="[
                  'px-3.5 py-1.5 rounded-xl text-xs transition-all border',
                  activeFilter === filter.id
                    ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] font-bold shadow-sm border-transparent'
                    : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600 dark:text-slate-300 font-semibold border-slate-200/80 dark:border-[#3F4F43] hover:bg-slate-100 dark:hover:bg-[#3F4F43]'
                ]">
                  {{ filter.label }} ({{ filter.count }})
                </button>
              </div>

              <!-- Dropdown & Sort Bar -->
              <div class="flex items-center gap-2 pt-1">
                <div class="relative flex-1">
                  <select v-model="selectedSubject"
                    class="w-full appearance-none bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-bold py-2.5 px-3.5 pr-8 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#006907]">
                    <option value="gen_physics">General Physics 2 (STEM 12)</option>
                    <option value="gen_chem">General Chemistry 2 (STEM 12)</option>
                  </select>
                  <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  </div>
                </div>
                <button
                  class="w-9 h-9 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0 hover:bg-slate-100 dark:hover:bg-[#3F4F43] transition-colors">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                  </svg>
                </button>
              </div>

            </div>

            <!-- TABULATOR-STYLE CLASSWORK PIPELINE LIST -->
            <div class="space-y-4">

              <!-- CARD 1: ACTIVE EVALUATING (Lab Activity 4) -->
              <div v-observe style="animation-delay: 150ms;"
                class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-3xl p-5 shadow-md border-2 border-[#006907] dark:border-[#86EFAC] space-y-4 relative overflow-hidden">

                <div class="flex items-start justify-between gap-3">
                  <div class="flex flex-wrap items-center gap-2">
                    <span
                      class="bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-900/40">
                      STEM • Gen Physics 2
                    </span>
                    <span
                      class="bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-md border border-amber-200/60 dark:border-amber-900/40 flex items-center gap-1">
                      <span>⚙️</span> Performance Task (40 Pts)
                    </span>
                  </div>

                  <div
                    class="bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10px] font-black px-2.5 py-1 rounded-full border border-rose-200 dark:border-rose-900/40 text-right shrink-0">
                    Due Today, <br>11:59 PM
                  </div>
                </div>

                <div class="space-y-1">
                  <h4 class="text-base font-black text-slate-900 dark:text-white leading-snug">
                    Laboratory Activity 4: Electromagnetic Induction & Faraday's Law
                  </h4>
                  <p class="text-xs text-slate-500 dark:text-slate-400 font-medium line-clamp-2">
                    Simulate magnetic field variations using PhET Interactive Faraday Sim. Derive induced electromotive
                    force...
                  </p>
                </div>

                <!-- Progress Track Container -->
                <div
                  class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-3.5 border border-slate-200/60 dark:border-[#3F4F43] space-y-2">
                  <div class="flex items-center justify-between text-xs font-black">
                    <span class="text-slate-800 dark:text-slate-200">Grade 12 STEM - Rizal</span>
                    <span class="text-[#005506] dark:text-[#86EFAC]">41 / 45 Submitted (91%)</span>
                  </div>

                  <div class="w-full h-2.5 rounded-full bg-slate-200 dark:bg-[#3F4F43] overflow-hidden flex">
                    <div class="h-full bg-[#005506] dark:bg-[#86EFAC]" style="width: 62%;"></div>
                    <div class="h-full bg-[#F9C20C]" style="width: 29%;"></div>
                    <div class="h-full bg-slate-300 dark:bg-slate-600" style="width: 9%;"></div>
                  </div>

                  <div class="flex items-center justify-between text-[10px] font-bold pt-0.5">
                    <span class="text-emerald-700 dark:text-emerald-400 flex items-center gap-1">
                      <span class="w-1.5 h-1.5 rounded-full bg-[#005506] dark:bg-[#86EFAC]"></span> 28 Graded
                    </span>
                    <span class="text-amber-700 dark:text-amber-400 flex items-center gap-1">
                      <span class="w-1.5 h-1.5 rounded-full bg-[#F9C20C]"></span> 13 Awaiting Review
                    </span>
                    <span class="text-slate-400 flex items-center gap-1">
                      <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600"></span> 4 Missing
                    </span>
                  </div>
                </div>

                <!-- Bottom Meta Actions -->
                <div class="flex items-center justify-between pt-1">
                  <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 dark:text-slate-400">
                    <span
                      class="bg-slate-100 dark:bg-[#232D26] px-2 py-1 rounded border border-slate-200 dark:border-[#3F4F43]">👓
                      PhET Sim</span>
                    <span
                      class="bg-slate-100 dark:bg-[#232D26] px-2 py-1 rounded border border-slate-200 dark:border-[#3F4F43]">⚖️
                      3 Rubrics</span>
                  </div>

                  <button
                    class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] px-3.5 py-1.5 rounded-xl text-xs font-black shadow-sm flex items-center gap-1">
                    Evaluating ➔
                  </button>
                </div>

              </div>

              <!-- CARD 2: PROBLEM SET 3 -->
              <div v-observe style="animation-delay: 250ms;"
                class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">

                <div class="flex items-start justify-between gap-3">
                  <div class="flex flex-wrap items-center gap-2">
                    <span
                      class="bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-900/40">
                      STEM • Gen Physics 2
                    </span>
                    <span
                      class="bg-slate-100 dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded-md border border-slate-200/80 dark:border-[#3F4F43]">
                      Written Work (25 Pts)
                    </span>
                  </div>

                  <span
                    class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-bold px-2.5 py-1 rounded-full border border-amber-200/60 dark:border-amber-900/40 shrink-0">
                    Due Mar 13
                  </span>
                </div>

                <div class="space-y-1">
                  <h4 class="text-base font-black text-slate-900 dark:text-white leading-snug">
                    Problem Set 3: Circuits & Kirchhoff's Rules
                  </h4>
                  <p class="text-xs text-slate-500 dark:text-slate-400 font-medium line-clamp-2">
                    Multi-loop DC network nodal equations and current loop solutions with resistor tolerance variations.
                  </p>
                </div>

                <div
                  class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-3.5 border border-slate-200/60 dark:border-[#3F4F43] space-y-2">
                  <div class="flex items-center justify-between text-xs font-black">
                    <span class="text-slate-800 dark:text-slate-200">Grade 12 STEM - Archimedes</span>
                    <span class="text-[#005506] dark:text-[#86EFAC]">38 / 44 Submitted</span>
                  </div>

                  <div class="w-full h-2.5 rounded-full bg-slate-200 dark:bg-[#3F4F43] overflow-hidden flex">
                    <div class="h-full bg-[#005506] dark:bg-[#86EFAC]" style="width: 86%;"></div>
                    <div class="h-full bg-rose-500" style="width: 14%;"></div>
                  </div>

                  <div class="flex items-center justify-between text-[10px] font-bold pt-0.5">
                    <span class="text-emerald-700 dark:text-emerald-400">100% of turned-in evaluated (38/38)</span>
                    <span class="text-rose-600 font-black">6 Overdue</span>
                  </div>
                </div>

              </div>

              <!-- CARD 3: ROBOTICS MILESTONE -->
              <div v-observe style="animation-delay: 350ms;"
                class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">

                <div class="flex items-start justify-between gap-3">
                  <div class="flex flex-wrap items-center gap-2">
                    <span
                      class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-black px-2.5 py-0.5 rounded-md border border-amber-200/60 dark:border-amber-900/40">
                      TVL-ICT • Robotics
                    </span>
                    <span
                      class="bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-md border border-amber-200/60 dark:border-amber-900/40 flex items-center gap-1">
                      <span>⚙️</span> Milestone (50 Pts)
                    </span>
                  </div>

                  <span
                    class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-bold px-2.5 py-1 rounded-full border border-amber-200/60 dark:border-amber-900/40 shrink-0">
                    Due Mar 15
                  </span>
                </div>

                <div class="space-y-1">
                  <h4 class="text-base font-black text-slate-900 dark:text-white leading-snug">
                    Robotics Project Milestone 1: Sensor Array Breadboard
                  </h4>
                  <p class="text-xs text-slate-500 dark:text-slate-400 font-medium line-clamp-2">
                    Hardware prototype assembly for ultrasonic range finder and IR line tracker interfaced with Arduino
                    Uno...
                  </p>
                </div>

                <div
                  class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-3.5 border border-slate-200/60 dark:border-[#3F4F43] space-y-2">
                  <div class="flex items-center justify-between text-xs font-black">
                    <span class="text-slate-800 dark:text-slate-200">TechPro 11 - Turing</span>
                    <span class="text-amber-700 dark:text-amber-400">12 / 38 Submitted</span>
                  </div>

                  <div class="w-full h-2.5 rounded-full bg-slate-200 dark:bg-[#3F4F43] overflow-hidden flex">
                    <div class="h-full bg-amber-500" style="width: 31%;"></div>
                  </div>

                  <div class="flex items-center justify-between text-[10px] font-bold pt-0.5">
                    <span class="text-slate-500 dark:text-slate-400">Submissions in progress</span>
                    <span class="text-slate-600 dark:text-slate-300">26 In Lab Assembly</span>
                  </div>
                </div>

              </div>

            </div>

            <!-- DEPED ORDER NO. 8 SYNC NOTICE CARD -->
            <div v-observe style="animation-delay: 450ms;"
              class="anim-slide-up bg-[#FEF9E7] dark:bg-amber-950/30 rounded-3xl p-5 border border-amber-200 dark:border-amber-900/40 flex items-start gap-3">
              <div
                class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-200 dark:border-amber-800">
                🎓
              </div>
              <div class="space-y-1 text-xs">
                <h5 class="font-black text-amber-950 dark:text-amber-300">DEPED ORDER NO. 8, S. 2015 FORM 7 SYNCED</h5>
                <p class="text-amber-900/80 dark:text-amber-300/80 font-medium leading-relaxed">
                  All scored performance tasks and written works immediately sync with the SHS Electronic Class Record
                  (E-Class Record) with automated transmuted final ratings.
                </p>
              </div>
            </div>

          </div>

          <!-- RIGHT COLUMN: GRADING QUEUE WORKSPACE (7 COLS) -->
          <div class="lg:col-span-7 space-y-5">

            <!-- Workspace Top Header Bar -->
            <div v-observe
              class="anim-fade-down bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-4">

              <div class="space-y-1">
                <div class="flex items-center gap-2">
                  <span
                    class="bg-[#005506] text-white dark:bg-[#86EFAC] dark:text-[#232D26] text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                    📝 GRADING QUEUE • ITEM {{ queuePosition?.current ?? 1 }} OF {{ queuePosition?.total ?? 13 }}
                  </span>
                </div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white">
                  Lab Activity 4: Faraday's Law Evaluation
                </h2>
              </div>

              <div class="flex items-center gap-2 shrink-0">
                <button @click="prevItem"
                  class="w-9 h-9 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-[#3F4F43] transition-colors">
                  ‹
                </button>
                <span
                  class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border border-emerald-200/60 dark:border-emerald-900/40 px-3.5 py-1.5 rounded-xl text-xs font-bold">
                  Section: STEM 12 – Jose Rizal
                </span>
                <button @click="nextItem"
                  class="w-9 h-9 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-[#3F4F43] transition-colors">
                  ›
                </button>
              </div>

            </div>

            <!-- STUDENT PROFILE SUBMISSION HERO -->
            <div v-observe style="animation-delay: 150ms;"
              class="anim-slide-up bg-[#FEF9E7] dark:bg-amber-950/30 rounded-3xl p-6 border border-amber-200/80 dark:border-amber-900/40 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

              <div class="flex items-center gap-4">
                <div
                  class="w-14 h-14 rounded-2xl bg-[#003803] text-white font-black text-xl flex items-center justify-center shrink-0 shadow-md">
                  {{ currentStudent?.initials ?? 'JD' }}
                </div>
                <div>
                  <h3 class="text-lg font-black text-slate-900 dark:text-white">{{ currentStudent?.name }}</h3>
                  <div class="flex items-center gap-2 mt-0.5">
                    <span
                      class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2 py-0.5 rounded-md border border-emerald-200">
                      ✓ Turned In Early
                    </span>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                      LRN: {{ currentStudent?.lrn }} • {{ currentStudent?.section }} • Seat #{{ currentStudent?.seat }}
                    </span>
                  </div>
                </div>
              </div>

              <div
                class="text-left sm:text-right shrink-0 border-t sm:border-t-0 pt-3 sm:pt-0 border-amber-200 dark:border-amber-900/40">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">SUBMISSION
                  TIME</span>
                <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200 block">{{
                  currentStudent?.submissionTime }}</span>
                <span class="text-[11px] font-black text-[#005506] dark:text-[#86EFAC]">{{ currentStudent?.timeStatus
                }}</span>
              </div>

            </div>

            <!-- SUBMITTED EVIDENCE & ARTIFACTS PANEL -->
            <div v-observe style="animation-delay: 250ms;"
              class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">

              <div class="flex items-center justify-between border-b border-slate-100 dark:border-[#3F4F43] pb-3">
                <div class="flex items-center gap-2">
                  <span class="text-emerald-700 dark:text-emerald-400">📎</span>
                  <h4 class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-white">
                    SUBMITTED EVIDENCE & ARTIFACTS ({{ submittedFiles?.length ?? 0 }} FILES)
                  </h4>
                </div>
                <span class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                  🛡️ Virus Scanned • Verified DepEd Cloud
                </span>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div v-for="file in submittedFiles" :key="file.id"
                  class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center shrink-0', file.iconBg]">
                      {{ file.icon }}
                    </div>
                    <div>
                      <p class="text-xs font-extrabold text-slate-800 dark:text-white">{{ file.name }}</p>
                      <p class="text-[10px] text-slate-400 font-medium">{{ file.size }} • {{ file.details }}</p>
                    </div>
                  </div>

                  <div class="flex items-center gap-1 text-slate-400">
                    <button class="p-1.5 hover:text-slate-700 dark:hover:text-white transition-colors">👁️</button>
                    <button class="p-1.5 hover:text-slate-700 dark:hover:text-white transition-colors">📥</button>
                  </div>
                </div>
              </div>

            </div>

            <!-- DEPED SHS SCIENCE PERFORMANCE RUBRIC BREAKDOWN -->
            <div v-observe style="animation-delay: 350ms;"
              class="anim-slide-up bg-[#FEF9E7]/60 dark:bg-[#2D3A31]/90 rounded-3xl p-6 shadow-sm border border-amber-200/80 dark:border-[#3F4F43] space-y-5">

              <!-- Header & Live Score Pill -->
              <div
                class="flex items-start justify-between gap-4 border-b border-amber-200/60 dark:border-[#3F4F43] pb-4">
                <div class="space-y-1">
                  <div class="flex items-center gap-2">
                    <span class="text-emerald-800 dark:text-[#86EFAC]">📋</span>
                    <h3 class="text-base font-black text-slate-900 dark:text-white">
                      DepEd SHS Science Performance Rubric Breakdown
                    </h3>
                  </div>
                  <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    Adjust criterion sliders or enter exact numbers. Grade calculates automatically.
                  </p>
                </div>

                <div class="text-right shrink-0">
                  <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">TOTAL SCORE</span>
                  <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-black text-[#005506] dark:text-[#86EFAC]">{{ calculatedTotalScore
                      }}</span>
                    <span class="text-xs font-bold text-slate-400">/ 40</span>
                  </div>
                  <span
                    class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[10px] font-extrabold px-2 py-0.5 rounded-full border border-emerald-200 inline-block mt-0.5">
                    {{ calculatePercentage }}% • {{ scoreDescriptor }}
                  </span>
                </div>
              </div>

              <!-- Dynamic Rubric Criteria -->
              <div v-for="(rubric, index) in rubrics" :key="rubric.id"
                class="bg-white dark:bg-[#232D26] p-4 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-3">
                <div class="flex items-start justify-between gap-3">
                  <div>
                    <h4 class="text-xs font-black text-slate-900 dark:text-white">{{ index + 1 }}. {{ rubric.title }}
                      ({{ rubric.weight }})</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                      {{ rubric.description }}
                    </p>
                  </div>
                  <div class="flex items-center gap-1 shrink-0">
                    <input type="number" v-model.number="rubric.score" :max="rubric.maxScore" min="0"
                      class="w-12 text-center py-1 bg-[#F9F7F1] dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] rounded-lg font-black text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#006907]" />
                    <span class="text-xs font-bold text-slate-400">/ {{ rubric.maxScore }}</span>
                  </div>
                </div>

                <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-[#3F4F43] overflow-hidden relative">
                  <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full transition-all duration-300"
                    :style="{ width: `${(rubric.score / rubric.maxScore) * 100}%` }"></div>
                </div>

                <div class="flex items-center justify-between text-[10px] font-bold text-slate-400">
                  <span v-for="scale in rubric.scaleLabels" :key="scale.label"
                    :class="{ 'text-[#005506] dark:text-[#86EFAC] font-black': scale.active }">
                    {{ scale.label }}
                  </span>
                </div>
              </div>

            </div>

            <!-- ACTION CONTROL BUTTONS BAR -->
            <div v-observe style="animation-delay: 450ms;" class="anim-slide-up grid grid-cols-1 sm:grid-cols-12 gap-3 pt-2">

              <button
                class="sm:col-span-4 bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-[#3F4F43] py-3 px-4 rounded-2xl text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-sm">
                <span>🔄</span> Return for Resubmission
              </button>

              <button
                class="sm:col-span-5 bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] hover:bg-[#004105] py-3 px-4 rounded-2xl text-xs font-extrabold transition-all flex items-center justify-center gap-2 shadow-md">
                <span>✓</span> Submit Score & Notify Learner
              </button>

              <button
                class="sm:col-span-3 bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] py-3 px-4 rounded-2xl text-xs font-black transition-all flex items-center justify-center gap-1 shadow-md">
                Next: Clarissa Santos ➔
              </button>

            </div>

          </div>

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import Sidebart from '@/Components/Sidebart.vue'
import navbartop from '@/Components/navbartop.vue'

// Font Scaling State Managed Locally via navbartop event
const fontSizeMode = ref('base')

// Classwork Pipeline & Evaluation Queue State
const selectedSubject = ref('gen_physics')
const activeFilter = ref('all')
const sparksBonusEnabled = ref(true)

const queuePosition = ref({
  current: 1,
  total: 13
})

const pipelineFilters = ref([
  { id: 'all', label: 'All', count: 6 },
  { id: 'needs_grading', label: 'Needs Grading', count: 3 },
  { id: 'published', label: 'Published', count: 2 },
  { id: 'drafts', label: 'Drafts', count: 1 }
])

const currentStudent = ref({
  name: 'Juan Carlos Dela Cruz',
  initials: 'JD',
  lrn: '109348200192',
  section: 'STEM 12 – Jose Rizal',
  seat: '14',
  submissionTime: 'TODAY, 9:42 AM',
  timeStatus: '14H 17M BEFORE DEADLINE'
})

const submittedFiles = ref([
  {
    id: 1,
    name: 'Faraday_Sim_Data.xlsx',
    size: '1.4 MB',
    details: '4 Sheets',
    icon: '📊',
    iconBg: 'bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC]'
  },
  {
    id: 2,
    name: 'Lab4_Formal_Report.pdf',
    size: '3.8 MB',
    details: '6 Pages',
    icon: '📄',
    iconBg: 'bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300'
  }
])

const rubrics = ref([
  {
    id: 1,
    title: 'Data Accuracy & PhET Simulations',
    weight: '40%',
    description: 'Verification of coil loop turns, magnetic flux rate, and graph linearity.',
    score: 15,
    maxScore: 15,
    scaleLabels: [
      { label: '0 (Deficient)', active: false },
      { label: '8 (Developing)', active: false },
      { label: '12 (Proficient)', active: false },
      { label: '15 (Exemplary)', active: true }
    ]
  },
  {
    id: 2,
    title: 'Analysis, Equations & Error Derivation',
    weight: '40%',
    description: "Lenz's law direction analysis, sign convention compliance, ± % error notes.",
    score: 14,
    maxScore: 15,
    scaleLabels: [
      { label: '0 (Deficient)', active: false },
      { label: '8 (Developing)', active: false },
      { label: '14 (Strong Proficient)', active: true },
      { label: '15 (Exemplary)', active: false }
    ]
  },
  {
    id: 3,
    title: 'DepEd Lab Format & Synthesis Quality',
    weight: '20%',
    description: 'Formal layout, literature references, scientific conclusions, and neatness.',
    score: 10,
    maxScore: 10,
    scaleLabels: [
      { label: '0 (Deficient)', active: false },
      { label: '5 (Basic)', active: false },
      { label: '8 (Accomplished)', active: false },
      { label: '10 (Exemplary)', active: true }
    ]
  }
])

const teacherFeedback = ref("Excellent calculation of magnetic flux through the secondary coil, Juan Carlos. Neat tabular presentation and your error discussion on simulation rounding was exceptionally insightful. Keep up the high standard!")

// Computed Evaluation Totals
const calculatedTotalScore = computed(() => {
  return rubrics.value.reduce((acc, r) => acc + (r.score || 0), 0)
})

const calculatePercentage = computed(() => {
  return ((calculatedTotalScore.value / 40) * 100).toFixed(1)
})

const scoreDescriptor = computed(() => {
  const pct = parseFloat(calculatePercentage.value)
  if (pct >= 95) return 'Exemplary (95–100)'
  if (pct >= 85) return 'Proficient (85–94)'
  if (pct >= 75) return 'Developing (75–84)'
  return 'Needs Revision'
})

const prevItem = () => {
  if (queuePosition.value.current > 1) queuePosition.value.current--
}

const nextItem = () => {
  if (queuePosition.value.current < queuePosition.value.total) queuePosition.value.current++
}

// Directive for smooth entry scroll animations
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
  font-size: 1.75rem !important;
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