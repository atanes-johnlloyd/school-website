<template>

  <Head title="Assignment & Classwork Evaluation Manager - Salawag LMS" />

  <div
    class="h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300 overflow-hidden">

    <!-- Sticky Sidebar Navigation (Hidden on Mobile) -->
    <div
      class="hidden md:block sticky top-0 h-screen z-40 shrink-0 shadow-lg overflow-y-auto bg-white dark:bg-[#2D3A31]">
      <Sidebart />
    </div>

    <!-- Main Workspace Canvas -->
    <main
      :class="['flex-1 relative overflow-y-auto h-screen flex flex-col w-full min-w-0', `text-scale-${fontSizeMode}`]">

      <!-- Reusable Top Navigation Component -->
      <navbartop searchPlaceholder="Search assignments, rubric-grade tasks, student turn-ins.."
        @font-size-changed="(size) => fontSizeMode = size" />

      <!-- MAIN PAGE CONTENT -->
      <div
        class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2 w-full max-w-full">

        <!-- ASSIGNMENTS HERO BANNER WITH ANIMATED OVERLAY & FADE DOWN -->
        <div v-observe
          class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[260px] flex flex-col justify-center"
          style="animation-delay: 50ms;">

          <!-- Background Image bound to local import -->
          <img :src="heroImage" alt="Assignments Background"
            class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <!-- Animated Green Overlay -->
          <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply">
          </div>

          <!-- Content Container -->
          <div class="relative z-10 p-4 sm:p-8 md:p-10 max-w-3xl space-y-3 sm:space-y-4">

            <!-- Quarter Badge with Inline SVG -->
            <div
              class="inline-flex items-center gap-1.5 sm:gap-2 bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
              <svg class="w-3.5 h-3.5 fill-current text-amber-300 shrink-0" viewBox="0 0 24 24">
                <path
                  d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
              </svg>
              <span>SY 2025–2026 • Quarter 2 Evaluation</span>
            </div>

            <!-- Main Title & Description -->
            <div class="space-y-2 sm:space-y-3">
              <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                Assignments & Classwork Evaluation Manager
              </h2>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Review artifacts, rubric-grade DepEd performance tasks, track turn-ins across Senior High STEM & TVL
                sections, and grant gamified Salawag Spark bonuses.
              </p>
            </div>

          </div>
        </div>

        <!-- 4-CARD ASSIGNMENT METRICS GRID -->
        <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">

          <!-- 1. ACTIVE TASKS -->
          <div v-observe style="animation-delay: 100ms;"
            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3 sm:space-y-4">

            <div class="flex items-start justify-between">
              <span
                class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                ACTIVE TASKS
              </span>
              <div
                class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-[#006907] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
                <svg class="w-4 h-4 fill-current text-[#006907] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                  <path
                    d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                </svg>
              </div>
            </div>

            <div class="space-y-1">
              <div class="text-3xl sm:text-4xl font-black text-[#005506] dark:text-[#86EFAC]">6</div>
              <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium">
                Ongoing across 4 sections
              </p>
            </div>

          </div>

          <!-- 2. TO REVIEW -->
          <div v-observe style="animation-delay: 150ms;"
            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3 sm:space-y-4">

            <div class="flex items-start justify-between">
              <span
                class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-amber-600 dark:text-amber-400">
                TO REVIEW
              </span>
              <div
                class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl sm:rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
                <svg class="w-4 h-4 fill-current text-amber-700 dark:text-amber-300" viewBox="0 0 24 24">
                  <path
                    d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                </svg>
              </div>
            </div>

            <div class="space-y-1">
              <div class="text-3xl sm:text-4xl font-black text-amber-700 dark:text-amber-400">14</div>
              <p class="text-xs sm:text-sm text-amber-600 dark:text-amber-400 font-bold">
                Submissions pending review
              </p>
            </div>

          </div>

          <!-- 3. TURN-IN RATE -->
          <div v-observe style="animation-delay: 200ms;"
            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3 sm:space-y-4">

            <div class="flex items-start justify-between">
              <span
                class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                TURN-IN RATE
              </span>
              <div
                class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-[#006907] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
                <svg class="w-4 h-4 fill-current text-[#006907] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                  <path
                    d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z" />
                </svg>
              </div>
            </div>

            <div class="space-y-1">
              <div class="text-3xl sm:text-4xl font-black text-[#005506] dark:text-[#86EFAC]">92.4%</div>
              <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium">
                +3.2% vs midterm average
              </p>
            </div>

          </div>

          <!-- 4. ON-TIME SUBMISSIONS -->
          <div v-observe style="animation-delay: 250ms;"
            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3 sm:space-y-4">

            <div class="flex items-start justify-between">
              <span
                class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                ON-TIME SUBMISSIONS
              </span>
              <div
                class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-[#006907] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
                <svg class="w-4 h-4 fill-current text-[#006907] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                  <path
                    d="M23 12l-2.44-2.79.34-3.69-3.61-.82-1.89-3.2L12 2.96 8.6 1.5 6.71 4.7l-3.61.81.34 3.68L1 12l2.44 2.79-.34 3.7 3.61.82 1.89 3.2 3.4-1.47 3.4 1.46 1.89-3.19 3.61-.82-.34-3.69L23 12zm-12.91 4.72l-3.8-3.81 1.48-1.48 2.32 2.33 5.85-5.87 1.48 1.48-7.33 7.35z" />
                </svg>
              </div>
            </div>

            <div class="space-y-1">
              <div class="text-3xl sm:text-4xl font-black text-[#005506] dark:text-[#86EFAC]">88.6%</div>
              <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium">
                Prompt compliance across STEM
              </p>
            </div>

          </div>

        </div>

        <!-- MAIN WORKSPACE CONTAINER -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-start w-full">

          <!-- LEFT COLUMN: TASK LIST FEED & DEPLOY PANEL (7 COLS) -->
          <div class="lg:col-span-7 space-y-4 sm:space-y-5 w-full min-w-0">

            <!-- TOP FILTER & CLASS SELECTOR BAR -->
            <div v-observe style="animation-delay: 300ms;"
              class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-3.5 sm:p-4 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
              <!-- Class Selector Dropdown -->
              <div class="relative flex-1 min-w-0">
                <select
                  class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-100 text-xs sm:text-sm font-extrabold px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer pr-8 truncate">
                  <option>Grade 12 STEM–2 Curie (Physics)</option>
                  <option>Grade 11 STEM–1 Newton (Math)</option>
                  <option>Grade 12 ICT–TechPro (Prog)</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-500">
                  <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M7 10l5 5 5-5z" />
                  </svg>
                </div>
              </div>

              <!-- Filter Pill Buttons -->
              <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 sm:pb-0">
                <button
                  class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-[11px] font-black px-3 py-1.5 sm:py-2 rounded-xl shadow-sm shrink-0">
                  All (6)
                </button>
                <button
                  class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-[11px] font-bold px-3 py-1.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] shrink-0 transition-colors">
                  Active (3)
                </button>
                <button
                  class="bg-[#FEF9E7] dark:bg-amber-950/40 text-amber-900 dark:text-amber-300 text-[11px] font-bold px-3 py-1.5 sm:py-2 rounded-xl border border-amber-200/80 dark:border-amber-900/40 shrink-0">
                  Needs Review (2)
                </button>
              </div>
            </div>

            <!-- DEPLOY NEW ASSESSMENT TASK BANNER -->
            <div v-observe style="animation-delay: 350ms;"
              class="anim-slide-up bg-[#FEF9E7]/90 dark:bg-[#232D26] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm border border-amber-200/80 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-3.5">
              <div class="flex items-start gap-3 min-w-0">
                <div
                  class="w-10 h-10 rounded-2xl bg-[#005506] text-white flex items-center justify-center shrink-0 shadow-sm">
                  <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                  </svg>
                </div>
                <div class="min-w-0">
                  <h3 class="text-sm sm:text-base font-black text-slate-900 dark:text-white truncate">Deploy New
                    Assessment Task
                  </h3>
                  <p class="text-xs text-slate-600 dark:text-slate-400 font-medium">Rubric parameters, weighted
                    components, and shared Drive files.</p>
                </div>
              </div>
              <button
                class="bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] text-xs font-black px-4 py-2.5 rounded-xl shadow-sm flex items-center justify-center gap-1.5 transition-all shrink-0 active:scale-95">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                  <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                </svg>
                + New Task
              </button>
            </div>

            <!-- TASK CARD 1: LAB ACTIVITY 5 (ACTIVE PENDING) -->
            <div v-observe style="animation-delay: 400ms;"
              class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm border-2 border-rose-500/80 dark:border-rose-500/60 space-y-3.5">

              <!-- Tags Row -->
              <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 flex-wrap">
                  <span
                    class="bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-0.5 rounded-md border border-emerald-200/60">
                    Lab Activity
                  </span>
                  <span
                    class="bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-md">
                    50 Points
                  </span>
                  <span
                    class="bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-md">
                    12 STEM–2 Curie
                  </span>
                </div>
                <span
                  class="bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 text-[10px] font-black px-2.5 py-0.5 rounded-full border border-rose-200/80">
                  • 14 Pending Grading
                </span>
              </div>

              <!-- Title & Description -->
              <div class="space-y-1">
                <h4 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white leading-snug">
                  Lab Activity 5: Total Internal Reflection & Snell's Law
                </h4>
                <p class="text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed">
                  Bench optic experiments with laser refraction indices through acrylic prisms.
                </p>
              </div>

              <!-- Due Date & Progress Bar -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2">
                <div class="flex flex-wrap items-center justify-between gap-2 text-xs font-bold">
                  <span class="text-amber-800 dark:text-amber-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24">
                      <path
                        d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                    </svg>
                    Due: Yesterday, 11:59 PM
                  </span>
                  <span class="text-slate-800 dark:text-white font-extrabold">35 of 38 Turn-ins (92%)</span>
                </div>
                <div class="w-full h-2 bg-slate-200 dark:bg-[#3F4F43] rounded-full overflow-hidden">
                  <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full" style="width: 92%;"></div>
                </div>
              </div>

              <!-- Attachment & Action Button -->
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300 min-w-0">
                  <svg class="w-4 h-4 fill-current text-slate-400 shrink-0" viewBox="0 0 24 24">
                    <path
                      d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                  </svg>
                  <span class="truncate">Lab_Guide_Optics_v2.pdf</span>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                  <button
                    class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-slate-700 dark:text-slate-200 text-xs font-bold px-3 py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] transition-colors">
                    Edit Task
                  </button>
                  <button
                    class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black px-4 py-2 rounded-xl shadow-sm flex items-center gap-1.5 transition-all">
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                      <path
                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                    </svg>
                    Open Grading Desk
                  </button>
                </div>
              </div>
            </div>

            <!-- TASK CARD 2: PROBLEM SET 3 -->
            <div v-observe style="animation-delay: 450ms;"
              class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3.5">

              <!-- Tags Row -->
              <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 flex-wrap">
                  <span
                    class="bg-amber-100 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-black px-2.5 py-0.5 rounded-md border border-amber-200/60">
                    Written Work
                  </span>
                  <span
                    class="bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-md">
                    30 Points
                  </span>
                  <span
                    class="bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-md">
                    12 STEM–2 Curie
                  </span>
                </div>
                <span
                  class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-black px-2.5 py-0.5 rounded-full border border-amber-200/80">
                  8 Ready Early
                </span>
              </div>

              <!-- Title & Description -->
              <div class="space-y-1">
                <h4 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white leading-snug">
                  Problem Set 3: Torque & Rotational Equilibrium
                </h4>
                <p class="text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed">
                  Calculations on static equilibrium, ladder problems, and center of gravity coordinates.
                </p>
              </div>

              <!-- Due Date & Progress Bar -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2">
                <div class="flex flex-wrap items-center justify-between gap-2 text-xs font-bold">
                  <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 fill-current text-slate-400 shrink-0" viewBox="0 0 24 24">
                      <path
                        d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                    </svg>
                    Due: In 2 days (Friday, 5:00 PM)
                  </span>
                  <span class="text-slate-800 dark:text-white font-extrabold">28 of 38 Turn-ins (73%)</span>
                </div>
                <div class="w-full h-2 bg-slate-200 dark:bg-[#3F4F43] rounded-full overflow-hidden">
                  <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full" style="width: 73%;"></div>
                </div>
              </div>

              <!-- Attachment & Action Button -->
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300 min-w-0">
                  <svg class="w-4 h-4 fill-current text-slate-400 shrink-0" viewBox="0 0 24 24">
                    <path
                      d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                  </svg>
                  <span class="truncate">PS3_Problem_Set.docx</span>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                  <button
                    class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-slate-700 dark:text-slate-200 text-xs font-bold px-3 py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] transition-colors">
                    Edit Task
                  </button>
                  <button
                    class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black px-4 py-2 rounded-xl shadow-sm flex items-center gap-1.5 transition-all">
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                      <path
                        d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                    </svg>
                    Review Early
                  </button>
                </div>
              </div>
            </div>

            <!-- TASK CARD 3: UNIT 2 CAPSTONE PROPOSAL -->
            <div v-observe style="animation-delay: 500ms;"
              class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3.5">

              <!-- Tags Row -->
              <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 flex-wrap">
                  <span
                    class="bg-[#FEF9E7] dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-black px-2.5 py-0.5 rounded-md border border-amber-200/60">
                    Performance Task
                  </span>
                  <span
                    class="bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-md">
                    100 Points
                  </span>
                  <span
                    class="bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-md">
                    12 STEM–1 Newton
                  </span>
                </div>
                <span
                  class="bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-0.5 rounded-full border border-emerald-200/60">
                  All 38 In
                </span>
              </div>

              <!-- Title & Description -->
              <div class="space-y-1">
                <h4 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white leading-snug">
                  Unit 2 Capstone Proposal: Hydroponics Sensor Rig
                </h4>
                <p class="text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed">
                  Automated pH, ambient illumination, and electrical conductivity telemetry schematics.
                </p>
              </div>

              <!-- Due Date & Progress Bar -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2">
                <div class="flex flex-wrap items-center justify-between gap-2 text-xs font-bold">
                  <span class="text-slate-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24">
                      <path
                        d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                    </svg>
                    Due: Oct 18 • 3 Graded
                  </span>
                  <span class="text-[#005506] dark:text-[#86EFAC] font-black">38 of 38 Turn-ins (100%)</span>
                </div>
                <div class="w-full h-2 bg-slate-200 dark:bg-[#3F4F43] rounded-full overflow-hidden">
                  <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full w-full"></div>
                </div>
              </div>

              <!-- Attachment & Action Button -->
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300 min-w-0">
                  <svg class="w-4 h-4 fill-current text-slate-400 shrink-0" viewBox="0 0 24 24">
                    <path
                      d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                  </svg>
                  <span class="truncate">Group Project (8 Groups)</span>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                  <button
                    class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-slate-700 dark:text-slate-200 text-xs font-bold px-3 py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] transition-colors">
                    Edit Task
                  </button>
                  <button
                    class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black px-4 py-2 rounded-xl shadow-sm transition-all">
                    Grade Projects
                  </button>
                </div>
              </div>
            </div>

          </div>

          <!-- RIGHT COLUMN: GRADING DESK PREVIEW PANEL (5 COLS) -->
          <div v-observe style="animation-delay: 300ms;"
            class="anim-slide-up lg:col-span-5 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4 sm:space-y-5 w-full min-w-0">

            <!-- Panel Header with Pager -->
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-[#3F4F43] pb-3 gap-2">
              <div class="min-w-0">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block truncate">GRADING DESK
                  PREVIEW</span>
                <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white truncate">Lab Activity 5
                  Evaluation
                </h3>
              </div>
              <div
                class="flex items-center gap-2 bg-[#F9F7F1] dark:bg-[#232D26] px-2.5 py-1 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] text-xs font-bold text-slate-700 dark:text-slate-300 shrink-0">
                <button class="hover:text-black dark:hover:text-white">‹</button>
                <span>1 of 14</span>
                <button class="hover:text-black dark:hover:text-white">›</button>
              </div>
            </div>

            <!-- Student Card -->
            <div
              class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div class="flex items-center gap-3 min-w-0">
                <div
                  class="w-10 h-10 rounded-full bg-[#005506] text-white font-extrabold text-xs flex items-center justify-center shrink-0">
                  MH
                </div>
                <div class="min-w-0">
                  <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white truncate">Mack Howell</h4>
                  <p class="text-[10px] font-semibold text-slate-500 truncate">LRN: 10928374019 • STEM–2 Curie</p>
                </div>
              </div>
              <span
                class="bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-1 rounded-full border border-emerald-200/60 shrink-0 text-center">
                Submitted On-Time
              </span>
            </div>

            <!-- Submitted File Attachment Pill -->
            <div
              class="bg-white dark:bg-[#2D3A31] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-2 text-xs">
              <div class="flex items-center gap-2 min-w-0">
                <div
                  class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 font-black text-[10px] flex items-center justify-center shrink-0">
                  PDF
                </div>
                <div class="min-w-0">
                  <span class="font-bold text-slate-800 dark:text-slate-200 block truncate">LabReport_05_Howell_</span>
                  <span class="text-[10px] text-slate-400 font-medium truncate block">3.4 MB • Uploaded Yesterday, 8:42
                    PM</span>
                </div>
              </div>
              <button
                class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-[#232D26] text-slate-500 transition-colors shrink-0">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                  <path
                    d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z" />
                </svg>
              </button>
            </div>

            <!-- Document Preview Frame -->
            <div
              class="relative rounded-2xl overflow-hidden border border-slate-200/80 dark:border-[#3F4F43] bg-slate-900 group">
              <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=800&auto=format&fit=crop"
                alt="Document Preview" class="w-full h-36 sm:h-44 object-cover opacity-80" />
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/30 flex flex-col justify-end p-3 text-white">
                <span class="text-[9px] font-black uppercase tracking-wider text-emerald-400">DOCUMENT PREVIEW (PAGE 1
                  OF 4)</span>
                <h5 class="text-xs font-bold drop-shadow-sm truncate">Snell's Law Critical Angle Bench Validation</h5>
              </div>
            </div>

            <!-- Analytical Rubric Form -->
            <div class="space-y-3 pt-1">
              <div class="flex items-center justify-between text-xs font-extrabold text-slate-800 dark:text-slate-200">
                <span class="uppercase text-slate-400 text-[10px]">ANALYTICAL RUBRIC</span>
                <span>Total Computed: <strong class="text-[#005506] dark:text-[#86EFAC] text-sm">46</strong> / 50</span>
              </div>

              <!-- Rubric Criteria 1 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-2 text-xs">
                <div class="min-w-0">
                  <h5 class="font-bold text-slate-800 dark:text-white truncate">Scientific Accuracy & Calculations</h5>
                  <p class="text-[10px] text-slate-500 truncate">Snell's formula, index n1/n2 precision</p>
                </div>
                <span
                  class="font-black text-slate-900 dark:text-white bg-white dark:bg-[#2D3A31] px-2.5 py-1 rounded-lg border border-slate-200/60 dark:border-[#3F4F43] shrink-0">
                  19/20
                </span>
              </div>

              <!-- Rubric Criteria 2 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-2 text-xs">
                <div class="min-w-0">
                  <h5 class="font-bold text-slate-800 dark:text-white truncate">Data Presentation & Graphs</h5>
                  <p class="text-[10px] text-slate-500 truncate">Linearized sin(i) vs sin(r) plotting</p>
                </div>
                <span
                  class="font-black text-slate-900 dark:text-white bg-white dark:bg-[#2D3A31] px-2.5 py-1 rounded-lg border border-slate-200/60 dark:border-[#3F4F43] shrink-0">
                  13/15
                </span>
              </div>

              <!-- Rubric Criteria 3 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-2 text-xs">
                <div class="min-w-0">
                  <h5 class="font-bold text-slate-800 dark:text-white truncate">Analysis & Deductive Conclusion</h5>
                  <p class="text-[10px] text-slate-500 truncate">Error margin evaluation (&lt;5% target)</p>
                </div>
                <span
                  class="font-black text-slate-900 dark:text-white bg-white dark:bg-[#2D3A31] px-2.5 py-1 rounded-lg border border-slate-200/60 dark:border-[#3F4F43] shrink-0">
                  14/15
                </span>
              </div>
            </div>

            <!-- Evaluation Remarks Box -->
            <div class="space-y-1.5 pt-1">
              <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase">
                <span>EVALUATION REMARKS</span>
                <span class="text-emerald-600 dark:text-emerald-400">Saved locally</span>
              </div>
              <textarea rows="3"
                class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-3 text-xs text-slate-800 dark:text-slate-100 font-medium focus:outline-none focus:ring-2 focus:ring-[#005506]"
                placeholder="Excellent work on resolving the critical angle equations. Clear and neat derivation layout."></textarea>
            </div>

            <!-- Primary Save Button -->
            <button
              class="w-full bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] font-black text-xs py-3 rounded-xl shadow-sm flex items-center justify-center gap-2 transition-all active:scale-95">
              <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
              </svg>
              Save & Return Grade
            </button>

          </div>

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import heroImage from '../../../../assets/img/local/assignment.png'
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

/* Base hidden state to prevent flash before observer triggers */
.anim-fade-down,
.anim-slide-up {
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