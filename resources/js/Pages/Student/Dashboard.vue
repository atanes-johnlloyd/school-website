<template>

  <Head title="Student Dashboard - Salawag LMS" />

  <!-- ROOT WRAPPER: Locked to screen height to ensure sticky sidebar behavior -->
  <div :class="[
    'h-screen w-full flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300 overflow-hidden',
    `text-scale-${fontSizeMode}`
  ]">
    <!-- SIDEBAR CONTAINER -->
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" class="sticky top-0 h-screen shrink-0 z-30" />

    <!-- MAIN SCROLLABLE AREA -->
    <main class="flex-1 h-full overflow-y-auto overflow-x-hidden min-w-0 flex flex-col justify-between w-full">
      <navbartop searchPlaceholder="Search subjects, assignments, quiz hub tasks.." @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size" />

      <div class="relative z-10 p-3 sm:p-6 md:p-8 space-y-4 sm:space-y-6 flex-1 pb-16 min-w-0">

        <!-- HERO -->
        <div
          class="animate-fade-in-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[300px] flex flex-col justify-center p-4 sm:p-8 md:p-10">

          <!-- High-Quality Student Dashboard Image -->
          <img :src="heroImage" alt="Student Dashboard Hero"
            class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <!-- Animated Green Overlay -->
          <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

          <!-- Content Container -->
          <div class="relative z-10 w-full max-w-4xl space-y-3 sm:space-y-5">

            <!-- Top Badge Container -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
              <div
                class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide">
                <span>✨</span> STUDENT PORTAL
              </div>

              <div
                class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                {{ active_term || 'Active Term' }}
              </div>
            </div>

            <!-- Avatar + Greeting Row -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3.5 sm:gap-5">

              <!-- Avatar -->
              <div
                class="w-14 h-14 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/30 bg-white/10 backdrop-blur-sm overflow-hidden shrink-0 shadow-lg flex items-center justify-center transition-transform hover:scale-105 duration-300">
                <img v-if="student?.avatar_url" :src="student.avatar_url" alt="Student Avatar"
                  class="w-full h-full object-cover" />
                <div v-else
                  class="w-full h-full bg-emerald-800 text-white font-black flex items-center justify-center text-xl sm:text-2xl uppercase">
                  {{ studentInitials }}
                </div>
              </div>

              <!-- Title + Metadata -->
              <div class="space-y-1 min-w-0">
                <h2 class="text-xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight truncate">
                  Good Day, {{ student?.name || 'Student' }}!
                </h2>

                <p class="text-white/90 text-[11px] sm:text-sm md:text-base leading-relaxed font-medium">
                  LRN: <span class="font-black text-[#F9C20C]">{{ student?.lrn || '—' }}</span>
                  <span v-if="student?.grade_level"> • Grade <span class="font-bold">{{ student.grade_level
                      }}</span></span>
                  <span v-if="student?.section"> - <span class="font-bold">{{ student.section }}</span></span>
                  • School Year <span class="font-bold">2026–2027</span>
                </p>
              </div>
            </div>

            <!-- Stat Cards Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 sm:gap-4 pt-1">

              <!-- Enrolled Classes -->
              <div
                class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/15 rounded-xl sm:rounded-2xl py-3 sm:py-4 px-4 hover:bg-black/40 hover:-translate-y-1 transition-all duration-300">
                <span
                  class="text-[10px] sm:text-[11px] font-semibold text-emerald-100/80 uppercase tracking-wider block">
                  Enrolled Classes
                </span>
                <div class="text-2xl sm:text-3xl font-black text-white leading-none mt-1.5">
                  {{ stats?.classes ?? 0 }}
                </div>
                <span class="text-[10px] text-emerald-100/70 block font-medium mt-1">{{ active_term || '—' }}</span>
              </div>

              <!-- Graded Work -->
              <div
                class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/15 rounded-xl sm:rounded-2xl py-3 sm:py-4 px-4 hover:bg-black/40 hover:-translate-y-1 transition-all duration-300">
                <span
                  class="text-[10px] sm:text-[11px] font-semibold text-emerald-100/80 uppercase tracking-wider block">
                  Graded Work
                </span>
                <div class="text-2xl sm:text-3xl font-black text-white leading-none mt-1.5">
                  {{ stats?.grades_available ?? 0 }}
                </div>
                <span class="text-[10px] text-emerald-100/70 block font-medium mt-1">Results released</span>
              </div>

              <!-- Pending Tasks -->
              <div
                class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/15 rounded-xl sm:rounded-2xl py-3 sm:py-4 px-4 hover:bg-black/40 hover:-translate-y-1 transition-all duration-300">
                <span
                  class="text-[10px] sm:text-[11px] font-semibold text-emerald-100/80 uppercase tracking-wider block">
                  Pending Tasks
                </span>
                <div class="flex items-center gap-2 mt-1.5">
                  <span class="text-2xl sm:text-3xl font-black text-white leading-none">
                    {{ stats?.pending_assignments ?? 0 }}
                  </span>
                  <span v-if="urgentCount > 0"
                    class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider animate-pulse">
                    {{ urgentCount }} Due Soon
                  </span>
                </div>
                <span class="text-[10px] text-emerald-100/70 block font-medium mt-1">Across all classes</span>
              </div>

            </div>

          </div>
        </div>

        <!-- MAIN BENTO GRID -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-4 sm:gap-6 items-start min-w-0">

          <!-- LEFT COLUMN -->
          <div class="xl:col-span-8 space-y-4 sm:space-y-6 animate-fade-slide-left min-w-0">

            <!-- ENROLLED CLASSES -->
            <div
              class="bento-card rounded-2xl sm:rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm space-y-4 sm:space-y-5 hover:shadow-md transition-all duration-300">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-3">
                  <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
                  <div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-none">Enrolled
                      Classes</h3>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                      {{ classList.length }} {{ classList.length === 1 ? 'class' : 'classes' }} this term
                    </p>
                  </div>
                </div>
                <Link :href="route('student.classes.index')"
                  class="bg-[#f0f4ee] dark:bg-[#232D26] text-[#004d08] dark:text-[#86EFAC] text-xs font-bold px-3.5 py-1.5 rounded-full border border-[#e1e8dd] dark:border-[#3F4F43] hover:bg-[#e4ede1] transition-colors self-start sm:self-auto flex items-center gap-1">
                  <span>View all</span>
                  <Icon icon="arrow-right" size="xs" />
                </Link>
              </div>

              <div v-if="classList.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 pt-1">
                <Link v-for="klass in classList.slice(0, 6)" :key="klass.id"
                  :href="route('student.classes.show', klass.id)"
                  class="relative bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/70 dark:border-[#3F4F43] shadow-sm flex flex-col justify-between space-y-3 hover:-translate-y-1 hover:shadow-md transition-all duration-200 group">
                  <div class="flex items-center justify-between gap-1">
                    <span
                      class="bg-white dark:bg-[#2D3A31] text-slate-800 dark:text-slate-200 font-extrabold text-[10px] px-2 py-1 rounded-md border border-slate-200 dark:border-[#3F4F43] uppercase tracking-wide truncate">
                      {{ klass.subject_code || 'SUBJECT' }}
                    </span>
                  </div>
                  <div class="min-w-0">
                    <h4
                      class="font-bold text-slate-900 dark:text-white text-base leading-tight group-hover:text-[#004d08] dark:group-hover:text-[#86EFAC] transition-colors truncate">
                      {{ klass.subject || 'Untitled Subject' }}
                    </h4>
                    <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mt-1 truncate">
                      {{ klass.teacher || 'TBA' }} • {{ klass.section || '—' }}
                    </p>
                  </div>
                  <div
                    class="text-[11px] font-bold text-[#004d08] dark:text-[#86EFAC] flex items-center justify-between pt-1">
                    <span>Open class</span>
                    <Icon icon="arrow-right" size="xs" class="group-hover:translate-x-1 transition-transform" />
                  </div>
                </Link>
              </div>

              <div v-else
                class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-6 sm:p-8 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
                <div class="flex justify-center text-[#004d08] dark:text-[#86EFAC]">
                  <Icon icon="book-open" size="xl" />
                </div>
                <p class="text-sm font-bold text-slate-800 dark:text-white">No classes enrolled yet</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Your enrolled subjects will appear here once the
                  registrar assigns them.</p>
              </div>
            </div>

            <!-- UPCOMING DEADLINES BENTO SECTION -->
            <div
              class="bento-card rounded-2xl sm:rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-4 sm:space-y-5 hover:shadow-md transition-all duration-300">
              <!-- Section Header & Filter Pills -->
              <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
                <div class="flex items-center gap-3">
                  <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
                  <div>
                    <h3
                      class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-none">
                      Upcoming Deadlines
                    </h3>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                      Next 7 days schedule
                    </p>
                  </div>
                </div>

                <!-- Interactive Filter Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar shrink-0 max-w-full">
                  <button @click="activeDeadlineFilter = 'all'" :class="[
                    'text-[10px] sm:text-[11px] font-black px-2.5 sm:px-3 py-1.5 rounded-xl shadow-sm transition-all shrink-0',
                    activeDeadlineFilter === 'all'
                      ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                      : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43]'
                  ]">
                    All Pending ({{ counts.all }})
                  </button>

                  <button @click="activeDeadlineFilter = 'quiz'" :class="[
                    'text-[10px] sm:text-[11px] font-black px-2.5 sm:px-3 py-1.5 rounded-xl transition-all shrink-0',
                    activeDeadlineFilter === 'quiz'
                      ? 'bg-amber-500 text-white dark:bg-amber-400 dark:text-slate-900'
                      : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43]'
                  ]">
                    Quizzes ({{ counts.quizzes }})
                  </button>

                  <button @click="activeDeadlineFilter = 'assignment'" :class="[
                    'text-[10px] sm:text-[11px] font-black px-2.5 sm:px-3 py-1.5 rounded-xl transition-all shrink-0',
                    activeDeadlineFilter === 'assignment'
                      ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                      : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43]'
                  ]">
                    Assignments ({{ counts.assignments }})
                  </button>
                </div>
              </div>

              <!-- HORIZONTALLY SCROLLABLE CARDS TRACK -->
              <div v-if="filteredDeadlines && filteredDeadlines.length" class="relative group min-w-0">
                <div
                  class="flex items-stretch gap-3 sm:gap-6 overflow-x-auto pb-4 pt-1 snap-x snap-mandatory scroll-smooth no-scrollbar -mx-2 px-2 sm:mx-0 sm:px-0">
                  <div v-for="(task, index) in filteredDeadlines" :key="task.id || index"
                    :style="{ animationDelay: `${index * 60}ms` }" :class="[
                      'snap-start shrink-0 w-[260px] sm:w-[320px] md:w-[340px] bg-white dark:bg-[#232D26] rounded-2xl sm:rounded-3xl p-4 sm:p-5 border-2 flex flex-col justify-between space-y-4 shadow-sm hover:shadow-md transition-all duration-300',
                      task.days_left <= 0
                        ? 'border-red-400 dark:border-red-500/70 bg-red-50/20'
                        : 'border-slate-200/80 dark:border-[#3F4F43] hover:border-[#005506] dark:hover:border-[#86EFAC]'
                    ]">
                    <!-- Card Top Details -->
                    <div class="space-y-3 min-w-0">
                      <!-- Header Row: Type Badge + Due Alert -->
                      <div class="flex items-center justify-between gap-2">
                        <span :class="[
                          'text-[10px] font-black px-2.5 py-0.5 rounded-md border uppercase tracking-wide',
                          task.type === 'quiz'
                            ? 'bg-amber-100/80 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 border-amber-200/60 dark:border-amber-900/40'
                            : 'bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-200/60 dark:border-emerald-900/40'
                        ]">
                          {{ task.type === 'quiz' ? 'Quiz' : 'Assignment' }}
                        </span>

                        <!-- Urgency State Pill -->
                        <div v-if="task.days_left <= 0"
                          class="inline-flex items-center gap-1 bg-red-600 text-white text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                          <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                          <span>DUE</span>
                        </div>
                        <div v-else-if="task.days_left <= 2"
                          class="inline-flex items-center bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-full border border-amber-200/80">
                          {{ task.days_left }} {{ task.days_left === 1 ? 'day' : 'days' }} left
                        </div>
                        <span v-else
                          class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                          Upcoming
                        </span>
                      </div>

                      <!-- Subject & Task Title -->
                      <div class="space-y-1 min-w-0">
                        <h4 class="text-xs font-black text-[#005506] dark:text-[#86EFAC] uppercase tracking-wide truncate">
                          {{ task.subject || 'General Subject' }}
                        </h4>
                        <h3
                          class="text-sm sm:text-lg font-bold text-slate-900 dark:text-white line-clamp-2 leading-snug">
                          {{ task.title || task.type }}
                        </h3>
                      </div>

                      <!-- Detailed Meta Box -->
                      <div
                        class="bg-[#F9F7F1] dark:bg-[#2D3A31] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2 text-xs">
                        <div class="flex items-center justify-between text-slate-700 dark:text-slate-300 font-medium">
                          <span class="text-slate-500 dark:text-slate-400">Due Date:</span>
                          <span class="font-bold text-slate-900 dark:text-white truncate ml-2">{{ task.due_human }}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-700 dark:text-slate-300 font-medium">
                          <span class="text-slate-500 dark:text-slate-400">Weight / Score:</span>
                          <span class="font-bold text-[#005506] dark:text-[#86EFAC] ml-2">{{ task.points }} pts</span>
                        </div>
                      </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                      <Link :href="task.show_url"
                        class="w-full bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] hover:bg-[#004105] py-2 sm:py-2.5 rounded-xl text-xs font-black shadow-sm flex items-center justify-center gap-2 transition-all active:scale-95">
                        <span>Open Deliverable</span>
                        <Icon icon="arrow-right" size="xs" />
                      </Link>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Empty State -->
              <div v-else
                class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-6 sm:p-8 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
                <div class="flex justify-center text-emerald-500">
                  <Icon icon="sparkles" size="xl" />
                </div>
                <p class="text-sm font-bold text-slate-800 dark:text-white">All clear!</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                  {{ activeDeadlineFilter === 'all' ? 'No pending assignments in the next 7 days.' : `No pending
                  ${activeDeadlineFilter}s found.` }}
                </p>
              </div>
            </div>

          </div>

          <!-- RIGHT COLUMN -->
          <div class="xl:col-span-4 space-y-4 sm:space-y-6 animate-fade-slide-right min-w-0">

            <!-- ANNOUNCEMENTS -->
            <div
              class="bento-card rounded-2xl sm:rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm space-y-4 hover:shadow-md transition-all duration-300">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                  <div class="w-2.5 h-6 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
                  <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">Announcements</h3>
                </div>
                <Link :href="route('student.announcements.feed')"
                  class="bg-[#f0f4ee] dark:bg-[#232D26] text-[#004d08] dark:text-[#86EFAC] text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-[#e1e8dd] dark:border-[#3F4F43] hover:bg-[#e4ede1] transition-colors">
                  View all
                </Link>
              </div>

              <div v-if="recent_announcements && recent_announcements.length" class="space-y-3">
                <div v-for="a in recent_announcements" :key="a.id"
                  class="relative bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 pl-6 border border-slate-200/70 dark:border-[#3F4F43] shadow-sm hover:translate-x-1 transition-all duration-200 min-w-0">
                  <div
                    :class="['absolute left-0 top-0 bottom-0 w-2 rounded-l-2xl', a.is_pinned ? 'bg-amber-400' : 'bg-[#004d08] dark:bg-[#86EFAC]']">
                  </div>
                  <div class="flex items-center justify-between gap-2 mb-1">
                    <span v-if="a.is_pinned"
                      class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[9px] font-black uppercase px-2 py-0.5 rounded-md inline-flex items-center gap-1">
                      <Icon icon="star" size="xs" />
                      <span>Pinned</span>
                    </span>
                    <span v-else class="text-[10px] font-bold text-slate-500 dark:text-slate-400 truncate">{{ a.subject ||
                      'General' }}</span>
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 shrink-0">{{ a.published_human
                      }}</span>
                  </div>
                  <h4 class="font-bold text-slate-900 dark:text-white text-sm leading-snug truncate">{{ a.title }}</h4>
                  <p class="text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed mt-1 line-clamp-2">{{
                    a.body_preview }}</p>
                </div>
              </div>

              <div v-else
                class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-6 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-1">
                <div class="flex justify-center text-slate-400">
                  <Icon icon="megaphone" size="lg" />
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">No announcements yet.</p>
              </div>
            </div>

            <!-- TERM SNAPSHOT -->
            <div
              class="bento-card rounded-2xl sm:rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm space-y-4 hover:shadow-md transition-all duration-300">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                  <div class="w-2.5 h-6 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
                  <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">Term Snapshot</h3>
                </div>
                <span
                  class="bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] text-[10px] font-extrabold px-2.5 py-0.5 rounded-full truncate max-w-[120px]">
                  {{ active_term || '—' }}
                </span>
              </div>

              <div class="grid grid-cols-3 gap-2">
                <div
                  class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-2.5 sm:p-3 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
                  <div class="text-xl sm:text-2xl font-black text-[#004d08] dark:text-[#86EFAC]">{{ stats?.classes ?? 0 }}</div>
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase block truncate">Classes</span>
                </div>
                <div
                  class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-2.5 sm:p-3 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
                  <div class="text-xl sm:text-2xl font-black text-amber-700 dark:text-amber-400">{{ stats?.pending_assignments ?? 0
                    }}</div>
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase block truncate">Pending</span>
                </div>
                <div
                  class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-2.5 sm:p-3 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
                  <div class="text-xl sm:text-2xl font-black text-[#004d08] dark:text-[#86EFAC]">{{ stats?.grades_available ?? 0 }}
                  </div>
                  <span class="text-[9px] sm:text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase block truncate">Graded</span>
                </div>
              </div>

              <div
                class="text-center text-[11px] font-semibold text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                {{ student?.strand || 'Strand not assigned' }}
                {{ student?.school_year ? `• S.Y. ${student.school_year}` : '' }}
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
import { Head, Link } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../assets/img/local/studentdashboard.png'

// 1. PROPS DECLARATION
const props = defineProps({
  student: { type: Object, default: () => ({}) },
  classes: { type: Object, default: () => ({ data: [] }) },
  stats: { type: Object, default: () => ({}) },
  upcoming_deadlines: { type: Array, default: () => [] },
  recent_announcements: { type: Array, default: () => [] },
  active_term: { type: String, default: null },
})

// 2. UI STATE
const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')

// 3. UPCOMING DEADLINES FILTER STATE
const activeDeadlineFilter = ref('all') // Options: 'all' | 'quiz' | 'assignment'

// 4. COMPUTED PROPERTIES
const classList = computed(() => props.classes?.data ?? [])

const studentInitials = computed(() => {
  const name = props.student?.name || 'S'
  const parts = name.trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
})

const urgentCount = computed(() =>
  (props.upcoming_deadlines || []).filter(t => (t.days_left ?? 99) <= 2).length
)

// Dynamic Filtered Deadlines Array
const filteredDeadlines = computed(() => {
  const list = props.upcoming_deadlines || []
  if (activeDeadlineFilter.value === 'all') {
    return list
  }
  return list.filter((task) => {
    if (activeDeadlineFilter.value === 'quiz') {
      return task.type === 'quiz'
    }
    if (activeDeadlineFilter.value === 'assignment') {
      return task.type === 'assignment' || task.type !== 'quiz'
    }
    return true
  })
})

// Counts mapped directly to `counts` object for template binding
const counts = computed(() => {
  const list = props.upcoming_deadlines || []
  return {
    all: list.length,
    quizzes: list.filter((t) => t.type === 'quiz').length,
    assignments: list.filter((t) => t.type === 'assignment' || t.type !== 'quiz').length
  }
})
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.animated-stroke-text {
  color: transparent;
  -webkit-text-stroke: 1.5px #ffffff;
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

@keyframes fadeSlideLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }

  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fadeSlideRight {
  from {
    opacity: 0;
    transform: translateX(30px);
  }

  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-fade-in-down {
  animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fade-slide-left {
  animation: fadeSlideLeft 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
}

.animate-fade-slide-right {
  animation: fadeSlideRight 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
}
</style>