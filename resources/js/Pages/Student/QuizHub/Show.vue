<template>
  <Head :title="`${quiz.title} - Salawag LMS`" />

  <StudentLayout>
    <div class="space-y-6 pb-10 font-['Inter'] min-w-0">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[260px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <img
          src="https://images.unsplash.com/photo-1606326608606-aa0b62935f2b?q=80&w=1600&auto=format&fit=crop"
          alt="Quiz Preview Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0"></div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full space-y-4 sm:space-y-5">

          <!-- Top row: badges + back -->
          <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
              <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                <div class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                  <Icon icon="clipboard-list" size="xs" />
                  QUIZ PREVIEW
                </div>

                <div v-if="my_attempt" class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                  <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                  {{ attemptLabel }}
                </div>
              </div>

              <div class="space-y-1">
                <h1 class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                  <span>QUIZ</span>
                  <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">PREVIEW</span>
                </h1>
                <p class="text-white/90 text-xs sm:text-sm leading-relaxed font-medium italic">
                  "Read the guidelines before starting."
                </p>
              </div>
            </div>

            <button type="button" @click="goBack"
              class="inline-flex items-center justify-center gap-2 bg-black/30 hover:bg-black/40 backdrop-blur-md text-white border border-white/20 font-bold px-4 py-2.5 rounded-xl transition-all active:scale-95 text-xs sm:text-sm cursor-pointer shadow-sm shrink-0 self-start">
              <Icon icon="arrow-left" size="xs" />
              <span>Back</span>
            </button>
          </div>

          <!-- Bottom row: info + stat cards -->
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-1">
            <div class="lg:col-span-7 flex items-center gap-3 sm:gap-4">
              <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl border-2 border-white/30 bg-white/10 backdrop-blur-sm overflow-hidden shrink-0 shadow-lg flex items-center justify-center transition-transform hover:scale-105 duration-300">
                <Icon icon="clipboard-list" size="lg" class="text-white" />
              </div>

              <div class="space-y-0.5 min-w-0">
                <div class="inline-block bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-[11px] font-black px-3 py-0.5 rounded-full shadow-xs uppercase tracking-wide">
                  {{ quiz.subject || 'Subject' }}
                  <span v-if="quiz.section"> • {{ quiz.section }}</span>
                </div>
                <h2 class="text-base sm:text-xl font-black text-white tracking-tight leading-snug truncate">
                  {{ quiz.title }}
                </h2>
                <p class="text-white/90 text-xs font-medium truncate">
                  Instructor: <span class="font-bold">{{ quiz.teacher || '—' }}</span>
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-3 gap-2.5 sm:gap-3 w-full">
              <div class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl p-3 sm:p-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between shadow-sm min-h-[75px]">
                <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">Items</span>
                <div class="text-lg sm:text-2xl font-black text-[#F9C20C] leading-tight my-0.5">{{ quiz.questions_count }}</div>
                <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium">{{ quiz.total_points }} pts</span>
              </div>

              <div class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl p-3 sm:p-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between shadow-sm min-h-[75px]">
                <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">Time</span>
                <div class="text-lg sm:text-2xl font-black text-white leading-tight my-0.5">{{ quiz.time_limit_minutes || '∞' }}</div>
                <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium">{{ quiz.time_limit_minutes ? 'minutes' : 'No limit' }}</span>
              </div>

              <div class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl p-3 sm:p-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between shadow-sm min-h-[75px]">
                <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">Passing</span>
                <div class="text-lg sm:text-2xl font-black text-[#86EFAC] leading-tight my-0.5">{{ quiz.passing_score ?? '—' }}</div>
                <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium">% threshold</span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- DESCRIPTION + INSTRUCTIONS -->
      <div v-if="quiz.description || quiz.instructions"
        class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm space-y-4">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
          <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
          <div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-none">Brief</h3>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">Read carefully before starting</p>
          </div>
        </div>

        <div v-if="quiz.description" class="space-y-1">
          <h4 class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Description</h4>
          <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">{{ quiz.description }}</p>
        </div>

        <div v-if="quiz.instructions" class="space-y-1">
          <h4 class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Instructions</h4>
          <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">{{ quiz.instructions }}</p>
        </div>
      </div>

      <!-- AVAILABILITY -->
      <div class="animate-fade-slide-up grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl p-4 flex items-center gap-3 shadow-sm">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
            <Icon icon="calendar" size="md" />
          </div>
          <div class="min-w-0">
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Opens</span>
            <span class="text-xs font-bold text-slate-900 dark:text-white block mt-0.5">{{ formatDate(quiz.available_from) }}</span>
          </div>
        </div>

        <div class="bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl p-4 flex items-center gap-3 shadow-sm">
          <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-300 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40">
            <Icon icon="clock" size="md" />
          </div>
          <div class="min-w-0">
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Closes</span>
            <span class="text-xs font-bold text-slate-900 dark:text-white block mt-0.5">{{ formatDate(quiz.available_until) }}</span>
          </div>
        </div>
      </div>

      <!-- EXISTING ATTEMPT WARNING -->
      <div v-if="my_attempt"
        class="animate-fade-slide-up bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/40 rounded-2xl p-4 space-y-2">
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
      <div
        class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm flex flex-col sm:flex-row items-center gap-4">
        <button v-if="my_attempt?.status === 'in_progress'"
          @click="resumeAttempt"
          class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white text-sm font-black px-6 py-3 rounded-2xl transition-all active:scale-95 shadow-md flex items-center justify-center gap-2 cursor-pointer">
          <Icon icon="arrow-right" size="sm" />
          Resume Attempt
        </button>

        <button v-else-if="my_attempt"
          @click="viewResult"
          class="w-full sm:w-auto bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-sm font-black px-6 py-3 rounded-2xl transition-all active:scale-95 shadow-md flex items-center justify-center gap-2 cursor-pointer">
          <Icon icon="chart-bar" size="sm" />
          View Result
        </button>

        <button v-else
          @click="startQuiz"
          :disabled="starting"
          class="w-full sm:w-auto bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-sm font-black px-6 py-3 rounded-2xl transition-all active:scale-95 shadow-md flex items-center justify-center gap-2 disabled:opacity-50 cursor-pointer">
          <Icon icon="send" size="sm" />
          {{ starting ? 'Starting...' : 'Start Quiz' }}
        </button>

        <p class="text-[11px] text-slate-500 dark:text-slate-400 sm:ml-auto text-center sm:text-right">
          By starting, you agree to the proctored assessment rules.<br>
          Tab switches and fullscreen exits are logged.
        </p>
      </div>

    </div>
  </StudentLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import StudentLayout from '@/Layouts/StudentLayout.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  quiz:       { type: Object, default: () => ({}) },
  my_attempt: { type: Object, default: null },
})

const starting = ref(false)

const attemptLabel = computed(() => {
  const s = props.my_attempt?.status
  return {
    in_progress: 'In Progress',
    submitted:   'Submitted',
    graded:      'Graded',
  }[s] || 'Attempt Recorded'
})

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

function goBack() {
  if (window.history.length > 2 && document.referrer) {
    window.history.back()
    return
  }
  router.visit(route('student.quizhub.index'))
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
@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-20px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes fadeSlideUp {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: translateY(0); }
}

.animate-fade-in-down  { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
</style>