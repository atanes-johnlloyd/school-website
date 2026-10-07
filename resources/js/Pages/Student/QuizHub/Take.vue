<template>
  <Head :title="`${quiz.title} - Assessment in Progress`" />

  <div class="fixed inset-0 z-50 bg-slate-900 text-white flex flex-col overflow-y-auto">
    <!-- HEADER -->
    <header class="max-w-6xl w-full mx-auto flex flex-wrap items-center justify-between gap-4 p-4 sm:p-6 border-b border-slate-800 shrink-0">
      <div class="flex items-center gap-3 min-w-0">
        <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping shrink-0"></span>
        <div class="min-w-0">
          <h1 class="font-extrabold text-base sm:text-lg text-white truncate">{{ quiz.title }}</h1>
          <p class="text-xs text-slate-400 font-mono truncate">
            {{ quiz.subject || 'Quiz' }} • Proctored Assessment
          </p>
        </div>
      </div>

      <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
        <div class="flex items-center gap-1.5 bg-rose-950/80 border border-rose-800 px-3 py-1.5 rounded-xl">
          <span class="text-xs font-extrabold text-rose-300 uppercase">Warnings:</span>
          <div class="flex gap-1">
            <span v-for="n in maxWarnings" :key="n"
              :class="['w-2.5 h-2.5 rounded-full transition-all',
                n <= warningCount ? 'bg-rose-500 animate-pulse' : 'bg-slate-700']"></span>
          </div>
        </div>

        <div v-if="hasTimer"
          :class="[
            'px-4 py-1.5 rounded-xl font-mono text-sm font-black border transition-colors',
            secondsLeft < 180
              ? 'bg-rose-600 text-white border-rose-400 animate-pulse'
              : 'bg-slate-800 text-amber-300 border-slate-700'
          ]">
          <span class="inline-flex items-center gap-1.5">
            <Icon icon="clock" size="sm" />
            {{ formattedTimeLeft }}
          </span>
        </div>
      </div>
    </header>

    <!-- PROGRESS -->
    <div class="max-w-6xl w-full mx-auto px-4 sm:px-6 pt-4 shrink-0">
      <div class="flex justify-between text-xs text-slate-400 font-bold mb-1.5">
        <span>Question {{ currentIndex + 1 }} of {{ questions.length }}</span>
        <span>{{ progressPercent }}% Completed</span>
      </div>
      <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
        <div class="bg-emerald-500 h-full transition-all duration-300" :style="{ width: progressPercent + '%' }"></div>
      </div>
    </div>

    <!-- QUESTION CARD -->
    <div class="flex-1 flex items-center justify-center p-4 sm:p-6">
      <div v-if="currentQuestion" class="max-w-3xl w-full bg-slate-800/90 border border-slate-700/80 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
        <div class="space-y-1">
          <span class="text-xs font-black uppercase text-emerald-400 tracking-wider">
            Item {{ currentIndex + 1 }} • {{ questionTypeLabel(currentQuestion.type) }}
          </span>
          <h3 class="text-lg sm:text-xl font-bold leading-relaxed text-slate-100">
            {{ currentQuestion.question_text }}
          </h3>
        </div>

        <!-- MC / TF -->
        <div v-if="currentQuestion.type === 'multiple_choice' || currentQuestion.type === 'true_false'" class="space-y-3">
          <button v-for="(opt, idx) in currentQuestion.options" :key="opt.id"
            @click="selectOption(opt.id)"
            :class="[
              'w-full text-left p-4 rounded-2xl border text-sm font-semibold transition-all flex items-center justify-between cursor-pointer',
              answers[currentQuestion.id]?.question_option_id === opt.id
                ? 'bg-emerald-600 border-emerald-400 text-white shadow-lg translate-x-1'
                : 'bg-slate-900/60 border-slate-700 text-slate-300 hover:bg-slate-700/60'
            ]">
            <span>{{ opt.option_text }}</span>
            <span :class="[
              'w-6 h-6 rounded-full border flex items-center justify-center text-xs font-bold shrink-0',
              answers[currentQuestion.id]?.question_option_id === opt.id
                ? 'border-white bg-white text-emerald-900'
                : 'border-slate-600'
            ]">
              {{ String.fromCharCode(65 + idx) }}
            </span>
          </button>
        </div>

        <!-- Short answer -->
        <div v-else-if="currentQuestion.type === 'short_answer'" class="space-y-2">
          <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Your Answer</label>
          <input :value="answers[currentQuestion.id]?.answer_text ?? ''"
            @input="e => setText(e.target.value)"
            type="text"
            placeholder="Type your answer..."
            class="w-full bg-slate-900 border border-slate-700 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:border-emerald-500" />
        </div>

        <!-- Essay -->
        <div v-else-if="currentQuestion.type === 'essay'" class="space-y-2">
          <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Your Response</label>
          <textarea :value="answers[currentQuestion.id]?.answer_text ?? ''"
            @input="e => setText(e.target.value)"
            rows="6"
            placeholder="Write your essay response..."
            class="w-full bg-slate-900 border border-slate-700 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:border-emerald-500 resize-none"></textarea>
        </div>

        <!-- Autosave status -->
        <div class="text-[11px] text-slate-500 font-semibold flex items-center gap-1.5">
          <span :class="['w-2 h-2 rounded-full',
            saveStatus === 'saving' ? 'bg-amber-400 animate-pulse' :
            saveStatus === 'saved'  ? 'bg-emerald-500' :
            saveStatus === 'error'  ? 'bg-rose-500' :
            'bg-slate-600']"></span>
          <span>{{ saveStatusLabel }}</span>
        </div>
      </div>
    </div>

    <!-- NAVIGATION -->
    <footer class="max-w-6xl w-full mx-auto flex items-center justify-between gap-4 p-4 sm:p-6 border-t border-slate-800 shrink-0">
      <button @click="currentIndex--"
        :disabled="currentIndex === 0"
        class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold disabled:opacity-40 disabled:cursor-not-allowed transition-all active:scale-95">
        ← Previous
      </button>

      <div class="flex items-center gap-2">
        <button v-for="(q, idx) in questions" :key="q.id"
          @click="currentIndex = idx"
          :class="[
            'w-8 h-8 rounded-lg text-[10px] font-black transition-all',
            currentIndex === idx
              ? 'bg-emerald-500 text-white'
              : answers[q.id]?.question_option_id || answers[q.id]?.answer_text
                ? 'bg-emerald-900 text-emerald-300 border border-emerald-700'
                : 'bg-slate-800 text-slate-500 border border-slate-700 hover:bg-slate-700'
          ]">
          {{ idx + 1 }}
        </button>
      </div>

      <button v-if="currentIndex < questions.length - 1"
        @click="currentIndex++"
        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all active:scale-95">
        Next →
      </button>
      <button v-else
        @click="confirmSubmit"
        class="px-6 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-500 text-slate-900 text-xs font-black transition-all active:scale-95">
        Submit Assessment
      </button>
    </footer>

    <!-- WARNING MODAL -->
    <div v-if="showWarningModal" class="fixed inset-0 z-[60] bg-rose-950/90 backdrop-blur-md flex items-center justify-center p-4">
      <div class="bg-slate-900 text-white rounded-3xl max-w-md w-full p-6 text-center space-y-4 border border-rose-500/50 shadow-2xl">
        <div class="flex justify-center text-rose-500">
          <Icon icon="alert-triangle" size="xl" />
        </div>
        <h3 class="font-black text-rose-400 text-xl">SECURITY WARNING #{{ warningCount }}</h3>
        <p class="text-xs text-slate-300 leading-relaxed">
          Focus loss or exit from fullscreen mode was detected. This violation has been logged.
        </p>
        <p class="text-xs font-bold text-amber-300">Remaining Strikes: {{ maxWarnings - warningCount }}</p>
        <button @click="acknowledgeWarning"
          class="w-full bg-rose-600 hover:bg-rose-500 text-white text-xs font-black py-3 rounded-xl transition-all active:scale-95">
          Return to Exam
        </button>
      </div>
    </div>

    <!-- CONFIRM SUBMIT MODAL -->
    <div v-if="showConfirmModal" class="fixed inset-0 z-[60] bg-slate-950/85 backdrop-blur-md flex items-center justify-center p-4">
      <div class="bg-slate-900 text-white rounded-3xl max-w-md w-full p-6 space-y-4 border border-slate-700 shadow-2xl">
        <h3 class="font-black text-white text-lg">Submit Assessment?</h3>
        <p class="text-xs text-slate-300 leading-relaxed">
          You have answered <strong>{{ answeredCount }}</strong> of <strong>{{ questions.length }}</strong> questions.
          Once submitted, you cannot change your answers.
        </p>
        <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-800">
          <button @click="showConfirmModal = false"
            class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition-all active:scale-95">
            Cancel
          </button>
          <button @click="submitAttempt"
            class="px-6 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-500 text-slate-900 text-xs font-black transition-all active:scale-95">
            Confirm Submit
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import axios from 'axios'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  attempt:   { type: Object, default: () => ({}) },
  quiz:      { type: Object, default: () => ({}) },
  questions: { type: Array,  default: () => [] },
})

const currentIndex = ref(0)
const answers = ref({})
const saveStatus = ref('idle')
const showWarningModal = ref(false)
const showConfirmModal = ref(false)
const warningCount = ref(props.attempt.warning_count ?? 0)
const maxWarnings = computed(() => props.attempt.max_warnings ?? 3)

// Hydrate initial answers from server
props.questions.forEach(q => {
  answers.value[q.id] = {
    question_option_id: q.my_answer?.question_option_id ?? null,
    answer_text: q.my_answer?.answer_text ?? null,
  }
})

const currentQuestion = computed(() => props.questions[currentIndex.value] ?? null)
const answeredCount = computed(() => Object.values(answers.value).filter(a => a.question_option_id || (a.answer_text && a.answer_text.length)).length)
const progressPercent = computed(() => props.questions.length ? Math.round((answeredCount.value / props.questions.length) * 100) : 0)

// ─── Timer ──────────────────────────────────────────
const secondsLeft = ref(0)
const hasTimer = computed(() => !!props.attempt.expires_at)
let timerHandle = null

function initTimer() {
  if (!hasTimer.value) return
  const expires = new Date(props.attempt.expires_at).getTime()
  const tick = () => {
    const now = Date.now()
    secondsLeft.value = Math.max(0, Math.floor((expires - now) / 1000))
    const secondsLeft = ref(0)
    const hasTimer = computed(() => !!props.attempt.expires_at)
    let timerHandle = null

    function initTimer() {
      if (!hasTimer.value) return
      const expires = new Date(props.attempt.expires_at).getTime()
      const tick = () => {
        const now = Date.now()
        secondsLeft.value = Math.max(0, Math.floor((expires - now) / 1000))
        if (secondsLeft.value <= 0) {
          clearInterval(timerHandle)
          submitAttempt(true) // ✅ FIX: Trigger backend submission instead of just navigating
        }
      }
      tick()
      timerHandle = setInterval(tick, 1000)
    }
  }
  tick()
  timerHandle = setInterval(tick, 1000)
}

const formattedTimeLeft = computed(() => {
  const m = Math.floor(secondsLeft.value / 60)
  const s = secondsLeft.value % 60
  return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`
})

// ─── Autosave ───────────────────────────────────────
let saveTimer = null
function scheduleSave() {
  saveStatus.value = 'saving'
  clearTimeout(saveTimer)
  saveTimer = setTimeout(persistAnswer, 600)
}

async function persistAnswer() {
  const q = currentQuestion.value
  if (!q) return
  const a = answers.value[q.id]
  try {
    await axios.post(route('student.quiz-attempts.answer', props.attempt.id), {
      question_id: q.id,
      question_option_id: a.question_option_id,
      answer_text: a.answer_text,
    })
    saveStatus.value = 'saved'
    setTimeout(() => { if (saveStatus.value === 'saved') saveStatus.value = 'idle' }, 1500)
  } catch (e) {
    saveStatus.value = 'error'
  }
}

const saveStatusLabel = computed(() => ({
  idle:    'All changes saved',
  saving:  'Saving…',
  saved:   'Saved',
  error:   'Failed to save — will retry',
})[saveStatus.value] || '')

function selectOption(optionId) {
  const q = currentQuestion.value
  if (!q) return
  answers.value[q.id] = { question_option_id: optionId, answer_text: null }
  scheduleSave()
}

function setText(value) {
  const q = currentQuestion.value
  if (!q) return
  answers.value[q.id] = { question_option_id: null, answer_text: value }
  scheduleSave()
}

// ─── Anti-cheat ─────────────────────────────────────
function handleBlur() {
  if (document.hidden === false && window.outerWidth === 0) return // devtools edge case
  registerViolation()
}

function handleFullscreenChange() {
  if (!document.fullscreenElement) registerViolation()
}

async function registerViolation() {
  try {
    const res = await axios.post(route('student.quiz-attempts.warning', props.attempt.id))
    warningCount.value = res.data.warning_count ?? (warningCount.value + 1)
    if (res.data.auto_submitted) {
      router.visit(route('student.quiz-attempts.result', props.attempt.id))
    } else {
      showWarningModal.value = true
    }
  } catch (e) {
    // Silent fail — nothing we can do if the server rejects
  }
}

function acknowledgeWarning() {
  showWarningModal.value = false
  requestFullscreen()
}

function requestFullscreen() {
  if (document.documentElement.requestFullscreen) {
    document.documentElement.requestFullscreen().catch(() => {})
  }
}

function preventContextMenu(e) { e.preventDefault() }
function preventKeyCombos(e) {
  if (e.ctrlKey || e.metaKey || e.key === 'F12' || e.key === 'Escape') e.preventDefault()
}

// ─── Submit ─────────────────────────────────────────
function confirmSubmit() { showConfirmModal.value = true }

async function submitAttempt(isTimeout = false) {
  try {
    if (!isTimeout) {
      showConfirmModal.value = false
    }
    
    // ✅ FIX: Send the reason to the backend so it can mark the attempt as 'expired' vs 'manual'
    await axios.post(route('student.quiz-attempts.submit', props.attempt.id), {
      reason: isTimeout ? 'expired' : 'manual'
    })
    
    exitLockdown()
    router.visit(route('student.quiz-attempts.result', props.attempt.id))
  } catch (e) {
    // If it's an auto-submit on timeout, don't alert the user—just force navigate to results
    if (isTimeout) {
      exitLockdown()
      router.visit(route('student.quiz-attempts.result', props.attempt.id))
    } else {
      alert('Failed to submit. Please try again.')
    }
  }
}

function exitLockdown() {
  if (document.fullscreenElement && document.exitFullscreen) {
    document.exitFullscreen().catch(() => {})
  }
}

function questionTypeLabel(type) {
  return {
    multiple_choice: 'Multiple Choice',
    true_false:      'True / False',
    short_answer:    'Short Answer',
    essay:           'Essay',
  }[type] || 'Question'
}

// ─── Lifecycle ──────────────────────────────────────
onMounted(() => {
  requestFullscreen()
  initTimer()
  window.addEventListener('blur', handleBlur)
  document.addEventListener('fullscreenchange', handleFullscreenChange)
  document.addEventListener('contextmenu', preventContextMenu)
  document.addEventListener('keydown', preventKeyCombos)
})

onUnmounted(() => {
  clearInterval(timerHandle)
  clearTimeout(saveTimer)
  window.removeEventListener('blur', handleBlur)
  document.removeEventListener('fullscreenchange', handleFullscreenChange)
  document.removeEventListener('contextmenu', preventContextMenu)
  document.removeEventListener('keydown', preventKeyCombos)
})
</script>