<template>
  <Head :title="`${attempt.student_name} — ${attempt.quiz_title} — Salawag LMS`" />

  <AuthenticatedLayout searchPlaceholder="Search attempts, questions..">
    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2 max-w-full">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[260px] flex flex-col justify-center">
        <img :src="heroImage" alt="Attempt Review Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 max-w-3xl space-y-3">
          <div class="flex flex-wrap items-center gap-2">
            <span :class="statusPillClass"
              class="text-[10px] sm:text-xs font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon :icon="statusIcon" size="xs" />
              {{ statusLabel }}
            </span>
            <span v-if="attempt.warning_count > 0"
              class="bg-amber-500/90 text-white text-[10px] sm:text-xs font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="exclamation-triangle" size="xs" />
              {{ attempt.warning_count }} warning{{ attempt.warning_count === 1 ? '' : 's' }}
            </span>
            <span v-if="attempt.student_lrn"
              class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="identification" size="xs" />
              LRN {{ attempt.student_lrn }}
            </span>
          </div>

          <div class="space-y-2">
            <!-- Student name = the hero -->
            <h2 class="text-3xl sm:text-5xl md:text-6xl font-black text-white tracking-tight leading-tight">
              {{ attempt.student_name }}
            </h2>

            <!-- Quiz title as a subtitle line -->
            <div class="flex items-center gap-2 text-white/90 text-xs sm:text-sm md:text-base font-semibold">
              <Icon icon="academic-cap" size="sm" class="text-white/80" />
              <span class="leading-relaxed">{{ attempt.quiz_title }}</span>
            </div>

            <p class="text-white text-[11px] sm:text-xs md:text-sm leading-relaxed font-medium max-w-2xl">
              Attempt review — {{ answers.length }} question{{ answers.length === 1 ? '' : 's' }}
              <span v-if="attempt.submitted_at"> · submitted {{ formatShort(attempt.submitted_at) }}</span>
            </p>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <BackButton fallback="teacher.quizzes.index"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              Back
            </BackButton>
            <Link v-if="attempt.quiz_id"
              :href="route('teacher.quizzes.submissions.index', attempt.quiz_id)"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              <Icon icon="chart-bar" size="xs" />
              All Submissions
            </Link>
          </div>
        </div>
      </div>

      <!-- STATS ROW -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43]">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Score</span>
          <div class="mt-1 flex items-baseline gap-1">
            <span class="text-2xl sm:text-3xl font-black text-[#005506] dark:text-[#86EFAC]">{{ attempt.score ?? '—' }}</span>
            <span class="text-xs font-bold text-slate-500">/ {{ totalPoints }}</span>
          </div>
        </div>

        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43]">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Correct</span>
          <div class="mt-1 flex items-baseline gap-1">
            <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ correctCount }}</span>
            <span class="text-xs font-bold text-slate-500">/ {{ answers.length }}</span>
          </div>
        </div>

        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43]">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Pending essays</span>
          <div class="mt-1 flex items-baseline gap-1">
            <span class="text-2xl sm:text-3xl font-black"
              :class="pendingCount > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white'">
              {{ pendingCount }}
            </span>
          </div>
        </div>

        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43]">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</span>
          <div class="mt-1">
            <span :class="statusPillClass" class="inline-flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full">
              <Icon :icon="statusIcon" size="xs" />
              {{ statusLabel }}
            </span>
          </div>
        </div>
      </div>

      <!-- ANSWERS -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
          <div class="w-2.5 h-7 bg-[#005506] dark:bg-[#86EFAC] rounded-full"></div>
          <div class="flex-1">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Answers</h3>
            <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
              In the order they were presented to the student
            </p>
          </div>
        </div>

        <div v-if="answers.length" class="space-y-3">
          <div v-for="(a, i) in answers" :key="a.id"
            :class="[
              'p-3.5 sm:p-4 rounded-xl border space-y-3 transition-colors',
              a.needs_grading
                ? 'bg-amber-50/60 dark:bg-amber-950/20 border-amber-200/70 dark:border-amber-900/40'
                : 'bg-[#F9F7F1] dark:bg-[#232D26] border-slate-200/80 dark:border-[#3F4F43]'
            ]">

            <!-- Question header -->
            <div class="flex items-start gap-3">
              <span class="w-7 h-7 rounded-lg bg-[#005506] text-white font-black text-[11px] flex items-center justify-center shrink-0">
                {{ String(i + 1).padStart(2, '0') }}
              </span>
              <div class="flex-1 min-w-0 space-y-1.5">
                <div class="flex flex-wrap items-center gap-2">
                  <span :class="typeBadgeClass(a.question_type)"
                    class="text-[9px] font-black uppercase px-2 py-0.5 rounded border">
                    {{ typeLabel(a.question_type) }}
                  </span>
                  <span
                    v-if="a.needs_grading"
                    class="text-[9px] font-black uppercase px-2 py-0.5 rounded border bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/70 dark:border-amber-900/40">
                    Needs grading
                  </span>
                  <span v-else-if="a.is_correct === true"
                    class="text-[9px] font-black uppercase px-2 py-0.5 rounded border bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-[#86EFAC] border-emerald-200/60 dark:border-emerald-900/40">
                    Correct
                  </span>
                  <span v-else-if="a.is_correct === false"
                    class="text-[9px] font-black uppercase px-2 py-0.5 rounded border bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200/60 dark:border-rose-900/40">
                    Incorrect
                  </span>
                  <span class="ml-auto text-[10px] font-black text-slate-500">
                    {{ a.points_awarded ?? 0 }} / {{ a.points_override ?? a.question_points }} pts
                  </span>
                </div>

                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-relaxed">
                  {{ a.question_text }}
                </p>
              </div>
            </div>

            <!-- Answer body -->
            <div class="pl-10 space-y-2 text-xs">
              <!-- Short answer / essay -->
              <template v-if="a.question_type === 'essay' || a.question_type === 'short_answer'">
                <div class="bg-white dark:bg-[#2D3A31] rounded-lg p-3 border border-slate-200/80 dark:border-[#3F4F43]">
                  <p class="text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Student response</p>
                  <p class="text-slate-800 dark:text-slate-100 font-medium whitespace-pre-wrap leading-relaxed">
                    {{ a.answer_text || '— No response —' }}
                  </p>
                </div>
              </template>

              <!-- Choice-based -->
              <template v-else>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                  <div class="bg-white dark:bg-[#2D3A31] rounded-lg p-3 border border-slate-200/80 dark:border-[#3F4F43]">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Chosen</p>
                    <p class="font-bold leading-snug"
                      :class="a.is_correct
                        ? 'text-emerald-700 dark:text-[#86EFAC]'
                        : 'text-rose-700 dark:text-rose-400'">
                      {{ a.chosen_option_text || '— No answer —' }}
                    </p>
                  </div>

                  <div v-if="a.correct_option_text && !a.is_correct"
                    class="bg-emerald-50 dark:bg-emerald-950/30 rounded-lg p-3 border border-emerald-200/70 dark:border-emerald-900/40">
                    <p class="text-[10px] font-black uppercase tracking-wider text-emerald-700 dark:text-[#86EFAC] mb-1">Correct answer</p>
                    <p class="font-bold text-emerald-800 dark:text-[#86EFAC] leading-snug">
                      {{ a.correct_option_text }}
                    </p>
                  </div>
                </div>
              </template>

              <!-- Explanation -->
              <div v-if="a.explanation"
                class="bg-blue-50 dark:bg-blue-950/30 rounded-lg p-3 border border-blue-200/70 dark:border-blue-900/40">
                <p class="text-[10px] font-black uppercase tracking-wider text-blue-700 dark:text-blue-300 mb-1 flex items-center gap-1.5">
                  <Icon icon="light-bulb" size="xs" />
                  Explanation
                </p>
                <p class="text-blue-900 dark:text-blue-200 leading-relaxed">{{ a.explanation }}</p>
              </div>

              <!-- Essay grading controls -->
              <div v-if="a.needs_grading"
                class="bg-white dark:bg-[#2D3A31] rounded-lg p-3 border border-amber-200/70 dark:border-amber-900/40 flex flex-wrap items-center gap-2">
                <label class="text-[10px] font-black uppercase tracking-wider text-slate-500">Award points</label>
                <input v-model.number="grades[a.id]" type="number" step="0.5" min="0"
                  :max="a.points_override ?? a.question_points"
                  class="w-24 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-[#3F4F43] bg-[#F9F7F1] dark:bg-[#232D26] text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
                <span class="text-[11px] font-bold text-slate-500">
                  / {{ a.points_override ?? a.question_points }} pts
                </span>
                <button @click="gradeAnswer(a)"
                  :disabled="gradingId === a.id"
                  class="ml-auto inline-flex items-center gap-1.5 bg-[#005506] hover:bg-[#004d05] text-white text-[11px] font-black px-3.5 py-2 rounded-xl shadow-sm transition-all active:scale-95 disabled:opacity-50">
                  <Icon icon="check" size="xs" />
                  {{ gradingId === a.id ? 'Saving…' : 'Save grade' }}
                </button>
              </div>
            </div>

          </div>
        </div>

        <div v-else
          class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-8 text-center border border-dashed border-slate-300 dark:border-[#3F4F43]">
          <Icon icon="clipboard-list" size="lg" class="text-slate-400 mx-auto mb-2" />
          <p class="text-xs font-bold text-slate-700 dark:text-white">No answers recorded</p>
          <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">The student didn’t submit any answers for this attempt.</p>
        </div>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { confirmAction, showError } from '@/Pages/useSweetAlert'
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import BackButton from '@/Components/BackButton.vue'
import heroImage from '../../../../assets/img/local/quiz-hub-hero.jpg'

const props = defineProps({
  attempt: { type: Object, required: true },
  answers: { type: Array,  default: () => [] },
})

const grades = ref({})
const gradingId = ref(null)

/* ── derived ─────────────────────────────────────────────── */
const totalPoints = computed(() =>
  props.answers.reduce((s, a) => s + Number(a.points_override ?? a.question_points ?? 0), 0)
)

const correctCount = computed(() =>
  props.answers.filter(a => a.is_correct === true).length
)

const pendingCount = computed(() =>
  props.answers.filter(a => a.needs_grading).length
)

const statusLabel = computed(() => {
  const s = (props.attempt.status || '').toLowerCase()
  if (s === 'graded')      return 'Graded'
  if (s === 'submitted')   return 'Awaiting grading'
  if (s === 'in_progress') return 'In progress'
  if (s === 'expired')     return 'Expired'
  return s ? s.charAt(0).toUpperCase() + s.slice(1) : 'Unknown'
})

const statusIcon = computed(() => {
  const s = (props.attempt.status || '').toLowerCase()
  if (s === 'graded')      return 'check-circle'
  if (s === 'submitted')   return 'clock'
  if (s === 'in_progress') return 'pencil'
  return 'information-circle'
})

const statusPillClass = computed(() => {
  const s = (props.attempt.status || '').toLowerCase()
  if (s === 'graded')      return 'bg-emerald-500/90 text-white'
  if (s === 'submitted')   return 'bg-amber-500/90 text-white'
  if (s === 'in_progress') return 'bg-blue-500/90 text-white'
  return 'bg-slate-200/90 text-slate-800'
})

/* ── helpers ─────────────────────────────────────────────── */
function typeLabel(type) {
  return {
    multiple_choice: 'MC',
    true_false:      'T/F',
    short_answer:    'Short',
    essay:           'Essay',
  }[type] || type || 'Q'
}

function typeBadgeClass(type) {
  const base = 'font-black uppercase'
  const map = {
    multiple_choice: 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-[#86EFAC] border-emerald-200/60 dark:border-emerald-900/40',
    true_false:      'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/60 dark:border-amber-900/40',
    short_answer:    'bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border-blue-200/60 dark:border-blue-900/40',
    essay:           'bg-violet-100 dark:bg-violet-950/60 text-violet-800 dark:text-violet-300 border-violet-200/60 dark:border-violet-900/40',
  }
  return `${base} ${map[type] || 'bg-slate-100 dark:bg-[#232D26] text-slate-700 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'}`
}

function formatShort(v) {
  if (!v) return '—'
  try {
    return new Date(v).toLocaleString('en-US', {
      month: 'short', day: 'numeric',
      hour: 'numeric', minute: '2-digit', hour12: true,
    })
  } catch { return '—' }
}

/* ── grading ─────────────────────────────────────────────── */
function gradeAnswer(answer) {
  const pts = grades.value[answer.id]
  if (pts === undefined || pts === null || pts === '') return

  gradingId.value = answer.id
  axios.put(route('teacher.quiz-answers.grade', answer.id), { points_awarded: pts })
    .then(() => router.reload({
      only: ['attempt', 'answers'],
      preserveScroll: true,
      preserveState: true,
    }))
    .catch(e => showError(e.response?.data?.message || 'Grading failed.'))
    .finally(() => { gradingId.value = null })
}

/* ── v-observe directive ─────────────────────────────────── */
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
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }
.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>