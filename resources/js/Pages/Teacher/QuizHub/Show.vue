<template>
  <Head :title="`${quiz.title} - Quiz Manager - Salawag LMS`" />

  <AuthenticatedLayout searchPlaceholder="Search quiz questions, bank items..">
    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2 max-w-full">

      <!-- HERO (unchanged) -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[240px] flex flex-col justify-center">
        <img :src="heroImage" alt="Quiz Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 max-w-3xl space-y-3">
          <div class="flex flex-wrap items-center gap-2">
            <span :class="quiz.is_published
              ? 'bg-emerald-500/90 text-white'
              : 'bg-slate-200/90 text-slate-800'"
              class="text-[10px] sm:text-xs font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon :icon="quiz.is_published ? 'check-circle' : 'edit'" size="xs" />
              {{ quiz.is_published ? 'Published' : 'Draft' }}
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="academic-cap" size="xs" />
              {{ quiz.subject }} • {{ quiz.section }}
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="award" size="xs" />
              {{ quiz.total_points }} pts
            </span>
          </div>

          <div class="space-y-1.5">
            <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
              {{ quiz.title }}
            </h2>
            <p v-if="quiz.description" class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium max-w-2xl">
              {{ quiz.description }}
            </p>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <BackButton fallback="teacher.quizzes.index"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              Back
            </BackButton>
            <button @click="togglePublish" :disabled="publishing"
              :class="quiz.is_published
                ? 'bg-white/15 hover:bg-white/25 text-white border-white/20'
                : 'bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D]'"
              class="inline-flex items-center gap-1.5 backdrop-blur-md border px-3.5 py-2 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 disabled:opacity-60">
              <Icon :icon="quiz.is_published ? 'x-circle' : 'check-circle'" size="xs" />
              {{ publishing ? 'Working…' : (quiz.is_published ? 'Unpublish' : 'Publish') }}
            </button>
            <Link :href="route('teacher.quizzes.submissions.index', quiz.id)"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              <Icon icon="chart-bar" size="xs" />
              Submissions
            </Link>
            <button @click="confirmDelete = true"
              class="inline-flex items-center gap-1.5 bg-rose-500/90 hover:bg-rose-600 text-white px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95">
              <Icon icon="trash" size="xs" />
              Delete
            </button>
          </div>
        </div>
      </div>

      <!-- META + SETTINGS ROW (unchanged) -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Time & Window</span>
          <div class="space-y-2 text-xs">
            <div class="flex items-center justify-between">
              <span class="text-slate-500 dark:text-slate-400 font-semibold">Time limit</span>
              <span class="font-black text-slate-900 dark:text-white">{{ quiz.time_limit_minutes || 'None' }}{{ quiz.time_limit_minutes ? ' min' : '' }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-slate-500 dark:text-slate-400 font-semibold">Passing score</span>
              <span class="font-black text-slate-900 dark:text-white">{{ quiz.passing_score ?? '—' }}{{ quiz.passing_score ? '%' : '' }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-slate-500 dark:text-slate-400 font-semibold">Opens</span>
              <span class="font-bold text-slate-800 dark:text-slate-200 text-[11px]">{{ formatShort(quiz.available_from) }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-slate-500 dark:text-slate-400 font-semibold">Closes</span>
              <span class="font-bold text-slate-800 dark:text-slate-200 text-[11px]">{{ formatShort(quiz.available_until) }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Behavior</span>
          <div class="space-y-1.5 text-xs">
            <button
              v-for="s in settingsRows"
              :key="s.key"
              type="button"
              @click="toggleSetting(s.key)"
              :disabled="togglingKey === s.key"
              class="w-full flex items-center justify-between py-1.5 rounded-lg px-1.5 hover:bg-slate-50 dark:hover:bg-[#232D26] transition-colors disabled:opacity-50">
              <span class="text-slate-600 dark:text-slate-300 font-semibold text-left">{{ s.label }}</span>
              <span
                :class="s.on
                  ? 'bg-emerald-500 border-emerald-500'
                  : 'bg-slate-300 dark:bg-[#3F4F43] border-slate-300 dark:border-[#3F4F43]'"
                class="relative inline-flex h-5 w-9 shrink-0 rounded-full border transition-colors">
                <span
                  :class="s.on ? 'translate-x-4' : 'translate-x-0.5'"
                  class="absolute top-1/2 -translate-y-1/2 h-3.5 w-3.5 rounded-full bg-white shadow transition-transform"></span>
              </span>
            </button>
          </div>
        </div>

        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Attached</span>
          <div class="text-3xl font-black text-slate-900 dark:text-white">{{ questions.length }}</div>
          <p class="text-[11px] font-medium text-slate-500">Questions attached</p>
          <p class="text-[11px] font-medium text-slate-500">Total weight: <strong class="text-slate-800 dark:text-slate-200">{{ quiz.total_points }} pts</strong></p>
        </div>
      </div>

      <!-- QUESTIONS SECTION -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

        <!-- Attached questions -->
        <!-- Attached questions -->
<div
  :class="[
    'lg:col-span-8 bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-6 shadow-sm border space-y-4 transition-colors',
    draggingType === 'bank'
      ? 'border-[#005506] dark:border-[#86EFAC] ring-2 ring-[#005506]/20'
      : 'border-slate-200/60 dark:border-[#3F4F43]'
  ]">
  <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
    <div class="w-2.5 h-7 bg-[#005506] dark:bg-[#86EFAC] rounded-full"></div>
    <div class="flex-1">
      <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Quiz Questions</h3>
      <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
        Drag to reorder · drag out to the bank to remove
      </p>
    </div>
  </div>

  <div v-if="localQuestions.length" class="space-y-3">
    <div
      v-for="(q, i) in localQuestions"
      :key="q.id"
      draggable="true"
      @dragstart="onQuizDragStart(i, $event)"
      @dragover.prevent="onQuizDragOver(i)"
      @dragleave="onQuizDragLeave(i)"
      @drop.stop.prevent="onQuizDrop(i)"
      @dragend="onDragEnd"
      :class="[
        'bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2 cursor-move transition-all select-none',
        dragOverIndex === i && draggingType === 'quiz' ? 'ring-2 ring-[#005506] dark:ring-[#86EFAC] scale-[1.01]' : '',
        dragOverIndex === i && draggingType === 'bank' ? 'ring-2 ring-dashed ring-emerald-500' : '',
        draggingIndex === i ? 'opacity-40' : ''
      ]">
      <div class="flex items-start justify-between gap-3">
        <div class="flex items-center gap-2 shrink-0">
          <span class="w-7 h-7 rounded-lg bg-[#005506] text-white font-black text-[11px] flex items-center justify-center">
            {{ String(i + 1).padStart(2, '0') }}
          </span>
          <span :class="typeBadgeClass(q.type)" class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md border">
            {{ typeLabel(q.type) }}
          </span>
          <Icon icon="menu" size="xs" class="text-slate-400" />
        </div>
        <button @click="detachQuestion(q)"
          class="w-7 h-7 rounded-lg bg-white dark:bg-[#2D3A31] hover:bg-rose-50 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center transition-colors shrink-0"
          title="Remove from quiz">
          <Icon icon="x" size="xs" />
        </button>
      </div>
      <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-relaxed line-clamp-3">
        {{ q.question_text }}
      </p>
      <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500">
        <span>{{ q.points_override ?? q.points }} pts</span>
        <span>•</span>
        <span>{{ q.options?.length || 0 }} options</span>
      </div>
    </div>
  </div>

  <div v-else
    @dragover.prevent="emptyDragOver = true"
    @dragleave="emptyDragOver = false"
    @drop.prevent="onEmptyDrop"
    :class="[
      'rounded-2xl p-8 text-center border border-dashed transition-colors',
      emptyDragOver && draggingType === 'bank'
        ? 'border-[#005506] dark:border-[#86EFAC] bg-emerald-50 dark:bg-emerald-950/30'
        : 'border-slate-300 dark:border-[#3F4F43] bg-[#F9F7F1] dark:bg-[#232D26]'
    ]">
    <Icon icon="clipboard-list" size="lg" class="text-slate-400 mx-auto mb-2" />
    <p class="text-xs font-bold text-slate-700 dark:text-white">No questions attached yet</p>
    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
      Drag questions here from the bank, or click them to attach.
    </p>
  </div>
</div>

<!-- Attach from bank -->
<div
  @dragover.prevent="onBankPanelDragOver"
  @dragleave="onBankPanelDragLeave"
  @drop.prevent="onBankPanelDrop"
  :class="[
    'lg:col-span-4 bg-white dark:bg-[#2D3A31] rounded-2xl p-4 sm:p-6 shadow-sm border space-y-4 sticky top-4 transition-all',
    draggingType === 'quiz'
      ? 'border-rose-400 dark:border-rose-500 ring-2 ring-rose-400/30 bg-rose-50/40 dark:bg-rose-950/20'
      : 'border-slate-200/60 dark:border-[#3F4F43]'
  ]">
  <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
    <Icon icon="book-open" size="md" class="text-[#005506] dark:text-[#86EFAC]" />
    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Attach from Bank</h3>
  </div>

  <p v-if="draggingType === 'quiz'" class="text-[10px] font-black text-rose-600 dark:text-rose-400 -mt-1 uppercase tracking-wider">
    Drop here to remove from quiz
  </p>
  <p v-else-if="quizSubjectId" class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 -mt-1">
    Showing items for <strong class="text-slate-700 dark:text-slate-200">{{ quiz.subject }}</strong> only.
  </p>

  <div class="relative">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
      <Icon icon="search" size="sm" />
    </span>
    <input v-model="bankSearch" type="text" placeholder="Search your bank..."
      class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-xs font-semibold text-slate-700 dark:text-slate-200" />
  </div>

  <div v-if="bankLoading" class="py-8 text-center">
    <div class="w-6 h-6 border-4 border-emerald-200 dark:border-emerald-900/60 border-t-[#005506] dark:border-t-[#86EFAC] rounded-full animate-spin mx-auto"></div>
  </div>

  <div v-else-if="filteredBank.length" class="space-y-2 max-h-96 overflow-y-auto pr-1">
    <div
      v-for="bq in filteredBank"
      :key="bq.id"
      role="button"
      tabindex="0"
      :draggable="!isAttached(bq.id)"
      @dragstart="onBankDragStart(bq, $event)"
      @dragend="onDragEnd"
      @click="attachQuestion(bq)"
      @keydown.enter="attachQuestion(bq)"
      :class="[
        'w-full text-left bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] transition-colors',
        isAttached(bq.id)
          ? 'opacity-50 cursor-not-allowed'
          : 'hover:border-[#005506] dark:hover:border-[#86EFAC] cursor-grab active:cursor-grabbing'
      ]">
      <div class="flex items-center gap-2 mb-1.5">
        <span :class="typeBadgeClass(bq.type)" class="text-[9px] font-black uppercase px-2 py-0.5 rounded border">
          {{ typeLabel(bq.type) }}
        </span>
        <span class="text-[10px] font-black text-slate-500">{{ bq.points }} pt</span>
        <span v-if="isAttached(bq.id)" class="ml-auto text-[9px] font-black text-emerald-600 dark:text-[#86EFAC]">✓ ATTACHED</span>
        <span v-else-if="attachingId === bq.id" class="ml-auto text-[9px] font-black text-slate-400">ATTACHING…</span>
      </div>
      <p class="text-[11px] font-semibold text-slate-800 dark:text-slate-100 leading-snug line-clamp-2">
        {{ bq.question_text }}
      </p>
    </div>
  </div>

  <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-xl p-4 text-center border border-dashed border-slate-300 dark:border-[#3F4F43]">
    <p class="text-[11px] text-slate-500 dark:text-slate-400 italic">
      No bank items match this quiz’s subject.
    </p>
  </div>

  <Link :href="route('teacher.questions.index')"
    class="w-full bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-xs font-bold py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
    <Icon icon="plus" size="xs" />
    Manage Question Bank
  </Link>
</div>

      </div>
    </div>

    <!-- DELETE CONFIRM MODAL (unchanged) -->
    <div v-if="confirmDelete" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4"
      @click.self="confirmDelete = false">
      <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-2xl w-full max-w-md p-6 space-y-4 border border-slate-100 dark:border-[#3F4F43]">
        <div class="flex items-start gap-3">
          <div class="w-11 h-11 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40">
            <Icon icon="trash" size="md" />
          </div>
          <div>
            <h3 class="font-black text-slate-900 dark:text-white text-base">Delete this quiz?</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">This cannot be undone. If any student has attempted it, deletion will be refused.</p>
          </div>
        </div>
        <div class="flex items-center justify-end gap-2 pt-1">
          <button @click="confirmDelete = false"
            class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white text-xs font-bold transition-colors">
            Cancel
          </button>
          <button @click="destroyQuiz" :disabled="deleting"
            class="bg-rose-600 hover:bg-rose-700 text-white px-6 py-2.5 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2">
            <Icon icon="trash" size="xs" />
            {{ deleting ? 'Deleting…' : 'Delete Quiz' }}
          </button>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import BackButton from '@/Components/BackButton.vue'
import heroImage from '../../../../assets/img/local/quiz-hub-hero.jpg'

const props = defineProps({
  quiz:      { type: Object, default: () => ({}) },
  questions: { type: Array,  default: () => [] },
})

const publishing = ref(false)
const deleting = ref(false)
const confirmDelete = ref(false)

// ── local mirror of props.questions for optimistic reorder ──
const localQuestions = ref([...props.questions])
watch(
  () => props.questions,
  (val) => { localQuestions.value = [...val] },
  { deep: true }
)

// ── bank ────────────────────────────────────────────────────
const bankSearch = ref('')
const bankLoading = ref(false)
const bankItems = ref([])
const attachingId = ref(null)

// ── behavior toggles ────────────────────────────────────────
const togglingKey = ref(null)
const settingOverrides = ref({})

const settingsRows = computed(() => {
  const ov = settingOverrides.value
  const pick = (k) => (ov[k] !== undefined ? ov[k] : !!props.quiz[k])
  return [
    { key: 'shuffle_questions',        label: 'Shuffle questions',       on: pick('shuffle_questions') },
    { key: 'shuffle_options',          label: 'Shuffle options',         on: pick('shuffle_options') },
    { key: 'show_score_immediately',   label: 'Show score immediately',  on: pick('show_score_immediately') },
    { key: 'show_correct_answers',     label: 'Show correct answers',    on: pick('show_correct_answers') },
    { key: 'show_explanations',        label: 'Show explanations',       on: pick('show_explanations') },
  ]
})

function toggleSetting(key) {
  const current = settingOverrides.value[key] !== undefined
    ? settingOverrides.value[key]
    : !!props.quiz[key]
  const next = !current

  settingOverrides.value = { ...settingOverrides.value, [key]: next }
  togglingKey.value = key

  axios.put(route('teacher.quizzes.update', props.quiz.id), { [key]: next })
    .then(() => {
      // Clear override so the fresh prop takes over on next render
      const copy = { ...settingOverrides.value }
      delete copy[key]
      settingOverrides.value = copy
      router.reload({ only: ['quiz'], preserveScroll: true, preserveState: true })
    })
    .catch(e => {
      settingOverrides.value = { ...settingOverrides.value, [key]: current }
      alert(e.response?.data?.message || 'Could not update setting.')
    })
    .finally(() => { togglingKey.value = null })
}

// ── drag state ──────────────────────────────────────────────
const draggingType   = ref(null)     // 'bank' | 'quiz'
const draggingBankId = ref(null)
const draggingQuizId = ref(null)
const draggingIndex  = ref(null)
const dragOverIndex  = ref(null)
const emptyDragOver  = ref(false)

const attachedIds = computed(() => new Set(localQuestions.value.map(q => q.id)))
const quizSubjectId = computed(() => props.quiz.subject_id ?? null)

const filteredBank = computed(() => {
  let items = bankItems.value
  if (quizSubjectId.value != null) {
    items = items.filter(item => item.subject_id === quizSubjectId.value)
  }
  const q = bankSearch.value.trim().toLowerCase()
  if (!q) return items
  return items.filter(item => (item.question_text || '').toLowerCase().includes(q))
})

function isAttached(id) { return attachedIds.value.has(id) }
function bankItemById(id) { return bankItems.value.find(b => b.id === id) || null }

async function loadBank() {
  bankLoading.value = true
  try {
    const { data } = await axios.get(route('teacher.questions.index'), {
      headers: { Accept: 'application/json' },
      params: {
        per_page: 100,
        subject_id: quizSubjectId.value ?? undefined,
      },
    })
    bankItems.value = data.questions?.data ?? []
  } catch (e) {
    console.error('Failed to load bank', e)
  } finally {
    bankLoading.value = false
  }
}
onMounted(loadBank)

// ── publish / delete ────────────────────────────────────────
function togglePublish() {
  publishing.value = true
  axios.put(route('teacher.quizzes.toggle-publish', props.quiz.id))
    .then(() => router.reload({ only: ['quiz'], preserveScroll: true, preserveState: true }))
    .catch(e => alert(e.response?.data?.message || 'Action failed.'))
    .finally(() => { publishing.value = false })
}

function destroyQuiz() {
  deleting.value = true
  router.delete(route('teacher.quizzes.destroy', props.quiz.id), {
    onFinish: () => { deleting.value = false },
  })
}

// ── attach / detach ─────────────────────────────────────────
function attachQuestion(bq) {
  if (!bq || isAttached(bq.id)) return
  attachingId.value = bq.id
  axios.post(route('teacher.quizzes.attach-questions', props.quiz.id), {
    question_ids: [bq.id],
  })
    .then(() => router.reload({ only: ['questions', 'quiz'], preserveScroll: true, preserveState: true }))
    .catch(e => alert(e.response?.data?.message || 'Attach failed.'))
    .finally(() => { attachingId.value = null })
}

function detachQuestion(q) {
  if (!q) return
  axios.delete(route('teacher.quizzes.detach-question', { quiz: props.quiz.id, question: q.id }))
    .then(() => router.reload({ only: ['questions', 'quiz'], preserveScroll: true, preserveState: true }))
    .catch(e => alert(e.response?.data?.message || 'Detach failed.'))
}

// ── drag: bank item ─────────────────────────────────────────
function onBankDragStart(bq, e) {
  if (isAttached(bq.id)) { e.preventDefault(); return }
  draggingType.value   = 'bank'
  draggingBankId.value = bq.id
  draggingQuizId.value = null
  draggingIndex.value  = null
  try {
    e.dataTransfer.effectAllowed = 'copy'
    e.dataTransfer.setData('text/plain', String(bq.id))
  } catch {}
}

// ── drag: quiz item ─────────────────────────────────────────
function onQuizDragStart(i, e) {
  const q = localQuestions.value[i]
  draggingType.value   = 'quiz'
  draggingIndex.value  = i
  draggingQuizId.value = q?.id ?? null
  draggingBankId.value = null
  try {
    e.dataTransfer.effectAllowed = 'move'
    if (q) e.dataTransfer.setData('text/plain', String(q.id))
  } catch {}
}

function onQuizDragOver(i) {
  if (draggingType.value === 'quiz' && draggingIndex.value === i) {
    dragOverIndex.value = null
    return
  }
  dragOverIndex.value = i
}
function onQuizDragLeave(i) {
  if (dragOverIndex.value === i) dragOverIndex.value = null
}

function onQuizDrop(i) {
  if (draggingType.value === 'bank' && draggingBankId.value != null) {
    const bq = bankItemById(draggingBankId.value)
    if (bq && !isAttached(bq.id)) attachQuestion(bq)
  } else if (draggingType.value === 'quiz' && draggingIndex.value != null) {
    reorderQuestions(draggingIndex.value, i)
  }
  onDragEnd()
}

function onEmptyDrop() {
  if (draggingType.value === 'bank' && draggingBankId.value != null) {
    const bq = bankItemById(draggingBankId.value)
    if (bq && !isAttached(bq.id)) attachQuestion(bq)
  }
  onDragEnd()
}

// ── drag: drop on bank panel = detach ───────────────────────
function onBankPanelDragOver() {
  if (draggingType.value === 'quiz') {
    // allowed
  }
}
function onBankPanelDragLeave() {}
function onBankPanelDrop() {
  if (draggingType.value === 'quiz' && draggingQuizId.value != null) {
    const q = localQuestions.value.find(x => x.id === draggingQuizId.value)
    if (q) detachQuestion(q)
  }
  onDragEnd()
}

function onDragEnd() {
  draggingType.value   = null
  draggingBankId.value = null
  draggingQuizId.value = null
  draggingIndex.value  = null
  dragOverIndex.value  = null
  emptyDragOver.value  = false
}

// ── optimistic reorder ──────────────────────────────────────
function reorderQuestions(fromIdx, toIdx) {
  if (fromIdx === toIdx) return
  const list = [...localQuestions.value]
  if (fromIdx < 0 || fromIdx >= list.length) return

  const [moved] = list.splice(fromIdx, 1)
  list.splice(toIdx, 0, moved)
  localQuestions.value = list   // ⚡ instant UI update

  const payload = list.map((q, i) => ({
    id: q.id,
    position: i,
    points_override: q.points_override ?? null,
  }))

  axios.put(route('teacher.quizzes.reorder-questions', props.quiz.id), {
    questions: payload,
  })
    .then(() => {
      // refresh total_points etc. but keep local order
      router.reload({ only: ['quiz'], preserveScroll: true, preserveState: true })
    })
    .catch(e => {
      alert(e.response?.data?.message || 'Reorder failed — restoring order.')
      localQuestions.value = [...props.questions]
    })
}

// ── helpers ─────────────────────────────────────────────────
function typeLabel(type) {
  return {
    multiple_choice: 'MC',
    true_false:      'T/F',
    short_answer:    'Short',
    essay:           'Essay',
  }[type] || type
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
</style>