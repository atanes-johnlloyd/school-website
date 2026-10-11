<template>
  <Head :title="`${quiz.title} - Result`" />

  <StudentLayout>
    <div class="space-y-6 pb-10 font-['Inter'] min-w-0 max-w-4xl mx-auto w-full">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[260px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <img
          src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=1600&auto=format&fit=crop"
          alt="Quiz Result Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0"></div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full space-y-4 sm:space-y-5">

          <!-- Top row: badges + back -->
          <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
              <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                <div class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                  <Icon icon="chart-bar" size="xs" />
                  QUIZ RESULT
                </div>

                <div class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                  <Icon :icon="passed ? 'check-circle' : 'x-circle'" size="xs" />
                  {{ passed ? 'Passed' : 'Not Passed' }}
                </div>

                <div v-if="attempt.has_pending_essays"
                  class="inline-flex items-center gap-2 bg-amber-400/20 text-amber-100 border border-amber-400/40 backdrop-blur-md text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                  <Icon icon="alert-circle" size="xs" />
                  Awaiting Essay Grading
                </div>

                <div v-if="attempt.warning_count > 0"
                  class="inline-flex items-center gap-2 bg-rose-500/20 text-rose-100 border border-rose-400/40 backdrop-blur-md text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                  <Icon icon="alert-triangle" size="xs" />
                  {{ attempt.warning_count }} Warning{{ attempt.warning_count === 1 ? '' : 's' }}
                </div>
              </div>

              <div class="space-y-1">
                <h1 class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                  <span>QUIZ</span>
                  <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">RESULT</span>
                </h1>
                <p class="text-white/90 text-xs sm:text-sm leading-relaxed font-medium italic">
                  "Assessment outcome and review."
                </p>
              </div>
            </div>

            <button type="button" @click="goBack"
              class="inline-flex items-center justify-center gap-2 bg-black/30 hover:bg-black/40 backdrop-blur-md text-white border border-white/20 font-bold px-4 py-2.5 rounded-xl transition-all active:scale-95 text-xs sm:text-sm cursor-pointer shadow-sm shrink-0 self-start">
              <Icon icon="arrow-left" size="xs" />
              <span>Back</span>
            </button>
          </div>

          <!-- Bottom row: quiz info -->
          <div class="pt-2 space-y-1">
            <div class="inline-block bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-[11px] font-black px-3 py-0.5 rounded-full shadow-xs uppercase tracking-wide">
              {{ quiz.subject || 'Subject' }}
            </div>
            <h2 class="font-black text-base sm:text-xl text-white tracking-tight leading-snug truncate">
              {{ quiz.title }}
            </h2>
          </div>

        </div>
      </div>

      <!-- SCORE CARD -->
      <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

        <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
          <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
          <div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-none">Score</h3>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">Overall performance</p>
          </div>
        </div>

        <div v-if="attempt.score_hidden" class="text-center py-6 space-y-2">
          <div class="flex justify-center text-slate-400">
            <Icon icon="lock-closed" size="xl" />
          </div>
          <p class="text-sm font-bold text-slate-700 dark:text-slate-200">Score hidden by instructor</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Your instructor will release results later.</p>
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="bg-emerald-50 dark:bg-emerald-950/40 rounded-2xl p-5 border border-emerald-200/80 dark:border-emerald-900/40 text-center space-y-1">
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

      <!-- ANSWER REVIEW -->
      <div v-if="attempt.show_correct_answers || attempt.show_explanations"
        class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
          <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
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
              <span v-if="a.is_correct === true"
                class="bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] text-[10px] font-black uppercase px-2 py-0.5 rounded-md shrink-0">
                Correct
              </span>
              <span v-else-if="a.is_correct === false"
                class="bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10px] font-black uppercase px-2 py-0.5 rounded-md shrink-0">
                Incorrect
              </span>
              <span v-else-if="a.needs_grading"
                class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-black uppercase px-2 py-0.5 rounded-md shrink-0">
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

            <div v-if="a.explanation"
              class="bg-white dark:bg-[#2D3A31] p-3 rounded-xl border border-slate-200/60 dark:border-[#3F4F43] text-xs text-slate-600 dark:text-slate-300 font-medium">
              <span class="font-bold text-slate-800 dark:text-slate-200">Explanation:</span> {{ a.explanation }}
            </div>
          </div>
        </div>
      </div>

      <!-- REVIEW LOCKED -->
      <div v-else
        class="animate-fade-slide-up rounded-3xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/40 p-6 text-center space-y-2">
        <div class="flex justify-center text-amber-600 dark:text-amber-400">
          <Icon icon="lock-closed" size="lg" />
        </div>
        <p class="text-sm font-bold text-amber-900 dark:text-amber-200">Detailed review locked</p>
        <p class="text-xs text-amber-800 dark:text-amber-300">
          Your instructor has disabled answer review for this quiz.
        </p>
      </div>

    </div>
  </StudentLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import StudentLayout from '@/Layouts/StudentLayout.vue'
import Icon from '@/Components/Icon.vue'

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

function goBack() {
  // Result is a terminal state — "back" always means "leave the quiz",
  // never "return to Take.vue", which is now a dead submitted attempt.
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