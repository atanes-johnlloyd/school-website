<template>
  <Head :title="`${quiz.title} - Salawag LMS`" />

    <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search quizzes..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size"
      />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- HERO -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 relative z-10">
            <div class="space-y-1 max-w-2xl">
              <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
                <span class="text-white">QUIZ</span>
                <span class="animated-stroke-text">PREVIEW</span>
              </div>
              <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
                Read the guidelines before starting.
              </p>
            </div>

            <Link :href="route('student.quizhub.index')"
              class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 self-start">
              <Icon icon="arrow-left" size="xs" />
              Back to QuizHub
            </Link>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2 relative z-10">
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 flex items-center justify-center shrink-0">
                <Icon icon="clipboard-list" size="xl" class="text-white" />
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                  {{ quiz.subject || 'Subject' }}<span v-if="quiz.section"> • {{ quiz.section }}</span>
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  {{ quiz.title }}
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  Instructor: {{ quiz.teacher || '—' }}
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-3 gap-2 sm:gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">Items</span>
                <div class="text-xl font-extrabold text-amber-300 my-0.5">{{ quiz.questions_count }}</div>
                <span class="text-[9px] text-emerald-100/70">{{ quiz.total_points }} pts</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">Time</span>
                <div class="text-xl font-extrabold text-white my-0.5">{{ quiz.time_limit_minutes || '∞' }}</div>
                <span class="text-[9px] text-emerald-100/70">{{ quiz.time_limit_minutes ? 'minutes' : 'No limit' }}</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">Passing</span>
                <div class="text-xl font-extrabold text-[#86EFAC] my-0.5">{{ quiz.passing_score ?? '—' }}</div>
                <span class="text-[9px] text-emerald-100/70">% threshold</span>
              </div>
            </div>
          </div>
        </div>

        <!-- INSTRUCTIONS + CTA -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

          <div v-if="quiz.description || quiz.instructions" class="space-y-4">
            <div v-if="quiz.description">
              <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Description</h3>
              <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">{{ quiz.description }}</p>
            </div>
            <div v-if="quiz.instructions">
              <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Instructions</h3>
              <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">{{ quiz.instructions }}</p>
            </div>
          </div>

          <!-- AVAILABILITY -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
                <Icon icon="calendar" size="md" />
              </div>
              <div class="min-w-0">
                <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 block">Opens</span>
                <span class="text-xs font-bold text-slate-900 dark:text-white block">{{ formatDate(quiz.available_from) }}</span>
              </div>
            </div>
            <div class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-300 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40">
                <Icon icon="clock" size="md" />
              </div>
              <div class="min-w-0">
                <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 block">Closes</span>
                <span class="text-xs font-bold text-slate-900 dark:text-white block">{{ formatDate(quiz.available_until) }}</span>
              </div>
            </div>
          </div>

          <!-- EXISTING ATTEMPT -->
          <div v-if="my_attempt" class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/40 rounded-2xl p-4 space-y-2">
            <div class="flex items-center gap-2">
              <Icon icon="alert-triangle" size="md" class="text-amber-700 dark:text-amber-300 shrink-0" />
              <span class="text-xs font-black uppercase tracking-wider text-amber-800 dark:text-amber-300">
                You already have an attempt
              </span>
            </div>
            <p class="text-xs text-amber-900 dark:text-amber-200 font-medium">
              Status: <strong class="uppercase">{{ my_attempt.status }}</strong>
              <span v-if="my_attempt.submitted_at"> • Submitted {{ formatDate(my_attempt.submitted_at) }}</span>
            </p>
          </div>

          <!-- CTA -->
          <div class="flex flex-col sm:flex-row items-center gap-3 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
            <button v-if="my_attempt?.status === 'in_progress'"
              @click="resumeAttempt"
              class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white text-sm font-black px-6 py-3 rounded-2xl transition-all active:scale-95 shadow-md flex items-center justify-center gap-2">
              <Icon icon="arrow-right" size="sm" />
              Resume Attempt
            </button>

            <button v-else-if="my_attempt"
              @click="viewResult"
              class="w-full sm:w-auto bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-sm font-black px-6 py-3 rounded-2xl transition-all active:scale-95 shadow-md flex items-center justify-center gap-2">
              <Icon icon="chart-bar" size="sm" />
              View Result
            </button>

            <button v-else
              @click="startQuiz"
              :disabled="starting"
              class="w-full sm:w-auto bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-sm font-black px-6 py-3 rounded-2xl transition-all active:scale-95 shadow-md flex items-center justify-center gap-2 disabled:opacity-50">
              <Icon icon="send" size="sm" />
              {{ starting ? 'Starting...' : 'Start Quiz' }}
            </button>

            <p class="text-[11px] text-slate-500 dark:text-slate-400 sm:ml-auto text-center sm:text-right">
              By starting, you agree to the proctored assessment rules.<br>
              Tab switches and fullscreen exits are logged.
            </p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')

const props = defineProps({
  quiz:       { type: Object, default: () => ({}) },
  my_attempt: { type: Object, default: null },
})

const starting = ref(false)

function startQuiz() {
  starting.value = true
  router.post(route('student.quizzes.start', props.quiz.id), {}, {
    onFinish: () => { starting.value = false },
  })
}

function resumeAttempt() {
  router.visit(route('student.quiz-attempts.active', props.my_attempt.id))
}

function viewResult() {
  router.visit(route('student.quiz-attempts.result', props.my_attempt.id))
}

function formatDate(value) {
  if (!value) return '—'
  try {
    return new Date(value).toLocaleString('en-US', {
      month: 'short', day: 'numeric', year: 'numeric',
      hour: 'numeric', minute: '2-digit', hour12: true,
    })
  } catch { return value }
}
</script>

<style scoped>
.animated-stroke-text { color: transparent; -webkit-text-stroke: 1.5px #ffffff; }
@keyframes fadeInDown  { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px);  } to { opacity: 1; transform: translateY(0); } }
@keyframes sheenMove   { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
.animate-fade-in-down  { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
.animate-sheen         { animation: sheenMove 4s ease-in-out infinite; }
</style>