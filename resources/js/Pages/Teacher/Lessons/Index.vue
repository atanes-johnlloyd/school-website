<template>

  <Head title="DepEd LR Resources & Lesson Log Hub - Salawag LMS" />

  <div class="min-h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300">

    <!-- Sticky Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-40 shrink-0 shadow-lg">
      <Sidebart />
    </div>

    <!-- Main Workspace Canvas -->
    <main :class="['flex-1 relative overflow-y-auto min-h-screen flex flex-col', `text-scale-${fontSizeMode}`]">

      <!-- Reusable Top Navigation Component -->
      <navbartop searchPlaceholder="Search lessons, DLL logs, PhET simulations, module decks..."
        @font-size-changed="(size) => fontSizeMode = size" />

      <!-- MAIN PAGE CONTENT -->
      <div class="relative z-10 px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2">

        <!-- UPDATED LESSONS HERO BANNER -->
        <div v-observe
          class="anim-fade-down relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[240px] flex flex-col justify-center p-6 sm:p-8 md:p-10">
          
          <!-- Background Image properly aligned -->
          <img :src="heroImage"
            alt="Lessons Background" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

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

        <!-- CLASSROOM MATERIALS & SECTION FILE MANAGER WORKSPACE -->
        <div v-observe style="animation-delay: 100ms;" class="anim-slide-up space-y-6">

          <!-- TOP FILTER BAR (SECTIONS & FILE TYPE TABS) -->
          <div
            class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">

            <!-- Row 1: Section Selector Header -->
            <div
              class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
              <div class="flex items-center gap-2">
                <span class="text-emerald-800 dark:text-[#86EFAC] text-base">🎓</span>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-white">
                  ASSIGNED ADVISORY & TEACHING SECTIONS
                </h3>
              </div>
              <span class="text-xs font-bold text-slate-500 dark:text-slate-400">
                4 Active Sections • 176 Learners Connected
              </span>
            </div>

            <!-- Row 2: Section Pills Filter -->
            <div class="flex flex-wrap items-center gap-2">
              <button
                class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] px-4 py-1.5 rounded-full text-xs font-black shadow-sm">
                田 All Sections
              </button>

              <button
                class="bg-[#FEF9E7] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-4 py-1.5 rounded-full text-xs font-extrabold border border-amber-200/80 dark:border-[#3F4F43] flex items-center gap-1.5 transition-colors">
                <span class="w-2 h-2 rounded-full bg-emerald-600"></span> 12–STEM Rizal (General Physics 2)
              </button>

              <button
                class="bg-[#FEF9E7] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-4 py-1.5 rounded-full text-xs font-extrabold border border-amber-200/80 dark:border-[#3F4F43] flex items-center gap-1.5 transition-colors">
                <span class="w-2 h-2 rounded-full bg-amber-600"></span> 12–STEM Archimedes (General Physics 2)
              </button>

              <button
                class="bg-[#FEF9E7] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-4 py-1.5 rounded-full text-xs font-extrabold border border-amber-200/80 dark:border-[#3F4F43] flex items-center gap-1.5 transition-colors">
                <span class="w-2 h-2 rounded-full bg-orange-600"></span> 11–TechPro Turing
              </button>
            </div>

            <!-- Row 3: File Category Tabs & Search Bar -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pt-2">
              <div class="flex flex-wrap items-center gap-1.5">
                <button
                  class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] px-3.5 py-1.5 rounded-xl text-xs font-black border border-emerald-200/80">
                  All Files (38)
                </button>
                <button
                  class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-3.5 py-1.5 rounded-xl text-xs font-bold border border-slate-200/80 dark:border-[#3F4F43]">
                  Daily Lesson Logs (7)
                </button>
                <button
                  class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-3.5 py-1.5 rounded-xl text-xs font-bold border border-slate-200/80 dark:border-[#3F4F43]">
                  Lecture Slides (.pptx) (12)
                </button>
                <button
                  class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] px-3.5 py-1.5 rounded-xl text-xs font-bold border border-slate-200/80 dark:border-[#3F4F43]">
                  Lab Manuals (9)
                </button>
              </div>

              <!-- Search Field -->
              <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">🔍</span>
                <input type="text" placeholder="Search by filename, topic, or file tag..."
                  class="w-full bg-[#F9F7F1] dark:bg-[#232D26] pl-9 pr-4 py-2 rounded-xl border border-slate-200 dark:border-[#3F4F43] text-xs font-medium text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#006907]" />
              </div>
            </div>

          </div>

          <!-- MAIN DUAL GRID: LEFT FILE STREAM (8 COLS) vs RIGHT DISPATCH PANELS (4 COLS) -->
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- LEFT COLUMN: FILE CARDS STREAM (8 COLS) -->
            <div class="lg:col-span-8 space-y-4">

              <!-- Breadcrumb & Sort Header Bar -->
              <div class="flex items-center justify-between text-xs font-bold text-slate-600 dark:text-slate-400 px-2">
                <div class="flex items-center gap-1.5">
                  <span>📂</span>
                  <span>General Physics 2</span>
                  <span>/</span>
                  <span>Unit 3: Electromagnetism & Optics</span>
                  <span>/</span>
                  <span class="text-[#005506] dark:text-[#86EFAC] font-black">Week 7 Materials</span>
                </div>

                <div class="flex items-center gap-1">
                  <span>Sort:</span>
                  <button
                    class="bg-white dark:bg-[#2D3A31] px-2.5 py-1 rounded-lg border border-slate-200 dark:border-[#3F4F43] text-slate-800 dark:text-white font-black text-[11px]">
                    Date Modified ↓
                  </button>
                </div>
              </div>

              <!-- ITEM 1: LAB MANUAL (ACTIVE IN 2 SECTIONS) -->
              <div
                class="bg-white dark:bg-[#2D3A31] rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">
                <div class="flex items-start justify-between gap-4">
                  <div class="flex items-start gap-3">
                    <div
                      class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100">
                      📄
                    </div>
                    <div class="space-y-1">
                      <div class="flex items-center gap-2">
                        <h4 class="text-sm font-black text-slate-900 dark:text-white">Lab_Manual_04_Snell_Refraction.pdf
                        </h4>
                        <span
                          class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-0.5 rounded-full border border-emerald-200">
                          Active in 2 Sections
                        </span>
                      </div>

                      <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400">
                        <span class="bg-slate-100 dark:bg-[#232D26] px-2 py-0.5 rounded">PDF • 3.4 MB</span>
                        <span>Updated 2 hrs ago by Engr. R. Bautista</span>
                      </div>
                    </div>
                  </div>

                  <!-- Actions -->
                  <div class="flex items-center gap-1.5 shrink-0">
                    <button
                      class="w-8 h-8 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">🔗</button>
                    <button
                      class="w-8 h-8 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">📱</button>
                    <button
                      class="bg-emerald-50 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] border border-emerald-200 text-xs font-black px-3 py-1.5 rounded-xl flex items-center gap-1">
                      <span>▶</span> Send Link
                    </button>
                    <button
                      class="w-8 h-8 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">⚙️</button>
                    <button
                      class="w-8 h-8 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">⋮</button>
                  </div>
                </div>

                <div
                  class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-[#3F4F43] text-xs font-bold">
                  <div class="flex items-center gap-1 text-[#005506] dark:text-[#86EFAC]">
                    <span>📥</span>
                    <span>78 / 89 Downloads (87%)</span>
                  </div>
                  <span class="text-amber-800 dark:text-amber-400">Rizal & Archimedes</span>
                </div>
              </div>

              <!-- ITEM 2: LECTURE SLIDES (12-STEM RIZAL ONLY) -->
              <div
                class="bg-white dark:bg-[#2D3A31] rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">
                <div class="flex items-start justify-between gap-4">
                  <div class="flex items-start gap-3">
                    <div
                      class="w-10 h-10 rounded-2xl bg-orange-50 dark:bg-orange-950/40 text-orange-600 flex items-center justify-center shrink-0 border border-orange-100">
                      ▶️
                    </div>
                    <div class="space-y-1">
                      <div class="flex items-center gap-2">
                        <h4 class="text-sm font-black text-slate-900 dark:text-white">
                          Lecture_Slides_Alternating_Current.pptx</h4>
                        <span
                          class="bg-amber-100 dark:bg-amber-950 text-amber-900 dark:text-amber-300 text-[10px] font-black px-2.5 py-0.5 rounded-full border border-amber-200">
                          12–STEM Rizal Only
                        </span>
                      </div>

                      <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400">
                        <span class="bg-slate-100 dark:bg-[#232D26] px-2 py-0.5 rounded">PPTX • 18.2 MB</span>
                        <span>Updated yesterday • Includes Embedded Simulation Links</span>
                      </div>
                    </div>
                  </div>

                  <!-- Actions -->
                  <div class="flex items-center gap-1.5 shrink-0">
                    <button
                      class="w-8 h-8 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">🔗</button>
                    <button
                      class="bg-emerald-50 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] border border-emerald-200 text-xs font-black px-3 py-1.5 rounded-xl flex items-center gap-1">
                      <span>▶</span> Send Link
                    </button>
                    <button
                      class="w-8 h-8 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">⚙️</button>
                    <button
                      class="w-8 h-8 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-rose-600">🗑️</button>
                  </div>
                </div>

                <div
                  class="pt-2 border-t border-slate-100 dark:border-[#3F4F43] text-xs font-bold text-slate-600 dark:text-slate-400 flex items-center gap-1">
                  <span>📥</span> 41 / 45 Downloads (91%)
                </div>
              </div>

              <!-- ITEM 3: DOST STARBOOKS (PINNED) -->
              <div
                class="bg-white dark:bg-[#2D3A31] rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">
                <div class="flex items-start justify-between gap-4">
                  <div class="flex items-start gap-3">
                    <div
                      class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-700 flex items-center justify-center shrink-0 border border-amber-100">
                      🌐
                    </div>
                    <div class="space-y-1">
                      <div class="flex items-center gap-2">
                        <h4 class="text-sm font-black text-slate-900 dark:text-white">DOST_STARBOOKS_Virtual_Lab_Portal
                        </h4>
                        <span
                          class="bg-[#F9C20C] text-[#2C3E2D] text-[10px] font-black px-2.5 py-0.5 rounded-full shadow-sm">
                          📌 Pinned • All STEM Sections
                        </span>
                      </div>

                      <span
                        class="bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200 inline-block">
                        Cloud URL • STARBOOKS DepEd Node
                      </span>
                      <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Free zero-rated data access
                        through Smart/Globe School SIMs</p>
                    </div>
                  </div>

                  <!-- Actions -->
                  <div class="flex items-center gap-1.5 shrink-0">
                    <button
                      class="bg-emerald-50 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] border border-emerald-200 text-xs font-black px-3 py-1.5 rounded-xl flex items-center gap-1">
                      <span>↗</span> Open
                    </button>
                    <button
                      class="w-8 h-8 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">🔗</button>
                    <button
                      class="w-8 h-8 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">✏️</button>
                    <button
                      class="w-8 h-8 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">📌</button>
                  </div>
                </div>

                <div
                  class="pt-2 border-t border-slate-100 dark:border-[#3F4F43] text-xs font-black text-[#005506] dark:text-[#86EFAC]">
                  128 Student Clicks this week
                </div>
              </div>

              <!-- ITEM 4: DLL (HIDDEN / TEACHER COPY) -->
              <div
                class="bg-[#F9F7F1]/80 dark:bg-[#232D26]/80 rounded-3xl p-5 border border-slate-200/80 dark:border-[#3F4F43] space-y-4">
                <div class="flex items-start justify-between gap-4">
                  <div class="flex items-start gap-3">
                    <div
                      class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] flex items-center justify-center shrink-0 border border-emerald-100">
                      📄
                    </div>
                    <div class="space-y-1">
                      <div class="flex items-center gap-2">
                        <h4 class="text-sm font-black text-slate-900 dark:text-white">DLL_Week_7_Faraday_Induction.docx
                        </h4>
                        <span
                          class="bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[10px] font-black px-2.5 py-0.5 rounded-full">
                          👁️‍🗨️ Hidden from Students (Teacher Copy)
                        </span>
                      </div>

                      <span
                        class="bg-slate-100 dark:bg-[#2D3A31] text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200 inline-block">
                        DOCX • 620 KB
                      </span>
                      <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                        Aligned to DepEd DO 42, s. 2016 • Verified by Dept Head Mrs. Villanueva
                      </p>
                    </div>
                  </div>

                  <!-- Actions -->
                  <div class="flex items-center gap-1.5 shrink-0">
                    <button
                      class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">📥</button>
                    <button
                      class="bg-white dark:bg-[#2D3A31] text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-[#3F4F43] text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-1">
                      <span>🔄</span> Replace
                    </button>
                    <button
                      class="bg-amber-100 dark:bg-amber-950 text-amber-900 dark:text-amber-300 border border-amber-200 text-xs font-extrabold px-3 py-1.5 rounded-xl flex items-center gap-1">
                      <span>👁️</span> Make Public
                    </button>
                    <button
                      class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">🗑️</button>
                  </div>
                </div>

                <div
                  class="pt-2 border-t border-slate-200/60 dark:border-[#3F4F43] text-xs font-black text-[#005506] dark:text-[#86EFAC]">
                  Division Audit Ready
                </div>
              </div>

              <!-- ITEM 5: PROBLEM SET 3 (SCHEDULED) -->
              <div
                class="bg-white dark:bg-[#2D3A31] rounded-3xl p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">
                <div class="flex items-start justify-between gap-4">
                  <div class="flex items-start gap-3">
                    <div
                      class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-700 flex items-center justify-center shrink-0 border border-amber-100">
                      🕒
                    </div>
                    <div class="space-y-1">
                      <div class="flex items-center gap-2">
                        <h4 class="text-sm font-black text-slate-900 dark:text-white">Problem_Set_3_Circuits.pdf</h4>
                        <span
                          class="bg-amber-100 dark:bg-amber-950 text-amber-900 dark:text-amber-300 text-[10px] font-black px-2.5 py-0.5 rounded-full border border-amber-200">
                          ⏰ Scheduled Release: Mar 21, 5:00 PM
                        </span>
                      </div>

                      <span
                        class="bg-slate-100 dark:bg-[#232D26] text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200 inline-block">
                        PDF • 1.1 MB
                      </span>
                      <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Targets: 12–STEM Rizal & 12–STEM
                        Archimedes</p>
                      <p class="text-[11px] text-slate-400 font-semibold">Unlocks automatically after Quiz 3 submission
                        deadline</p>
                    </div>
                  </div>

                  <!-- Actions -->
                  <div class="flex items-center gap-1.5 shrink-0">
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-[#3F4F43] text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-1">
                      <span>📅</span> Edit Schedule
                    </button>
                    <button
                      class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-black px-3 py-1.5 rounded-xl flex items-center gap-1 shadow-sm">
                      <span>🚀</span> Release Now
                    </button>
                    <button
                      class="w-8 h-8 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300">🗑️</button>
                  </div>
                </div>
              </div>

            </div>

            <!-- RIGHT COLUMN: DISPATCH & UPLOAD CONTROL PANELS (4 COLS) -->
            <div class="lg:col-span-4 space-y-5">

              <!-- PANEL 1: QUICK LINK & SHARE DESK -->
              <div
                class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-[#3F4F43] pb-3">
                  <div class="flex items-center gap-2">
                    <div
                      class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-black">
                      🔗</div>
                    <div>
                      <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Quick Link
                        & Share Desk</h3>
                    </div>
                  </div>
                  <span
                    class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[9px] font-black px-2 py-0.5 rounded-full border border-emerald-200">
                    1-Click Dispatch
                  </span>
                </div>

                <!-- Selected File Selector -->
                <div class="space-y-1">
                  <label class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">SELECTED FILE TO
                    DISTRIBUTE</label>
                  <select
                    class="w-full bg-[#FEF9E7] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-bold py-2.5 px-3 rounded-xl border border-amber-200 dark:border-[#3F4F43] focus:outline-none">
                    <option>Lab_Manual_04_Snell_Refraction.pdf</option>
                    <option>Lecture_Slides_Alternating_Current.pptx</option>
                  </select>
                </div>

                <!-- Direct Portal Link & Copy Button -->
                <div class="space-y-1">
                  <label class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Direct Student
                    Portal Link</label>
                  <div class="flex items-center gap-1">
                    <div
                      class="flex-1 bg-[#F9F7F1] dark:bg-[#232D26] px-3 py-2 rounded-xl border border-slate-200 dark:border-[#3F4F43] text-[11px] font-mono text-slate-600 dark:text-slate-300 truncate">
                      https://portal.salawagshs.edu.ph/r/phy2-snell-lab
                    </div>
                    <button
                      class="bg-[#003803] text-white px-3 py-2 rounded-xl text-xs font-black flex items-center gap-1 shrink-0">
                      📋 Copy
                    </button>
                  </div>
                </div>

                <!-- Classroom QR Mode Box -->
                <div
                  class="bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 rounded-2xl border border-slate-200 dark:border-[#3F4F43] flex items-center justify-between gap-3">
                  <div class="flex items-center gap-2">
                    <span class="text-2xl">📱</span>
                    <div>
                      <h4 class="text-xs font-black text-slate-900 dark:text-white">Classroom QR Mode</h4>
                      <p class="text-[10px] text-slate-400 font-medium">Display on TV for student phone scan</p>
                    </div>
                  </div>
                  <button
                    class="bg-white dark:bg-[#2D3A31] text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-[#3F4F43] px-3 py-1.5 rounded-xl text-xs font-black shrink-0">
                    Enlarge QR
                  </button>
                </div>

                <!-- Dispatch SMS / Notification Button -->
                <div class="space-y-1 pt-1">
                  <button
                    class="w-full bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] py-3 px-4 rounded-2xl text-xs font-black shadow-md flex items-center justify-center gap-2 transition-all">
                    <span>📲</span> Send Link to Student App & Parent SMS
                  </button>
                  <p class="text-[10px] text-slate-400 font-medium text-center">
                    Sends an immediate alert to 89 students and registered emergency guardians.
                  </p>
                </div>

              </div>

              <!-- PANEL 2: UPLOAD & ASSIGN PANEL -->
              <div
                class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-[#3F4F43] pb-3">
                  <div class="flex items-center gap-2">
                    <span class="text-emerald-800 dark:text-[#86EFAC] text-lg">☁️</span>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Upload &
                      Assign</h3>
                  </div>
                  <span class="text-[10px] font-extrabold text-slate-400">Max 50MB</span>
                </div>

                <!-- Drag & Drop Upload Zone -->
                <div
                  class="border-2 border-dashed border-emerald-600/40 bg-[#FEF9E7]/60 dark:bg-[#232D26] rounded-2xl p-6 text-center space-y-2 cursor-pointer hover:border-[#005506] transition-all">
                  <div
                    class="w-10 h-10 rounded-2xl bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] mx-auto flex items-center justify-center text-lg font-black">
                    📤
                  </div>
                  <h4 class="text-xs font-black text-slate-900 dark:text-white">Drop files here or click to browse</h4>
                  <p class="text-[10px] text-slate-400 font-medium">Supports PDF, DOCX, PPTX, MP4, ZIP</p>
                </div>

                <!-- Target Sections Checkboxes -->
                <div class="space-y-2">
                  <label class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">TARGET SECTIONS TO
                    RECEIVE FILE</label>

                  <div
                    class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-2xl border border-slate-200 dark:border-[#3F4F43] space-y-2.5">
                    <label
                      class="flex items-center gap-2 cursor-pointer text-xs font-black text-slate-800 dark:text-slate-200">
                      <input type="checkbox" checked class="rounded text-[#005506] focus:ring-[#005506] w-4 h-4">
                      <span>12–STEM Rizal (45 Learners)</span>
                    </label>

                    <label
                      class="flex items-center gap-2 cursor-pointer text-xs font-black text-slate-800 dark:text-slate-200">
                      <input type="checkbox" checked class="rounded text-[#005506] focus:ring-[#005506] w-4 h-4">
                      <span>12–STEM Archimedes (44 Learners)</span>
                    </label>

                    <label
                      class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-500 dark:text-slate-400">
                      <input type="checkbox" class="rounded text-[#005506] focus:ring-[#005506] w-4 h-4">
                      <span>11–TechPro Turing (42 Learners)</span>
                    </label>

                    <label
                      class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-500 dark:text-slate-400">
                      <input type="checkbox" class="rounded text-[#005506] focus:ring-[#005506] w-4 h-4">
                      <span>12–STEM Newton (45 Learners)</span>
                    </label>
                  </div>
                </div>

                <!-- Initial Visibility Pills -->
                <div class="space-y-2">
                  <label class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">INITIAL
                    VISIBILITY</label>

                  <div class="grid grid-cols-3 gap-1.5">
                    <button
                      class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] py-2 rounded-xl text-[11px] font-black border border-emerald-200 text-center">
                      Public Now
                    </button>
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600 dark:text-slate-300 py-2 rounded-xl text-[11px] font-bold border border-slate-200 dark:border-[#3F4F43] text-center">
                      Faculty Only
                    </button>
                    <button
                      class="bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600 dark:text-slate-300 py-2 rounded-xl text-[11px] font-bold border border-slate-200 dark:border-[#3F4F43] text-center">
                      Scheduled
                    </button>
                  </div>
                </div>

                <!-- Submit Upload Button -->
                <button
                  class="w-full bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] py-3 rounded-2xl text-xs font-black shadow-md flex items-center justify-center gap-2 transition-all">
                  <span>📤</span> Upload & Push to Sections
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
import { ref } from 'vue'
import heroImage from '../../../../assets/img/local/lessons-hero.jpg'
import { Head } from '@inertiajs/vue3'
import Sidebart from '@/Components/Sidebart.vue'
import navbartop from '@/Components/navbartop.vue'

// Font Scaling State Managed Locally via navbartop event
const fontSizeMode = ref('base')

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