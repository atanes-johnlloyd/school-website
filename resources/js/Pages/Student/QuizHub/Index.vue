<template>
  <Head title="QuizHub - Salawag LMS" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search quizzes, subjects, or challenge tasks..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size"
      />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- HERO -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="space-y-1 relative z-10">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
              <span class="text-white">SALAWAG</span>
              <span class="animated-stroke-text">QUIZHUB</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              "Master your domain through proctored evaluations and interactive problem-solving."
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs animate-spin-slow">★</span>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2 relative z-10">
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 flex items-center justify-center shrink-0">
                <Icon icon="sparkles" size="xl" class="text-white" />
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                  {{ active_term || 'Active Term' }}
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  Active & Scheduled Assessments
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  {{ counts.all }} {{ counts.all === 1 ? 'quiz' : 'quizzes' }} across your classes
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-2 gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80">Ready to Take</span>
                <div class="text-xl font-extrabold text-amber-300 my-0.5">{{ counts.available }}</div>
                <span class="text-[10px] text-emerald-100/70">Available now</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80">Completed</span>
                <div class="text-xl font-extrabold text-white my-0.5">{{ counts.completed }}</div>
                <span class="text-[10px] text-emerald-100/70">Ready to review</span>
              </div>
            </div>
          </div>
        </div>

        <!-- WORKSPACE -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

          <!-- TABS + SEARCH -->
          <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="flex items-center gap-1.5 bg-[#f5f7f2] dark:bg-[#232D26] p-1.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] overflow-x-auto w-full sm:w-auto">
              <button v-for="tab in filterTabs" :key="tab.id" @click="activeTab = tab.id"
                :class="[
                  'px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all shrink-0 flex items-center gap-1.5 cursor-pointer active:scale-95',
                  activeTab === tab.id
                    ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-xs'
                    : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'
                ]">
                <span>{{ tab.label }}</span>
                <span :class="[
                  'text-[10px] px-1.5 py-0.2 rounded-full font-black transition-colors',
                  activeTab === tab.id ? 'bg-white/20 text-white dark:text-[#232D26]' : 'bg-slate-200 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300'
                ]">{{ tab.count }}</span>
              </button>
            </div>

            <div class="relative w-full sm:w-72">
              <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 dark:text-slate-500">
                <Icon icon="search" size="sm" />
              </span>
              <input v-model="searchQuery" type="text" placeholder="Search quiz topic or subject..."
                class="w-full bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl pl-8 pr-8 py-2 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC]" />
              <button v-if="searchQuery" @click="searchQuery = ''"
                class="absolute inset-y-0 right-2.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs font-bold">
                <Icon icon="x" size="xs" />
              </button>
            </div>
          </div>

          <!-- QUIZ CARDS -->
          <div v-if="filteredQuizzes.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <div v-for="quiz in filteredQuizzes" :key="quiz.id"
              class="bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-3xl p-5 hover:border-slate-300 dark:hover:border-emerald-500/50 transition-all duration-300 flex flex-col justify-between space-y-4 group relative hover:-translate-y-1 hover:shadow-md">

              <div class="space-y-3">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                  <span class="text-[10px] font-extrabold uppercase text-[#004d08] dark:text-[#86EFAC] bg-white dark:bg-[#2D3A31] px-2.5 py-0.5 rounded-md border border-slate-200 dark:border-[#3F4F43]">
                    {{ quiz.subject || 'Subject' }}
                  </span>
                  <span :class="statusPillClass(quiz.status)">
                    {{ statusLabel(quiz.status) }}
                  </span>
                </div>

                <div>
                  <h3 class="font-extrabold text-slate-900 dark:text-white text-base leading-snug group-hover:text-[#004d08] dark:group-hover:text-[#86EFAC] transition-colors line-clamp-2">
                    {{ quiz.title }}
                  </h3>
                  <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-1">
                    {{ quiz.description || 'No description provided.' }}
                  </p>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-white dark:bg-[#2D3A31] p-3 rounded-2xl border border-slate-200/60 dark:border-[#3F4F43]">
                  <div class="inline-flex items-center gap-1.5">
                    <Icon icon="clock" size="xs" class="text-slate-400" />
                    {{ quiz.time_limit_minutes ? quiz.time_limit_minutes + ' min' : 'No limit' }}
                  </div>
                  <div class="inline-flex items-center gap-1.5">
                    <Icon icon="clipboard-list" size="xs" class="text-slate-400" />
                    {{ quiz.questions_count }} items
                  </div>
                  <div class="inline-flex items-center gap-1.5">
                    <Icon icon="award" size="xs" class="text-amber-500" />
                    {{ quiz.total_points }} pts
                  </div>
                  <div class="inline-flex items-center gap-1.5">
                    <Icon icon="target" size="xs" class="text-slate-400" />
                    Pass: {{ quiz.passing_score ?? '—' }}%
                  </div>
                </div>

                <p v-if="quiz.availability_note" class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold inline-flex items-center gap-1">
                  <Icon icon="info" size="xs" />
                  {{ quiz.availability_note }}
                </p>
              </div>

              <div>
                <!-- Available -->
                <button v-if="quiz.status === 'available'"
                  @click="startQuizPrep(quiz)"
                  class="w-full bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-xs font-black py-3 rounded-2xl transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                  <Icon icon="arrow-right" size="sm" />
                  Start Assessment
                </button>

                <!-- In progress -->
                <Link v-else-if="quiz.status === 'in_progress' && quiz.my_attempt"
                  :href="route('student.quiz-attempts.active', quiz.my_attempt.id)"
                  class="w-full bg-amber-500 hover:bg-amber-600 text-white text-xs font-black py-3 rounded-2xl transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                  <Icon icon="clock" size="sm" />
                  Resume Attempt
                </Link>

                <!-- Completed -->
                <Link v-else-if="quiz.status === 'completed' && quiz.my_attempt"
                  :href="route('student.quiz-attempts.result', quiz.my_attempt.id)"
                  class="w-full bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-800 dark:text-slate-100 border border-slate-300 dark:border-[#3F4F43] text-xs font-extrabold py-3 rounded-2xl transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                  <Icon icon="chart-bar" size="sm" />
                  Review Score ({{ quiz.my_attempt.score ?? '—' }}/{{ quiz.total_points }})
                </Link>

                <!-- Upcoming -->
                <button v-else-if="quiz.status === 'upcoming'" disabled
                  class="w-full bg-slate-200 dark:bg-[#232D26] text-slate-500 dark:text-slate-500 text-xs font-extrabold py-3 rounded-2xl cursor-not-allowed text-center inline-flex items-center justify-center gap-2">
                  <Icon icon="lock-closed" size="sm" />
                  Not Yet Open
                </button>

                <!-- Closed -->
                <button v-else disabled
                  class="w-full bg-slate-200 dark:bg-[#232D26] text-slate-500 dark:text-slate-500 text-xs font-extrabold py-3 rounded-2xl cursor-not-allowed text-center inline-flex items-center justify-center gap-2">
                  <Icon icon="x-circle" size="sm" />
                  Closed
                </button>
              </div>
            </div>
          </div>

          <div v-else class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
            <div class="flex justify-center text-slate-400">
              <Icon icon="clipboard-list" size="xl" />
            </div>
            <p class="text-sm font-bold text-slate-800 dark:text-white">No quizzes here</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              {{ searchQuery ? 'Try a different search term.' : 'Nothing to show for this filter yet.' }}
            </p>
          </div>

        </div>
      </div>
    </main>

    <!-- PRE-QUIZ GUIDELINES MODAL -->
    <div v-if="showPrepModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4">
      <div class="bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] text-slate-900 dark:text-slate-100 rounded-2xl max-w-lg w-full p-6 sm:p-7 space-y-6 shadow-xl">

        <div class="flex items-start justify-between gap-4 border-b border-slate-100 dark:border-[#3F4F43] pb-5">
          <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-500/30 text-amber-600 dark:text-amber-300 flex items-center justify-center shrink-0">
              <Icon icon="alert-triangle" size="md" />
            </div>
            <div>
              <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-snug">Assessment Guidelines</h3>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">
                {{ selectedPrepQuiz?.title }}
              </p>
            </div>
          </div>

          <button @click="showPrepModal = false"
            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-[#3F4F43] cursor-pointer">
            <Icon icon="x" size="md" />
          </button>
        </div>

        <div class="space-y-3.5">
          <div class="flex items-start gap-3.5 p-3.5 rounded-xl bg-slate-50 dark:bg-[#232D26] border border-slate-100 dark:border-[#3F4F43]">
            <div class="p-2 bg-white dark:bg-[#2D3A31] rounded-lg border border-slate-200/80 dark:border-[#3F4F43] text-slate-700 dark:text-slate-200 shrink-0">
              <Icon icon="clock" size="sm" />
            </div>
            <div class="text-xs leading-relaxed">
              <span class="font-bold text-slate-900 dark:text-white block mb-0.5">Timer Rules</span>
              <span class="text-slate-600 dark:text-slate-300">
                {{ selectedPrepQuiz?.time_limit_minutes
                  ? `You have ${selectedPrepQuiz.time_limit_minutes} minutes. The countdown does NOT pause.`
                  : 'No time limit on this quiz.' }}
              </span>
            </div>
          </div>

          <div class="flex items-start gap-3.5 p-3.5 rounded-xl bg-slate-50 dark:bg-[#232D26] border border-slate-100 dark:border-[#3F4F43]">
            <div class="p-2 bg-white dark:bg-[#2D3A31] rounded-lg border border-slate-200/80 dark:border-[#3F4F43] text-slate-700 dark:text-slate-200 shrink-0">
              <Icon icon="check-circle" size="sm" />
            </div>
            <div class="text-xs leading-relaxed">
              <span class="font-bold text-slate-900 dark:text-white block mb-0.5">Autosave</span>
              <span class="text-slate-600 dark:text-slate-300">Every answer is saved automatically. You can close and resume the attempt.</span>
            </div>
          </div>

          <div class="flex items-start gap-3.5 p-3.5 rounded-xl bg-rose-50/50 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-500/30">
            <div class="p-2 bg-white dark:bg-[#2D3A31] rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-500 shrink-0">
              <Icon icon="alert-triangle" size="sm" />
            </div>
            <div class="text-xs leading-relaxed">
              <span class="font-bold text-rose-950 dark:text-rose-300 block mb-0.5">3 Warnings Limit</span>
              <span class="text-rose-700 dark:text-rose-400">Tab switching or leaving fullscreen mode logs a warning. 3 warnings = auto-submit.</span>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
          <button @click="showPrepModal = false"
            class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-[#3F4F43] hover:bg-slate-50 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-300 text-xs font-semibold transition-all cursor-pointer active:scale-95">
            Cancel
          </button>
          <button @click="enterQuiz"
            :disabled="starting"
            class="px-6 py-2.5 rounded-xl bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-xs font-bold transition-all shadow-sm cursor-pointer flex items-center gap-2 active:scale-95 disabled:opacity-50">
            <span>{{ starting ? 'Starting...' : 'Start Exam' }}</span>
            <Icon icon="arrow-right" size="sm" />
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  quizzes:     { type: Array,  default: () => [] },
  counts:      { type: Object, default: () => ({ all: 0, available: 0, in_progress: 0, upcoming: 0, completed: 0, closed: 0 }) },
  active_term: { type: String, default: null },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')
const activeTab = ref('all')
const searchQuery = ref('')

const showPrepModal = ref(false)
const selectedPrepQuiz = ref(null)
const starting = ref(false)

const filterTabs = computed(() => [
  { id: 'all',         label: 'All',        count: props.counts.all },
  { id: 'available',   label: 'Available',  count: props.counts.available },
  { id: 'in_progress', label: 'In Progress',count: props.counts.in_progress },
  { id: 'upcoming',    label: 'Upcoming',   count: props.counts.upcoming },
  { id: 'completed',   label: 'Completed',  count: props.counts.completed },
])

const filteredQuizzes = computed(() => {
  const q = searchQuery.value.trim().toLowerCase()
  return props.quizzes.filter(quiz => {
    const matchesTab = activeTab.value === 'all' || quiz.status === activeTab.value
    const matchesSearch = !q ||
      (quiz.title || '').toLowerCase().includes(q) ||
      (quiz.subject || '').toLowerCase().includes(q)
    return matchesTab && matchesSearch
  })
})

function startQuizPrep(quiz) {
  selectedPrepQuiz.value = quiz
  showPrepModal.value = true
}

function enterQuiz() {
  if (!selectedPrepQuiz.value) return
  starting.value = true
  router.post(route('student.quizzes.start', selectedPrepQuiz.value.id), {}, {
    onFinish: () => { starting.value = false },
  })
}

function statusPillClass(status) {
  return {
    available:   'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    in_progress: 'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40',
    upcoming:    'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border bg-sky-100 dark:bg-sky-950/60 text-sky-800 dark:text-sky-300 border-sky-200 dark:border-sky-900/40',
    completed:   'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border bg-violet-100 dark:bg-violet-950/60 text-violet-800 dark:text-violet-300 border-violet-200 dark:border-violet-900/40',
    closed:      'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border bg-slate-200 dark:bg-[#3F4F43] text-slate-600 dark:text-slate-300 border-slate-300 dark:border-[#3F4F43]',
  }[status] || 'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'
}

function statusLabel(status) {
  return {
    available:   'Available',
    in_progress: 'In Progress',
    upcoming:    'Upcoming',
    completed:   'Completed',
    closed:      'Closed',
  }[status] || status
}
</script>

<style scoped>
.animated-stroke-text { color: transparent; -webkit-text-stroke: 1.5px #ffffff; }

@keyframes fadeInDown  { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px);  } to { opacity: 1; transform: translateY(0); } }
@keyframes sheenMove   { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
@keyframes spinSlow    { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

.animate-fade-in-down  { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
.animate-sheen         { animation: sheenMove 4s ease-in-out infinite; }
.animate-spin-slow     { display: inline-block; animation: spinSlow 12s linear infinite; }

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
</style>