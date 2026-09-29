<template>

  <Head title="DepEd LR Resources & Lesson Log Hub - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search lessons, DLL logs, PhET simulations, module decks...">

    <!-- MAIN PAGE CONTENT CONTAINER -->
    <div class="relative z-10 px-4 sm:px-6 md:px-10 pb-24 space-y-6 flex-1 mt-4">

      <!-- LESSONS HERO BANNER -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[240px] flex flex-col justify-center p-6 sm:p-8 md:p-10">

        <!-- Background Image properly aligned -->
        <img :src="heroImage" alt="Lessons Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <!-- Animated Green Overlay -->
        <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

        <!-- Content Container -->
        <div class="relative z-10 max-w-3xl space-y-4">

          <!-- Top Badge Pills Container -->
          <div class="flex flex-wrap items-center gap-2.5">
            <div
              class="inline-flex items-center gap-2 bg-[#F9C20C] text-[#2C3E2D] font-black text-xs px-4 py-1.5 rounded-full shadow-sm tracking-wide">
              <span>📁</span> CLASSROOM MATERIALS & SECTION FILE MANAGER
            </div>

            <div
              class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-xs font-bold px-4 py-1.5 rounded-full shadow-sm">
              <span>📅</span> SY 2025–2026 • 2nd Semester
            </div>
          </div>

          <!-- Title & Description -->
          <div class="space-y-2">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-white tracking-tight leading-tight">
              Section Files & Learning Resource Hub
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium max-w-2xl">
              Upload, organize, edit, and distribute handouts, slide decks, lab manuals, and supplementary links
              directly to your enrolled sections.
            </p>
          </div>

        </div>
      </div>

      <!-- WORKSPACE CONTENT BELOW HERO BANNER -->
      <div v-observe style="animation-delay: 100ms;" class="anim-slide-up space-y-4 sm:space-y-6">

        <!-- TOP BAR: SUBJECT SELECTOR & ACTION BUTTONS -->
        <div
          class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-3.5 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4">

          <!-- Subject Dropdown Selector -->
          <div class="relative w-full md:w-80">
            <select
              class="w-full bg-[#FEF9E7] dark:bg-[#232D26] text-slate-900 dark:text-white text-xs sm:text-sm font-black px-4 py-3 rounded-xl sm:rounded-2xl border border-amber-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer">
              <option>General Physics 1 (Grade 12 STEM)</option>
              <option>General Physics 2 (Grade 12 STEM)</option>
              <option>General Chemistry 1 (Grade 11 STEM)</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
              ▼
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <button
              class="bg-[#FEF9E7] dark:bg-amber-950/40 text-amber-900 dark:text-amber-300 hover:bg-amber-100 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl text-xs font-black border border-amber-200 shadow-sm flex items-center gap-1.5 sm:gap-2 transition-all">
              <span>🔗</span> Add Google Drive Link
            </button>
            <button
              class="bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl text-xs font-black shadow-sm flex items-center gap-1.5 sm:gap-2 transition-all active:scale-95">
              <span>☁️</span> Upload Learning Material
            </button>
          </div>
        </div>

        <!-- UNIT FILTER TABS BAR -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 no-scrollbar">
          <button
            class="bg-white dark:bg-[#2D3A31] text-slate-700 dark:text-slate-300 hover:bg-slate-100 px-3.5 sm:px-4 py-2 rounded-xl sm:rounded-2xl text-xs font-black border border-slate-200 dark:border-[#3F4F43] shrink-0 transition-colors">
            All Units (6)
          </button>
          <button
            class="bg-white dark:bg-[#2D3A31] text-slate-700 dark:text-slate-300 hover:bg-slate-100 px-3.5 sm:px-4 py-2 rounded-xl sm:rounded-2xl text-xs font-black border border-slate-200 dark:border-[#3F4F43] shrink-0 transition-colors">
            Unit 1: Kinematics
          </button>
          <button
            class="bg-white dark:bg-[#2D3A31] text-slate-700 dark:text-slate-300 hover:bg-slate-100 px-3.5 sm:px-4 py-2 rounded-xl sm:rounded-2xl text-xs font-black border border-slate-200 dark:border-[#3F4F43] shrink-0 transition-colors">
            Unit 2: Dynamics & Energy
          </button>
          <button
            class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] px-3.5 sm:px-4 py-2 rounded-xl sm:rounded-2xl text-xs font-black shadow-sm shrink-0 flex items-center gap-1.5 sm:gap-2">
            <span class="w-2 h-2 rounded-full bg-amber-300 animate-pulse"></span> Unit 3: Rotational Motion (Active)
          </button>
          <button
            class="bg-white dark:bg-[#2D3A31] text-slate-700 dark:text-slate-300 hover:bg-slate-100 px-3.5 sm:px-4 py-2 rounded-xl sm:rounded-2xl text-xs font-black border border-slate-200 dark:border-[#3F4F43] shrink-0 transition-colors">
            Unit 4: Fluids & Waves
          </button>
        </div>

        <!-- MAIN DUAL GRID: LEFT UNITS & RESOURCE STREAMS (8 COLS) vs RIGHT PANELS (4 COLS) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-start">

          <!-- LEFT COLUMN: UNITS & RESOURCES (8 COLS) -->
          <div class="lg:col-span-8 space-y-5 sm:space-y-6 w-full min-w-0">

            <!-- UNIT 3 CONTAINER CARD -->
            <div
              class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">

              <!-- Unit Header -->
              <div
                class="flex items-start justify-between gap-3 pb-3 sm:pb-4 border-b border-slate-100 dark:border-[#3F4F43]">
                <div class="flex items-start gap-3 min-w-0">
                  <div
                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center font-black text-xs sm:text-sm shrink-0 border border-emerald-100">
                    03
                  </div>
                  <div class="space-y-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                      <h3 class="text-sm sm:text-base font-black text-slate-900 dark:text-white truncate">
                        Unit 3: Rotational Dynamics & Moment of Inertia
                      </h3>
                      <span
                        class="bg-[#FEF9E7] dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] font-black px-2.5 py-0.5 rounded-full border border-amber-200 shrink-0">
                        CURRENT SYLLABUS UNIT
                      </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                      Competency: STEM_GP12RD-IIa-1 to STEM_GP12RED-IIa-6
                    </p>
                  </div>
                </div>

                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-500 shrink-0">
                  <span>4 Resources Shared</span>
                  <button class="p-1 hover:bg-slate-100 dark:hover:bg-[#232D26] rounded-lg">▲</button>
                </div>
              </div>

              <!-- RESOURCE ITEM 1 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-start gap-3 min-w-0">
                  <div
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100 font-bold">
                    ▶️
                  </div>
                  <div class="space-y-0.5 min-w-0">
                    <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white truncate">
                      Lesson Slide Deck 04: Torque & Rotational Equilib
                    </h4>
                    <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold text-slate-500">
                      <span class="text-emerald-700 dark:text-emerald-400 font-black">Published</span>
                      <span>•</span>
                      <span>🔗 Google Drive Link</span>
                      <span>•</span>
                      <span>14.2 MB</span>
                      <span>•</span>
                      <span>Shared to 4 Sections</span>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                  <button
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">👁️</button>
                  <button
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">🔗</button>
                  <button
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">⋮</button>
                </div>
              </div>

              <!-- RESOURCE ITEM 2 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-start gap-3 min-w-0">
                  <div
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-emerald-50 text-[#005506] flex items-center justify-center shrink-0 border border-emerald-100 font-bold">
                    ⚖️
                  </div>
                  <div class="space-y-0.5 min-w-0">
                    <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white truncate">
                      Laboratory Manual 05: Total Internal Reflection & ...
                    </h4>
                    <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold text-slate-500">
                      <span class="text-[#005506] dark:text-[#86EFAC] font-black">PDF Download</span>
                      <span>•</span>
                      <span>3.8 MB</span>
                      <span>•</span>
                      <span class="text-emerald-700 dark:text-emerald-400 font-extrabold">✓ 38/38 Students
                        Downloaded</span>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                  <button
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">📥</button>
                  <button
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">🔗</button>
                  <button
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">⋮</button>
                </div>
              </div>

              <!-- RESOURCE ITEM 3 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-start gap-3 min-w-0">
                  <div
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-100 font-bold">
                    🔒
                  </div>
                  <div class="space-y-0.5 min-w-0">
                    <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white truncate">
                      Problem Set Solution Guide 03: Equilibrium
                    </h4>
                    <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold text-slate-500">
                      <span
                        class="bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 px-2 py-0.5 rounded font-black">Restricted</span>
                      <span class="text-amber-700 dark:text-amber-400 font-extrabold">Releases Friday 5:00 PM
                        (Post-Lecture)</span>
                      <span>•</span>
                      <span>DOCX Document</span>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                  <button
                    class="bg-white dark:bg-[#2D3A31] text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-[#3F4F43] text-xs font-bold px-3 py-2 rounded-xl">
                    Modify Schedule
                  </button>
                  <button
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">⋮</button>
                </div>
              </div>

              <!-- RESOURCE ITEM 4 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-start gap-3 min-w-0">
                  <div
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100 font-bold">
                    🌐
                  </div>
                  <div class="space-y-0.5 min-w-0">
                    <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white truncate">
                      Interactive PhET Simulation Link: Torque & ...
                    </h4>
                    <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold text-slate-500">
                      <span>External Link</span>
                      <span class="bg-emerald-100 text-[#005506] px-1.5 py-0.5 rounded font-black text-[9px]">Iframe
                        Preview Ready</span>
                      <span>University of Colorado Boulder Interactive Suite</span>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                  <button
                    class="bg-white dark:bg-[#2D3A31] text-[#005506] dark:text-[#86EFAC] border border-emerald-200 text-xs font-black px-3.5 py-2 rounded-xl flex items-center gap-1 shadow-sm">
                    Open Sandbox ↗
                  </button>
                  <button
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">⋮</button>
                </div>
              </div>

            </div>

            <!-- UNIT 2 CONTAINER CARD -->
            <div
              class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">

              <div
                class="flex items-start justify-between gap-3 pb-3 sm:pb-4 border-b border-slate-100 dark:border-[#3F4F43]">
                <div class="flex items-start gap-3 min-w-0">
                  <div
                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-black text-xs sm:text-sm shrink-0 border border-slate-200">
                    02
                  </div>
                  <div class="space-y-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                      <h3 class="text-sm sm:text-base font-black text-slate-900 dark:text-white truncate">
                        Unit 2: Work, Energy, and Conservation Laws
                      </h3>
                      <span
                        class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-black px-2.5 py-0.5 rounded-full shrink-0">
                        COMPLETED TOPIC
                      </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                      Competency: STEM_GP12WE-Ib-14 to STEM_GP12WE-Id-26
                    </p>
                  </div>
                </div>

                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-500 shrink-0">
                  <span>2 Resources Shared</span>
                  <button class="p-1 hover:bg-slate-100 dark:hover:bg-[#232D26] rounded-lg">▲</button>
                </div>
              </div>

              <!-- RESOURCE ITEM 1 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-start gap-3 min-w-0">
                  <div
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-emerald-50 text-[#005506] flex items-center justify-center shrink-0 border border-emerald-100 font-bold">
                    📄
                  </div>
                  <div class="space-y-0.5 min-w-0">
                    <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white truncate">
                      Complete Unit Summary & Practice Drills.pdf
                    </h4>
                    <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold text-slate-500">
                      <span>4.1 MB</span>
                      <span>•</span>
                      <span class="text-emerald-700 dark:text-emerald-400 font-bold">126 Student Downloads</span>
                      <span>•</span>
                      <span
                        class="bg-slate-200 dark:bg-slate-700 px-2 py-0.5 rounded text-slate-600 dark:text-slate-300">Archived
                        Unit</span>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                  <button
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">👁️</button>
                  <button
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">⋮</button>
                </div>
              </div>

              <!-- RESOURCE ITEM 2 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-start gap-3 min-w-0">
                  <div
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-100 font-bold">
                    ▶️
                  </div>
                  <div class="space-y-0.5 min-w-0">
                    <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white truncate">
                      Video Lecture Link: Conservative vs Non-Conservative
                    </h4>
                    <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold text-slate-500">
                      <span>Google Drive Stream Link</span>
                      <span>•</span>
                      <span>1080p MP4 (Direct Play)</span>
                    </div>
                  </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                  <button
                    class="bg-white dark:bg-[#2D3A31] text-[#005506] dark:text-[#86EFAC] border border-emerald-200 text-xs font-black px-3.5 py-2 rounded-xl shadow-sm">
                    Stream ↗
                  </button>
                  <button
                    class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">⋮</button>
                </div>
              </div>

            </div>

          </div>

          <!-- RIGHT COLUMN: PACING MATRIX & GOOGLE DRIVE FOLDERS (4 COLS) -->
          <div class="lg:col-span-4 space-y-5 sm:space-y-6 w-full min-w-0">

            <!-- PANEL 1: SECTION PACING MATRIX -->
            <div
              class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">

              <div class="flex items-center justify-between border-b border-slate-100 dark:border-[#3F4F43] pb-3">
                <div class="flex items-center gap-2">
                  <span class="text-emerald-800 dark:text-[#86EFAC] text-base">📊</span>
                  <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Section
                    Pacing Matrix</h3>
                </div>
                <span
                  class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-1 rounded-full border border-emerald-200">
                  Week 8
                </span>
              </div>

              <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                Live syllabus progress adjusted for holiday suspensions and weather disruptions.
              </p>

              <!-- Section Card 1 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2">
                <div class="flex items-center justify-between text-xs font-black text-slate-900 dark:text-white">
                  <span>Grade 12 STEM–2 Curie</span>
                  <span
                    class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[9px] px-2 py-0.5 rounded-full">On-Track</span>
                </div>
                <div class="w-full h-2 bg-slate-200 dark:bg-[#3F4F43] rounded-full overflow-hidden">
                  <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full w-[85%]"></div>
                </div>
                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                  <span>Lesson 3.4 completed</span>
                  <span class="text-[#005506] dark:text-[#86EFAC]">Next: Gyroscopic Precession</span>
                </div>
              </div>

              <!-- Section Card 2 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2">
                <div class="flex items-center justify-between text-xs font-black text-slate-900 dark:text-white">
                  <span>Grade 12 STEM–1 Newton</span>
                  <span
                    class="bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 text-[9px] px-2 py-0.5 rounded-full">Lagging
                    -1 Day</span>
                </div>
                <div class="w-full h-2 bg-slate-200 dark:bg-[#3F4F43] rounded-full overflow-hidden">
                  <div class="h-full bg-amber-500 rounded-full w-[65%]"></div>
                </div>
                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                  <span>Lesson 3.2 completed</span>
                  <span class="text-amber-700 dark:text-amber-400 font-bold">Needs 1 catch-up session</span>
                </div>
              </div>

              <!-- Section Card 3 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2">
                <div class="flex items-center justify-between text-xs font-black text-slate-900 dark:text-white">
                  <span>Grade 11 STEM–1 Faraday</span>
                  <span
                    class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[9px] px-2 py-0.5 rounded-full">On-Track</span>
                </div>
                <div class="w-full h-2 bg-slate-200 dark:bg-[#3F4F43] rounded-full overflow-hidden">
                  <div class="h-full bg-[#005506] dark:bg-[#86EFAC] rounded-full w-[90%]"></div>
                </div>
                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                  <span>Lesson 4.1 completed</span>
                  <span>Pacing as scheduled</span>
                </div>
              </div>

              <!-- Section Card 4 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2">
                <div class="flex items-center justify-between text-xs font-black text-slate-900 dark:text-white">
                  <span>Grade 12 STEM–3 Euler</span>
                  <span
                    class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[9px] px-2 py-0.5 rounded-full">Pacing
                    Review</span>
                </div>
                <div class="w-full h-2 bg-slate-200 dark:bg-[#3F4F43] rounded-full overflow-hidden">
                  <div class="h-full bg-slate-500 rounded-full w-[50%]"></div>
                </div>
                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                  <span>Lesson 3.1 completed</span>
                  <span>Review scheduled on Thu</span>
                </div>
              </div>

              <button
                class="w-full bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-slate-800 dark:text-slate-200 font-bold text-xs py-3 rounded-2xl border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center gap-2 transition-colors">
                <span>🔄</span> Re-align Section Pacing Schedule
              </button>
            </div>

            <!-- PANEL 2: LINKED GOOGLE DRIVE FOLDERS -->
            <div
              class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">

              <div class="flex items-center justify-between border-b border-slate-100 dark:border-[#3F4F43] pb-3">
                <div class="flex items-center gap-2">
                  <span class="text-amber-600 text-base">📁</span>
                  <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Linked Google
                    Drive Folders</h3>
                </div>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              </div>

              <!-- Folder 1 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                  <span class="text-xl shrink-0">📂</span>
                  <div class="min-w-0">
                    <h4 class="text-xs font-black text-slate-900 dark:text-white truncate">STEM 12 – General Physics 2
                      Hub</h4>
                    <p class="text-[10px] text-slate-400 font-medium truncate">SSHS Domain Authorized Student
                      Repository</p>
                  </div>
                </div>
                <button
                  class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300 shrink-0">↗</button>
              </div>

              <!-- Folder 2 -->
              <div
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                  <span class="text-xl shrink-0">📂</span>
                  <div class="min-w-0">
                    <h4 class="text-xs font-black text-slate-900 dark:text-white truncate">DepEd LR Portal References
                      Node</h4>
                    <p class="text-[10px] text-slate-400 font-medium truncate">DepEd Official Self-Learning Modules
                    </p>
                  </div>
                </div>
                <button
                  class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300 shrink-0">↗</button>
              </div>

              <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 pt-1">
                <span>Drive API Connected • Last Synced 15m ago</span>
                <span class="text-emerald-600 dark:text-emerald-400 font-black">Active</span>
              </div>
            </div>

          </div>

        </div>

        <!-- BOTTOM STICKY ACTION BAR -->
        <div
          class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col sm:flex-row items-center justify-between gap-4">

          <div class="flex items-center gap-3 min-w-0">
            <div
              class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100">
              📊
            </div>
            <div class="min-w-0">
              <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white truncate">Batch Export File
                Links (.pdf / .csv) & Portal Utility</h4>
              <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium truncate">Export all active unit
                resource records and download audit logs.</p>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto shrink-0">
            <button
              class="flex-1 sm:flex-none bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 text-slate-700 dark:text-slate-200 text-xs font-black px-4 py-3 rounded-2xl border border-slate-200 dark:border-[#3F4F43] transition-colors">
              📥 Batch Export File Links
            </button>
            <button
              class="flex-1 sm:flex-none bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] text-xs font-black px-5 py-3 rounded-2xl shadow-sm transition-all active:scale-95">
              🚀 Publish Selected Files to Student Portals
            </button>
          </div>
        </div>

      </div>

    </div>

  </AuthenticatedLayout>

</template>

<script setup>
import { ref } from 'vue'
import heroImage from '../../../../assets/img/local/lessons-hero.jpg'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Mock Resource List Data
const resourceList = ref([
  {
    id: 1,
    subject: 'STEM • Gen Physics 2',
    type: 'Daily Lesson Log (DLL)',
    title: 'Module 4: Electromagnetic Induction & Faraday\'s Law',
    description: 'Complete DepEd DO 42 compliant lesson log with interactive PhET simulation embeds.'
  },
  {
    id: 2,
    subject: 'STEM • Gen Physics 2',
    type: 'Presentation Deck',
    title: 'DC Circuits & Kirchhoff\'s Rules Slide Set',
    description: 'High-contrast slides covering multi-loop network derivation and resistor tolerance calculation.'
  },
  {
    id: 3,
    subject: 'TVL-ICT • Robotics',
    type: 'Lab Assembly Guide',
    title: 'Arduino Sensor Array Breadboard Schematic',
    description: 'Hardware wiring diagrams and code template for ultrasonic rangefinder integration.'
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
  line-title: 1.75rem !important;
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