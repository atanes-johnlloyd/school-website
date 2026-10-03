<template>

  <Head :title="`${classroom.subject} - Salawag LMS`" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop searchPlaceholder="Search announcements, assignments, materials..."
        @open-sidebar="isSidebarOpen = true" @font-size-changed="(size) => fontSizeMode = size" />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- Hero Banner -->
        <div
          class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
          <!-- Background Image Layer (Subject Workspace / Study Theme) -->
          <img
            :src="heroImage || 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=1600&auto=format&fit=crop'"
            alt="Subject Workspace Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <!-- Animated Dark Green Overlay -->
          <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply">
          </div>

          <!-- Ambient Light Glow Highlights -->
          <div
            class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0">
          </div>
          <div
            class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0">
          </div>

          <!-- Content Container -->
          <div class="relative z-10 w-full space-y-4 sm:space-y-6">
            <!-- Top Row: Badges, Title & Back Link -->
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
              <div class="space-y-2 max-w-2xl">
                <!-- Top Capsule Badges -->
                <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                  <span
                    class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                    <span>📚</span> ACADEMICS
                  </span>

                  <span
                    class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Subject Workspace
                  </span>
                </div>

                <!-- Title & Quote -->
                <div class="space-y-1">
                  <h1
                    class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                    <span>SUBJECT</span>
                    <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">WORKSPACE</span>
                  </h1>
                  <p class="text-white/90 text-xs sm:text-sm leading-relaxed font-medium italic">
                    "Your journey to knowledge starts with one click."
                  </p>
                </div>
              </div>

              <!-- Navigation Link Button -->
              <Link :href="route('student.classes.index')"
                class="inline-flex items-center justify-center gap-2 bg-black/30 hover:bg-black/40 backdrop-blur-md text-white border border-white/20 font-bold px-4 py-2.5 rounded-xl transition-all active:scale-95 text-xs sm:text-sm cursor-pointer shadow-sm shrink-0 self-start">
                <span>← All Subjects</span>
              </Link>
            </div>

            <!-- Bottom Row: Subject Details & Stat Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2">
              <!-- Subject Info Card -->
              <div
                class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4 bg-black/20 backdrop-blur-md border border-white/10 p-4 rounded-2xl shadow-sm">
                <div
                  class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl border border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center text-2xl sm:text-3xl transition-transform hover:scale-105 duration-300">
                  📚
                </div>
                <div class="space-y-1 text-center sm:text-left">
                  <div
                    class="inline-block bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-[11px] font-black px-3 py-0.5 rounded-full shadow-xs uppercase tracking-wide">
                    {{ classroom.subject_code || 'SUBJECT' }}
                    <span v-if="classroom.section"> • {{ classroom.section }}</span>
                  </div>
                  <h2 class="font-black text-base sm:text-xl text-white tracking-tight leading-snug drop-shadow-xs">
                    {{ classroom.subject || 'Untitled Subject' }}
                  </h2>
                  <p class="text-xs text-white/80 font-medium">
                    Instructor: <span class="text-emerald-300 font-bold">{{ classroom.teacher || '—' }}</span>
                    <span v-if="classroom.term"> • {{ classroom.term }}</span>
                  </p>
                </div>
              </div>

              <!-- Quick Metrics Grid -->
              <div class="lg:col-span-5 grid grid-cols-3 gap-2 sm:gap-3">
                <!-- Announcements Stat Card -->
                <div
                  class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between hover:-translate-y-1 hover:bg-black/40 transition-all duration-300 shadow-sm">
                  <span class="text-[10px] font-bold text-white/80">Announcements</span>
                  <div class="text-xl font-black text-[#F9C20C] my-0.5 drop-shadow-xs">{{ announcementsList.length }}
                  </div>
                  <span class="text-[9px] text-white/70 font-medium">Class updates</span>
                </div>

                <!-- Assignments Stat Card -->
                <div
                  class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between hover:-translate-y-1 hover:bg-black/40 transition-all duration-300 shadow-sm">
                  <span class="text-[10px] font-bold text-white/80">Assignments</span>
                  <div class="text-xl font-black text-white my-0.5 drop-shadow-xs">{{ assignmentsList.length }}</div>
                  <span class="text-[9px] text-white/70 font-medium">Published</span>
                </div>

                <!-- Graded Stat Card -->
                <div
                  class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between hover:-translate-y-1 hover:bg-black/40 transition-all duration-300 shadow-sm">
                  <span class="text-[10px] font-bold text-white/80">Graded</span>
                  <div class="text-xl font-black text-[#86EFAC] my-0.5 drop-shadow-xs">{{ gradedCount }}</div>
                  <span class="text-[9px] text-white/70 font-medium">of {{ assignmentsList.length }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB NAVIGATION -->
        <div
          class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-2 sm:p-3 shadow-sm flex items-center gap-1.5 sm:gap-2 overflow-x-auto">
          <button v-for="t in tabs" :key="t.id" @click="activeTab = t.id" :class="[
            'px-4 py-2.5 rounded-2xl text-xs font-extrabold transition-all shrink-0 flex items-center gap-2 active:scale-95',
            activeTab === t.id
              ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-sm'
              : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-[#232D26] hover:text-[#004d08] dark:hover:text-[#86EFAC]'
          ]">
            <span>{{ t.icon }}</span>
            <span>{{ t.label }}</span>
            <span v-if="t.count !== null" :class="[
              'text-[10px] px-1.5 py-0.5 rounded-full font-black',
              activeTab === t.id
                ? 'bg-white/20 text-white dark:text-[#232D26]'
                : 'bg-slate-200/80 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300'
            ]">{{ t.count }}</span>
          </button>
        </div>

        <!-- TAB: ANNOUNCEMENTS -->
        <div v-if="activeTab === 'announcements'" class="animate-fade-slide-up space-y-6">
          <template v-if="announcementsList.length">
            <!-- Pinned -->
            <div v-if="pinnedAnnouncement" @click="openAnnouncementModal(pinnedAnnouncement)"
              class="bg-gradient-to-br from-[#004d08] to-[#003304] dark:from-[#152B1C] dark:to-[#0F2114] text-white rounded-3xl p-6 sm:p-8 shadow-md relative overflow-hidden group cursor-pointer">
              <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none">
              </div>
              <div class="relative z-10 space-y-4">
                <div class="flex items-center justify-between flex-wrap gap-2">
                  <span
                    class="bg-amber-400 text-slate-950 font-black text-[10px] uppercase tracking-wider px-3 py-1 rounded-full flex items-center gap-1 shadow-xs">
                    📌 Pinned Announcement
                  </span>
                  <span class="text-xs text-emerald-200/80 font-medium">{{ formatDate(pinnedAnnouncement.published_at)
                    }}</span>
                </div>
                <div class="space-y-2 max-w-3xl">
                  <h3 class="font-black text-xl sm:text-2xl text-white group-hover:text-emerald-300 transition-colors">
                    {{ pinnedAnnouncement.title }}
                  </h3>
                  <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed line-clamp-3">
                    {{ pinnedAnnouncement.body_preview || pinnedAnnouncement.body }}
                  </p>
                </div>
                <div
                  class="pt-4 border-t border-white/10 flex items-center justify-between flex-wrap gap-3 text-xs text-emerald-200">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-white border border-white/30">
                      {{ initialOf(pinnedAnnouncement.author) }}
                    </div>
                    <div>
                      <span class="font-extrabold block text-white">{{ pinnedAnnouncement.author || 'Instructor'
                        }}</span>
                      <span class="text-[10px] text-emerald-300">Class Instructor</span>
                    </div>
                  </div>
                  <span
                    class="font-bold text-white group-hover:translate-x-1 transition-transform flex items-center gap-1">
                    Read Full Notice →
                  </span>
                </div>
              </div>
            </div>

            <!-- Regular -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="item in regularAnnouncements" :key="item.id" @click="openAnnouncementModal(item)"
                class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-3xl p-6 shadow-sm border border-slate-200/80 dark:border-[#3F4F43] min-h-[220px] flex flex-col justify-between hover:border-[#004d08] dark:hover:border-[#86EFAC] hover:shadow-md transition-all cursor-pointer group relative">
                <div class="space-y-3">
                  <div class="flex items-center justify-between gap-2">
                    <span
                      class="text-[10px] font-extrabold uppercase bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] px-3 py-1 rounded-full">
                      {{ item.category || 'General' }}
                    </span>
                    <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500">{{
                      formatDate(item.published_at) }}</span>
                  </div>
                  <h4
                    class="font-extrabold text-slate-900 dark:text-white text-base leading-snug group-hover:text-[#004d08] dark:group-hover:text-[#86EFAC] transition-colors">
                    {{ item.title }}
                  </h4>
                  <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed font-medium">
                    {{ item.body_preview || item.body }}
                  </p>
                </div>
                <div
                  class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 font-semibold">
                  <div class="flex items-center gap-2">
                    <div
                      class="w-6 h-6 rounded-full bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-[10px] flex items-center justify-center font-bold">
                      {{ initialOf(item.author) }}
                    </div>
                    <span class="text-slate-700 dark:text-slate-200 font-bold">{{ item.author || 'Instructor' }}</span>
                  </div>
                  <span
                    class="text-[#004d08] dark:text-[#86EFAC] font-bold group-hover:translate-x-0.5 transition-transform">Read
                    →</span>
                </div>
              </div>
            </div>
          </template>

          <div v-else
            class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
            <div class="text-4xl">📢</div>
            <p class="font-bold text-slate-800 dark:text-white text-base">No announcements yet</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Your teacher hasn't posted anything to this class yet.
            </p>
          </div>
        </div>

        <!-- TAB: ASSIGNMENTS -->
        <div v-if="activeTab === 'assignments'" class="animate-fade-slide-up space-y-6">
          <div
            class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] flex flex-wrap items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-2 flex-wrap">
              <span
                class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Filter:</span>
              <button v-for="f in assignmentFilters" :key="f.id" @click="assignmentFilter = f.id" :class="[
                'px-3 py-1.5 rounded-xl text-xs font-extrabold transition-all active:scale-95',
                assignmentFilter === f.id
                  ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                  : 'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#3F4F43]'
              ]">
                {{ f.label }}
              </button>
            </div>
            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">
              Showing {{ filteredAssignments.length }} of {{ assignmentsList.length }} items
            </span>
          </div>

          <div v-if="filteredAssignments.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="item in filteredAssignments" :key="item.id"
              class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-3xl p-6 shadow-sm border border-slate-200/80 dark:border-[#3F4F43] flex flex-col justify-between hover:border-[#004d08] dark:hover:border-[#86EFAC] hover:shadow-md transition-all group relative">
              <div class="space-y-4">
                <div class="flex items-start justify-between gap-2">
                  <span
                    class="text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full bg-slate-100 dark:bg-[#232D26] text-slate-700 dark:text-slate-300">
                    {{ categoryLabel(item.category) }}
                  </span>
                  <span v-if="item.submission" :class="statusBadgeClass(item.submission.status)"
                    class="px-3 py-1 rounded-full text-[10px] font-extrabold border shrink-0 shadow-2xs">
                    {{ statusLabel(item.submission.status) }}
                  </span>
                  <span v-else
                    class="px-3 py-1 rounded-full text-[10px] font-extrabold border border-amber-400 text-amber-800 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 shrink-0 shadow-2xs">
                    Pending
                  </span>
                </div>

                <div class="space-y-1.5">
                  <h4
                    class="font-extrabold text-slate-900 dark:text-white text-base leading-snug group-hover:text-[#004d08] dark:group-hover:text-[#86EFAC] transition-colors">
                    {{ item.title }}
                  </h4>
                  <p class="text-xs text-slate-500 dark:text-slate-400 font-medium line-clamp-2">
                    {{ item.instructions || 'No additional instructions provided.' }}
                  </p>
                </div>

                <div
                  class="flex items-center gap-2 bg-slate-100/70 dark:bg-[#232D26] p-2.5 rounded-2xl text-xs text-slate-600 dark:text-slate-300 font-semibold">
                  <span class="text-base">📅</span>
                  <div>
                    <span
                      class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase">Deadline</span>
                    <span>{{ item.due_at ? formatDate(item.due_at) : 'No deadline' }}</span>
                  </div>
                </div>
              </div>

              <div
                class="pt-5 border-t border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between mt-4">
                <div>
                  <span
                    class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase block">Grade</span>
                  <span class="text-xs font-black text-slate-900 dark:text-white">
                    {{ item.submission?.grade ?? '--' }} <span class="text-slate-400 dark:text-slate-500 font-normal">/
                      {{
                      item.points }}</span>
                  </span>
                </div>

                <Link :href="route('student.assignments.show', item.id)" :class="[
                  'px-4 py-2 text-xs font-extrabold rounded-xl transition-all shadow-2xs active:scale-95',
                  item.submission?.status === 'graded'
                    ? 'bg-slate-200 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-200 hover:bg-slate-300'
                    : 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] hover:bg-[#003805]'
                ]">
                  {{ item.submission?.status === 'graded' ? 'View Grade' : item.submission ? 'View Submission' : 'OpenTask' }}
                </Link>
              </div>
            </div>
          </div>

          <div v-else
            class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
            <div class="text-4xl">📝</div>
            <p class="font-bold text-slate-800 dark:text-white text-base">No assignments found</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Try changing your filter or wait for your teacher to
              post new
              work.</p>
          </div>
        </div>

        <!-- TAB: MATERIALS -->
        <div v-if="activeTab === 'materials'" class="animate-fade-slide-up space-y-6">
          <div
            class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
            <div class="text-4xl">📚</div>
            <p class="font-bold text-slate-800 dark:text-white text-base">Course materials coming soon</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              Learning resources will be available here once the Lesson module is published.
            </p>
            <Link :href="route('student.classes.lessons.index', classroom.id)"
              class="inline-block mt-2 bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-black px-5 py-2.5 rounded-2xl hover:bg-[#003805] transition-colors shadow-sm active:scale-95">
              Open Lessons Page →
            </Link>
          </div>
        </div>

        <!-- TAB: GRADES -->
        <div v-if="activeTab === 'grades'" class="animate-fade-slide-up space-y-6">
          <div
            class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-3xl p-6 border border-slate-200/80 dark:border-[#3F4F43] shadow-sm space-y-5">
            <div
              class="flex items-center justify-between border-b border-slate-200 dark:border-[#3F4F43] pb-3 gap-3 flex-wrap">
              <div class="flex items-center gap-3">
                <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
                <div>
                  <h3 class="font-bold text-slate-900 dark:text-white text-base">Gradebook Summary</h3>
                  <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    Live computed scores for this class
                  </p>
                </div>
              </div>
              <Link :href="route('student.classes.grades.show', classroom.id)"
                class="bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-black px-4 py-2 rounded-2xl hover:bg-[#003805] transition-colors shadow-sm active:scale-95 shrink-0">
                Full Report →
              </Link>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              <div
                class="bg-emerald-50 dark:bg-emerald-950/40 rounded-2xl p-4 border border-emerald-200 dark:border-emerald-900/40 text-center">
                <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 block">Written
                  Work</span>
                <span class="text-2xl font-black text-[#004d08] dark:text-[#86EFAC] block mt-1">{{
                  gradesSummary.written_work ??
                  '—' }}</span>
                <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 block mt-0.5">25%</span>
              </div>
              <div
                class="bg-amber-50 dark:bg-amber-950/40 rounded-2xl p-4 border border-amber-200 dark:border-amber-900/40 text-center">
                <span
                  class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 block">Performance</span>
                <span class="text-2xl font-black text-amber-700 dark:text-amber-400 block mt-1">{{
                  gradesSummary.performance_task ?? '—' }}</span>
                <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 block mt-0.5">50%</span>
              </div>
              <div
                class="bg-emerald-50 dark:bg-emerald-950/40 rounded-2xl p-4 border border-emerald-200 dark:border-emerald-900/40 text-center">
                <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 block">Quarterly
                  Exam</span>
                <span class="text-2xl font-black text-[#004d08] dark:text-[#86EFAC] block mt-1">{{
                  gradesSummary.quarterly_exam
                  ?? '—' }}</span>
                <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 block mt-0.5">25%</span>
              </div>
              <div
                class="bg-[#004d08] dark:bg-[#152B1C] rounded-2xl p-4 border border-[#003805] dark:border-[#3F4F43] text-center">
                <span class="text-[10px] font-black uppercase text-emerald-200 dark:text-emerald-300 block">Final
                  Grade</span>
                <span class="text-2xl font-black text-amber-300 block mt-1">{{ gradesSummary.final_grade ?? '—'
                  }}</span>
                <span class="text-[9px] font-bold text-emerald-300/80 dark:text-emerald-400/70 block mt-0.5">
                  {{ gradesSummary.remarks ? remarksLabel(gradesSummary.remarks) : 'Pending' }}
                </span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </main>

    <!-- ANNOUNCEMENT MODAL -->
    <div v-if="selectedAnnouncement"
      class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 animate-fade-in"
      @click.self="selectedAnnouncement = null">
      <div
        class="bg-white dark:bg-[#2D3A31] rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100 dark:border-[#3F4F43]">
        <div class="flex items-start justify-between gap-4">
          <div class="space-y-1">
            <span
              class="text-[10px] font-extrabold uppercase bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] px-3 py-1 rounded-full">
              {{ selectedAnnouncement.category || 'Announcement' }}
            </span>
            <h3 class="font-extrabold text-slate-900 dark:text-white text-xl pt-2">{{ selectedAnnouncement.title }}</h3>
            <p class="text-xs text-slate-400 dark:text-slate-500 font-semibold">Posted on {{
              formatDate(selectedAnnouncement.published_at) }}</p>
          </div>
          <button @click="selectedAnnouncement = null"
            class="text-slate-400 hover:text-slate-700 dark:hover:text-white text-lg font-bold">✕</button>
        </div>

        <div
          class="text-slate-700 dark:text-slate-300 text-xs sm:text-sm leading-relaxed space-y-3 bg-slate-50 dark:bg-[#232D26] p-4 rounded-2xl border border-slate-200/60 dark:border-[#3F4F43] max-h-72 overflow-auto whitespace-pre-wrap">
          {{ selectedAnnouncement.body }}
        </div>

        <div class="flex items-center justify-between pt-2">
          <div class="flex items-center gap-2">
            <div
              class="w-8 h-8 rounded-full bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs flex items-center justify-center font-bold">
              {{ initialOf(selectedAnnouncement.author) }}
            </div>
            <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200">{{ selectedAnnouncement.author ||
              'Instructor' }}</span>
          </div>
          <button @click="selectedAnnouncement = null"
            class="px-5 py-2 bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-black rounded-xl active:scale-95 transition-all">
            Close Notice
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'

const props = defineProps({
  classroom: { type: Object, default: () => ({}) },
  announcements: { type: Array, default: () => [] },
  assignments: { type: Array, default: () => [] },
  gradesSummary: { type: Object, default: () => ({}) },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')
const activeTab = ref('announcements')
const assignmentFilter = ref('all')

const announcementsList = computed(() => props.announcements ?? [])
const assignmentsList = computed(() => props.assignments ?? [])

const gradedCount = computed(() =>
  assignmentsList.value.filter(a => a.submission?.status === 'graded').length
)

const tabs = computed(() => [
  { id: 'announcements', label: 'Announcements', icon: '📢', count: announcementsList.value.length },
  { id: 'assignments', label: 'Assignments', icon: '📝', count: assignmentsList.value.length },
  { id: 'materials', label: 'Materials', icon: '📚', count: null },
  { id: 'grades', label: 'Grades', icon: '📊', count: null },
])

const pinnedAnnouncement = computed(() => announcementsList.value.find(a => a.is_pinned) ?? null)
const regularAnnouncements = computed(() => announcementsList.value.filter(a => !a.is_pinned))

const assignmentFilters = [
  { id: 'all', label: 'All' },
  { id: 'pending', label: 'Pending' },
  { id: 'submitted', label: 'Submitted' },
  { id: 'graded', label: 'Graded' },
]

const filteredAssignments = computed(() => {
  const list = assignmentsList.value
  if (assignmentFilter.value === 'all') return list
  if (assignmentFilter.value === 'pending')
    return list.filter(a => !a.submission || a.submission.status === 'not_submitted')
  if (assignmentFilter.value === 'submitted')
    return list.filter(a => a.submission && ['submitted', 'late'].includes(a.submission.status))
  if (assignmentFilter.value === 'graded')
    return list.filter(a => a.submission?.status === 'graded')
  return list
})

const selectedAnnouncement = ref(null)

function openAnnouncementModal(item) {
  selectedAnnouncement.value = item
}

function initialOf(name) {
  if (typeof name !== 'string' || name.length === 0) return '?'
  return name.charAt(0).toUpperCase()
}

function formatDate(value) {
  if (!value) return '—'
  try {
    return new Date(value).toLocaleDateString('en-US', {
      month: 'short', day: 'numeric', year: 'numeric',
      hour: 'numeric', minute: '2-digit',
    })
  } catch { return value }
}

function statusBadgeClass(status) {
  return {
    graded: 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-[#86EFAC] border-emerald-600 dark:border-emerald-900/40',
    submitted: 'bg-blue-50 dark:bg-blue-950/40 text-blue-800 dark:text-blue-300 border-blue-400 dark:border-blue-900/40',
    late: 'bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border-amber-400 dark:border-amber-900/40',
    not_submitted: 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]',
  }[status] || 'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'
}

function statusLabel(status) {
  return {
    graded: 'Graded',
    submitted: 'Submitted',
    late: 'Submitted (Late)',
    not_submitted: 'Not Submitted',
  }[status] || status
}

function categoryLabel(cat) {
  return {
    written_work: 'Written Work',
    performance_task: 'Performance Task',
    quarterly_exam: 'Quarterly Exam',
  }[cat] || 'Homework'
}

function remarksLabel(remarks) {
  return {
    outstanding: 'Outstanding',
    very_satisfactory: 'Very Satisfactory',
    satisfactory: 'Satisfactory',
    fairly_satisfactory: 'Fairly Satisfactory',
    did_not_meet_expectations: 'Did Not Meet',
  }[remarks] || '—'
}
</script>

<style scoped>
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

@keyframes fadeSlideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes floatSoft {

  0%,
  100% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(-4px);
  }
}

@keyframes sheenMove {
  0% {
    transform: translateX(-100%);
  }

  100% {
    transform: translateX(200%);
  }
}

@keyframes spinSlow {
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(6px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-down {
  animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fade-slide-up {
  animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
}

.animate-float-soft {
  animation: floatSoft 3s ease-in-out infinite;
}

.animate-sheen {
  animation: sheenMove 4s ease-in-out infinite;
}

.animate-spin-slow {
  display: inline-block;
  animation: spinSlow 12s linear infinite;
}

.animate-fade-in {
  animation: fadeIn 0.3s ease-in-out both;
}

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
</style>