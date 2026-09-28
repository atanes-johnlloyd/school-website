<template>

  <Head title="Teacher Dashboard - Salawag LMS" />

  <div class="min-h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300">

    <!-- Sticky Sidebar Navigation (Hidden on Mobile) -->
    <div class="hidden md:block sticky top-0 h-screen z-40 shrink-0 shadow-lg">
      <Sidebart />
    </div>

    <!-- Main Workspace Canvas -->
    <main
      :class="['flex-1 relative overflow-y-auto min-h-screen flex flex-col w-full min-w-0', `text-scale-${fontSizeMode}`]">

      <!-- Top Navigation Component -->
      <Navbartop searchPlaceholder="Search learners (LRN), strand records, advisories.."
        @font-size-changed="(size) => fontSizeMode = size" />

      <!-- DASHBOARD CONTENT -->
      <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

        <!-- RE-DONE DASHBOARD HERO BANNER (100% CONSISTENT WITH LMS SYSTEM) -->
        <div v-observe
          class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[260px] flex flex-col justify-center p-4 sm:p-8 md:p-10">

          <!-- High-Quality Faculty Workspace Image (Local Import) -->
          <img :src="heroImage" alt="Institutional Faculty Portal Background"
            class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <!-- Animated Green Overlay (Blend mode matched to system) -->
          <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

          <!-- Content Container -->
          <div class="relative z-10 max-w-3xl space-y-2.5 sm:space-y-4">

            <!-- Top Badge Container -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
              <div
                class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide">
                <span>✨</span> INSTITUTIONAL FACULTY PORTAL
              </div>

              <div
                class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Term Cycle Active
              </div>
            </div>

            <!-- Main Title & Metadata Container -->
            <div class="space-y-1 sm:space-y-2">
              <h2 class="text-xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                Welcome back, Engr. Ramon Bautista!
              </h2>

              <p class="text-white/90 text-[11px] sm:text-sm md:text-base leading-relaxed font-medium">
                Faculty ID: <span class="font-black text-[#F9C20C]">EMP-86196</span> • ICT & Sciences Department •
                School Year <span class="font-bold">2026–2027 (1st Semester)</span>
              </p>
            </div>

          </div>
        </div>

        <!-- 4-CARD DASHBOARD METRICS GRID -->
        <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">

          <!-- 1. TEACHING ROSTER -->
          <div v-observe style="animation-delay: 100ms;"
            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">

            <div class="space-y-2 sm:space-y-3">
              <div class="flex items-start justify-between">
                <span
                  class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                  TEACHING ROSTER
                </span>
                <div
                  class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-[#006907] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
                  🎓
                </div>
              </div>

              <div class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">3</div>

              <div class="flex flex-wrap gap-1.5 pt-1">
                <span
                  class="bg-[#F2EFE9] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-full border border-slate-200/80 dark:border-[#3F4F43]">
                  G12 STEM
                </span>
                <span
                  class="bg-[#F2EFE9] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-full border border-slate-200/80 dark:border-[#3F4F43]">
                  G11 STEM
                </span>
                <span
                  class="bg-amber-100 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-bold px-2.5 py-1 rounded-full border border-amber-200/80 dark:border-amber-900/40">
                  G12 ICT
                </span>
              </div>
            </div>

            <div
              class="pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[11px] sm:text-xs">
              <span class="text-slate-500 dark:text-slate-400 font-medium">Curricular Load: <br><strong
                  class="text-slate-800 dark:text-slate-200 font-bold">18.0 Hrs/Wk</strong></span>
              <span class="text-[#006907] dark:text-[#86EFAC] font-black text-right">Autonomous <br>Cluster A</span>
            </div>
          </div>

          <!-- 2. ENROLLED SCHOLARS -->
          <div v-observe style="animation-delay: 200ms;"
            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">

            <div class="space-y-2 sm:space-y-3">
              <div class="flex items-start justify-between">
                <span
                  class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                  ENROLLED SCHOLARS
                </span>
                <div
                  class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
                  👨‍👩‍👧‍👦
                </div>
              </div>

              <div class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">117</div>

              <div class="space-y-1.5 pt-1">
                <div class="flex items-center justify-between text-xs">
                  <span
                    class="text-[#006907] dark:text-[#86EFAC] font-black flex items-center gap-1 text-[11px] sm:text-xs">
                    <span>✔</span> 97.5% Cap Saturation
                  </span>
                  <span class="text-slate-500 dark:text-slate-400 font-semibold text-[10px] sm:text-[11px]">117 / 120
                    Total</span>
                </div>

                <div class="w-full h-1.5 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
                  <div class="h-full bg-[#006907] dark:bg-[#86EFAC] rounded-full" style="width: 97.5%;"></div>
                </div>
              </div>
            </div>

            <div
              class="pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[11px] sm:text-xs">
              <span class="text-slate-500 dark:text-slate-400 font-medium">Verified Enrollee Records</span>
              <span class="text-[#006907] dark:text-[#86EFAC] font-black">Sync: Live</span>
            </div>
          </div>

          <!-- 3. NEEDS GRADING QUEUE -->
          <div v-observe style="animation-delay: 300ms;"
            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">

            <div class="space-y-2 sm:space-y-3">
              <div class="flex items-start justify-between">
                <span
                  class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                  NEEDS GRADING QUEUE
                </span>
                <div
                  class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40">
                  ⚠️
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-3xl sm:text-4xl font-black text-rose-600 dark:text-rose-400">18</span>
                <span
                  class="bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 text-[10px] font-black px-2.5 py-0.5 rounded-full border border-rose-200/80">
                  Priority
                </span>
              </div>

              <div
                class="flex items-center gap-1.5 text-[11px] sm:text-xs text-slate-600 dark:text-slate-300 font-medium pt-1">
                <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                <span>15 Normal • 2 Late • 3 Essays</span>
              </div>
            </div>

            <div
              class="pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[11px] sm:text-xs">
              <span class="text-orange-700 dark:text-orange-400 font-extrabold">Est. Desk Time: 35 min</span>
              <span class="text-slate-400">⏱️</span>
            </div>
          </div>

          <!-- 4. ACTIVE PUBLISHED TASKS -->
          <div v-observe style="animation-delay: 400ms;"
            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">

            <div class="space-y-2 sm:space-y-3">
              <div class="flex items-start justify-between">
                <span
                  class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                  ACTIVE PUBLISHED TASKS
                </span>
                <div
                  class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
                  ☑️
                </div>
              </div>

              <div class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">6</div>

              <div class="flex items-center justify-between pt-1">
                <span class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-300 font-medium">4 Assign. • 2
                  Quizzes</span>
                <span
                  class="bg-emerald-100 dark:bg-emerald-950 text-[#006907] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-0.5 rounded-full border border-emerald-200">
                  Active
                </span>
              </div>
            </div>

            <div
              class="pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[11px] sm:text-xs">
              <span class="text-slate-500 dark:text-slate-400 font-medium">Next: Sep 21 (Mon)</span>
              <button class="text-[#006907] dark:text-[#86EFAC] font-black hover:underline">View All</button>
            </div>
          </div>

        </div>

        <!-- SECTION 1: ASSIGNED CLASSES & ACADEMIC SECTIONS -->
        <div v-observe style="animation-delay: 300ms;" class="anim-slide-up space-y-4 sm:space-y-5">

          <!-- Header Panel -->
          <div
            class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="space-y-0.5">
              <h3 class="text-sm sm:text-base font-extrabold text-slate-800 dark:text-white">Assigned Classes & Academic
                Sections</h3>
              <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">Official instructional
                clusters, curriculum weighting formulas, and active student registries</p>
            </div>
            <span
              class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[11px] sm:text-xs font-bold px-3 sm:px-3.5 py-1 sm:py-1.5 rounded-full border border-emerald-200/60 dark:border-emerald-900/40 shrink-0 self-start sm:self-center">
              1st Semester Academic Term
            </span>
          </div>

          <!-- 3 CLASS CARDS GRID -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">

            <!-- CARD 1: GENERAL SCIENCE -->
            <div
              class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-5">
              <div class="space-y-3 sm:space-y-4">
                <!-- Top Tags -->
                <div class="flex items-center justify-between gap-2">
                  <span
                    class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-extrabold px-2 sm:px-2.5 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-900/40">
                    CORE-SCI • G12 STEM
                  </span>
                  <span
                    class="bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 text-[10px] font-bold px-2 sm:px-2.5 py-0.5 rounded-md">
                    38 / 40 Cap
                  </span>
                </div>

                <!-- Title & Section -->
                <div class="space-y-1">
                  <h4 class="text-sm sm:text-base font-extrabold text-slate-800 dark:text-white">General Science</h4>
                  <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">Section: STEM Alpha •
                    Natural Sciences Division</p>
                </div>

                <!-- Schedule Badges with Inline SVGs -->
                <div class="flex flex-wrap gap-1.5 pt-1">
                  <span
                    class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                      <path
                        d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                    </svg>
                    Mon / Wed / Fri
                  </span>
                  <span
                    class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                      <path
                        d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                    </svg>
                    07:30 – 09:30 AM
                  </span>
                  <span
                    class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                      <path
                        d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm5 12.82l-5 2.73-5-2.73v-3.82l5 2.73 5-2.73v3.82zM12 5l7 3.82-7 3.82-7-3.82L12 5z" />
                    </svg>
                    Room LAB-SCI1
                  </span>
                </div>

                <!-- Assessment Weights Box -->
                <div
                  class="bg-[#FEF9E7]/80 dark:bg-[#232D26] p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border border-amber-200/80 dark:border-[#3F4F43] space-y-2">
                  <div
                    class="flex items-center justify-between text-[9px] font-extrabold tracking-wider text-slate-400 uppercase">
                    <span>ASSESSMENT WEIGHTS</span>
                    <span>FORMULA</span>
                  </div>
                  <div class="grid grid-cols-3 gap-1.5 sm:gap-2 text-center">
                    <div
                      class="bg-white dark:bg-[#2D3A31] p-1 sm:p-1.5 rounded-lg sm:rounded-xl border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 block">Written</span>
                      <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white">25%</span>
                    </div>
                    <div
                      class="bg-white dark:bg-[#2D3A31] p-1 sm:p-1.5 rounded-lg sm:rounded-xl border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 block">Perf. Task</span>
                      <span class="text-[11px] sm:text-xs font-black text-[#005506] dark:text-[#86EFAC]">50%</span>
                    </div>
                    <div
                      class="bg-white dark:bg-[#2D3A31] p-1 sm:p-1.5 rounded-lg sm:rounded-xl border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 block">Quarterly</span>
                      <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white">25%</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Footer with Vector Icons -->
              <div class="flex items-center gap-1.5 sm:gap-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                <button
                  class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-300 text-[11px] sm:text-xs font-bold px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1 sm:gap-1.5 flex-1 transition-all">
                  <svg class="w-3.5 h-3.5 fill-current text-slate-500" viewBox="0 0 24 24">
                    <path
                      d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                  </svg>
                  Roster (38)
                </button>
                <button
                  class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] hover:bg-emerald-100 dark:hover:bg-emerald-950 text-[11px] sm:text-xs font-extrabold px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-emerald-200/60 dark:border-emerald-900/40 flex items-center justify-center gap-1 sm:gap-1.5 flex-1 transition-all">
                  <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                    <path
                      d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z" />
                  </svg>
                  Gradebook
                </button>
                <button
                  class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center shrink-0 transition-colors">
                  <svg class="w-4 h-4 fill-current text-slate-500 dark:text-slate-400" viewBox="0 0 24 24">
                    <path
                      d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM8 14H6v-2h2v2zm0-3H6V9h2v2zm0-3H6V6h2v2zm7 6h-5v-2h5v2zm3-3h-8V9h8v2zm0-3h-8V6h8v2z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- CARD 2: GENERAL MATHEMATICS -->
            <div
              class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-5">
              <div class="space-y-3 sm:space-y-4">
                <!-- Top Tags -->
                <div class="flex items-center justify-between gap-2">
                  <span
                    class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-extrabold px-2 sm:px-2.5 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-900/40">
                    CORE-MATH • G11 STEM
                  </span>
                  <span
                    class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-bold px-2 sm:px-2.5 py-0.5 rounded-md border border-amber-200/60 dark:border-amber-900/40">
                    40 / 40 (Full)
                  </span>
                </div>

                <!-- Title & Section -->
                <div class="space-y-1">
                  <h4 class="text-sm sm:text-base font-extrabold text-slate-800 dark:text-white">General Mathematics
                  </h4>
                  <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">Section: STEM Delta •
                    Computational Sciences</p>
                </div>

                <!-- Schedule Badges with Inline SVGs -->
                <div class="flex flex-wrap gap-1.5 pt-1">
                  <span
                    class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                      <path
                        d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                    </svg>
                    Mon / Wed / Fri
                  </span>
                  <span
                    class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                      <path
                        d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                    </svg>
                    10:00 – 12:00 PM
                  </span>
                  <span
                    class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                      <path
                        d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm5 12.82l-5 2.73-5-2.73v-3.82l5 2.73 5-2.73v3.82zM12 5l7 3.82-7 3.82-7-3.82L12 5z" />
                    </svg>
                    Room 101
                  </span>
                </div>

                <!-- Assessment Weights Box -->
                <div
                  class="bg-[#FEF9E7]/80 dark:bg-[#232D26] p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border border-amber-200/80 dark:border-[#3F4F43] space-y-2">
                  <div
                    class="flex items-center justify-between text-[9px] font-extrabold tracking-wider text-slate-400 uppercase">
                    <span>ASSESSMENT WEIGHTS</span>
                    <span>FORMULA</span>
                  </div>
                  <div class="grid grid-cols-3 gap-1.5 sm:gap-2 text-center">
                    <div
                      class="bg-white dark:bg-[#2D3A31] p-1 sm:p-1.5 rounded-lg sm:rounded-xl border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 block">Written</span>
                      <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white">30%</span>
                    </div>
                    <div
                      class="bg-white dark:bg-[#2D3A31] p-1 sm:p-1.5 rounded-lg sm:rounded-xl border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 block">Perf. Task</span>
                      <span class="text-[11px] sm:text-xs font-black text-[#005506] dark:text-[#86EFAC]">45%</span>
                    </div>
                    <div
                      class="bg-white dark:bg-[#2D3A31] p-1 sm:p-1.5 rounded-lg sm:rounded-xl border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 block">Quarterly</span>
                      <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white">25%</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Footer -->
              <div class="flex items-center gap-1.5 sm:gap-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                <button
                  class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-300 text-[11px] sm:text-xs font-bold px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1 sm:gap-1.5 flex-1 transition-all">
                  <svg class="w-3.5 h-3.5 fill-current text-slate-500" viewBox="0 0 24 24">
                    <path
                      d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                  </svg>
                  Roster (40)
                </button>
                <button
                  class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] hover:bg-emerald-100 dark:hover:bg-emerald-950 text-[11px] sm:text-xs font-extrabold px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-emerald-200/60 dark:border-emerald-900/40 flex items-center justify-center gap-1 sm:gap-1.5 flex-1 transition-all">
                  <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                    <path
                      d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z" />
                  </svg>
                  Gradebook
                </button>
                <button
                  class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center shrink-0 transition-colors">
                  <svg class="w-4 h-4 fill-current text-slate-500 dark:text-slate-400" viewBox="0 0 24 24">
                    <path
                      d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM8 14H6v-2h2v2zm0-3H6V9h2v2zm0-3H6V6h2v2zm7 6h-5v-2h5v2zm3-3h-8V9h8v2zm0-3h-8V6h8v2z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- CARD 3: COMPUTER PROGRAMMING -->
            <div
              class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-5">
              <div class="space-y-3 sm:space-y-4">
                <!-- Top Tags -->
                <div class="flex items-center justify-between gap-2">
                  <span
                    class="bg-amber-100/80 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-extrabold px-2 sm:px-2.5 py-0.5 rounded-md border border-amber-200/60 dark:border-amber-900/40">
                    ICT-PROG • G12 ICT
                  </span>
                  <span
                    class="bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 text-[10px] font-bold px-2 sm:px-2.5 py-0.5 rounded-md">
                    39 / 40 Cap
                  </span>
                </div>

                <!-- Title & Section -->
                <div class="space-y-1">
                  <h4 class="text-sm sm:text-base font-extrabold text-slate-800 dark:text-white">Computer Programming
                  </h4>
                  <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">Section: ICT TechPro
                    • Technical Lab</p>
                </div>

                <!-- Schedule Badges with Inline SVGs -->
                <div class="flex flex-wrap gap-1.5 pt-1">
                  <span
                    class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                      <path
                        d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                    </svg>
                    Tue / Thu
                  </span>
                  <span
                    class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                      <path
                        d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                    </svg>
                    01:00 – 04:00 PM
                  </span>
                  <span
                    class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                      <path
                        d="M20 18c1.1 0 1.99-.9 1.99-2L22 5c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 5h16v11H4V5z" />
                    </svg>
                    Room LAB-COMP1
                  </span>
                </div>

                <!-- Assessment Weights Box -->
                <div
                  class="bg-[#FEF9E7]/80 dark:bg-[#232D26] p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border border-amber-200/80 dark:border-[#3F4F43] space-y-2">
                  <div
                    class="flex items-center justify-between text-[9px] font-extrabold tracking-wider text-slate-400 uppercase">
                    <span>ASSESSMENT WEIGHTS</span>
                    <span>FORMULA</span>
                  </div>
                  <div class="grid grid-cols-3 gap-1.5 sm:gap-2 text-center">
                    <div
                      class="bg-white dark:bg-[#2D3A31] p-1 sm:p-1.5 rounded-lg sm:rounded-xl border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 block">Written</span>
                      <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white">20%</span>
                    </div>
                    <div
                      class="bg-white dark:bg-[#2D3A31] p-1 sm:p-1.5 rounded-lg sm:rounded-xl border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 block">Perf. Task</span>
                      <span class="text-[11px] sm:text-xs font-black text-[#005506] dark:text-[#86EFAC]">60%</span>
                    </div>
                    <div
                      class="bg-white dark:bg-[#2D3A31] p-1 sm:p-1.5 rounded-lg sm:rounded-xl border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 block">Quarterly</span>
                      <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white">20%</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Footer -->
              <div class="flex items-center gap-1.5 sm:gap-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                <button
                  class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-300 text-[11px] sm:text-xs font-bold px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1 sm:gap-1.5 flex-1 transition-all">
                  <svg class="w-3.5 h-3.5 fill-current text-slate-500" viewBox="0 0 24 24">
                    <path
                      d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                  </svg>
                  Roster (39)
                </button>
                <button
                  class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] hover:bg-emerald-100 dark:hover:bg-emerald-950 text-[11px] sm:text-xs font-extrabold px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-emerald-200/60 dark:border-emerald-900/40 flex items-center justify-center gap-1 sm:gap-1.5 flex-1 transition-all">
                  <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                    <path
                      d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z" />
                  </svg>
                  Gradebook
                </button>
                <button
                  class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center shrink-0 transition-colors">
                  <svg class="w-4 h-4 fill-current text-slate-500 dark:text-slate-400" viewBox="0 0 24 24">
                    <path
                      d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM8 14H6v-2h2v2zm0-3H6V9h2v2zm0-3H6V6h2v2zm7 6h-5v-2h5v2zm3-3h-8V9h8v2zm0-3h-8V6h8v2z" />
                  </svg>
                </button>
              </div>
            </div>

          </div>

        </div>

        <!-- SECTION 2: RECENT CLASS ANNOUNCEMENTS & ACADEMIC CARE & SUPPORT -->
        <div v-observe style="animation-delay: 400ms;"
          class="anim-slide-up grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 items-start">

          <!-- LEFT COLUMN: RECENT CLASS ANNOUNCEMENTS (7 COLS) -->
          <div
            class="lg:col-span-7 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4 sm:space-y-5">

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-[#3F4F43] pb-3 sm:pb-4">
              <div class="flex items-center gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                  <path
                    d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM8 14H6v-2h2v2zm0-3H6V9h2v2zm0-3H6V6h2v2zm7 6h-5v-2h5v2zm3-3h-8V9h8v2zm0-3h-8V6h8v2z" />
                </svg>
                <h3 class="text-sm sm:text-base font-extrabold text-slate-800 dark:text-white">Recent Class
                  Announcements</h3>
              </div>
              <span
                class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] sm:text-xs font-bold px-2.5 sm:px-3.5 py-0.5 sm:py-1 rounded-full border border-emerald-200/60 dark:border-emerald-900/40">
                Dispatch
              </span>
            </div>

            <!-- ANNOUNCEMENT 1 (PINNED) -->
            <div
              class="bg-[#FEF9E7]/80 dark:bg-[#232D26] p-3.5 sm:p-5 rounded-xl sm:rounded-2xl border border-amber-200/80 dark:border-[#3F4F43] space-y-2.5 sm:space-y-3">
              <div class="flex flex-wrap items-center justify-between gap-2">
                <span
                  class="bg-[#F9C20C] text-[#2C3E2D] text-[9px] sm:text-[10px] font-black px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-md shadow-sm flex items-center gap-1 sm:gap-1.5">
                  <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 fill-current" viewBox="0 0 24 24">
                    <path d="M16 9V4l1 0V2H7v2l1 0v5L6 11v2h5v7l1 1 1-1v-7h5v-2l-2-2z" />
                  </svg>
                  PINNED ANNOUNCEMENT
                </span>
                <span class="text-[10px] sm:text-[11px] font-bold text-slate-400">Posted Sep 18, 2026 • 09:15 AM</span>
              </div>

              <div class="space-y-1">
                <h4 class="text-xs sm:text-sm font-extrabold text-slate-800 dark:text-white leading-snug">
                  Midterm Examination Schedule Released — October 12 to 16, 2026
                </h4>
                <p class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed">
                  The Office of Academic Affairs has published the official midterm proctoring matrix. All subject
                  teachers must finalize student class standings and preliminary performance task records in the
                  institutional gradebook by Friday, October 9.
                </p>
              </div>

              <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-3 pt-2 border-t border-amber-200/60 dark:border-[#3F4F43]">
                <span class="text-[11px] sm:text-xs font-extrabold text-[#005506] dark:text-[#86EFAC]">
                  Target Audience: All Classes (117 Students)
                </span>
                <button
                  class="text-slate-800 dark:text-slate-200 hover:text-[#005506] dark:hover:text-[#86EFAC] text-[11px] sm:text-xs font-bold flex items-center gap-1 transition-colors self-end sm:self-auto">
                  View Exam Guidelines
                  <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                    <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- ANNOUNCEMENT 2 (LAB NOTICE) -->
            <div
              class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2.5 sm:space-y-3">
              <div class="flex flex-wrap items-center justify-between gap-2">
                <span
                  class="bg-slate-200/80 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[9px] sm:text-[10px] font-extrabold px-2 sm:px-2.5 py-0.5 rounded-md">
                  LAB NOTICE
                </span>
                <span class="text-[10px] sm:text-[11px] font-bold text-slate-400">Posted Sep 16, 2026</span>
              </div>

              <div class="space-y-1">
                <h4 class="text-xs sm:text-sm font-extrabold text-slate-800 dark:text-white leading-snug">
                  Science Laboratory Chemical Storage & Safety Equipment Calibration
                </h4>
                <p class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed">
                  Ventilation hood inspection scheduled for LAB-SCI1 this Saturday. All reagents for Grade 12 STEM
                  laboratory sets have been replenished.
                </p>
              </div>
            </div>

            <!-- Announcements Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 pt-1 text-[11px] sm:text-xs">
              <span class="text-slate-500 dark:text-slate-400 font-medium">Showing 2 of 14 published
                notifications</span>
              <button class="text-[#005506] dark:text-[#86EFAC] hover:underline font-extrabold flex items-center gap-1">
                Go to Communications Board
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                  <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z" />
                </svg>
              </button>
            </div>

          </div>

          <!-- RIGHT COLUMN: ACADEMIC CARE & SUPPORT (5 COLS) -->
          <div
            class="lg:col-span-5 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4 sm:space-y-5">

            <!-- Header -->
            <div class="flex items-start justify-between border-b border-slate-100 dark:border-[#3F4F43] pb-3 sm:pb-4">
              <div class="flex items-start gap-2.5">
                <div
                  class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/30">
                  <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current text-rose-700 dark:text-rose-300"
                    viewBox="0 0 24 24">
                    <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z" />
                  </svg>
                </div>
                <div>
                  <h3 class="text-sm sm:text-base font-extrabold text-slate-800 dark:text-white">Academic Care & Support
                  </h3>
                  <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">Autonomous
                    early-intervention tracking</p>
                </div>
              </div>

              <span
                class="bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 text-[10px] sm:text-xs font-extrabold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full border border-rose-200/80 dark:border-rose-900/40 shrink-0">
                3 Flags
              </span>
            </div>

            <!-- Alert Banner Box -->
            <div
              class="bg-rose-50/70 dark:bg-rose-950/40 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-rose-200/80 dark:border-rose-900/40 flex items-start gap-2.5 sm:gap-3">
              <svg class="w-4 h-4 fill-current text-rose-700 dark:text-rose-400 shrink-0 mt-0.5" viewBox="0 0 24 24">
                <path
                  d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" />
              </svg>
              <p class="text-[11px] sm:text-xs font-semibold text-rose-900 dark:text-rose-300 leading-relaxed">
                <strong>3 students flagged</strong> with missing submissions or consecutive attendance concerns in
                <strong>Grade 12 - STEM</strong>.
              </p>
            </div>

            <!-- FLAGGED STUDENTS LIST -->
            <div class="space-y-2.5 sm:space-y-3">

              <!-- Student 1 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-2.5 sm:gap-3">
                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                  <div
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-slate-200 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 text-[11px] sm:text-xs font-extrabold flex items-center justify-center shrink-0">
                    JD
                  </div>
                  <div class="min-w-0">
                    <h5 class="text-[11px] sm:text-xs font-extrabold text-slate-800 dark:text-white truncate">Dela Cruz,
                      Joshua M.</h5>
                    <p class="text-[9px] sm:text-[10px] font-semibold text-rose-700 dark:text-rose-400 truncate">2
                      Missing Lab Reports • General Science</p>
                  </div>
                </div>
                <button
                  class="bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl border border-slate-200/80 dark:border-[#3F4F43] shrink-0 transition-colors">
                  Notify
                </button>
              </div>

              <!-- Student 2 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-2.5 sm:gap-3">
                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                  <div
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-slate-200 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 text-[11px] sm:text-xs font-extrabold flex items-center justify-center shrink-0">
                    MA
                  </div>
                  <div class="min-w-0">
                    <h5 class="text-[11px] sm:text-xs font-extrabold text-slate-800 dark:text-white truncate">Aquino,
                      Michaela R.</h5>
                    <p class="text-[9px] sm:text-[10px] font-semibold text-amber-800 dark:text-amber-400 truncate">2
                      Unexcused Absences • Room LAB-SCI1</p>
                  </div>
                </div>
                <button
                  class="bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl border border-slate-200/80 dark:border-[#3F4F43] shrink-0 transition-colors">
                  Notify
                </button>
              </div>

              <!-- Student 3 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-2.5 sm:gap-3">
                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                  <div
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-slate-200 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 text-[11px] sm:text-xs font-extrabold flex items-center justify-center shrink-0">
                    CS
                  </div>
                  <div class="min-w-0">
                    <h5 class="text-[11px] sm:text-xs font-extrabold text-slate-800 dark:text-white truncate">Santos,
                      Carlo P.</h5>
                    <p class="text-[9px] sm:text-[10px] font-semibold text-rose-700 dark:text-rose-400 truncate">
                      Assignment 1 Overdue > 4 Days</p>
                  </div>
                </div>
                <button
                  class="bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl border border-slate-200/80 dark:border-[#3F4F43] shrink-0 transition-colors">
                  Notify
                </button>
              </div>

            </div>

            <!-- Footer Counselor Contact -->
            <div
              class="flex flex-col sm:flex-row items-center justify-between gap-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43] text-[11px] sm:text-xs">
              <span class="text-slate-500 dark:text-slate-400 font-medium">Counselor: Ms. A. Velasco</span>
              <button
                class="text-[#005506] dark:text-[#86EFAC] hover:underline font-extrabold flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                  <path
                    d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V8h16v10z" />
                </svg>
                Open Case Notes
              </button>
            </div>

          </div>

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import heroImage from '../../../assets/img/local/dashboard.png'
import { Head } from '@inertiajs/vue3'
import Sidebart from '@/Components/Sidebart.vue'
import Navbartop from '@/Components/navbartop.vue'

defineProps({
  stats: Object,
  activeTerm: String,
  today: String,
  needs_grading: Array,
  today_schedule: Array,
})

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

/* Page load and refresh entrance animations */
@keyframes slideUpFade {
  from {
    opacity: 0;
    transform: translateY(25px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-15px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.not-visible {
  opacity: 0;
}

.anim-fade-down,
.is-animated.anim-fade-down {
  animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.anim-slide-up,
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