<template>

  <Head title="Teacher Dashboard - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search learners (LRN), strand records, advisories..">

    <!-- DASHBOARD CONTENT -->
    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

      <!-- HERO BANNER -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[260px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <img :src="heroImage" alt="Institutional Faculty Portal Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 max-w-3xl space-y-2.5 sm:space-y-4">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <div
              class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide">
              <span>✨</span> INSTITUTIONAL FACULTY PORTAL
            </div>
            <div
              class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              {{ active_term || 'Term Cycle Active' }}
            </div>
          </div>

          <h2 class="text-xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
            Welcome back, {{ teacherName }}!
          </h2>

          <p class="text-white/90 text-[11px] sm:text-sm md:text-base leading-relaxed font-medium">
            <span v-if="teacherProfile.employee_no">Faculty ID: <span class="font-black text-[#F9C20C]">{{ teacherProfile.employee_no }}</span> • </span>
            <span v-if="teacherProfile.department">{{ teacherProfile.department }} • </span>
            {{ today || active_term || '' }}
          </p>
        </div>
      </div>

      <!-- 4-CARD METRICS GRID -->
      <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">

        <!-- 1. TEACHING ROSTER -->
        <div v-observe style="animation-delay: 100ms;"
          class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">
          <div class="space-y-2 sm:space-y-3">
            <div class="flex items-start justify-between">
              <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                TEACHING ROSTER
              </span>
              <div
                class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-[#006907] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
                <Icon icon="academic-cap" size="md" />
              </div>
            </div>

            <div class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">{{ stats.classes || 0 }}</div>

            <div class="flex flex-wrap gap-1.5 pt-1">
              <span v-for="badge in gradeLevelBadges" :key="badge"
                class="bg-[#F2EFE9] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-full border border-slate-200/80 dark:border-[#3F4F43]">
                {{ badge }}
              </span>
              <span v-if="!gradeLevelBadges.length" class="text-[10px] text-slate-400">No classes assigned</span>
            </div>
          </div>

          <div class="pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[11px] sm:text-xs">
            <span class="text-slate-500 dark:text-slate-400 font-medium">Total Students<br>
              <strong class="text-slate-800 dark:text-slate-200 font-bold">{{ stats.students || 0 }} learners</strong>
            </span>
            <Link :href="route('teacher.classes.index')" class="text-[#006907] dark:text-[#86EFAC] font-black text-right hover:underline">
              View Classes
            </Link>
          </div>
        </div>

        <!-- 2. ENROLLED SCHOLARS -->
        <div v-observe style="animation-delay: 200ms;"
          class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">
          <div class="space-y-2 sm:space-y-3">
            <div class="flex items-start justify-between">
              <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                ENROLLED SCHOLARS
              </span>
              <div
                class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
                <Icon icon="users" size="md" />
              </div>
            </div>

            <div class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">{{ stats.students || 0 }}</div>

            <div class="space-y-1.5 pt-1">
              <div class="flex items-center justify-between text-xs">
                <span class="text-[#006907] dark:text-[#86EFAC] font-black flex items-center gap-1 text-[11px] sm:text-xs">
                  <Icon icon="users" size="xs" /> {{ stats.students || 0 }} learners
                </span>
              </div>
              <div class="w-full h-1.5 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
                <div class="h-full bg-[#006907] dark:bg-[#86EFAC] rounded-full w-full"></div>
              </div>
            </div>
          </div>

          <div class="pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[11px] sm:text-xs">
            <span class="text-slate-500 dark:text-slate-400 font-medium">Verified Enrollee Records</span>
            <span class="text-[#006907] dark:text-[#86EFAC] font-black">Sync: Live</span>
          </div>
        </div>

        <!-- 3. NEEDS GRADING QUEUE -->
        <div v-observe style="animation-delay: 300ms;"
          class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">
          <div class="space-y-2 sm:space-y-3">
            <div class="flex items-start justify-between">
              <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                NEEDS GRADING QUEUE
              </span>
              <div
                class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40">
                <Icon icon="alert-triangle" size="md" />
              </div>
            </div>

            <div class="flex items-baseline gap-2">
              <span class="text-3xl sm:text-4xl font-black text-rose-600 dark:text-rose-400">{{ stats.pending_grading || 0 }}</span>
              <span class="bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 text-[10px] font-black px-2.5 py-0.5 rounded-full border border-rose-200/80">
                Priority
              </span>
            </div>

            <div class="flex items-center gap-1.5 text-[11px] sm:text-xs text-slate-600 dark:text-slate-300 font-medium pt-1">
              <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
              <span>{{ needs_grading.length }} {{ needs_grading.length === 1 ? 'submission' : 'submissions' }} awaiting</span>
            </div>
          </div>

          <div class="pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[11px] sm:text-xs">
            <Link :href="route('teacher.tasks.index')" class="text-orange-700 dark:text-orange-400 font-extrabold hover:underline">
              Open grading queue
            </Link>
            <Icon icon="arrow-right" size="xs" class="text-slate-400" />
          </div>
        </div>

        <!-- 4. ACTIVE PUBLISHED TASKS -->
        <div v-observe style="animation-delay: 400ms;"
          class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4">
          <div class="space-y-2 sm:space-y-3">
            <div class="flex items-start justify-between">
              <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                ACTIVE PUBLISHED TASKS
              </span>
              <div
                class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
                <Icon icon="clipboard-check" size="md" />
              </div>
            </div>

            <div class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">{{ stats.active_tasks || 0 }}</div>

            <div class="flex items-center justify-between pt-1">
              <span class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-300 font-medium">Assignments & Quizzes</span>
              <span class="bg-emerald-100 dark:bg-emerald-950 text-[#006907] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-0.5 rounded-full border border-emerald-200">Active</span>
            </div>
          </div>

          <div class="pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[11px] sm:text-xs">
            <span class="text-slate-500 dark:text-slate-400 font-medium">{{ today || '' }}</span>
            <Link :href="route('teacher.tasks.index')" class="text-[#006907] dark:text-[#86EFAC] font-black hover:underline">View All</Link>
          </div>
        </div>

      </div>

            <!-- SECTION 1: ASSIGNED CLASSES -->
      <div v-observe style="animation-delay: 300ms;" class="anim-slide-up space-y-4 sm:space-y-5">
        <div
          class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <div class="space-y-0.5">
            <h3 class="text-sm sm:text-base font-extrabold text-slate-800 dark:text-white">Assigned Classes & Academic Sections</h3>
            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
              {{ classes.length }} {{ classes.length === 1 ? 'class' : 'classes' }} • Official instructional clusters
            </p>
          </div>
          <div class="flex items-center gap-2 shrink-0 self-start sm:self-center">
            <span class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[11px] sm:text-xs font-bold px-3 sm:px-3.5 py-1 sm:py-1.5 rounded-full border border-emerald-200/60 dark:border-emerald-900/40">
              {{ active_term || 'Current Term' }}
            </span>
            <Link v-if="classes.length > 3" :href="route('teacher.classes.index')"
              class="text-[#005506] dark:text-[#86EFAC] text-[11px] sm:text-xs font-black hover:underline whitespace-nowrap">
              View all →
            </Link>
          </div>
        </div>

        <div v-if="classes.length" class="overflow-x-auto pb-3 -mx-3 sm:-mx-6 md:mx-0 px-3 sm:px-6 md:px-0">
          <div class="flex gap-4 sm:gap-6 snap-x snap-mandatory min-w-max">
            <div v-for="klass in classes" :key="klass.id"
              class="snap-start shrink-0 w-[300px] sm:w-[340px] md:w-[360px] bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-5">
              <div class="space-y-3 sm:space-y-4">
                <div class="flex items-center justify-between gap-2">
                  <span class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-extrabold px-2 sm:px-2.5 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-900/40">
                    {{ klass.subject_code || 'SUBJECT' }}<span v-if="klass.grade_level"> • G{{ klass.grade_level }}</span>
                  </span>
                  <span class="bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 text-[10px] font-bold px-2 sm:px-2.5 py-0.5 rounded-md">
                    {{ klass.students_count }} students
                  </span>
                </div>

                <div class="space-y-1">
                  <h4 class="text-sm sm:text-base font-extrabold text-slate-800 dark:text-white">{{ klass.subject || 'Untitled Subject' }}</h4>
                  <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">Section: {{ klass.section || '—' }}</p>
                </div>

                <div class="bg-[#FEF9E7]/80 dark:bg-[#232D26] p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border border-amber-200/80 dark:border-[#3F4F43] space-y-2">
                  <div class="flex items-center justify-between text-[9px] font-extrabold tracking-wider text-slate-400 uppercase">
                    <span>ASSESSMENT WEIGHTS</span>
                    <span>FORMULA</span>
                  </div>
                  <div class="grid grid-cols-3 gap-1.5 sm:gap-2 text-center">
                    <div class="bg-white dark:bg-[#2D3A31] p-1 sm:p-1.5 rounded-lg sm:rounded-xl border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 block">Written</span>
                      <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white">{{ klass.weight_written_work }}%</span>
                    </div>
                    <div class="bg-white dark:bg-[#2D3A31] p-1 sm:p-1.5 rounded-lg sm:rounded-xl border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 block">Perf. Task</span>
                      <span class="text-[11px] sm:text-xs font-black text-[#005506] dark:text-[#86EFAC]">{{ klass.weight_performance_task }}%</span>
                    </div>
                    <div class="bg-white dark:bg-[#2D3A31] p-1 sm:p-1.5 rounded-lg sm:rounded-xl border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 block">Quarterly</span>
                      <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white">{{ klass.weight_quarterly_exam }}%</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="flex items-center gap-1.5 sm:gap-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                <Link :href="route('teacher.classes.show', klass.id)"
                  class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-300 text-[11px] sm:text-xs font-bold px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1 sm:gap-1.5 flex-1 transition-all">
                  <Icon icon="users" size="xs" />
                  Roster ({{ klass.students_count }})
                </Link>
                <Link :href="route('teacher.classes.gradebook.show', klass.id)"
                  class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] hover:bg-emerald-100 dark:hover:bg-emerald-950 text-[11px] sm:text-xs font-extrabold px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border border-emerald-200/60 dark:border-emerald-900/40 flex items-center justify-center gap-1 sm:gap-1.5 flex-1 transition-all">
                  <Icon icon="chart-bar" size="xs" />
                  Gradebook
                </Link>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-12 shadow-sm border border-dashed border-slate-300 dark:border-[#3F4F43] text-center space-y-3">
          <Icon icon="book-open" size="xl" class="text-slate-400 mx-auto" />
          <p class="text-sm font-bold text-slate-800 dark:text-white">No classes assigned yet</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Classes you teach this term will appear here.</p>
        </div>
      </div>

      <!-- SECTION 2: ANNOUNCEMENTS + TODAY'S SCHEDULE -->
      <div v-observe style="animation-delay: 400ms;"
        class="anim-slide-up grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 items-start">

        <!-- LEFT COLUMN: RECENT CLASS ANNOUNCEMENTS -->
        <div class="lg:col-span-7 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4 sm:space-y-5">
          <div class="flex items-center justify-between border-b border-slate-100 dark:border-[#3F4F43] pb-3 sm:pb-4">
            <div class="flex items-center gap-2">
              <Icon icon="megaphone" size="md" class="text-[#005506] dark:text-[#86EFAC]" />
              <h3 class="text-sm sm:text-base font-extrabold text-slate-800 dark:text-white">Recent Class Announcements</h3>
            </div>
            <span class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] sm:text-xs font-bold px-2.5 sm:px-3.5 py-0.5 sm:py-1 rounded-full border border-emerald-200/60 dark:border-emerald-900/40">
              {{ recent_announcements.length }} recent
            </span>
          </div>

          <div v-if="recent_announcements.length" class="space-y-3">
            <div v-for="a in recent_announcements" :key="a.id"
              :class="[
                'p-3.5 sm:p-5 rounded-xl sm:rounded-2xl border space-y-2.5 sm:space-y-3',
                a.is_pinned
                  ? 'bg-[#FEF9E7]/80 dark:bg-[#232D26] border-amber-200/80 dark:border-[#3F4F43]'
                  : 'bg-[#F9F7F1] dark:bg-[#232D26] border-slate-200/80 dark:border-[#3F4F43]'
              ]">
              <div class="flex flex-wrap items-center justify-between gap-2">
                <span :class="[
                  'text-[9px] sm:text-[10px] font-black px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-md',
                  a.is_pinned
                    ? 'bg-[#F9C20C] text-[#2C3E2D] shadow-sm'
                    : 'bg-slate-200/80 dark:bg-slate-700 text-slate-700 dark:text-slate-300'
                ]">
                  {{ a.is_pinned ? 'PINNED' : (a.subject || 'CLASS') }}
                </span>
                <span class="text-[10px] sm:text-[11px] font-bold text-slate-400">{{ a.published_human }}</span>
              </div>

              <div class="space-y-1">
                <h4 class="text-xs sm:text-sm font-extrabold text-slate-800 dark:text-white leading-snug">{{ a.title }}</h4>
                <p class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed">{{ a.body_preview }}</p>
              </div>

              <div v-if="a.section" class="flex items-center justify-between pt-2 border-t border-slate-200/60 dark:border-[#3F4F43]">
                <span class="text-[10px] sm:text-[11px] font-extrabold text-[#005506] dark:text-[#86EFAC]">{{ a.section }}</span>
              </div>
            </div>
          </div>

          <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] p-8 rounded-2xl border border-dashed border-slate-300 dark:border-[#3F4F43] text-center space-y-2">
            <Icon icon="megaphone" size="lg" class="text-slate-400 mx-auto" />
            <p class="text-xs text-slate-500 dark:text-slate-400">No announcements posted yet.</p>
          </div>
        </div>

        <!-- RIGHT COLUMN: TODAY'S SCHEDULE -->
        <div class="lg:col-span-5 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4 sm:space-y-5">
          <div class="flex items-start justify-between border-b border-slate-100 dark:border-[#3F4F43] pb-3 sm:pb-4">
            <div class="flex items-start gap-2.5">
              <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                <Icon icon="calendar" size="sm" />
              </div>
              <div>
                <h3 class="text-sm sm:text-base font-extrabold text-slate-800 dark:text-white">Today's Schedule</h3>
                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">{{ today || '' }}</p>
              </div>
            </div>

            <span class="bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] sm:text-xs font-extrabold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full border border-emerald-200/80 dark:border-emerald-900/40 shrink-0">
              {{ today_schedule.length }} classes
            </span>
          </div>

          <div v-if="today_schedule.length" class="space-y-2.5 sm:space-y-3">
            <div v-for="s in today_schedule" :key="s.id"
              class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-3.5 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-2.5 sm:gap-3">
              <div class="w-12 sm:w-14 shrink-0 text-center">
                <span class="text-[10px] sm:text-[11px] font-black text-[#005506] dark:text-[#86EFAC] block">{{ s.time_start }}</span>
                <span class="text-[9px] text-slate-400 font-bold block">{{ s.time_end }}</span>
              </div>
              <div class="flex-1 min-w-0">
                <h5 class="text-[11px] sm:text-xs font-extrabold text-slate-800 dark:text-white truncate">{{ s.subject }}</h5>
                <p class="text-[9px] sm:text-[10px] font-semibold text-slate-500 dark:text-slate-400 truncate">
                  {{ s.section }}<span v-if="s.room"> • {{ s.room }}</span>
                </p>
              </div>
            </div>
          </div>

          <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] p-6 rounded-2xl border border-dashed border-slate-300 dark:border-[#3F4F43] text-center space-y-2">
            <Icon icon="calendar" size="lg" class="text-slate-400 mx-auto" />
            <p class="text-xs font-bold text-slate-800 dark:text-white">No classes today</p>
            <p class="text-[10px] text-slate-500 dark:text-slate-400">Enjoy the free day.</p>
          </div>

          <div v-if="needs_grading.length" class="pt-2 border-t border-slate-100 dark:border-[#3F4F43] space-y-2">
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Latest to Grade</span>
            <Link v-for="g in needs_grading.slice(0, 3)" :key="g.id"
              :href="route('teacher.assignments.show', g.assignment_id)"
              class="block bg-[#F9F7F1] dark:bg-[#232D26] p-2.5 rounded-xl border border-slate-200/60 dark:border-[#3F4F43] hover:border-[#005506] dark:hover:border-[#86EFAC] transition-colors">
              <p class="text-[11px] font-bold text-slate-800 dark:text-white truncate">{{ g.assignment }}</p>
              <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate">
                {{ g.student_name }} • {{ g.submitted_human }}
                <span v-if="g.is_late" class="text-amber-700 dark:text-amber-400 font-bold"> • Late</span>
              </p>
            </Link>
          </div>
        </div>

      </div>

    </div>

  </AuthenticatedLayout>

</template>

<script setup>
import heroImage from '../../../assets/img/local/dashboard.png'
import { Head, Link, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import { computed } from 'vue'

const props = defineProps({
  stats:                { type: Object, default: () => ({ classes: 0, students: 0, pending_grading: 0, active_tasks: 0 }) },
  classes:              { type: Array,  default: () => [] },
  needs_grading:        { type: Array,  default: () => [] },
  today_schedule:       { type: Array,  default: () => [] },
  recent_announcements: { type: Array,  default: () => [] },
  active_term:          { type: String, default: null },
  today:                { type: String, default: null },
})

const page = usePage()

const teacherName = computed(() => page.props.auth?.user?.name || 'Teacher')
const teacherProfile = computed(() => page.props.auth?.user?.teacher || {})

const gradeLevelBadges = computed(() => {
  const levels = new Set()
  props.classes.forEach(c => { if (c.grade_level) levels.add(c.grade_level) })
  return [...levels].map(l => `G${l} Grade`).slice(0, 4)
})

const vObserve = {
  mounted(el) {
    el.classList.add('not-visible')
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) el.classList.add('is-animated')
      else el.classList.remove('is-animated')
    }, { threshold: 0.1 })
    observer.observe(el)
  },
}
</script>

<style scoped>
@keyframes pulse-opacity {
  0%, 100% { opacity: 0.88; }
  50%      { opacity: 0.65; }
}
.animate-overlay { animation: pulse-opacity 6s infinite ease-in-out; }

@keyframes slideUpFade {
  from { opacity: 0; transform: translateY(25px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-15px); }
  to   { opacity: 1; transform: translateY(0); }
}

.not-visible { opacity: 0; }

.anim-fade-down,
.is-animated.anim-fade-down {
  animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.anim-slide-up,
.is-animated.anim-slide-up {
  animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-sm :deep(.text-lg)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-sm :deep(.text-xl)   { font-size: 1.125rem !important; line-height: 1.75rem !important; }
.text-scale-sm :deep(.text-3xl)  { font-size: 1.5rem !important; line-height: 2rem !important; }
.text-scale-sm :deep(.sm\:text-4xl) { font-size: 1.875rem !important; line-height: 2.25rem !important; }
.text-scale-sm :deep(.md\:text-5xl) { font-size: 2.25rem !important; line-height: 1 !important; }

.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
.text-scale-lg :deep(.text-lg)   { font-size: 1.25rem !important; line-height: 1.75rem !important; }
.text-scale-lg :deep(.text-xl)   { font-size: 1.5rem !important; line-height: 2rem !important; }
.text-scale-lg :deep(.text-3xl)  { font-size: 2.25rem !important; line-height: 2.5rem !important; }
.text-scale-lg :deep(.sm\:text-4xl) { font-size: 2.75rem !important; line-height: 1 !important; }
.text-scale-lg :deep(.md\:text-5xl) { font-size: 3.5rem !important; line-height: 1 !important; }
</style>