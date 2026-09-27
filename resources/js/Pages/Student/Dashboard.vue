<template>
  <Head title="Student Dashboard - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <!-- Main Content Container -->
      <div class="relative z-10 p-6 md:p-8 space-y-6 flex-1 pb-16">
        
        <!-- HERO HEADER BANNER -->
        <div class="w-full bg-[#004d08] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] relative overflow-hidden space-y-4">
          <div class="space-y-1">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none">
              <span class="text-white">STUDENT</span>
              <span class="animated-stroke-text">DASHBOARD</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              "Your journey to knowledge starts with one click."
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs">★</span>
            </div>
          </div>

          <!-- Bottom Profile Row & Quick Stats Cards -->
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-1">
            <div class="lg:col-span-6 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center">
                <img v-if="auth?.user?.avatar" :src="auth.user.avatar" alt="Student Avatar" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full bg-emerald-800 text-white font-bold flex items-center justify-center text-2xl uppercase">
                  {{ auth?.user?.name ? auth.user.name.charAt(0) : 'T' }}
                </div>
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                  {{ activeTerm || 'S.Y. 2026-2027 • 1st Semester' }}
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  Good Day! {{ auth?.user?.name || 'Test Student' }}
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  LRN: {{ auth?.user?.lrn || '01102006700612' }} • {{ auth?.user?.section || 'Grade 12 STEM - Section Rizal' }}
                </p>
              </div>
            </div>

            <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-3 gap-3.5">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-4 px-4 min-h-[105px] flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80 block">General Average</span>
                <div class="text-2xl font-extrabold text-amber-300 leading-none my-1">94.8</div>
                <span class="text-[10px] text-emerald-100/70 block font-medium">With high honors</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-4 px-4 min-h-[105px] flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80 block">Attendance Average</span>
                <div class="text-2xl font-extrabold text-white leading-none my-1">99.2%</div>
                <span class="text-[10px] text-emerald-100/70 block font-medium">0 Unexcused • 48 Days Present</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-4 px-4 min-h-[105px] flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80 block">Urgent Task</span>
                <div class="flex items-center gap-2 my-1">
                  <span class="text-2xl font-extrabold text-white leading-none">{{ urgentCount }}</span>
                  <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">Due Today</span>
                </div>
                <span class="text-[10px] text-emerald-100/70 block font-medium">Prioritize imminent deadlines</span>
              </div>
            </div>
          </div>
        </div>

        <!-- MAIN BENTO GRID LAYOUT (8-COL LEFT / 4-COL RIGHT) -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
          
          <!-- LEFT COLUMN (SPAN 8) -->
          <div class="xl:col-span-8 space-y-6">
            
            <!-- 1. TODAY'S COURSE FLOW -->
            <div class="rounded-3xl bg-[#fbfdf9] border border-slate-200/80 p-6 shadow-sm space-y-5">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-3">
                  <div class="w-2.5 h-7 bg-[#004d08] rounded-full"></div>
                  <div>
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight leading-none">Today's Course Flow</h3>
                    <p class="text-xs font-semibold text-slate-500 mt-1">4 Enrolled Periods • Tuesday Class Block</p>
                  </div>
                </div>
                <span class="bg-[#f0f4ee] text-[#004d08] text-xs font-bold px-3.5 py-1.5 rounded-full border border-[#e1e8dd]">
                  Section 12 - STEM Rizal
                </span>
              </div>

              <!-- Course Periods Grid -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                
                <!-- Period 1 -->
                <div class="relative bg-[#f5f7f2] rounded-2xl p-4 border-2 border-amber-400/90 shadow-sm flex flex-col justify-between space-y-3">
                  <div class="flex items-center justify-between gap-1">
                    <span class="bg-amber-400 text-slate-900 font-extrabold text-[11px] px-2.5 py-1 rounded-full">07:30 - 09:00 AM</span>
                    <div class="flex items-center gap-1">
                      <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                      <span class="text-xs font-extrabold text-[#004d08]">Live Now</span>
                    </div>
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-900 text-base leading-tight">General Physics 2</h4>
                    <p class="text-xs text-slate-600 font-medium mt-1">Engr. Ramon Bautista • Lab 02, Wing A</p>
                  </div>
                  <div class="bg-white rounded-xl p-3 border border-slate-200/80">
                    <p class="text-xs font-semibold text-slate-800 leading-snug">
                      <span class="text-slate-500 font-bold block text-[10px] uppercase tracking-wider mb-0.5">Topic</span>
                      Electromagnetic Induction & Faraday's Flux
                    </p>
                  </div>
                  <button class="w-full bg-[#004d08] hover:bg-[#003805] text-white font-bold text-xs py-2.5 px-3 rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span>Submit Lab Notes (PDF)</span>
                  </button>
                </div>

                <!-- Period 2 -->
                <div class="relative bg-[#f5f7f2] rounded-2xl p-4 border border-slate-200/70 shadow-sm flex flex-col justify-between space-y-3">
                  <div class="flex items-center justify-between gap-1">
                    <span class="text-slate-700 font-extrabold text-[11px]">09:30 - 11:30 AM</span>
                    <span class="text-xs font-bold text-slate-500">In 45 mins</span>
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-900 text-base leading-tight">Basic Calculus</h4>
                    <p class="text-xs text-slate-600 font-medium mt-1">Mrs. Elena Mendoza • Rm 302, Wing B</p>
                  </div>
                  <div class="bg-white rounded-xl p-3 border border-slate-200/80">
                    <p class="text-xs font-semibold text-slate-800 leading-snug">
                      <span class="text-slate-500 font-bold block text-[10px] uppercase tracking-wider mb-0.5">Focus</span>
                      Integration by Partial Fractions
                    </p>
                  </div>
                  <button class="w-full bg-[#eae3d2]/60 hover:bg-[#eae3d2] text-slate-800 font-bold text-xs py-2.5 px-3 rounded-xl flex items-center justify-center gap-2 transition-all">
                    <span>Preview Problem Set</span>
                  </button>
                </div>

                <!-- Period 3 -->
                <div class="relative bg-[#f5f7f2] rounded-2xl p-4 border border-slate-200/70 shadow-sm flex flex-col justify-between space-y-3">
                  <div class="flex items-center justify-between gap-1">
                    <span class="text-slate-700 font-extrabold text-[11px]">01:00 - 03:00 PM</span>
                    <span class="text-xs font-bold text-slate-500">Afternoon</span>
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-900 text-base leading-tight">Practical Research II</h4>
                    <p class="text-xs text-slate-600 font-medium mt-1">Dr. Josefa Garcia • Audio-Visual Rm</p>
                  </div>
                  <div class="bg-white rounded-xl p-3 border border-slate-200/80">
                    <p class="text-xs font-semibold text-slate-800 leading-snug">
                      <span class="text-slate-500 font-bold block text-[10px] uppercase tracking-wider mb-0.5">Milestone</span>
                      Chapter 4 Mock Oral Defense
                    </p>
                  </div>
                  <button class="w-full bg-[#eae3d2]/60 hover:bg-[#eae3d2] text-slate-800 font-bold text-xs py-2.5 px-3 rounded-xl flex items-center justify-center gap-2 transition-all">
                    <span>Open Group Deck</span>
                  </button>
                </div>

              </div>
            </div>

            <!-- 2. ASSESSMENTS & TASKS PIPELINE -->
            <div class="rounded-3xl bg-[#fbfdf9] border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
              
              <!-- Section Header & Filter Controls -->
              <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-2 border-b border-slate-200/60">
                <div class="flex items-center gap-3">
                  <div class="w-2.5 h-7 bg-[#004d08] rounded-full"></div>
                  <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-none">
                      Assessments & Tasks
                    </h3>
                    <p class="text-xs font-semibold text-slate-500 mt-1">
                      Track, submit, and review your academic deliverables
                    </p>
                  </div>
                </div>

                <!-- Status Filter Pills -->
                <div class="flex flex-wrap items-center gap-2">
                  <button 
                    v-for="status in statusFilters" 
                    :key="status.id"
                    @click="activeStatusFilter = status.id"
                    :class="[
                      'px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all',
                      activeStatusFilter === status.id
                        ? 'bg-[#004d08] text-white shadow-sm'
                        : 'bg-[#f0f4ee] text-slate-700 hover:bg-[#e4ede1]'
                    ]"
                  >
                    {{ status.label }}
                  </button>
                </div>
              </div>

              <!-- Structured Task List Rows -->
              <div class="space-y-3">
                
                <div 
                  v-for="task in filteredTaskList" 
                  :key="task.id"
                  :class="[
                    'group bg-[#f5f7f2] rounded-2xl p-4 sm:p-5 border transition-all hover:shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4',
                    task.urgency === 'today' ? 'border-red-300/80 bg-red-50/20' : 'border-slate-200/70 hover:border-slate-300'
                  ]"
                >
                  <!-- Left side: Icon, Type Badge & Info -->
                  <div class="flex items-start gap-4">
                    
                    <!-- Category Icon Badge -->
                    <div 
                      :class="[
                        'w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 border font-bold text-sm shadow-sm',
                        task.type === 'Quiz' ? 'bg-amber-100 text-amber-800 border-amber-200' :
                        task.type === 'Lab' ? 'bg-purple-100 text-purple-800 border-purple-200' :
                        'bg-emerald-100 text-[#004d08] border-emerald-200'
                      ]"
                    >
                      <span v-if="task.type === 'Quiz'">✍️</span>
                      <span v-else-if="task.type === 'Lab'">🧪</span>
                      <span v-else>📄</span>
                    </div>

                    <div class="space-y-1">
                      <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-extrabold uppercase text-slate-800 bg-white px-2.5 py-0.5 rounded-md border border-slate-200">
                          {{ task.type }}
                        </span>
                        <span class="text-xs font-bold text-[#004d08]">
                          {{ task.subject }}
                        </span>
                        <span class="text-xs text-slate-400">• {{ task.teacher }}</span>
                      </div>

                      <h4 class="font-bold text-slate-900 text-base leading-snug group-hover:text-[#004d08] transition-colors">
                        {{ task.title }}
                      </h4>
                      
                      <p class="text-xs text-slate-600 font-medium max-w-2xl">
                        {{ task.description }}
                      </p>
                    </div>

                  </div>

                  <!-- Right side: Deadline, Points & Submissions Trigger -->
                  <div class="flex items-center justify-between md:justify-end gap-3.5 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-slate-200/60">
                    
                    <div class="flex items-center gap-2.5">
                      <div v-if="task.urgency === 'today'" class="inline-flex items-center gap-1 bg-red-600 text-white text-[10px] font-extrabold px-2.5 py-1.5 rounded-md uppercase tracking-wider leading-none">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping shrink-0"></span>
                        <span>DUE TODAY</span>
                      </div>
                      <div v-else-if="task.urgency === 'soon'" class="inline-flex items-center bg-amber-100 text-amber-800 text-[11px] font-bold px-2.5 py-1.5 rounded-md leading-none border border-amber-200/80">
                        In 2 Days
                      </div>

                      <div class="text-left md:text-right leading-tight">
                        <span class="text-xs font-bold text-slate-800 block">
                          {{ task.formattedDue }}
                        </span>
                        <span class="text-[11px] font-medium text-slate-500 block mt-0.5">
                          Weight: {{ task.points }} Points
                        </span>
                      </div>
                    </div>

                    <button 
                      @click="openTaskModal(task)"
                      :class="[
                        'px-4 py-2.5 rounded-xl font-bold text-xs transition-all shadow-sm flex items-center justify-center gap-2 whitespace-nowrap leading-none',
                        task.status === 'graded' || task.status === 'submitted'
                          ? 'bg-slate-200 text-slate-700 hover:bg-slate-300'
                          : task.urgency === 'today'
                          ? 'bg-red-600 hover:bg-red-700 text-white'
                          : 'bg-[#004d08] hover:bg-[#003805] text-white'
                      ]"
                    >
                      <span>{{ task.status === 'graded' ? 'View Grade' : task.status === 'submitted' ? 'View Submission' : 'Submit Work' }}</span>
                      <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                      </svg>
                    </button>

                  </div>

                </div>

              </div>

            </div>

          </div>

          <!-- RIGHT TELEMETRY COLUMN (SPAN 4) -->
          <div class="xl:col-span-4 space-y-6">
            
            <!-- 1. ANNOUNCEMENTS SECTION -->
            <div class="rounded-3xl bg-[#fbfdf9] border border-slate-200/80 p-6 shadow-sm space-y-4">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                  <div class="w-2.5 h-6 bg-[#004d08] rounded-full"></div>
                  <h3 class="text-lg font-bold text-slate-900 tracking-tight">Announcements</h3>
                </div>
                <span class="bg-[#f0f4ee] text-[#004d08] text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-[#e1e8dd]">Recent</span>
              </div>

              <!-- Announcements Items -->
              <div class="space-y-3">
                <div class="relative bg-[#f5f7f2] rounded-2xl p-4 pl-6 border border-slate-200/70 shadow-sm">
                  <div class="absolute left-0 top-0 bottom-0 w-2 bg-[#004d08] rounded-l-2xl"></div>
                  <div class="flex items-center justify-between gap-2 mb-1">
                    <h4 class="font-bold text-slate-900 text-sm">Midterm Examination Schedule Released</h4>
                    <span class="text-[10px] font-bold text-slate-600 bg-white px-2 py-0.5 rounded-full border border-slate-200/80">Oct 24</span>
                  </div>
                  <p class="text-xs text-slate-600 font-medium leading-relaxed">Please review your subject schedules in the calendar tab for exam room assignments.</p>
                </div>

                <div class="relative bg-[#f5f7f2] rounded-2xl p-4 pl-6 border border-slate-200/70 shadow-sm">
                  <div class="absolute left-0 top-0 bottom-0 w-2 bg-[#004d08] rounded-l-2xl"></div>
                  <div class="flex items-center justify-between gap-2 mb-1">
                    <h4 class="font-bold text-slate-900 text-sm">Campus Science Fair Registration</h4>
                    <span class="text-[10px] font-bold text-slate-600 bg-white px-2 py-0.5 rounded-full border border-slate-200/80">Oct 18</span>
                  </div>
                  <p class="text-xs text-slate-600 font-medium leading-relaxed">STEM students are encouraged to submit project proposals to physics advisors.</p>
                </div>
              </div>
            </div>

            <!-- 2. GATE & ATTENDANCE TELEMETRY -->
            <div class="rounded-3xl bg-[#fbfdf9] border border-slate-200/80 p-6 shadow-sm space-y-4">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                  <div class="w-2.5 h-6 bg-[#004d08] rounded-full"></div>
                  <h3 class="text-lg font-bold text-slate-900 tracking-tight">Gate & Attendance Telemetry</h3>
                </div>
                <span class="bg-emerald-100 text-[#004d08] text-[10px] font-extrabold px-2.5 py-0.5 rounded-full">
                  Zero Tardiness
                </span>
              </div>

              <!-- Turnstile Log Card -->
              <div class="bg-[#f5f7f2] rounded-2xl p-3.5 border border-slate-200/80 flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-[#e1e8dd] text-[#004d08] flex items-center justify-center font-bold text-sm">
                    👤
                  </div>
                  <div>
                    <span class="text-[11px] font-bold text-slate-600 block">Gate 1 Main Turnstile Ingress</span>
                    <span class="text-sm font-extrabold text-slate-900">07:14 AM Today</span>
                  </div>
                </div>
                <span class="bg-emerald-100 text-[#004d08] text-xs font-bold px-2.5 py-1 rounded-lg">On Time</span>
              </div>

              <!-- Attendance Grid Header -->
              <div class="flex items-center justify-between pt-1 text-xs">
                <span class="font-bold text-slate-700">February Attendance Grid (18/18 Days)</span>
                <span class="font-extrabold text-[#004d08]">100%</span>
              </div>

              <!-- 4x5 Attendance Grid Blocks -->
              <div class="grid grid-cols-5 gap-2">
                <div v-for="i in 15" :key="i" class="h-6 bg-[#004d08] rounded-md"></div>
                <div class="h-6 bg-amber-400 border-2 border-[#004d08] rounded-md"></div>
                <div v-for="i in 4" :key="'up'+i" class="h-6 bg-[#eae3d2]/60 rounded-md"></div>
              </div>
            </div>

          </div>

        </div>

        <!-- NEW FULL-WIDTH BOTTOM SECTION: SUBJECT QUICK LAUNCH & RESOURCE HUB -->
        <div class="rounded-3xl bg-[#fbfdf9] border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
          
          <!-- Header Bar -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200/60">
            <div class="flex items-center gap-3">
              <div class="w-2.5 h-7 bg-[#004d08] rounded-full"></div>
              <div>
                <h3 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-none">
                  Subject Quick Launch & Resource Hub
                </h3>
                <p class="text-xs font-semibold text-slate-500 mt-1">
                  Instant access to Google Drive folders, syllabi, lecture slides, and teacher consultations
                </p>
              </div>
            </div>

            <span class="bg-[#f0f4ee] text-[#004d08] text-xs font-bold px-3.5 py-1.5 rounded-full border border-[#e1e8dd] self-start sm:self-auto">
              6 Active Enrolled Subjects
            </span>
          </div>

          <!-- Subjects Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            
            <div 
              v-for="subject in subjectHub" 
              :key="subject.id"
              class="bg-[#f5f7f2] rounded-2xl p-5 border border-slate-200/70 shadow-sm flex flex-col justify-between space-y-4 hover:border-slate-300 transition-all hover:shadow-md"
            >
              <!-- Top Row: Subject Code & Consultation Status -->
              <div class="flex items-center justify-between gap-2">
                <span class="bg-white text-slate-800 font-extrabold text-[11px] px-2.5 py-1 rounded-md border border-slate-200/80 uppercase">
                  {{ subject.code }}
                </span>

                <!-- Teacher Availability Pill -->
                <div class="flex items-center gap-1.5 bg-white px-2.5 py-1 rounded-full border border-slate-200/80 text-[10px] font-bold text-slate-700">
                  <span 
                    :class="[
                      'w-2 h-2 rounded-full',
                      subject.teacherOnline ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300'
                    ]"
                  ></span>
                  <span>{{ subject.officeHours }}</span>
                </div>
              </div>

              <!-- Subject Title & Teacher Info -->
              <div class="space-y-1">
                <h4 class="font-bold text-slate-900 text-lg leading-snug">
                  {{ subject.title }}
                </h4>
                <p class="text-xs font-semibold text-slate-600 flex items-center gap-1.5">
                  <span class="w-5 h-5 rounded-full bg-[#004d08] text-white text-[10px] flex items-center justify-center font-bold">
                    {{ subject.teacherInitials }}
                  </span>
                  <span>{{ subject.teacher }}</span>
                  <span class="text-slate-400">• {{ subject.room }}</span>
                </p>
              </div>

              <!-- Module Progress Meter -->
              <div class="space-y-1 bg-white p-3 rounded-xl border border-slate-200/70">
                <div class="flex justify-between text-[11px] font-bold text-slate-700">
                  <span>Course Syllabus Progress</span>
                  <span>{{ subject.progress }}%</span>
                </div>
                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                  <div class="h-full bg-[#004d08] rounded-full" :style="{ width: subject.progress + '%' }"></div>
                </div>
              </div>

              <!-- Quick Links Action Row -->
              <div class="pt-2 border-t border-slate-200/60 grid grid-cols-4 gap-1.5">
                
                <a 
                  :href="subject.driveUrl" 
                  target="_blank"
                  title="Google Drive Folder"
                  class="bg-white hover:bg-emerald-50 text-slate-700 hover:text-[#004d08] p-2 rounded-xl border border-slate-200/80 flex flex-col items-center justify-center gap-1 transition-all group"
                >
                  <span class="text-base">📁</span>
                  <span class="text-[9px] font-extrabold uppercase">Drive</span>
                </a>

                <a 
                  :href="subject.syllabusUrl" 
                  target="_blank"
                  title="Syllabus PDF"
                  class="bg-white hover:bg-emerald-50 text-slate-700 hover:text-[#004d08] p-2 rounded-xl border border-slate-200/80 flex flex-col items-center justify-center gap-1 transition-all group"
                >
                  <span class="text-base">📜</span>
                  <span class="text-[9px] font-extrabold uppercase">Syllabus</span>
                </a>

                <a 
                  :href="subject.slidesUrl" 
                  target="_blank"
                  title="Lecture Slides"
                  class="bg-white hover:bg-emerald-50 text-slate-700 hover:text-[#004d08] p-2 rounded-xl border border-slate-200/80 flex flex-col items-center justify-center gap-1 transition-all group"
                >
                  <span class="text-base">📊</span>
                  <span class="text-[9px] font-extrabold uppercase">Slides</span>
                </a>

                <a 
                  :href="subject.chatUrl" 
                  target="_blank"
                  title="Class Group Chat"
                  class="bg-white hover:bg-emerald-50 text-slate-700 hover:text-[#004d08] p-2 rounded-xl border border-slate-200/80 flex flex-col items-center justify-center gap-1 transition-all group"
                >
                  <span class="text-base">💬</span>
                  <span class="text-[9px] font-extrabold uppercase">Chat</span>
                </a>

              </div>

            </div>

          </div>

        </div>

      </div>

    </main>

    <!-- ACTION / SUBMISSION MODAL DRAWER -->
    <div v-if="selectedTask" class="fixed inset-0 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-6 sm:p-8 space-y-5 border border-slate-100">
        <div class="flex items-center justify-between border-b pb-3 border-slate-100">
          <div>
            <span class="text-xs font-extrabold uppercase text-[#004d08] bg-emerald-50 px-2.5 py-1 rounded-md">
              {{ selectedTask.type }} • {{ selectedTask.subject }}
            </span>
            <h3 class="font-bold text-xl text-slate-900 mt-2">{{ selectedTask.title }}</h3>
          </div>
          <button @click="selectedTask = null" class="text-slate-400 hover:text-slate-700 font-bold text-lg p-1">✕</button>
        </div>

        <div class="space-y-3 text-xs text-slate-600">
          <p><span class="font-bold text-slate-800">Teacher:</span> {{ selectedTask.teacher }}</p>
          <p><span class="font-bold text-slate-800">Deadline:</span> {{ selectedTask.formattedDue }}</p>
          <p><span class="font-bold text-slate-800">Points:</span> {{ selectedTask.points }} pts</p>
          <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80">
            <span class="font-bold text-slate-800 block mb-1">Instructions:</span>
            {{ selectedTask.description }}
          </div>
        </div>

        <!-- Submission Form -->
        <form @submit.prevent="submitTask" class="space-y-4 pt-2">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Text Notes / Submission link</label>
            <textarea v-model="submissionForm.text_content" class="w-full border border-slate-300 rounded-xl p-3 text-xs focus:ring-2 focus:ring-[#004d08] focus:outline-none" rows="3" placeholder="Type notes or paste Google Drive link..."></textarea>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Attach File (PDF, DOCX, ZIP)</label>
            <input type="file" @change="handleFile" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#004d08] file:text-white hover:file:bg-[#003805]" />
          </div>

          <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
            <button type="button" @click="selectedTask = null" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-900">Cancel</button>
            <button type="submit" :disabled="submissionForm.processing" class="px-5 py-2.5 bg-[#004d08] hover:bg-[#003805] text-white font-bold text-xs rounded-xl transition-all disabled:opacity-50">
              {{ submissionForm.processing ? 'Submitting...' : 'Submit Deliverable' }}
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

const props = defineProps({
  auth: Object,
  classes: Object,
  stats: Object,
  upcomingDeadlines: Array,
  recentAnnouncements: Array,
  activeTerm: String,
})

const activeStatusFilter = ref('pending')
const selectedTask = ref(null)

const submissionForm = useForm({
  text_content: '',
  file: null,
})

const statusFilters = [
  { id: 'all', label: 'All Tasks' },
  { id: 'pending', label: 'Pending Action' },
  { id: 'submitted', label: 'Submitted & Graded' }
]

const taskDataset = [
  {
    id: 1,
    title: 'General Mathematics - Midterm Quiz',
    subject: 'General Mathematics',
    teacher: 'Mr. Santos',
    type: 'Quiz',
    points: 50,
    description: 'Solve online quiz covering logarithmic equations, composite functions, and rational expressions.',
    formattedDue: 'Today, 11:59 PM',
    urgency: 'today',
    status: 'pending'
  },
  {
    id: 2,
    title: 'General Physics 1 - Lab Report',
    subject: 'General Physics 1',
    teacher: 'Engr. Bautista',
    type: 'Lab',
    points: 100,
    description: 'Prepare a 3-page kinematic chart and vector resolution report in PDF format.',
    formattedDue: 'Sep 26, 11:59 PM',
    urgency: 'soon',
    status: 'pending'
  },
  {
    id: 3,
    title: 'Oral Communication - Persuasive Speech Video',
    subject: 'Oral Communication',
    teacher: 'Ms. Cruz',
    type: 'Assignment',
    points: 100,
    description: 'Record a 3-minute video presentation advocating for environmental sustainability in senior high.',
    formattedDue: 'Sep 28, 11:59 PM',
    urgency: 'upcoming',
    status: 'pending'
  },
  {
    id: 4,
    title: 'Empowerment Tech - Wireframe Submission',
    subject: 'Empowerment Tech',
    teacher: 'Mr. Reyes',
    type: 'Assignment',
    points: 80,
    description: 'Figma wireframe and preliminary HTML structure submission.',
    formattedDue: 'Sep 20, 11:59 PM',
    urgency: 'completed',
    status: 'graded'
  }
]

// Enrolled Subjects Dataset for Resource Hub
const subjectHub = [
  {
    id: 1,
    code: 'PHYS-102',
    title: 'General Physics 2',
    teacher: 'Engr. Ramon Bautista',
    teacherInitials: 'RB',
    room: 'Lab 02, Wing A',
    progress: 75,
    teacherOnline: true,
    officeHours: 'Online • Available Now',
    driveUrl: '#',
    syllabusUrl: '#',
    slidesUrl: '#',
    chatUrl: '#'
  },
  {
    id: 2,
    code: 'MATH-201',
    title: 'Basic Calculus',
    teacher: 'Mrs. Elena Mendoza',
    teacherInitials: 'EM',
    room: 'Rm 302, Wing B',
    progress: 60,
    teacherOnline: false,
    officeHours: 'Office Hours @ 2 PM',
    driveUrl: '#',
    syllabusUrl: '#',
    slidesUrl: '#',
    chatUrl: '#'
  },
  {
    id: 3,
    code: 'RES-302',
    title: 'Practical Research II',
    teacher: 'Dr. Josefa Garcia',
    teacherInitials: 'JG',
    room: 'Audio-Visual Rm',
    progress: 85,
    teacherOnline: true,
    officeHours: 'Online • Available Now',
    driveUrl: '#',
    syllabusUrl: '#',
    slidesUrl: '#',
    chatUrl: '#'
  },
  {
    id: 4,
    code: 'COMM-101',
    title: 'Oral Communication',
    teacher: 'Ms. Clara Cruz',
    teacherInitials: 'CC',
    room: 'Rm 204, Wing A',
    progress: 50,
    teacherOnline: false,
    officeHours: 'Consultation Tomorrow',
    driveUrl: '#',
    syllabusUrl: '#',
    slidesUrl: '#',
    chatUrl: '#'
  },
  {
    id: 5,
    code: 'SCI-202',
    title: 'Earth & Life Science',
    teacher: 'Mr. Marco Santos',
    teacherInitials: 'MS',
    room: 'Lab 01, Wing C',
    progress: 90,
    teacherOnline: true,
    officeHours: 'Online • Available Now',
    driveUrl: '#',
    syllabusUrl: '#',
    slidesUrl: '#',
    chatUrl: '#'
  },
  {
    id: 6,
    code: 'TECH-105',
    title: 'Empowerment Technologies',
    teacher: 'Mr. Gabriel Reyes',
    teacherInitials: 'GR',
    room: 'Comp Lab 3',
    progress: 40,
    teacherOnline: false,
    officeHours: 'Office Hours @ 4 PM',
    driveUrl: '#',
    syllabusUrl: '#',
    slidesUrl: '#',
    chatUrl: '#'
  }
]

const urgentCount = computed(() => {
  return taskDataset.filter(t => t.urgency === 'today').length
})

const filteredTaskList = computed(() => {
  if (activeStatusFilter.value === 'all') return taskDataset
  if (activeStatusFilter.value === 'pending') return taskDataset.filter(t => t.status === 'pending')
  return taskDataset.filter(t => t.status === 'submitted' || t.status === 'graded')
})

function openTaskModal(task) {
  selectedTask.value = task
}

function handleFile(e) {
  submissionForm.file = e.target.files[0]
}

function submitTask() {
  if (!selectedTask.value) return
  submissionForm.post(route('student.assignments.submit', selectedTask.value.id), {
    forceFormData: true,
    onSuccess: () => {
      selectedTask.value = null
      submissionForm.reset()
    }
  })
}
</script>

<style scoped>

.animated-stroke-text {
  color: transparent;
  -webkit-text-stroke: 1.5px #ffffff;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(0, 85, 6, 0.05);
  border-radius: 8px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(0, 85, 6, 0.25);
  border-radius: 8px;
}
</style>