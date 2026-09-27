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
          <img :src="heroImage"
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

        <!-- CLASSWORK PIPELINE & CREATE ASSIGNMENT WORKSPACE GRID -->
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

            <!-- PIPELINE CARDS LIST -->
            <div class="space-y-4">

              <!-- CARD 1: PERFORMANCE TASK -->
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
                    Active in Class 👁️
                  </button>
                </div>

              </div>

              <!-- CARD 2: WRITTEN WORK -->
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

              <!-- CARD 3: MILESTONE -->
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

          </div>

          <!-- RIGHT COLUMN: CREATE ASSIGNMENT FORM & RUBRIC BUILDER (7 COLS) -->
          <div class="lg:col-span-7 space-y-5">

            <!-- Header Bar -->
            <div v-observe
              class="anim-fade-down bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between gap-4">
              <div class="flex items-center gap-3">
                <div
                  class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/30">
                  📝
                </div>
                <div>
                  <h2 class="text-lg font-black text-slate-900 dark:text-white">Create Assignment or Online Activity
                  </h2>
                  <div class="flex items-center gap-2 mt-0.5">
                    <span
                      class="bg-amber-100 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-black px-2.5 py-0.5 rounded-md border border-amber-200/60 dark:border-amber-900/40">
                      ✓ DepEd DO 8, s. 2015 Compliant
                    </span>
                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">
                      Author interactive tasks, attach standard rubrics, and publish directly to SHS classes.
                    </span>
                  </div>
                </div>
              </div>

              <div class="flex items-center gap-1.5 shrink-0">
                <button
                  class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-black px-3.5 py-1.5 rounded-full shadow-sm">
                  Active Editor
                </button>
                <button
                  class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600 dark:text-slate-300 text-xs font-bold px-3.5 py-1.5 rounded-full border border-slate-200 dark:border-[#3F4F43]">
                  Drafts (1)
                </button>
              </div>
            </div>

            <!-- Main Assignment Form Container -->
            <div v-observe style="animation-delay: 100ms;"
              class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-6">

              <!-- 1. SELECT ASSESSMENT COMPONENT TYPE -->
              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <label
                    class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-white flex items-center gap-2">
                    <span>1.</span> SELECT ASSESSMENT COMPONENT TYPE (DEPED WEIGHTING)
                  </label>
                  <span class="text-[10px] font-bold text-slate-400">DO 8, s. 2015 Transmutation Weights</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                  <!-- Written Work -->
                  <button
                    class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] text-left hover:border-[#005506] transition-all space-y-2">
                    <div class="flex items-center justify-between">
                      <span class="text-base">📑</span>
                      <span
                        class="bg-slate-200/60 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[10px] font-black px-2 py-0.5 rounded-md">25%</span>
                    </div>
                    <div>
                      <h4 class="text-xs font-black text-slate-900 dark:text-white">Written Work</h4>
                      <p class="text-[10px] text-slate-400 font-medium">Quizzes & problem sets</p>
                    </div>
                  </button>

                  <!-- Performance Task (Selected) -->
                  <button
                    class="bg-emerald-50/60 dark:bg-emerald-950/40 p-3 rounded-2xl border-2 border-[#005506] dark:border-[#86EFAC] text-left space-y-2">
                    <div class="flex items-center justify-between">
                      <span class="text-base">🧪</span>
                      <span
                        class="bg-[#005506] text-white dark:bg-[#86EFAC] dark:text-[#232D26] text-[10px] font-black px-2 py-0.5 rounded-md">40-50%</span>
                    </div>
                    <div>
                      <h4 class="text-xs font-black text-[#005506] dark:text-[#86EFAC]">Performance Task</h4>
                      <p class="text-[10px] text-emerald-800/80 dark:text-emerald-300/80 font-medium">Labs &
                        demonstrations</p>
                    </div>
                  </button>

                  <!-- Quarterly Exam -->
                  <button
                    class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] text-left hover:border-[#005506] transition-all space-y-2">
                    <div class="flex items-center justify-between">
                      <span class="text-base">📊</span>
                      <span
                        class="bg-slate-200/60 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[10px] font-black px-2 py-0.5 rounded-md">20%</span>
                    </div>
                    <div>
                      <h4 class="text-xs font-black text-slate-900 dark:text-white">Quarterly Exam</h4>
                      <p class="text-[10px] text-slate-400 font-medium">Periodical evaluation</p>
                    </div>
                  </button>

                  <!-- Gamified Quest -->
                  <button
                    class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] text-left hover:border-[#005506] transition-all space-y-2">
                    <div class="flex items-center justify-between">
                      <span class="text-base">🎮</span>
                      <span
                        class="bg-[#F9C20C] text-[#2C3E2D] text-[10px] font-black px-2 py-0.5 rounded-md">+Bonus</span>
                    </div>
                    <div>
                      <h4 class="text-xs font-black text-slate-900 dark:text-white">Gamified Quest</h4>
                      <p class="text-[10px] text-slate-400 font-medium">Hint quest & challenges</p>
                    </div>
                  </button>
                </div>
              </div>

              <!-- 2. TARGET SECTION & 3. COMPETENCY CODE -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                  <label
                    class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-white flex items-center gap-1.5">
                    <span>👥</span> 2. TARGET SECTION & TRACK
                  </label>
                  <select
                    class="w-full bg-[#FEF9E7] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-bold py-2.5 px-3.5 rounded-xl border border-amber-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#006907]">
                    <option>Grade 12 STEM - Jose Rizal (45 Learners)</option>
                    <option>Grade 12 STEM - Archimedes (44 Learners)</option>
                    <option>TechPro 11 - Turing (38 Learners)</option>
                  </select>
                </div>

                <div class="space-y-1.5">
                  <label
                    class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-white flex items-center gap-1.5">
                    <span>📖</span> SUBJECT / COMPETENCY CODE
                  </label>
                  <select
                    class="w-full bg-[#FEF9E7] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-bold py-2.5 px-3.5 rounded-xl border border-amber-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#006907]">
                    <option>General Physics 2 • STEM_GP12EM-IVa-1</option>
                    <option>General Physics 2 • STEM_GP12EM-IVb-12</option>
                  </select>
                </div>
              </div>

              <!-- 3. ACTIVITY TITLE -->
              <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                  <label
                    class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-white flex items-center gap-1.5">
                    <span>📝</span> 3. ACTIVITY / ASSIGNMENT TITLE
                  </label>
                  <span class="text-[10px] font-bold text-slate-400">DISPLAY TITLE IN STUDENT PORTAL</span>
                </div>
                <input type="text" value="Lab Activity 5: Electromagnetic Wave Optics & Snell's Law Experiment"
                  class="w-full bg-[#FEF9E7] dark:bg-[#232D26] px-4 py-3 rounded-2xl border border-amber-200 dark:border-[#3F4F43] text-xs font-black text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#006907]" />
              </div>

              <!-- 4. INSTRUCTIONS & PROMPT SPECIFICATION -->
              <div class="space-y-2">
                <div class="flex items-center justify-between">
                  <label
                    class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-white flex items-center gap-1.5">
                    <span>💻</span> 4. INSTRUCTIONS & PROMPT SPECIFICATION
                  </label>
                  <span class="text-[10px] font-semibold text-slate-400">Markdown & LaTeX Math Enabled</span>
                </div>

                <div
                  class="bg-[#FEF9E7] dark:bg-[#232D26] rounded-2xl border border-amber-200 dark:border-[#3F4F43] overflow-hidden space-y-2 p-3">
                  <!-- Editor Action Bar -->
                  <div
                    class="flex flex-wrap items-center gap-2 pb-2 border-b border-amber-200/60 dark:border-[#3F4F43] text-xs font-bold text-slate-600 dark:text-slate-300">
                    <button
                      class="px-2 py-1 bg-white dark:bg-[#2D3A31] rounded border border-slate-200 dark:border-[#3F4F43] font-black">B</button>
                    <button
                      class="px-2 py-1 bg-white dark:bg-[#2D3A31] rounded border border-slate-200 dark:border-[#3F4F43] italic">I</button>
                    <button
                      class="px-2 py-1 bg-white dark:bg-[#2D3A31] rounded border border-slate-200 dark:border-[#3F4F43] underline">U</button>
                    <span class="text-slate-300">|</span>
                    <span
                      class="bg-white dark:bg-[#2D3A31] px-2 py-1 rounded border border-slate-200 dark:border-[#3F4F43] font-mono text-[10px]">∑
                      n₁sin(θ₁) = n₂sin(θ₂)</span>
                    <button
                      class="px-2 py-1 bg-white dark:bg-[#2D3A31] rounded border border-slate-200 dark:border-[#3F4F43] font-mono text-[10px]">&lt;&gt;
                      Code</button>

                    <div class="ml-auto flex items-center gap-1.5">
                      <button
                        class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-[10px] font-bold px-2.5 py-1 rounded-lg">
                        📎 Attach DOST Resource
                      </button>
                      <button
                        class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[10px] font-bold px-2.5 py-1 rounded-lg border border-emerald-200">
                        👓 Embed PhET Sim
                      </button>
                    </div>
                  </div>

                  <textarea rows="4"
                    class="w-full bg-transparent text-xs font-medium text-slate-800 dark:text-slate-200 focus:outline-none resize-none leading-relaxed p-1"
                    placeholder="Type assignment instructions...">1. Access the PhET 'Bending Light' Interactive Simulation module via the provided DepEd portal token.
2. Calculate the index of refraction (n) across 5 differing prism optical densities using Snell's Law equation: n₁ sin(θ₁) = n₂ sin(θ₂).
3. Tabulate incident vs. refraction angles, record laser color wavelength...</textarea>
                </div>
              </div>

              <!-- 5. PERMITTED SUBMISSION MODE -->
              <div class="space-y-2">
                <label
                  class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-white flex items-center gap-1.5">
                  <span>📤</span> 5. PERMITTED SUBMISSION MODE
                </label>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                  <!-- Digital File Upload (Selected) -->
                  <label
                    class="bg-emerald-50/60 dark:bg-emerald-950/40 p-3 rounded-2xl border-2 border-[#005506] dark:border-[#86EFAC] flex items-start gap-2 cursor-pointer">
                    <input type="radio" name="submission_mode" checked
                      class="mt-0.5 text-[#005506] focus:ring-[#005506]">
                    <div>
                      <h5 class="text-xs font-black text-slate-900 dark:text-white">Digital File Upload</h5>
                      <p class="text-[10px] text-slate-500 font-medium">PDF, XLSX, DOCX, ZIP</p>
                    </div>
                  </label>

                  <!-- PhET Sim Embed -->
                  <label
                    class="bg-[#FEF9E7] dark:bg-[#232D26] p-3 rounded-2xl border border-amber-200 dark:border-[#3F4F43] flex items-start gap-2 cursor-pointer">
                    <input type="radio" name="submission_mode" class="mt-0.5 text-[#005506] focus:ring-[#005506]">
                    <div>
                      <h5 class="text-xs font-black text-slate-900 dark:text-white">PhET Sim Embed</h5>
                      <p class="text-[10px] text-slate-500 font-medium">Live sim state link</p>
                    </div>
                  </label>

                  <!-- Online Rich Text -->
                  <label
                    class="bg-[#FEF9E7] dark:bg-[#232D26] p-3 rounded-2xl border border-amber-200 dark:border-[#3F4F43] flex items-start gap-2 cursor-pointer">
                    <input type="radio" name="submission_mode" class="mt-0.5 text-[#005506] focus:ring-[#005506]">
                    <div>
                      <h5 class="text-xs font-black text-slate-900 dark:text-white">Online Rich Text</h5>
                      <p class="text-[10px] text-slate-500 font-medium">Direct browser entry</p>
                    </div>
                  </label>

                  <!-- In-Class Demo -->
                  <label
                    class="bg-[#FEF9E7] dark:bg-[#232D26] p-3 rounded-2xl border border-amber-200 dark:border-[#3F4F43] flex items-start gap-2 cursor-pointer">
                    <input type="radio" name="submission_mode" class="mt-0.5 text-[#005506] focus:ring-[#005506]">
                    <div>
                      <h5 class="text-xs font-black text-slate-900 dark:text-white">In-Class Demo</h5>
                      <p class="text-[10px] text-slate-500 font-medium">Hands-on lab rubric</p>
                    </div>
                  </label>
                </div>
              </div>

              <!-- DUE DATE & POINTS ROW -->
              <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                <div class="sm:col-span-4 space-y-1">
                  <label
                    class="text-[11px] font-black uppercase text-slate-700 dark:text-slate-300 flex items-center gap-1">
                    <span>📅</span> Due Date & Cutoff
                  </label>
                  <input type="text" value="03/20/2026, 11:59 PM"
                    class="w-full bg-[#FEF9E7] dark:bg-[#232D26] px-3.5 py-2.5 rounded-xl border border-amber-200 dark:border-[#3F4F43] text-xs font-black text-slate-900 dark:text-white focus:outline-none" />
                </div>

                <div class="sm:col-span-4 space-y-1">
                  <label
                    class="text-[11px] font-black uppercase text-slate-700 dark:text-slate-300 flex items-center gap-1">
                    <span>📈</span> Max Score / Points
                  </label>
                  <div class="flex items-center gap-2">
                    <input type="number" value="40"
                      class="w-full bg-[#FEF9E7] dark:bg-[#232D26] px-3.5 py-2.5 rounded-xl border border-amber-200 dark:border-[#3F4F43] text-xs font-black text-slate-900 dark:text-white focus:outline-none text-center" />
                    <span class="text-xs font-bold text-slate-400 shrink-0">Pts total</span>
                  </div>
                </div>

                <div class="sm:col-span-4 flex items-center gap-2 pt-5">
                  <input type="checkbox" id="grace_period" checked
                    class="rounded text-[#005506] focus:ring-[#005506] w-4 h-4">
                  <label for="grace_period" class="text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">
                    +24h Late Penalty Cap (10%)
                  </label>
                </div>
              </div>

              <!-- 7. DEPED HOLISTIC RUBRIC CRITERIA MATRIX -->
              <div
                class="bg-[#FEF9E7]/60 dark:bg-[#232D26] p-5 sm:p-6 rounded-3xl border border-amber-200/80 dark:border-[#3F4F43] space-y-5">
                <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-amber-200/60 dark:border-[#3F4F43] pb-4">
                  <div class="flex items-start gap-3">
                    <span class="text-emerald-800 dark:text-[#86EFAC] text-xl">📋</span>
                    <div>
                      <h3 class="text-sm font-black text-slate-900 dark:text-white">
                        7. DepEd Holistic Rubric Criteria Matrix
                      </h3>
                      <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                        Preset standard DO 8 science matrices loaded. Customize weighting & criteria levels.
                      </p>
                    </div>
                  </div>

                  <div class="flex items-center gap-2 shrink-0">
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Preset:</span>
                    <select
                      class="bg-white dark:bg-[#2D3A31] text-slate-800 dark:text-slate-200 text-xs font-bold py-1.5 px-3 rounded-xl border border-slate-200 dark:border-[#3F4F43] focus:outline-none">
                      <option>4-Criteria Science Inquiry Rubric (40 Pts)</option>
                      <option>3-Criteria DepEd Lab Rubric (30 Pts)</option>
                    </select>
                  </div>
                </div>

                <!-- CRITERIA CARDS GRID -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                  <!-- Criterion 1 -->
                  <div
                    class="bg-white dark:bg-[#2D3A31] p-4 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-3">
                    <div class="flex items-center justify-between">
                      <span class="text-xs font-black text-slate-800 dark:text-white">Criterion 1</span>
                      <span
                        class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2 py-0.5 rounded-md">
                        15 Pts (37.5%)
                      </span>
                    </div>

                    <input type="text" value="Refraction Law"
                      class="w-full bg-[#FEF9E7] dark:bg-[#232D26] px-3 py-1.5 rounded-lg border border-amber-200 dark:border-[#3F4F43] text-xs font-black text-slate-900 dark:text-white focus:outline-none" />

                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium line-clamp-3">
                      Verification of incident & refracted angle precision and Snell equation proof.
                    </p>

                    <div
                      class="pt-2 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[10px] font-bold text-slate-400">
                      <span>Exemplary: 15</span>
                      <span>Proficient: 12</span>
                      <span>Basic: 8</span>
                    </div>
                  </div>

                  <!-- Criterion 2 -->
                  <div
                    class="bg-white dark:bg-[#2D3A31] p-4 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-3">
                    <div class="flex items-center justify-between">
                      <span class="text-xs font-black text-slate-800 dark:text-white">Criterion 2</span>
                      <span
                        class="bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 text-[10px] font-black px-2 py-0.5 rounded-md">
                        15 Pts (37.5%)
                      </span>
                    </div>

                    <input type="text" value="Graphical Reg"
                      class="w-full bg-[#FEF9E7] dark:bg-[#232D26] px-3 py-1.5 rounded-lg border border-amber-200 dark:border-[#3F4F43] text-xs font-black text-slate-900 dark:text-white focus:outline-none" />

                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium line-clamp-3">
                      Plotting index slopes with error bars, sign conventions, and medium absorption notes.
                    </p>

                    <div
                      class="pt-2 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[10px] font-bold text-slate-400">
                      <span>Exemplary: 15</span>
                      <span>Proficient: 12</span>
                      <span>Basic: 8</span>
                    </div>
                  </div>

                  <!-- Criterion 3 -->
                  <div
                    class="bg-white dark:bg-[#2D3A31] p-4 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-3">
                    <div class="flex items-center justify-between">
                      <span class="text-xs font-black text-slate-800 dark:text-white">Criterion 3</span>
                      <span
                        class="bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 text-[10px] font-black px-2 py-0.5 rounded-md">
                        10 Pts (25.0%)
                      </span>
                    </div>

                    <input type="text" value="Scientific Synt"
                      class="w-full bg-[#FEF9E7] dark:bg-[#232D26] px-3 py-1.5 rounded-lg border border-amber-200 dark:border-[#3F4F43] text-xs font-black text-slate-900 dark:text-white focus:outline-none" />

                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium line-clamp-3">
                      Standard SHS laboratory template, scientific vocabulary, citations, and clarity.
                    </p>

                    <div
                      class="pt-2 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[10px] font-bold text-slate-400">
                      <span>Exemplary: 10</span>
                      <span>Proficient: 9</span>
                      <span>Basic: 5</span>
                    </div>
                  </div>
                </div>

                <!-- Rubric Controls Bar -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                  <button
                    class="w-full sm:w-auto bg-white dark:bg-[#2D3A31] text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-4 py-2 rounded-xl text-xs font-bold border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center gap-1">
                    <span>➕</span> Add Custom Criterion
                  </button>

                  <span
                    class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-xs font-black px-4 py-2 rounded-xl border border-emerald-200">
                    Total Rubric Weight: 40 / 40 Points (100% Balanced)
                  </span>
                </div>
              </div>

              <!-- PUBLISH & ACTION CONTROLS FOOTER -->
              <div
                class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                  <button
                    class="flex-1 sm:flex-none bg-[#EFECE6] dark:bg-[#232D26] text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-[#3F4F43] px-5 py-3 rounded-2xl text-xs font-extrabold transition-all">
                    Save as Draft
                  </button>

                  <button
                    class="flex-1 sm:flex-none bg-[#EFECE6] dark:bg-[#232D26] text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-[#3F4F43] px-5 py-3 rounded-2xl text-xs font-extrabold transition-all flex items-center justify-center gap-1.5">
                    <span>📅</span> Schedule Publish
                  </button>
                </div>

                <button
                  class="w-full sm:w-auto bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] px-6 py-3 rounded-2xl text-xs font-black shadow-md transition-all flex items-center justify-center gap-2">
                  <span>🚀</span> Publish to Selected Classes (Instant SMS to Parents)
                </button>
              </div>

            </div>

          </div>

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import heroImage from '../../../../assets/img/desktop-home-banner.png'
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