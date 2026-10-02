<template>
  <Head title="Quiz Hub & Exam Bank - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search quizzes, question banks, exams..">

    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2 max-w-full">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[260px] flex flex-col justify-center">
        <img :src="heroImage" alt="Quiz Hub Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 max-w-3xl space-y-3 sm:space-y-4">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <div class="inline-flex items-center gap-1.5 sm:gap-2 bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-xs font-black uppercase tracking-wider px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
              <Icon icon="sparkles" size="xs" />
              Quiz Hub
            </div>
            <div class="inline-flex items-center gap-1.5 sm:gap-2 bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
              <Icon icon="calendar" size="xs" />
              {{ active_term || 'Active Term' }}
            </div>
          </div>

          <div class="space-y-2 sm:space-y-3">
            <h2 class="text-xl sm:text-3xl md:text-5xl font-black text-white tracking-tight leading-tight">
              Quiz Hub & Question Bank
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              Author quizzes across every class you teach, curate your personal question bank, and manage deployment windows.
            </p>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <button @click="openCreateQuiz"
              class="inline-flex items-center gap-1.5 bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] px-3.5 py-2 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95">
              <Icon icon="plus" size="xs" />
              New Quiz
            </button>
            <Link :href="route('teacher.questions.index')"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              <Icon icon="book-open" size="xs" />
              Question Bank ({{ questionStats.total }})
            </Link>
          </div>
        </div>
      </div>

      <!-- STATS -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3" style="animation-delay: 100ms;">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Quizzes</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="clipboard-list" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ stats.total }}</div>
          <p class="text-[11px] font-medium text-slate-500">Across {{ classrooms.length }} classes</p>
        </div>

        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3" style="animation-delay: 150ms;">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Published</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="check-circle" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-[#005506] dark:text-[#86EFAC]">{{ stats.published }}</div>
          <p class="text-[11px] font-medium text-slate-500">Live for students</p>
        </div>

        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3" style="animation-delay: 200ms;">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Questions</span>
            <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
              <Icon icon="book-open" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ questionStats.total }}</div>
          <p class="text-[11px] font-medium text-slate-500">In your bank</p>
        </div>

        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3" style="animation-delay: 250ms;">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Attempts</span>
            <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-900/40">
              <Icon icon="users" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ stats.total_attempts }}</div>
          <p class="text-[11px] font-medium text-slate-500">Student submissions</p>
        </div>
      </div>

      <!-- FILTER BAR -->
      <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-3.5 sm:p-4 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3" style="animation-delay: 300ms;">
        <div class="flex flex-col lg:flex-row lg:items-center gap-3">
          <div class="relative w-full lg:w-72 shrink-0">
            <select v-model="classFilter"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-100 text-xs font-extrabold px-3.5 py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer pr-8 truncate">
              <option value="all">All Classes ({{ quizzes.length }})</option>
              <option v-for="c in classrooms" :key="c.id" :value="c.id">
                {{ c.subject }} — {{ c.section }} ({{ c.quizzes_count }})
              </option>
            </select>
            <Icon icon="chevron-down" size="xs" class="absolute right-3 top-3.5 text-slate-400 pointer-events-none" />
          </div>

          <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 lg:pb-0">
            <button v-for="f in statusFilters" :key="f.id" @click="statusFilter = f.id"
              :class="[
                'text-[11px] font-black px-3.5 py-1.5 sm:py-2 rounded-xl shadow-sm transition-all shrink-0 active:scale-95',
                statusFilter === f.id
                  ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                  : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43]'
              ]">
              {{ f.label }} ({{ f.count }})
            </button>
          </div>

          <div class="relative flex-1 min-w-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
              <Icon icon="search" size="sm" />
            </span>
            <input v-model="search" type="text" placeholder="Search quiz title or description..."
              class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-xs font-semibold text-slate-700 dark:text-slate-200" />
          </div>
        </div>
      </div>

      <!-- MAIN LAYOUT -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-start">

        <!-- LEFT COLUMN -->
        <div class="lg:col-span-8 space-y-4 w-full min-w-0">

          <div class="grid grid-cols-3 gap-3">
            <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-3.5 border border-slate-200/60 dark:border-[#3F4F43] shadow-sm flex flex-col justify-between">
              <span class="text-[9px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Ready</span>
              <span class="text-xl font-black text-[#005506] dark:text-[#86EFAC] mt-1">{{ stats.published }}</span>
            </div>
            <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-3.5 border border-slate-200/60 dark:border-[#3F4F43] shadow-sm flex flex-col justify-between">
              <span class="text-[9px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">In Draft</span>
              <span class="text-xl font-black text-slate-700 dark:text-slate-200 mt-1">{{ stats.draft }}</span>
            </div>
            <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-3.5 border border-slate-200/60 dark:border-[#3F4F43] shadow-sm flex flex-col justify-between">
              <span class="text-[9px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Attempts</span>
              <span class="text-xl font-black text-blue-700 dark:text-blue-400 mt-1">{{ stats.total_attempts }}</span>
            </div>
          </div>

          <div v-if="filteredQuizzes.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="(q, idx) in filteredQuizzes" :key="q.id"
              v-observe
              :style="{ animationDelay: `${idx * 40}ms` }"
              class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3.5 hover:-translate-y-1 hover:shadow-md transition-all">

              <div class="space-y-3">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                  <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 inline-flex items-center gap-1 truncate max-w-[70%]">
                    <Icon icon="academic-cap" size="xs" />
                    {{ q.subject }} • {{ q.section }}
                  </span>
                  <span :class="q.is_published
                    ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC]'
                    : 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400'"
                    class="text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider shrink-0">
                    {{ q.is_published ? 'Published' : 'Draft' }}
                  </span>
                </div>

                <h4 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white leading-snug line-clamp-2">
                  {{ q.title }}
                </h4>

                <p v-if="q.description" class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 font-medium">
                  {{ q.description }}
                </p>

                <div class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] grid grid-cols-3 gap-2 text-center">
                  <div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase block">Questions</span>
                    <span class="text-xs font-black text-slate-800 dark:text-slate-200">{{ q.questions_count }}</span>
                  </div>
                  <div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase block">Points</span>
                    <span class="text-xs font-black text-slate-800 dark:text-slate-200">{{ q.total_points }}</span>
                  </div>
                  <div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase block">Attempts</span>
                    <span class="text-xs font-black text-slate-800 dark:text-slate-200">{{ q.attempts_count }}</span>
                  </div>
                </div>

                <div v-if="q.available_until || q.time_limit_minutes" class="flex flex-wrap gap-2 text-[10px] font-bold text-slate-500 dark:text-slate-400">
                  <span v-if="q.time_limit_minutes" class="inline-flex items-center gap-1">
                    <Icon icon="clock" size="xs" />
                    {{ q.time_limit_minutes }} min
                  </span>
                  <span v-if="q.available_until" class="inline-flex items-center gap-1">
                    <Icon icon="calendar" size="xs" />
                    Closes {{ formatShort(q.available_until) }}
                  </span>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                <Link :href="route('teacher.quizzes.show', q.id)"
                  class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-[11px] font-black py-2 rounded-xl shadow-sm flex items-center justify-center gap-1.5 transition-all">
                  <Icon icon="edit" size="xs" />
                  Manage
                </Link>
                <Link :href="route('teacher.quizzes.submissions.index', q.id)"
                  class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] font-bold py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
                  <Icon icon="chart-bar" size="xs" />
                  Submissions
                </Link>
              </div>
            </div>
          </div>

          <div v-else
            class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-12 shadow-sm border border-dashed border-slate-300 dark:border-[#3F4F43] text-center space-y-3">
            <Icon icon="clipboard-list" size="xl" class="text-slate-400 mx-auto" />
            <p class="text-sm font-bold text-slate-800 dark:text-white">
              {{ quizzes.length === 0 ? 'No quizzes yet' : 'No quizzes match your filters' }}
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              {{ quizzes.length === 0 ? 'Create your first quiz to get started.' : 'Try clearing the filters.' }}
            </p>
          </div>

          <!-- Recent Attempts feed -->
          <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4" style="animation-delay: 350ms;">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
              <div class="w-2.5 h-7 bg-blue-500 rounded-full"></div>
              <div class="flex-1 min-w-0">
                <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">Recent Student Attempts</h3>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5">Latest submissions across all your quizzes</p>
              </div>
              <span class="bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-[10px] font-black px-2.5 py-1 rounded-full border border-blue-200 dark:border-blue-900/40 shrink-0">
                {{ recent_attempts.length }}
              </span>
            </div>

            <div v-if="recent_attempts.length" class="space-y-2">
              <Link v-for="a in recent_attempts" :key="a.id"
                :href="route('teacher.quiz-attempts.show', a.id)"
                class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-3 hover:border-[#005506] dark:hover:border-[#86EFAC] hover:-translate-x-1 transition-all">

                <div class="flex items-center gap-3 min-w-0">
                  <div class="w-9 h-9 rounded-full bg-[#005506] text-white font-extrabold text-[11px] flex items-center justify-center shrink-0">
                    {{ initialsOf(a.student_name) }}
                  </div>
                  <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ a.student_name }}</p>
                    <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 truncate">
                      {{ a.quiz_title }} <span v-if="a.subject">• {{ a.subject }}</span>
                    </p>
                  </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                  <div class="text-right">
                    <span class="text-xs font-black text-slate-900 dark:text-white block">
                      {{ a.score !== null ? a.score : '—' }}<span v-if="a.total_points" class="text-slate-400 font-normal"> / {{ a.total_points }}</span>
                    </span>
                    <span class="text-[10px] font-semibold text-slate-400 block">{{ a.submitted_human }}</span>
                  </div>
                  <Icon icon="chevron-right" size="xs" class="text-slate-400" />
                </div>
              </Link>
            </div>

            <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-8 text-center border border-dashed border-slate-300 dark:border-[#3F4F43]">
              <Icon icon="users" size="lg" class="text-slate-400 mx-auto mb-2" />
              <p class="text-xs text-slate-500 dark:text-slate-400 italic">No student attempts yet.</p>
            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="lg:col-span-4 w-full space-y-4 sm:space-y-5">

          <!-- Needs Attention -->
          <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4 sticky top-4"
            style="animation-delay: 380ms;">

            <!-- Needs Attention section -->
            <div class="space-y-3 pb-4 border-b border-slate-100 dark:border-[#3F4F43]">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <Icon icon="alert-triangle" size="md" :class="needsAttention.length ? 'text-rose-600 dark:text-rose-400' : 'text-slate-400'" />
                  <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Needs Attention</h3>
                </div>
                <span :class="needsAttention.length
                  ? 'bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900/40'
                  : 'bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40'"
                  class="text-[10px] font-black px-2.5 py-1 rounded-full border">
                  {{ needsAttention.length }}
                </span>
              </div>

              <div v-if="needsAttention.length" class="space-y-2">
                <Link v-for="item in needsAttention.slice(0, 4)" :key="item.id + item.type"
                  :href="route('teacher.quizzes.show', item.id)"
                  class="bg-rose-50/60 dark:bg-rose-950/30 p-2.5 rounded-xl border border-rose-200/60 dark:border-rose-900/40 flex items-start gap-2.5 hover:border-rose-400 dark:hover:border-rose-700 transition-colors">
                  <div class="w-6 h-6 rounded-lg bg-rose-100 dark:bg-rose-900/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 mt-0.5">
                    <Icon icon="alert-circle" size="xs" />
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-bold text-slate-900 dark:text-white truncate">{{ item.title }}</p>
                    <p class="text-[10px] font-semibold text-rose-700 dark:text-rose-400 truncate">{{ item.label }}</p>
                  </div>
                </Link>
                <p v-if="needsAttention.length > 4" class="text-[10px] font-bold text-slate-500 dark:text-slate-400 text-center pt-1">
                  + {{ needsAttention.length - 4 }} more
                </p>
              </div>

              <div v-else class="bg-emerald-50/60 dark:bg-emerald-950/30 rounded-xl p-3 text-center border border-emerald-200/60 dark:border-emerald-900/40">
                <p class="text-[11px] font-bold text-[#005506] dark:text-[#86EFAC]">✓ All caught up</p>
              </div>
            </div>

            <!-- Closing Soon section -->
            <div class="space-y-3 pb-4 border-b border-slate-100 dark:border-[#3F4F43]">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <Icon icon="clock" size="md" :class="closingSoon.length ? 'text-amber-600 dark:text-amber-400' : 'text-slate-400'" />
                  <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Closing Soon</h3>
                </div>
                <span :class="closingSoon.length
                  ? 'bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40'
                  : 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]'"
                  class="text-[10px] font-black px-2.5 py-1 rounded-full border">
                  {{ closingSoon.length }}
                </span>
              </div>

              <div v-if="closingSoon.length" class="space-y-2">
                <Link v-for="item in closingSoon.slice(0, 3)" :key="item.id"
                  :href="route('teacher.quizzes.show', item.id)"
                  class="bg-amber-50/60 dark:bg-amber-950/20 p-2.5 rounded-xl border border-amber-200/60 dark:border-amber-900/40 flex items-start gap-2.5 hover:border-amber-400 transition-colors">
                  <div class="w-6 h-6 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0 mt-0.5">
                    <Icon icon="clock" size="xs" />
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-bold text-slate-900 dark:text-white truncate">{{ item.title }}</p>
                    <p class="text-[10px] font-semibold text-amber-800 dark:text-amber-400 truncate">
                      Closes {{ relativeClose(item.available_until) }}
                    </p>
                  </div>
                </Link>
                <p v-if="closingSoon.length > 3" class="text-[10px] font-bold text-slate-500 dark:text-slate-400 text-center pt-1">
                  + {{ closingSoon.length - 3 }} more
                </p>
              </div>

              <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-xl p-3 text-center border border-dashed border-slate-300 dark:border-[#3F4F43]">
                <p class="text-[11px] text-slate-500 dark:text-slate-400 italic">Nothing closing in 7 days.</p>
              </div>
            </div>

            <!-- Question Bank section -->
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <Icon icon="book-open" size="md" class="text-[#005506] dark:text-[#86EFAC]" />
                  <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Question Bank</h3>
                </div>
                <span class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-1 rounded-full border border-emerald-200 dark:border-emerald-900/40">
                  {{ questionStats.total }}
                </span>
              </div>

              <div v-if="Object.keys(questionStats.by_type).length" class="space-y-1.5">
                <div v-for="(count, type) in questionStats.by_type" :key="type"
                  class="flex items-center justify-between bg-[#F9F7F1] dark:bg-[#232D26] px-3 py-2 rounded-lg border border-slate-200/80 dark:border-[#3F4F43]">
                  <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200 truncate">
                    {{ typeLabel(type) }}
                  </span>
                  <span class="text-[11px] font-black text-slate-900 dark:text-white shrink-0 ml-2">{{ count }}</span>
                </div>
              </div>
              <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-xl p-3 text-center border border-dashed border-slate-300 dark:border-[#3F4F43]">
                <p class="text-[11px] text-slate-500 dark:text-slate-400 italic">Bank is empty.</p>
              </div>

              <Link :href="route('teacher.questions.index')"
                class="w-full bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black py-2.5 rounded-xl shadow-sm flex items-center justify-center gap-1.5 transition-all active:scale-95">
                <Icon icon="arrow-right" size="xs" />
                Open Question Bank
              </Link>
            </div>

          </div>
        </div>

      </div>
    </div>

    <!-- Teleported class picker -->
    <Teleport to="body">
      <div v-if="showClassPicker"
        class="fixed z-[100] w-72 bg-white dark:bg-[#2D3A31] rounded-2xl shadow-xl border border-slate-200 dark:border-[#3F4F43] overflow-hidden"
        :style="{ top: pickerPos.top + 'px', left: pickerPos.left + 'px' }">
        <div class="px-4 py-2.5 border-b border-slate-100 dark:border-[#3F4F43] bg-slate-50 dark:bg-[#232D26]">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Pick a Class</span>
        </div>
        <div class="max-h-72 overflow-y-auto">
          <Link v-for="c in classrooms" :key="c.id"
            :href="route('teacher.classes.quizzes.index', c.id)"
            @click="showClassPicker = false"
            class="block px-4 py-3 border-b border-slate-100 dark:border-[#3F4F43] last:border-b-0 hover:bg-slate-50 dark:hover:bg-[#3F4F43] transition-colors">
            <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">{{ c.subject }}</p>
            <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">{{ c.section }} • {{ c.quizzes_count }} quizzes</p>
          </Link>
          <div v-if="!classrooms.length" class="px-4 py-6 text-center text-[11px] text-slate-400 italic">
            No classes assigned.
          </div>
        </div>
      </div>
      <div v-if="showClassPicker" @click="showClassPicker = false" class="fixed inset-0 z-[99] bg-transparent"></div>
    </Teleport>

    <!-- CREATE QUIZ MODAL -->
<div v-if="showCreateQuiz"
  class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-start justify-center p-4 overflow-y-auto"
  @click.self="closeCreateQuiz">
  <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-2xl w-full max-w-2xl p-6 space-y-5 border border-slate-100 dark:border-[#3F4F43] my-8">

    <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
      <div>
        <h3 class="font-black text-slate-900 dark:text-white text-lg">Create New Quiz</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-0.5">
          Pick a class, set the parameters, then add questions after creation.
        </p>
      </div>
      <button @click="closeCreateQuiz"
        class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors shrink-0">
        <Icon icon="x" size="xs" />
      </button>
    </div>

    <form @submit.prevent="submitCreateQuiz" class="space-y-4">
      <div v-if="createError" class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl text-rose-700 dark:text-rose-300 text-xs">
        {{ createError }}
      </div>

      <div class="space-y-1">
        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Class *</label>
        <select v-model="createClassId"
          class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer">
          <option value="">— Select class —</option>
          <option v-for="c in classrooms" :key="c.id" :value="c.id">
            {{ c.subject }} — {{ c.section }}
          </option>
        </select>
      </div>

      <div class="space-y-1">
        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Quiz Title *</label>
        <input v-model="createForm.title" type="text" placeholder="e.g. Chapter 1 Concept Check"
          class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="space-y-1">
          <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Category *</label>
          <select v-model="createForm.category"
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer">
            <option value="written_work">Written Work</option>
            <option value="performance_task">Performance Task</option>
            <option value="quarterly_exam">Quarterly Exam</option>
          </select>
        </div>

        <div class="space-y-1">
          <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Time limit (min)</label>
          <input v-model.number="createForm.time_limit_minutes" type="number" min="0" placeholder="Optional"
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
        </div>
      </div>

      <div class="space-y-1">
        <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Description</label>
        <textarea v-model="createForm.description" rows="2"
          class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-3 text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] resize-none"></textarea>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="space-y-1">
          <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Passing score (%)</label>
          <input v-model.number="createForm.passing_score" type="number" min="0" max="100" placeholder="e.g. 75"
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
        </div>
        <div class="space-y-1">
          <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Opens</label>
          <input v-model="createForm.available_from" type="datetime-local"
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
        </div>
        <div class="space-y-1">
          <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Closes</label>
          <input v-model="createForm.available_until" type="datetime-local"
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
        </div>
      </div>

      <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-[#3F4F43]">
        <button type="button" @click="closeCreateQuiz"
          class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white text-xs font-bold transition-colors">
          Cancel
        </button>
        <button type="submit" :disabled="createForm.processing || !createClassId"
          class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] px-6 py-2.5 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2">
          <Icon icon="plus" size="xs" />
          {{ createForm.processing ? 'Creating…' : 'Create Quiz' }}
        </button>
      </div>
    </form>
  </div>
</div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onBeforeUnmount } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/quiz-hub-hero.jpg'
import { useForm } from '@inertiajs/vue3'

const showCreateQuiz = ref(false)
const createClassId = ref('')
const createError = ref('')

const createForm = useForm({
  title: '',
  category: 'written_work',
  description: '',
  instructions: '',
  time_limit_minutes: null,
  passing_score: null,
  available_from: '',
  available_until: '',
  shuffle_questions: false,
  shuffle_options: false,
  show_score_immediately: true,
  show_correct_answers: true,
  show_explanations: true,
})

function openCreateQuiz() {
  createForm.reset()
  createForm.clearErrors()
  createClassId.value = ''
  createError.value = ''
  showCreateQuiz.value = true
}

function closeCreateQuiz() {
  showCreateQuiz.value = false
  createError.value = ''
}

function submitCreateQuiz() {
  if (!createClassId.value) {
    createError.value = 'Please select a class.'
    return
  }
  createError.value = ''
  createForm.post(route('teacher.classes.quizzes.store', createClassId.value), {
    onSuccess: () => {
      showCreateQuiz.value = false
      createForm.reset()
      createClassId.value = ''
    },
    onError: () => {
      createError.value = 'Please review the form and try again.'
    },
  })
}

const props = defineProps({
  quizzes:         { type: Array,  default: () => [] },
  classrooms:      { type: Array,  default: () => [] },
  questionStats:   { type: Object, default: () => ({ total: 0, by_type: {} }) },
  stats:           { type: Object, default: () => ({ total: 0, published: 0, draft: 0, total_attempts: 0 }) },
  recent_attempts: { type: Array,  default: () => [] },
  active_term:     { type: String, default: null },
})

/* ─── Filters ─────────────────────────────────────────── */
const classFilter  = ref('all')
const statusFilter = ref('all')
const search       = ref('')

const statusFilters = computed(() => [
  { id: 'all',       label: 'All',       count: props.quizzes.length },
  { id: 'published', label: 'Published', count: props.quizzes.filter(q => q.is_published).length },
  { id: 'draft',     label: 'Drafts',    count: props.quizzes.filter(q => !q.is_published).length },
].filter(f => f.id === 'all' || f.count > 0))

const filteredQuizzes = computed(() => {
  const q = search.value.trim().toLowerCase()
  return props.quizzes.filter(quiz => {
    if (classFilter.value !== 'all' && quiz.classroom_id !== classFilter.value) return false
    if (statusFilter.value === 'published' && !quiz.is_published) return false
    if (statusFilter.value === 'draft' && quiz.is_published) return false
    if (q && !(
      (quiz.title || '').toLowerCase().includes(q) ||
      (quiz.description || '').toLowerCase().includes(q)
    )) return false
    return true
  })
})

/* ─── Needs Attention (derived) ───────────────────────── */
const needsAttention = computed(() => {
  const now = Date.now()
  const out = []

  props.quizzes.forEach(q => {
    // 1. No questions attached
    if (q.questions_count === 0) {
      out.push({
        id: q.id,
        title: q.title,
        type: 'no_questions',
        label: q.is_published ? 'Published with no questions' : 'No questions attached',
      })
      return
    }

    // 2. Published but availability window has passed
    if (q.is_published && q.available_until) {
      const t = new Date(q.available_until).getTime()
      if (t < now) {
        out.push({
          id: q.id,
          title: q.title,
          type: 'past_due',
          label: 'Past due but still published',
        })
        return
      }
    }

    // 3. Stale draft — older than 7 days
    if (!q.is_published && q.created_at) {
      const age = now - new Date(q.created_at).getTime()
      if (age > 7 * 86400000) {
        out.push({
          id: q.id,
          title: q.title,
          type: 'stale_draft',
          label: 'Draft over 7 days old',
        })
      }
    }
  })

  return out
})

/* ─── Closing Soon (derived) ──────────────────────────── */
const closingSoon = computed(() => {
  const now = Date.now()
  const week = 7 * 86400000
  return props.quizzes
    .filter(q => q.is_published && q.available_until)
    .filter(q => {
      const t = new Date(q.available_until).getTime()
      return t > now && t < now + week
    })
    .sort((a, b) => new Date(a.available_until) - new Date(b.available_until))
})

function relativeClose(iso) {
  const t = new Date(iso).getTime()
  const diff = t - Date.now()
  if (diff <= 0) return 'now'
  const days = Math.floor(diff / 86400000)
  if (days >= 1) return `in ${days}d`
  const hours = Math.floor(diff / 3600000)
  if (hours >= 1) return `in ${hours}h`
  const minutes = Math.floor(diff / 60000)
  return `in ${minutes}m`
}

function onWindowChange() {
  if (showClassPicker.value) recomputePos()
}

/* ─── Helpers ─────────────────────────────────────────── */
function typeLabel(type) {
  return {
    multiple_choice: 'Multiple Choice',
    true_false:      'True / False',
    short_answer:    'Short Answer',
    essay:           'Essay',
  }[type] || type
}

function formatShort(v) {
  if (!v) return '—'
  try {
    return new Date(v).toLocaleDateString('en-US', {
      month: 'short', day: 'numeric',
      hour: 'numeric', minute: '2-digit', hour12: true,
    })
  } catch { return '—' }
}

function initialsOf(name) {
  if (!name) return '?'
  const parts = String(name).trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
}

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
@keyframes pulse-opacity { 0%, 100% { opacity: 0.88; } 50% { opacity: 0.65; } }
.animate-overlay { animation: pulse-opacity 6s infinite ease-in-out; }

@keyframes slideUpFade { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }

.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up { animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
</style>