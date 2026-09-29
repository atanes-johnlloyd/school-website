<template>

  <Head title="My Classes & Section Rosters - Salawag LMS" />

  <div class="min-h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300">

    <!-- Sticky Sidebar Navigation (Hidden on Mobile, Visible on MD+) -->
    <div class="hidden md:block sticky top-0 h-screen z-40 shrink-0 shadow-lg">
      <Sidebart />
    </div>

    <!-- Main Workspace Canvas -->
    <main
      :class="['flex-1 relative overflow-y-auto min-h-screen flex flex-col w-full min-w-0', `text-scale-${fontSizeMode}`]">

      <!-- Reusable Top Navigation Component -->
      <navbartop searchPlaceholder="Search learners (LRN), strand records, advisories.."
        @font-size-changed="(size) => fontSizeMode = size" />

      <!-- MAIN PAGE CONTENT -->
      <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

        <!-- CLASSES HERO BANNER -->
        <div v-observe
          class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center anim-fade-down">
          <!-- Background Image properly aligned and fitted -->
          <img :src="heroImage" alt="Classes Hub Background"
            class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <!-- Animated Green Overlay -->
          <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply">
          </div>

          <!-- Inner Content Container -->
          <div class="relative z-10 p-4 sm:p-8 md:p-10 flex flex-col justify-center">

            <!-- Top Badges -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 mb-3 sm:mb-4">
              <span
                class="bg-[#005506] text-white border border-emerald-400/30 text-[10px] sm:text-[11px] md:text-xs font-bold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                <span>🏛️</span> DepEd Region IV-A • SDO Dasmariñas
              </span>
              <span
                class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                <span>📅</span> S.Y. 2025–2026 • 2nd Semester (Midterm)
              </span>
            </div>

            <!-- Title & Description -->
            <div class="space-y-1.5 sm:space-y-2 max-w-3xl">
              <h2 class="text-xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight">
                My Classes & Section Rosters Hub
              </h2>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Manage section registries, track DepEd Learner Reference Numbers (LRN), inspect quarterly transmutation
                metrics, and dispatch real-time parent advisories.
              </p>
            </div>

          </div>
        </div>

        <!-- TEACHING ASSIGNMENTS & ROSTERS SECTION -->
        <div class="space-y-4 sm:space-y-6 pt-2 sm:pt-4">

          <!-- Section Header & Filter Pills -->
          <div v-observe
            class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-3.5 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4"
            style="animation-delay: 100ms;">
            <!-- Strand Filter Pills -->
            <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto no-scrollbar">
              <button
                class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-[11px] sm:text-xs font-black px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl shadow-sm transition-all shrink-0">
                All Classes (5)
              </button>
              <button
                class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-[11px] sm:text-xs font-bold px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] transition-all shrink-0">
                STEM (3)
              </button>
              <button
                class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-[11px] sm:text-xs font-bold px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] transition-all shrink-0">
                ICT (2)
              </button>
            </div>

            <!-- Active Term Dropdown Selector -->
            <div class="flex items-center justify-between sm:justify-start gap-2 shrink-0 w-full md:w-auto">
              <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Active Term:</span>
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-extrabold px-3 py-1.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-2 shadow-sm">
                <svg class="w-3.5 h-3.5 fill-current text-[#005506] dark:text-[#86EFAC]" viewBox="0 0 24 24">
                  <path
                    d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                </svg>
                <span>1st Semester 2026–2027</span>
                <svg class="w-3 h-3 fill-current text-slate-400" viewBox="0 0 24 24">
                  <path d="M7 10l5 5 5-5z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- HORIZONTALLY SCROLLABLE CLASS CARDS TRACK (5 CARDS) -->
          <div class="relative group">
            <div
              class="flex items-stretch gap-4 sm:gap-6 overflow-x-auto pb-4 pt-1 snap-x snap-mandatory scroll-smooth no-scrollbar -mx-3 px-3 sm:mx-0 sm:px-0">

              <!-- CARD 1: GRADE 12 - STEM -->
              <div v-observe
                class="anim-slide-up snap-start shrink-0 w-[280px] sm:w-[340px] md:w-[360px] bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-md border-2 border-[#005506] dark:border-[#86EFAC] flex flex-col justify-between space-y-4 sm:space-y-5"
                style="animation-delay: 150ms;">
                <div class="space-y-3 sm:space-y-4">
                  <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                      <span
                        class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2 sm:px-2.5 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-900/40">
                        STEM STRAND
                      </span>
                      <span
                        class="bg-amber-100/80 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-md">
                        160 Hours
                      </span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">CORE-SCI</span>
                  </div>

                  <div class="space-y-0.5">
                    <h4 class="text-sm sm:text-base font-black text-slate-900 dark:text-white">Grade 12 – STEM</h4>
                    <h3 class="text-base sm:text-lg font-black text-[#005506] dark:text-[#86EFAC]">General Science</h3>
                  </div>

                  <div
                    class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2.5 text-xs">
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-[#005506] dark:text-[#86EFAC] shrink-0 mt-0.5"
                        viewBox="0 0 24 24">
                        <path
                          d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm5 12.82l-5 2.73-5-2.73v-3.82l5 2.73 5-2.73v3.82zM12 5l7 3.82-7 3.82-7-3.82L12 5z" />
                      </svg>
                      <span><strong>LAB-SCI1</strong> <span class="text-slate-500 dark:text-slate-400">(Lab
                          1)</span></span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-[#005506] dark:text-[#86EFAC] shrink-0 mt-0.5"
                        viewBox="0 0 24 24">
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                      </svg>
                      <span>Mon / Wed / Fri • 07:30 – 09:30 AM</span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-[#005506] dark:text-[#86EFAC] shrink-0 mt-0.5"
                        viewBox="0 0 24 24">
                        <path
                          d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                      </svg>
                      <span>Adviser: <strong>Mr. Bo Senger</strong> <span class="text-slate-400">(ID: 7)</span></span>
                    </div>
                  </div>

                  <div class="space-y-1 sm:space-y-1.5">
                    <div class="flex items-center justify-between text-[11px] sm:text-xs">
                      <span class="text-slate-500 dark:text-slate-400 font-medium">Capacity</span>
                      <span class="font-extrabold text-slate-900 dark:text-white">38 / 40 <span
                          class="text-slate-400 font-normal">(95%)</span></span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
                      <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full" style="width: 95%;"></div>
                    </div>
                  </div>

                  <div class="space-y-1 sm:space-y-1.5 pt-0.5">
                    <div class="flex items-center gap-1.5">
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">WW:
                        <strong>25%</strong></span>
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">PT:
                        <strong>50%</strong></span>
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">QE:
                        <strong>25%</strong></span>
                    </div>
                    <div class="text-right">
                      <span
                        class="text-[10px] font-black text-[#005506] dark:text-[#86EFAC] inline-flex items-center gap-1">✔
                        100% Validated</span>
                    </div>
                  </div>
                </div>

                <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                  <button
                    class="w-full bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] hover:bg-[#004105] py-2 sm:py-2.5 rounded-xl text-xs font-black shadow-sm flex items-center justify-center gap-2 transition-all">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                      <path
                        d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                    View Roster & Desk
                  </button>
                  <div class="grid grid-cols-2 gap-2">
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
                      Edit Weights
                    </button>
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
                      Gradebook
                    </button>
                  </div>
                </div>
              </div>

              <!-- CARD 2: GRADE 11 - STEM -->
              <div v-observe
                class="anim-slide-up snap-start shrink-0 w-[280px] sm:w-[340px] md:w-[360px] bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-5"
                style="animation-delay: 250ms;">
                <div class="space-y-3 sm:space-y-4">
                  <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                      <span
                        class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2 sm:px-2.5 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-900/40">
                        STEM STRAND
                      </span>
                      <span
                        class="bg-amber-100/80 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-md">
                        160 Hours
                      </span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">CORE-MATH</span>
                  </div>

                  <div class="space-y-0.5">
                    <h4 class="text-sm sm:text-base font-black text-slate-900 dark:text-white">Grade 11 – STEM</h4>
                    <h3 class="text-base sm:text-lg font-black text-slate-800 dark:text-white">General Mathematics</h3>
                  </div>

                  <div
                    class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2.5 text-xs">
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path
                          d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm5 12.82l-5 2.73-5-2.73v-3.82l5 2.73 5-2.73v3.82zM12 5l7 3.82-7 3.82-7-3.82L12 5z" />
                      </svg>
                      <span><strong>Room 101</strong> <span
                          class="text-slate-500 dark:text-slate-400">(Main)</span></span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                      </svg>
                      <span>Tue / Thu • 10:00 – 12:00 PM</span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path
                          d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                      </svg>
                      <span>Adviser: <strong>Self Assigned</strong></span>
                    </div>
                  </div>

                  <div class="space-y-1 sm:space-y-1.5">
                    <div class="flex items-center justify-between text-[11px] sm:text-xs">
                      <span class="text-slate-500 dark:text-slate-400 font-medium">Capacity</span>
                      <span class="font-black text-amber-700 dark:text-amber-400">40 / 40 <span
                          class="text-xs">(Full)</span></span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
                      <div class="h-full bg-amber-600 rounded-full w-full"></div>
                    </div>
                  </div>

                  <div class="space-y-1 sm:space-y-1.5 pt-0.5">
                    <div class="flex items-center gap-1.5">
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">WW:
                        <strong>30%</strong></span>
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">PT:
                        <strong>40%</strong></span>
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">QE:
                        <strong>30%</strong></span>
                    </div>
                    <div class="text-right">
                      <span
                        class="text-[10px] font-black text-[#005506] dark:text-[#86EFAC] inline-flex items-center gap-1">✔
                        Configured</span>
                    </div>
                  </div>
                </div>

                <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                  <button
                    class="w-full bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-800 dark:text-white py-2 sm:py-2.5 rounded-xl text-xs font-black border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-2 transition-all">
                    Switch Active Roster
                  </button>
                  <div class="grid grid-cols-2 gap-2">
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-xl border border-slate-200/80 transition-colors">Attendance</button>
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-xl border border-slate-200/80 transition-colors">Gradebook</button>
                  </div>
                </div>
              </div>

              <!-- CARD 3: GRADE 12 - ICT -->
              <div v-observe
                class="anim-slide-up snap-start shrink-0 w-[280px] sm:w-[340px] md:w-[360px] bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-5"
                style="animation-delay: 350ms;">
                <div class="space-y-3 sm:space-y-4">
                  <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                      <span
                        class="bg-amber-100/80 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-black px-2 sm:px-2.5 py-0.5 rounded-md border border-amber-200/60 dark:border-amber-900/40">
                        ICT STRAND
                      </span>
                      <span
                        class="bg-amber-100/80 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-md">
                        80 Hours
                      </span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ICT-PROG</span>
                  </div>

                  <div class="space-y-0.5">
                    <h4 class="text-sm sm:text-base font-black text-slate-900 dark:text-white">Grade 12 – ICT</h4>
                    <h3 class="text-base sm:text-lg font-black text-slate-800 dark:text-white">Computer Programming</h3>
                  </div>

                  <div
                    class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2.5 text-xs">
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path
                          d="M20 18c1.1 0 1.99-.9 1.99-2L22 5c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 5h16v11H4V5z" />
                      </svg>
                      <span><strong>LAB-COMP1</strong> <span class="text-slate-500 dark:text-slate-400">(Comp
                          Lab)</span></span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                      </svg>
                      <span>Fri • 08:00 – 12:00 PM</span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path
                          d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                      </svg>
                      <span>Adviser: <strong>Mrs. Carmina Cruz</strong></span>
                    </div>
                  </div>

                  <div class="space-y-1 sm:space-y-1.5">
                    <div class="flex items-center justify-between text-[11px] sm:text-xs">
                      <span class="text-slate-500 dark:text-slate-400 font-medium">Capacity</span>
                      <span class="font-extrabold text-slate-900 dark:text-white">39 / 40 <span
                          class="text-slate-400 font-normal">(97%)</span></span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
                      <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full" style="width: 97%;"></div>
                    </div>
                  </div>

                  <div class="space-y-1 sm:space-y-1.5 pt-0.5">
                    <div class="flex items-center gap-1.5">
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">WW:
                        <strong>20%</strong></span>
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">PT:
                        <strong>60%</strong></span>
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">QE:
                        <strong>20%</strong></span>
                    </div>
                    <div class="text-right">
                      <span
                        class="text-[10px] font-black text-[#005506] dark:text-[#86EFAC] inline-flex items-center gap-1">✔
                        Configured</span>
                    </div>
                  </div>
                </div>

                <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                  <button
                    class="w-full bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-slate-800 dark:text-white py-2 sm:py-2.5 rounded-xl text-xs font-black border border-slate-200/80 transition-all">Switch
                    Active Roster</button>
                  <div class="grid grid-cols-2 gap-2">
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-xl border border-slate-200/80 transition-colors">Attendance</button>
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-xl border border-slate-200/80 transition-colors">Gradebook</button>
                  </div>
                </div>
              </div>

              <!-- CARD 4: GRADE 12 - STEM PHYSICS -->
              <div v-observe
                class="anim-slide-up snap-start shrink-0 w-[280px] sm:w-[340px] md:w-[360px] bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-5"
                style="animation-delay: 450ms;">
                <div class="space-y-3 sm:space-y-4">
                  <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                      <span
                        class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2 sm:px-2.5 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-900/40">
                        STEM STRAND
                      </span>
                      <span
                        class="bg-amber-100/80 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-md">
                        120 Hours
                      </span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">SPEC-PHYS</span>
                  </div>

                  <div class="space-y-0.5">
                    <h4 class="text-sm sm:text-base font-black text-slate-900 dark:text-white">Grade 12 – STEM Beta</h4>
                    <h3 class="text-base sm:text-lg font-black text-slate-800 dark:text-white">General Physics 2</h3>
                  </div>

                  <div
                    class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2.5 text-xs">
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path
                          d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm5 12.82l-5 2.73-5-2.73v-3.82l5 2.73 5-2.73v3.82zM12 5l7 3.82-7 3.82-7-3.82L12 5z" />
                      </svg>
                      <span><strong>SciLab 2</strong> <span
                          class="text-slate-500 dark:text-slate-400">(Physics)</span></span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                      </svg>
                      <span>Tue / Thu • 01:00 – 03:00 PM</span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path
                          d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                      </svg>
                      <span>Adviser: <strong>Dr. Alan Turing</strong></span>
                    </div>
                  </div>

                  <div class="space-y-1 sm:space-y-1.5">
                    <div class="flex items-center justify-between text-[11px] sm:text-xs">
                      <span class="text-slate-500 dark:text-slate-400 font-medium">Capacity</span>
                      <span class="font-extrabold text-slate-900 dark:text-white">36 / 40 <span
                          class="text-slate-400 font-normal">(90%)</span></span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
                      <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full" style="width: 90%;"></div>
                    </div>
                  </div>

                  <div class="space-y-1 sm:space-y-1.5 pt-0.5">
                    <div class="flex items-center gap-1.5">
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">WW:
                        <strong>25%</strong></span>
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">PT:
                        <strong>45%</strong></span>
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">QE:
                        <strong>30%</strong></span>
                    </div>
                    <div class="text-right">
                      <span
                        class="text-[10px] font-black text-[#005506] dark:text-[#86EFAC] inline-flex items-center gap-1">✔
                        Configured</span>
                    </div>
                  </div>
                </div>

                <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                  <button
                    class="w-full bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-slate-800 dark:text-white py-2 sm:py-2.5 rounded-xl text-xs font-black border border-slate-200/80 transition-all">Switch
                    Active Roster</button>
                  <div class="grid grid-cols-2 gap-2">
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-xl border border-slate-200/80 transition-colors">Attendance</button>
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-xl border border-slate-200/80 transition-colors">Gradebook</button>
                  </div>
                </div>
              </div>

              <!-- CARD 5: GRADE 11 - ICT WEB DESIGN -->
              <div v-observe
                class="anim-slide-up snap-start shrink-0 w-[280px] sm:w-[340px] md:w-[360px] bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-5"
                style="animation-delay: 550ms;">
                <div class="space-y-3 sm:space-y-4">
                  <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                      <span
                        class="bg-amber-100/80 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-black px-2 sm:px-2.5 py-0.5 rounded-md border border-amber-200/60 dark:border-amber-900/40">
                        ICT STRAND
                      </span>
                      <span
                        class="bg-amber-100/80 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-md">
                        100 Hours
                      </span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ICT-WEB</span>
                  </div>

                  <div class="space-y-0.5">
                    <h4 class="text-sm sm:text-base font-black text-slate-900 dark:text-white">Grade 11 – ICT TechLab
                    </h4>
                    <h3 class="text-base sm:text-lg font-black text-slate-800 dark:text-white">Web Development & UI</h3>
                  </div>

                  <div
                    class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2.5 text-xs">
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path
                          d="M20 18c1.1 0 1.99-.9 1.99-2L22 5c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 5h16v11H4V5z" />
                      </svg>
                      <span><strong>LAB-COMP2</strong> <span
                          class="text-slate-500 dark:text-slate-400">(Multimedia)</span></span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                      </svg>
                      <span>Mon / Wed • 03:00 – 05:00 PM</span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700 dark:text-slate-300 font-medium">
                      <svg class="w-4 h-4 fill-current text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path
                          d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                      </svg>
                      <span>Adviser: <strong>Engr. Grace Hopper</strong></span>
                    </div>
                  </div>

                  <div class="space-y-1 sm:space-y-1.5">
                    <div class="flex items-center justify-between text-[11px] sm:text-xs">
                      <span class="text-slate-500 dark:text-slate-400 font-medium">Capacity</span>
                      <span class="font-extrabold text-slate-900 dark:text-white">37 / 40 <span
                          class="text-slate-400 font-normal">(92%)</span></span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
                      <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full" style="width: 92%;"></div>
                    </div>
                  </div>

                  <div class="space-y-1 sm:space-y-1.5 pt-0.5">
                    <div class="flex items-center gap-1.5">
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">WW:
                        <strong>20%</strong></span>
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">PT:
                        <strong>60%</strong></span>
                      <span
                        class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200/80 dark:border-[#3F4F43]">QE:
                        <strong>20%</strong></span>
                    </div>
                    <div class="text-right">
                      <span
                        class="text-[10px] font-black text-[#005506] dark:text-[#86EFAC] inline-flex items-center gap-1">✔
                        Configured</span>
                    </div>
                  </div>
                </div>

                <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                  <button
                    class="w-full bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-slate-800 dark:text-white py-2 sm:py-2.5 rounded-xl text-xs font-black border border-slate-200/80 transition-all">Switch
                    Active Roster</button>
                  <div class="grid grid-cols-2 gap-2">
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-xl border border-slate-200/80 transition-colors">Attendance</button>
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-xl border border-slate-200/80 transition-colors">Gradebook</button>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>

        <!-- ACTIVE CLASS HERO BANNER -->
        <div v-observe
          class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-[#3F4F43] bg-[#005506] p-4 sm:p-8 md:p-10 text-white min-h-[180px] sm:min-h-[200px] flex flex-col justify-center anim-fade-down"
          style="animation-delay: 200ms;">

          <!-- Background Image Layer -->
          <img :src="banner" alt="Classroom Banner Background"
            class="absolute inset-0 w-full h-full object-cover object-[center_30%] z-0 opacity-70 dark:opacity-50 mix-blend-soft-light pointer-events-none transition-all duration-300" />

          <!-- Lighter Animated Green Overlay -->
          <div
            class="absolute inset-0 bg-gradient-to-r from-[#005506]/85 via-[#006907]/70 to-[#004105]/80 animate-overlay z-0">
          </div>

          <!-- Ambient Light Glow Highlights -->
          <div
            class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/30 blur-3xl pointer-events-none z-0">
          </div>
          <div
            class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/25 blur-3xl pointer-events-none z-0">
          </div>

          <!-- Content Container -->
          <div class="relative z-10 space-y-2.5 sm:space-y-3.5 max-w-4xl">
            <!-- Top Badges -->
            <div class="flex flex-wrap items-center gap-2">
              <span
                class="bg-[#F9C20C] text-[#2C3E2D] text-[9px] sm:text-[10px] md:text-xs font-black px-2.5 sm:px-3.5 py-0.5 sm:py-1 rounded-full shadow-sm flex items-center gap-1.5 uppercase tracking-wider">
                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-[#005506] animate-ping"></span>
                ACTIVE CLASS VIEW
              </span>
              <span
                class="bg-white/20 backdrop-blur-md text-white border border-white/25 text-[10px] sm:text-[11px] md:text-xs font-bold px-2.5 sm:px-3.5 py-0.5 sm:py-1 rounded-full shadow-sm">
                Subject Code: CORE-SCI • Section ID: SEC-12A
              </span>
            </div>

            <!-- Title -->
            <h2
              class="text-xl sm:text-3xl md:text-4xl font-extrabold tracking-tight leading-tight text-white drop-shadow-md">
              Grade 12 – STEM • General Science
            </h2>

            <!-- Schedule & Room Info Bar -->
            <div
              class="flex flex-wrap items-center gap-x-3 sm:gap-x-4 gap-y-1 text-[11px] sm:text-xs md:text-sm font-medium text-emerald-50 pt-0.5">
              <div class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current text-[#F9C20C] shrink-0" viewBox="0 0 24 24">
                  <path
                    d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm5 12.82l-5 2.73-5-2.73v-3.82l5 2.73 5-2.73v3.82zM12 5l7 3.82-7 3.82-7-3.82L12 5z" />
                </svg>
                <span>Allocated Room: <strong class="text-white font-bold">LAB-SCI1 (Science Lab 1)</strong></span>
              </div>
              <span class="hidden sm:inline text-white/50">•</span>
              <div class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current text-[#F9C20C] shrink-0" viewBox="0 0 24 24">
                  <path
                    d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                </svg>
                <span>Schedule: <strong class="text-white font-bold">Mon/Wed/Fri 07:30 – 09:30 AM</strong></span>
              </div>
              <span class="hidden sm:inline text-white/50">•</span>
              <span class="text-emerald-200 font-semibold">1st Semester 2026–2027</span>
            </div>
          </div>
        </div>

        <!-- TABULATOR ENROLLED STUDENT ROSTER SECTION -->
        <div v-observe
          class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4 sm:space-y-6"
          style="animation-delay: 300ms;">

          <!-- Header & Toolbar Action Buttons -->
          <div
            class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-4 border-b border-slate-100 dark:border-[#3F4F43] pb-4 sm:pb-6">
            <div class="flex items-start gap-2.5 sm:gap-3">
              <div
                class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24">
                  <path
                    d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                </svg>
              </div>
              <div>
                <h3 class="text-base sm:text-lg font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
                  Enrolled Student Roster (38)
                </h3>
                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                  Verified entries from institutional <code
                    class="bg-slate-100 dark:bg-[#232D26] px-1 py-0.5 rounded text-[10px] text-emerald-800 dark:text-emerald-300">class_students</code>
                  and <code
                    class="bg-slate-100 dark:bg-[#232D26] px-1 py-0.5 rounded text-[10px] text-emerald-800 dark:text-emerald-300">students</code>
                  records
                </p>
              </div>
            </div>

            <!-- Action Toolbar Buttons -->
            <div class="flex flex-wrap items-center gap-2">
              <button
                class="bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] text-[11px] sm:text-xs font-black px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl shadow-sm flex items-center gap-1.5 transition-all active:scale-95">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current" viewBox="0 0 24 24">
                  <path
                    d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9 0c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm9 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V20h6v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                + Enroll Student to Class
              </button>
              <button
                class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-bold px-3 py-2 sm:py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1 transition-colors">
                <svg class="w-3.5 h-3.5 fill-current text-slate-500" viewBox="0 0 24 24">
                  <path
                    d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z" />
                </svg>
                Sync Admissions
              </button>
              <button
                class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-bold px-2.5 py-2 sm:py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1 transition-colors">
                <svg class="w-3.5 h-3.5 fill-current text-slate-500" viewBox="0 0 24 24">
                  <path
                    d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z" />
                </svg>
                PDF
              </button>
              <button
                class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-bold px-2.5 py-2 sm:py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1 transition-colors">
                <svg class="w-3.5 h-3.5 fill-current text-slate-500" viewBox="0 0 24 24">
                  <path
                    d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                </svg>
                CSV
              </button>
            </div>
          </div>

          <!-- Filter Bar & Active Counts -->
          <div
            class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4 bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
            <!-- Search Input Filter -->
            <div class="relative w-full sm:w-80">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                  <path
                    d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                </svg>
              </span>
              <input type="text" placeholder="Filter this class roster..."
                class="w-full pl-9 pr-4 py-2 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-xs text-slate-700 dark:text-slate-200 shadow-sm" />
            </div>

            <!-- Active Count Pills -->
            <div class="flex items-center justify-between sm:justify-end gap-3 text-xs font-extrabold shrink-0">
              <span class="flex items-center gap-1.5 text-slate-700 dark:text-slate-300">
                <span class="w-2.5 h-2.5 rounded-full bg-[#005506] dark:bg-[#86EFAC]"></span> 37 Active
              </span>
              <span class="flex items-center gap-1.5 text-rose-700 dark:text-rose-400">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> 1 Dropped / Transferred
              </span>
            </div>
          </div>

          <!-- TABULATOR TABLE CONTAINER WITH FULL HORIZONTAL MOBILE SCROLL -->
          <div
            class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
            <table class="w-full min-w-[650px] text-left border-collapse">
              <thead>
                <tr
                  class="bg-[#F9F7F1] dark:bg-[#232D26] text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-[#3F4F43]">
                  <th class="py-3 px-3 sm:px-4">ID & STUDENT NUMBER</th>
                  <th class="py-3 px-3 sm:px-4">FULL NAME</th>
                  <th class="py-3 px-3 sm:px-4">GENDER</th>
                  <th class="py-3 px-3 sm:px-4">CONTACT</th>
                  <th class="py-3 px-3 sm:px-4">ADDRESS / BARANGAY</th>
                  <th class="py-3 px-3 sm:px-4">ENROLLMENT STATUS</th>
                  <th class="py-3 px-3 sm:px-4 text-center">ACTIONS</th>
                </tr>
              </thead>
              <tbody
                class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-xs font-medium text-slate-700 dark:text-slate-200">

                <!-- ROW 1 -->
                <tr class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                  <td class="py-3.5 px-3 sm:px-4 font-bold text-slate-900 dark:text-white">
                    210891196115 <span
                      class="bg-slate-100 dark:bg-[#232D26] text-slate-500 px-1.5 py-0.5 rounded text-[10px] font-semibold ml-1">ID:
                      10</span>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <div class="flex items-center gap-2.5">
                      <span
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 font-extrabold text-[11px] sm:text-xs flex items-center justify-center shrink-0">PB</span>
                      <span class="font-extrabold text-slate-800 dark:text-white">Priscilla Beahan</span>
                    </div>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-600 dark:text-slate-300">Female</td>
                  <td class="py-3.5 px-3 sm:px-4 font-mono text-slate-600 dark:text-slate-300">09078002481</td>
                  <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-600 dark:text-slate-300 leading-snug">Brgy.
                    Paliparan
                    III, Dasmariñas</td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <span
                      class="bg-emerald-50 dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] border border-emerald-200/60 dark:border-emerald-900/40 text-[10px] sm:text-[11px] font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full inline-flex items-center gap-1">
                      • Active
                    </span>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <div class="flex items-center justify-center gap-1.5">
                      <button
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current" viewBox="0 0 24 24">
                          <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                        </svg>
                      </button>
                      <button
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current" viewBox="0 0 24 24">
                          <path
                            d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>

                <!-- ROW 2 -->
                <tr class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                  <td class="py-3.5 px-3 sm:px-4 font-bold text-slate-900 dark:text-white">
                    495927041034 <span
                      class="bg-slate-100 dark:bg-[#232D26] text-slate-500 px-1.5 py-0.5 rounded text-[10px] font-semibold ml-1">ID:
                      11</span>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <div class="flex items-center gap-2.5">
                      <span
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-amber-100 text-amber-900 dark:bg-amber-950/60 dark:text-amber-300 font-extrabold text-[11px] sm:text-xs flex items-center justify-center shrink-0">DR</span>
                      <span class="font-extrabold text-slate-800 dark:text-white">Domenic Roob</span>
                    </div>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-600 dark:text-slate-300">Male</td>
                  <td class="py-3.5 px-3 sm:px-4 font-mono text-slate-600 dark:text-slate-300">09371032134</td>
                  <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-600 dark:text-slate-300 leading-snug">Brgy.
                    Paliparan I,
                    Dasmariñas</td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <span
                      class="bg-emerald-50 dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] border border-emerald-200/60 dark:border-emerald-900/40 text-[10px] sm:text-[11px] font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full inline-flex items-center gap-1">
                      • Active
                    </span>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <div class="flex items-center justify-center gap-1.5">
                      <button
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current" viewBox="0 0 24 24">
                          <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                        </svg>
                      </button>
                      <button
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current" viewBox="0 0 24 24">
                          <path
                            d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>

                <!-- ROW 3 -->
                <tr class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                  <td class="py-3.5 px-3 sm:px-4 font-bold text-slate-900 dark:text-white">
                    854588994993 <span
                      class="bg-slate-100 dark:bg-[#232D26] text-slate-500 px-1.5 py-0.5 rounded text-[10px] font-semibold ml-1">ID:
                      12</span>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <div class="flex items-center gap-2.5">
                      <span
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 font-extrabold text-[11px] sm:text-xs flex items-center justify-center shrink-0">BM</span>
                      <span class="font-extrabold text-slate-800 dark:text-white">Bernie Mann</span>
                    </div>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-600 dark:text-slate-300">Male</td>
                  <td class="py-3.5 px-3 sm:px-4 font-mono text-slate-600 dark:text-slate-300">09200203044</td>
                  <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-600 dark:text-slate-300 leading-snug">Brgy.
                    Paliparan
                    III, Dasmariñas</td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <span
                      class="bg-emerald-50 dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] border border-emerald-200/60 dark:border-emerald-900/40 text-[10px] sm:text-[11px] font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full inline-flex items-center gap-1">
                      • Active
                    </span>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <div class="flex items-center justify-center gap-1.5">
                      <button
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current" viewBox="0 0 24 24">
                          <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                        </svg>
                      </button>
                      <button
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current" viewBox="0 0 24 24">
                          <path
                            d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>

                <!-- ROW 4 -->
                <tr class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                  <td class="py-3.5 px-3 sm:px-4 font-bold text-slate-900 dark:text-white">
                    288126615810 <span
                      class="bg-slate-100 dark:bg-[#232D26] text-slate-500 px-1.5 py-0.5 rounded text-[10px] font-semibold ml-1">ID:
                      13</span>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <div class="flex items-center gap-2.5">
                      <span
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-amber-100 text-amber-900 dark:bg-amber-950/60 dark:text-amber-300 font-extrabold text-[11px] sm:text-xs flex items-center justify-center shrink-0">BR</span>
                      <span class="font-extrabold text-slate-800 dark:text-white">Beverly Rolfson</span>
                    </div>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-600 dark:text-slate-300">Female</td>
                  <td class="py-3.5 px-3 sm:px-4 font-mono text-slate-600 dark:text-slate-300">09194883921</td>
                  <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-600 dark:text-slate-300 leading-snug">Brgy.
                    Salawag,
                    Dasmariñas</td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <span
                      class="bg-emerald-50 dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] border border-emerald-200/60 dark:border-emerald-900/40 text-[10px] sm:text-[11px] font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full inline-flex items-center gap-1">
                      • Active
                    </span>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <div class="flex items-center justify-center gap-1.5">
                      <button
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current" viewBox="0 0 24 24">
                          <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                        </svg>
                      </button>
                      <button
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current" viewBox="0 0 24 24">
                          <path
                            d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>

                <!-- ROW 5 -->
                <tr class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                  <td class="py-3.5 px-3 sm:px-4 font-bold text-slate-900 dark:text-white">
                    084728357537 <span
                      class="bg-slate-100 dark:bg-[#232D26] text-slate-500 px-1.5 py-0.5 rounded text-[10px] font-semibold ml-1">ID:
                      14</span>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <div class="flex items-center gap-2.5">
                      <span
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 font-extrabold text-[11px] sm:text-xs flex items-center justify-center shrink-0">JH</span>
                      <span class="font-extrabold text-slate-800 dark:text-white">Juliet Harber</span>
                    </div>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-600 dark:text-slate-300">Female</td>
                  <td class="py-3.5 px-3 sm:px-4 font-mono text-slate-600 dark:text-slate-300">09664910283</td>
                  <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-600 dark:text-slate-300 leading-snug">Brgy.
                    Salawag,
                    Dasmariñas</td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <span
                      class="bg-emerald-50 dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] border border-emerald-200/60 dark:border-emerald-900/40 text-[10px] sm:text-[11px] font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full inline-flex items-center gap-1">
                      • Active
                    </span>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <div class="flex items-center justify-center gap-1.5">
                      <button
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current" viewBox="0 0 24 24">
                          <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
                        </svg>
                      </button>
                      <button
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current" viewBox="0 0 24 24">
                          <path
                            d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>

                <!-- ROW 6 (DROPPED / TRANSFERRED) -->
                <tr
                  class="bg-rose-50/30 dark:bg-rose-950/10 hover:bg-rose-50/50 dark:hover:bg-rose-950/20 transition-colors opacity-75">
                  <td class="py-3.5 px-3 sm:px-4 font-bold text-slate-400 line-through">
                    719284719201 <span
                      class="bg-slate-100 dark:bg-[#232D26] text-slate-400 px-1.5 py-0.5 rounded text-[10px] font-semibold ml-1 no-underline">ID:
                      72</span>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <div class="flex items-center gap-2.5">
                      <span
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 font-extrabold text-[11px] sm:text-xs flex items-center justify-center shrink-0">EM</span>
                      <span class="font-bold text-slate-500 dark:text-slate-400 line-through">Enrico Magallanes</span>
                    </div>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-500">Male</td>
                  <td class="py-3.5 px-3 sm:px-4 font-mono text-slate-400">09551930219</td>
                  <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-500 leading-snug">Brgy. Salawag, Dasmariñas
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <span
                      class="bg-rose-100/80 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border border-rose-200/80 dark:border-rose-900/40 text-[10px] sm:text-[11px] font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full inline-flex items-center gap-1">
                      • Dropped / Transferred
                    </span>
                  </td>
                  <td class="py-3.5 px-3 sm:px-4">
                    <div class="flex items-center justify-center gap-1.5">
                      <button
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors"
                        title="View Audit History">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-current" viewBox="0 0 24 24">
                          <path
                            d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>

          <!-- PAGINATION FOOTER -->
          <div class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 pt-2">
            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium text-center sm:text-left">
              Showing <strong class="text-slate-800 dark:text-white">1 to 6</strong> of <strong
                class="text-slate-800 dark:text-white">38</strong> enrolled entries
            </p>

            <div class="flex items-center gap-1 sm:gap-1.5">
              <button
                class="px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] text-[11px] sm:text-xs font-bold text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-[#232D26] cursor-not-allowed">
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
                class="px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-[#2D3A31] hover:bg-slate-100 transition-colors">
                Next
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
import heroImage from '../../../../assets/img/desktop-home-banner.png'
import { Head } from '@inertiajs/vue3'
import Sidebart from '@/Components/Sidebart.vue'
import navbartop from '@/Components/navbartop.vue'

import banner from '../../../../assets/img/local/classroom.png'

// Font Scaling State Managed Locally via navbartop event
const fontSizeMode = ref('base')

// IntersectionObserver Directive to Trigger Replayable Scroll Animations
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

/* Entrance Animations */
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