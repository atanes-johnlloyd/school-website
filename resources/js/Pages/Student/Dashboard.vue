<template>
  <Head title="Student Dashboard - Salawag LMS" />

  <div
    :class="[
      'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
      `text-scale-${fontSizeMode}`
    ]"
  >
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search subjects, assignments, quiz hub tasks.."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size"
      />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- HERO -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <img :src="heroImage" alt="Student Dashboard Hero"
              class="w-full h-full object-cover object-center opacity-25 dark:opacity-15 mix-blend-overlay scale-105 transition-transform duration-700 hover:scale-100" />
            <div class="absolute inset-0 bg-gradient-to-r from-[#004d08]/90 via-[#004d08]/70 to-transparent dark:from-[#152B1C]/95 dark:via-[#152B1C]/80"></div>
          </div>
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none z-0"></div>

          <div class="space-y-1 relative z-10">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
              <span class="text-white">STUDENT</span>
              <span class="animated-stroke-text">DASHBOARD</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              "Your journey to knowledge starts with one click."
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs animate-spin-slow">★</span>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-1 relative z-10">
            <div class="lg:col-span-6 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center transition-transform hover:scale-105 duration-300">
                <img v-if="student?.avatar_url" :src="student.avatar_url" alt="Student Avatar" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full bg-emerald-800 text-white font-bold flex items-center justify-center text-2xl uppercase">
                  {{ studentInitials }}
                </div>
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10 animate-float-soft">
                  {{ active_term || 'Active Term' }}
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  Good Day! {{ student?.name || 'Student' }}
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  LRN: {{ student?.lrn || '—' }} •
                  {{ student?.grade_level ? `Grade ${student.grade_level}` : '' }}
                  {{ student?.section ? `- ${student.section}` : '' }}
                </p>
              </div>
            </div>

            <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-3 gap-3.5">
              <div class="stat-card bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-4 px-4 min-h-[105px] flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                <span class="text-[11px] font-medium text-emerald-100/80 block">Enrolled Classes</span>
                <div class="text-2xl font-extrabold text-white leading-none my-1">{{ stats?.classes ?? 0 }}</div>
                <span class="text-[10px] text-emerald-100/70 block font-medium">{{ active_term || '—' }}</span>
              </div>
              <div class="stat-card bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-4 px-4 min-h-[105px] flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                <span class="text-[11px] font-medium text-emerald-100/80 block">Graded Work</span>
                <div class="text-2xl font-extrabold text-white leading-none my-1">{{ stats?.grades_available ?? 0 }}</div>
                <span class="text-[10px] text-emerald-100/70 block font-medium">Results released</span>
              </div>
              <div class="stat-card bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-4 px-4 min-h-[105px] flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                <span class="text-[11px] font-medium text-emerald-100/80 block">Pending Tasks</span>
                <div class="flex items-center gap-2 my-1">
                  <span class="text-2xl font-extrabold text-white leading-none">{{ stats?.pending_assignments ?? 0 }}</span>
                  <span v-if="urgentCount > 0" class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider animate-pulse">
                    {{ urgentCount }} Due Soon
                  </span>
                </div>
                <span class="text-[10px] text-emerald-100/70 block font-medium">Across all classes</span>
              </div>
            </div>
          </div>
        </div>

        <!-- MAIN BENTO GRID -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

          <!-- LEFT COLUMN -->
          <div class="xl:col-span-8 space-y-6 animate-fade-slide-left">

            <!-- ENROLLED CLASSES -->
            <div class="bento-card rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm space-y-5 hover:shadow-md transition-all duration-300">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-3">
                  <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
                  <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-none">Enrolled Classes</h3>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                      {{ classList.length }} {{ classList.length === 1 ? 'class' : 'classes' }} this term
                    </p>
                  </div>
                </div>
                <Link
                  :href="route('student.classes.index')"
                  class="bg-[#f0f4ee] dark:bg-[#232D26] text-[#004d08] dark:text-[#86EFAC] text-xs font-bold px-3.5 py-1.5 rounded-full border border-[#e1e8dd] dark:border-[#3F4F43] hover:bg-[#e4ede1] transition-colors self-start sm:self-auto flex items-center gap-1"
                >
                  <span>View all</span>
                  <Icon icon="arrow-right" size="xs" />
                </Link>
              </div>

              <div v-if="classList.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pt-1">
                <Link
                  v-for="klass in classList.slice(0, 6)"
                  :key="klass.id"
                  :href="route('student.classes.show', klass.id)"
                  class="relative bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/70 dark:border-[#3F4F43] shadow-sm flex flex-col justify-between space-y-3 hover:-translate-y-1 hover:shadow-md transition-all duration-200 group"
                >
                  <div class="flex items-center justify-between gap-1">
                    <span class="bg-white dark:bg-[#2D3A31] text-slate-800 dark:text-slate-200 font-extrabold text-[10px] px-2 py-1 rounded-md border border-slate-200 dark:border-[#3F4F43] uppercase tracking-wide">
                      {{ klass.subject_code || 'SUBJECT' }}
                    </span>
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-base leading-tight group-hover:text-[#004d08] dark:group-hover:text-[#86EFAC] transition-colors">
                      {{ klass.subject || 'Untitled Subject' }}
                    </h4>
                    <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mt-1">
                      {{ klass.teacher || 'TBA' }} • {{ klass.section || '—' }}
                    </p>
                  </div>
                  <div class="text-[11px] font-bold text-[#004d08] dark:text-[#86EFAC] flex items-center justify-between">
                    <span>Open class</span>
                    <Icon icon="arrow-right" size="xs" class="group-hover:translate-x-1 transition-transform" />
                  </div>
                </Link>
              </div>

              <div v-else class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-8 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
                <div class="flex justify-center text-[#004d08] dark:text-[#86EFAC]">
                  <Icon icon="book-open" size="xl" />
                </div>
                <p class="text-sm font-bold text-slate-800 dark:text-white">No classes enrolled yet</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Your enrolled subjects will appear here once the registrar assigns them.</p>
              </div>
            </div>

            <!-- UPCOMING DEADLINES -->
            <div class="bento-card rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6 hover:shadow-md transition-all duration-300">
              <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-2 border-b border-slate-200/60 dark:border-[#3F4F43]">
                <div class="flex items-center gap-3">
                  <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
                  <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-none">Upcoming Deadlines</h3>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">Next 7 days</p>
                  </div>
                </div>
              </div>

              <div v-if="upcoming_deadlines && upcoming_deadlines.length" class="space-y-3">
                <div
                  v-for="(task, index) in upcoming_deadlines"
                  :key="task.id"
                  :style="{ animationDelay: `${index * 60}ms` }"
                  :class="[
                    'task-row group bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 sm:p-5 border transition-all duration-200 hover:-translate-x-1 hover:shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4',
                    task.days_left <= 0 ? 'border-red-300/80 dark:border-red-500/50 bg-red-50/20' : 'border-slate-200/70 dark:border-[#3F4F43] hover:border-slate-300'
                  ]"
                >
                  <div class="flex items-start gap-4">
                    <div :class="[
                        'w-11 h-11 rounded-2xl border flex items-center justify-center shrink-0 shadow-sm',
                        task.type === 'quiz'
                          ? 'bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900/40'
                          : 'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40'
                      ]">
                      <Icon :icon="task.type === 'quiz' ? 'clipboard-list' : 'document-text'" size="lg" />
                    </div>
                    <div class="space-y-1">
                      <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-[#004d08] dark:text-[#86EFAC]">{{ task.subject || 'Subject' }}</span>
                        <span v-if="task.type === 'quiz'" class="text-[9px] font-black uppercase bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 px-1.5 py-0.5 rounded">
                          Quiz
                        </span>
                    </div>
                  </div>
                  </div>

                  <div class="flex items-center justify-between md:justify-end gap-3.5 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-slate-200/60 dark:border-[#3F4F43]">
                    <div class="flex items-center gap-2.5">
                      <div v-if="task.days_left <= 0" class="inline-flex items-center gap-1 bg-red-600 text-white text-[10px] font-extrabold px-2.5 py-1.5 rounded-md uppercase tracking-wider leading-none">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping shrink-0"></span>
                        <span>DUE</span>
                      </div>
                      <div v-else-if="task.days_left <= 2" class="inline-flex items-center bg-amber-100 text-amber-800 text-[11px] font-bold px-2.5 py-1.5 rounded-md leading-none border border-amber-200/80">
                        {{ task.days_left }} {{ task.days_left === 1 ? 'day' : 'days' }} left
                      </div>

                      <div class="text-left md:text-right leading-tight">
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">{{ task.due_human }}</span>
                        <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 block mt-0.5">Weight: {{ task.points }} pts</span>
                      </div>
                    </div>

                    <Link
                      :href="task.show_url"
                      class="px-4 py-2.5 rounded-xl ..."
                    >
                      <span>Open</span>
                      <Icon icon="arrow-right" size="xs" />
                    </Link>
                  </div>
                </div>
              </div>

              <div v-else class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-8 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
                <div class="flex justify-center text-emerald-500">
                  <Icon icon="sparkles" size="xl" />
                </div>
                <p class="text-sm font-bold text-slate-800 dark:text-white">All clear!</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">No pending assignments in the next 7 days.</p>
              </div>
            </div>

          </div>

          <!-- RIGHT COLUMN -->
          <div class="xl:col-span-4 space-y-6 animate-fade-slide-right">

            <!-- ANNOUNCEMENTS -->
            <div class="bento-card rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-6 shadow-sm space-y-4 hover:shadow-md transition-all duration-300">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                  <div class="w-2.5 h-6 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
                  <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Announcements</h3>
                </div>
                <Link :href="route('student.announcements.feed')" class="bg-[#f0f4ee] dark:bg-[#232D26] text-[#004d08] dark:text-[#86EFAC] text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-[#e1e8dd] dark:border-[#3F4F43] hover:bg-[#e4ede1] transition-colors">
                  View all
                </Link>
              </div>

              <div v-if="recent_announcements && recent_announcements.length" class="space-y-3">
                <div
                  v-for="a in recent_announcements"
                  :key="a.id"
                  class="relative bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 pl-6 border border-slate-200/70 dark:border-[#3F4F43] shadow-sm hover:translate-x-1 transition-all duration-200"
                >
                  <div :class="['absolute left-0 top-0 bottom-0 w-2 rounded-l-2xl', a.is_pinned ? 'bg-amber-400' : 'bg-[#004d08] dark:bg-[#86EFAC]']"></div>
                  <div class="flex items-center justify-between gap-2 mb-1">
                    <span v-if="a.is_pinned" class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[9px] font-black uppercase px-2 py-0.5 rounded-md inline-flex items-center gap-1">
                      <Icon icon="star" size="xs" />
                      <span>Pinned</span>
                    </span>
                    <span v-else class="text-[10px] font-bold text-slate-500 dark:text-slate-400">{{ a.subject || 'General' }}</span>
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">{{ a.published_human }}</span>
                  </div>
                  <h4 class="font-bold text-slate-900 dark:text-white text-sm leading-snug">{{ a.title }}</h4>
                  <p class="text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed mt-1">{{ a.body_preview }}</p>
                </div>
              </div>

              <div v-else class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-6 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-1">
                <div class="flex justify-center text-slate-400">
                  <Icon icon="megaphone" size="lg" />
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">No announcements yet.</p>
              </div>
            </div>

            <!-- TERM SNAPSHOT -->
            <div class="bento-card rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-6 shadow-sm space-y-4 hover:shadow-md transition-all duration-300">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                  <div class="w-2.5 h-6 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
                  <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Term Snapshot</h3>
                </div>
                <span class="bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] text-[10px] font-extrabold px-2.5 py-0.5 rounded-full">
                  {{ active_term || '—' }}
                </span>
              </div>

              <div class="grid grid-cols-3 gap-2">
                <div class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-3 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
                  <div class="text-2xl font-black text-[#004d08] dark:text-[#86EFAC]">{{ stats?.classes ?? 0 }}</div>
                  <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase">Classes</span>
                </div>
                <div class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-3 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
                  <div class="text-2xl font-black text-amber-700 dark:text-amber-400">{{ stats?.pending_assignments ?? 0 }}</div>
                  <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase">Pending</span>
                </div>
                <div class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-3 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
                  <div class="text-2xl font-black text-[#004d08] dark:text-[#86EFAC]">{{ stats?.grades_available ?? 0 }}</div>
                  <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase">Graded</span>
                </div>
              </div>

              <div class="text-center text-[11px] font-semibold text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
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

const props = defineProps({
  student: { type: Object, default: () => ({}) },
  classes: { type: Object, default: () => ({ data: [] }) },
  stats: { type: Object, default: () => ({}) },
  upcoming_deadlines: { type: Array, default: () => [] },
  recent_announcements: { type: Array, default: () => [] },
  active_term: { type: String, default: null },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')

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
</script>

<style scoped>
.animated-stroke-text {
  color: transparent;
  -webkit-text-stroke: 1.5px #ffffff;
}

@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-20px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeSlideLeft {
  from { opacity: 0; transform: translateX(-30px); }
  to   { opacity: 1; transform: translateX(0); }
}
@keyframes fadeSlideRight {
  from { opacity: 0; transform: translateX(30px); }
  to   { opacity: 1; transform: translateX(0); }
}
@keyframes floatSoft {
  0%, 100% { transform: translateY(0); }
  50%      { transform: translateY(-4px); }
}
@keyframes sheenMove {
  0%   { transform: translateX(-100%); }
  100% { transform: translateX(200%); }
}
@keyframes spinSlow {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}

.animate-fade-in-down    { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-left { animation: fadeSlideLeft 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
.animate-fade-slide-right{ animation: fadeSlideRight 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both; }
.animate-float-soft      { animation: floatSoft 3s ease-in-out infinite; }
.animate-sheen           { animation: sheenMove 4s ease-in-out infinite; }
.animate-spin-slow       { display: inline-block; animation: spinSlow 12s linear infinite; }

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
</style>