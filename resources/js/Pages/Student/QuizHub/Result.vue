<template>
  <Head :title="`${quiz.title} - Result`" />

    <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search results..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size"
      />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16 max-w-4xl mx-auto w-full">

        <!-- HERO -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 relative z-10">
            <div class="space-y-1 max-w-2xl">
              <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
                <span class="text-white">QUIZ</span>
                <span class="animated-stroke-text">RESULT</span>
              </div>
              <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
                Assessment outcome and review.
              </p>
            </div>

            <Link :href="route('student.quizhub.index')"
              class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 self-start">
              <Icon icon="arrow-left" size="xs" />
              Back to QuizHub
            </Link>
          </div>

          <div class="pt-2 space-y-2 relative z-10">
            <p class="text-xs font-bold text-emerald-200">{{ quiz.subject || 'Subject' }}</p>
            <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
              {{ quiz.title }}
            </h2>
            <div class="flex flex-wrap items-center gap-2 pt-1">
              <span :class="passedBadgeClass" class="inline-flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wider px-3 py-1 rounded-full border">
                <Icon :icon="passed ? 'check-circle' : 'x-circle'" size="xs" />
                {{ passed ? 'Passed' : 'Not Passed' }}
              </span>
              <span v-if="attempt.has_pending_essays" class="inline-flex items-center gap-1.5 bg-amber-400/20 text-amber-200 text-[11px] font-black uppercase tracking-wider px-3 py-1 rounded-full border border-amber-400/30">
                <Icon icon="alert-circle" size="xs" />
                Awaiting Essay Grading
              </span>
              <span v-if="attempt.warning_count > 0" class="inline-flex items-center gap-1.5 bg-rose-500/20 text-rose-200 text-[11px] font-black uppercase tracking-wider px-3 py-1 rounded-full border border-rose-500/30">
                <Icon icon="alert-triangle" size="xs" />
                {{ attempt.warning_count }} Warning{{ attempt.warning_count === 1 ? '' : 's' }}
              </span>
            </div>
          </div>
        </div>

        <!-- SCORE CARD -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-6 sm:p-8 shadow-sm space-y-6">
          <div v-if="attempt.score_hidden" class="text-center py-6 space-y-2">
            <div class="flex justify-center text-slate-400">
              <Icon icon="lock-closed" size="xl" />
            </div>
            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">Score hidden by instructor</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Your instructor will release results later.</p>
          </div>

          <div v-else class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/40 dark:to-teal-950/30 rounded-2xl p-5 border border-emerald-200/80 dark:border-emerald-900/40 text-center space-y-1">
              <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 block">Your Score</span>
              <span class="text-4xl font-black text-[#004d08] dark:text-[#86EFAC] block">{{ attempt.score ?? '—' }}</span>
              <span class="text-xs font-bold text-slate-500 dark:text-slate-400">of {{ quiz.total_points }} points</span>
            </div>
            <div class="bg-amber-50 dark:bg-amber-950/40 rounded-2xl p-5 border border-amber-200/80 dark:border-amber-900/40 text-center space-y-1">
              <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 block">Percentage</span>
              <span class="text-4xl font-black text-amber-700 dark:text-amber-400 block">{{ scorePercent }}%</span>
              <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Passing: {{ quiz.passing_score ?? '—' }}%</span>
            </div>
            <div class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-5 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
              <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 block">Submitted</span>
              <span class="text-base font-extrabold text-slate-900 dark:text-white block pt-1">{{ formatDate(attempt.submitted_at) }}</span>
              <span class="text-xs font-bold text-slate-500 dark:text-slate-400 capitalize">{{ attempt.status }}</span>
            </div>
          </div>
        </div>

        <!-- REVIEW -->
        <div v-if="attempt.show_correct_answers || attempt.show_explanations" class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-6 sm:p-8 shadow-sm space-y-6">
          <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
            <div>
              <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-none">Answer Review</h3>
              <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">Item-by-item breakdown</p>
            </div>
          </div>

          <div class="space-y-4">
            <div v-for="(a, idx) in answers" :key="a.question_id"
              :class="[
                'bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 border space-y-3',
                a.is_correct === true ? 'border-emerald-300 dark:border-emerald-900/60' :
                a.is_correct === false ? 'border-rose-300 dark:border-rose-900/60' :
                'border-slate-200 dark:border-[#3F4F43]'
              ]">
              <div class="flex items-start justify-between gap-3">
                <h4 class="font-bold text-slate-900 dark:text-white text-sm leading-snug">
                  {{ idx + 1 }}. {{ a.question_text }}
                </h4>
                <span v-if="a.is_correct === true" class="bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] text-[10px] font-black uppercase px-2 py-0.5 rounded-md shrink-0">
                  Correct
                </span>
                <span v-else-if="a.is_correct === false" class="bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10px] font-black uppercase px-2 py-0.5 rounded-md shrink-0">
                  Incorrect
                </span>
                <span v-else-if="a.needs_grading" class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-black uppercase px-2 py-0.5 rounded-md shrink-0">
                  Pending
                </span>
              </div>

              <div class="text-xs space-y-1">
                <p class="text-slate-600 dark:text-slate-400">
                  Your answer:
                  <strong class="text-slate-900 dark:text-slate-100">
                    {{ a.your_option_text || a.your_text || 'Not answered' }}
                  </strong>
                </p>
                <p v-if="a.correct_option_text && a.is_correct !== true" class="text-emerald-700 dark:text-[#86EFAC] font-semibold">
                  Correct answer: <strong>{{ a.correct_option_text }}</strong>
                </p>
              </div>

              <div v-if="a.explanation" class="bg-white dark:bg-[#2D3A31] p-3 rounded-xl border border-slate-200/60 dark:border-[#3F4F43] text-xs text-slate-600 dark:text-slate-300 font-medium">
                <span class="font-bold text-slate-800 dark:text-slate-200">Explanation:</span> {{ a.explanation }}
              </div>
            </div>
          </div>
        </div>

        <!-- REVIEW LOCKED -->
        <div v-else class="animate-fade-slide-up rounded-3xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/40 p-6 text-center space-y-2">
          <div class="flex justify-center text-amber-600 dark:text-amber-400">
            <Icon icon="lock-closed" size="lg" />
          </div>
          <p class="text-sm font-bold text-amber-900 dark:text-amber-200">Detailed review locked</p>
          <p class="text-xs text-amber-800 dark:text-amber-300">
            Your instructor has disabled answer review for this quiz.
          </p>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')

const props = defineProps({
  quiz:    { type: Object, default: () => ({}) },
  attempt: { type: Object, default: () => ({}) },
  answers: { type: Array,  default: () => [] },
})

const passed = computed(() => props.attempt.passed === true)

const scorePercent = computed(() => {
  if (props.attempt.score === null || props.attempt.score === undefined) return '—'
  if (!props.quiz.total_points) return '—'
  return Math.round((props.attempt.score / props.quiz.total_points) * 100)
})

const passedBadgeClass = computed(() => passed.value
  ? 'bg-emerald-400/20 text-emerald-200 border-emerald-400/30'
  : 'bg-rose-500/20 text-rose-200 border-rose-500/30'
)

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