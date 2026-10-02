<template>
  <Head :title="`Grades — ${classroom.subject || 'Subject'} - Salawag LMS`" />

  <div
    :class="[
      'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
      `text-scale-${fontSizeMode}`
    ]"
  >
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search assessments, categories, or scores..."
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
                <span class="text-white">SUBJECT</span>
                <span class="animated-stroke-text">GRADES</span>
              </div>
              <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
                Category breakdown and per-assessment scores.
              </p>
              <div class="flex items-center gap-2 pt-1 max-w-xl">
                <div class="h-[1.5px] w-full bg-white/40"></div>
                <span class="text-white text-xs animate-spin-slow">★</span>
              </div>
            </div>

            <Link :href="route('student.grades.index')"
              class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 self-start">
              ← All Grades
            </Link>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2 relative z-10">
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center text-3xl transition-transform hover:scale-105 duration-300">
                📈
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10 animate-float-soft">
                  {{ classroom.section || '—' }}
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  {{ classroom.subject || 'Untitled Subject' }}
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  Teacher: {{ classroom.teacher || '—' }}
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-2 gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                <span class="text-[11px] font-medium text-emerald-100/80">Final Grade</span>
                <div :class="['text-2xl font-extrabold my-0.5', finalGradeTextColor]">
                  {{ formatScore(grades.final_grade) }}
                </div>
                <span class="text-[10px] text-emerald-100/70">{{ remarksLabel(grades.remarks) }}</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                <span class="text-[11px] font-medium text-emerald-100/80">Assessments</span>
                <div class="text-2xl font-extrabold text-white my-0.5">
                  {{ gradedCount }}/{{ assignments.length }}
                </div>
                <span class="text-[10px] text-emerald-100/70">Graded items</span>
              </div>
            </div>
          </div>
        </div>

        <!-- CATEGORY BREAKDOWN -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-5">
          <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
            <div>
              <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-none">
                Category Breakdown
              </h3>
              <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                DepEd DO 8, s. 2015 weighted components
              </p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div
              v-for="cat in categories"
              :key="cat.key"
              class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-5 border border-slate-200/80 dark:border-[#3F4F43] space-y-3 hover:-translate-y-1 hover:shadow-md transition-all duration-300"
            >
              <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                  {{ cat.label }}
                </span>
                <span class="text-[10px] font-extrabold text-[#004d08] dark:text-[#86EFAC] bg-emerald-100 dark:bg-emerald-950/60 px-2 py-0.5 rounded-full">
                  Weight: {{ cat.weight }}%
                </span>
              </div>

              <div class="flex items-end justify-between gap-2">
                <div :class="['text-3xl font-black', gradeColor(grades[cat.key])]">
                  {{ formatScore(grades[cat.key]) }}
                </div>
                <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold text-right leading-tight">
                  {{ categoryCount(cat.key) }}<br>items
                </div>
              </div>

              <div class="w-full h-2 bg-slate-100 dark:bg-[#2D3A31] rounded-full overflow-hidden">
                <div
                  class="h-full rounded-full transition-all duration-500"
                  :class="progressBarColor(grades[cat.key])"
                  :style="{ width: `${Math.min(parseFloat(grades[cat.key] || 0), 100)}%` }"
                ></div>
              </div>
            </div>
          </div>
        </div>

        <!-- ASSESSMENTS LIST -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-5">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="flex items-center gap-3">
              <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
              <div>
                <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-none">
                  Assessment Scores
                </h3>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                  Every published assessment in this subject
                </p>
              </div>
            </div>

            <div class="flex items-center gap-1.5 bg-[#f5f7f2] dark:bg-[#232D26] p-1.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] overflow-x-auto">
              <button
                v-for="f in filters"
                :key="f.id"
                @click="activeFilter = f.id"
                :class="[
                  'px-3 py-1.5 rounded-xl text-xs font-extrabold transition-all whitespace-nowrap active:scale-95',
                  activeFilter === f.id
                    ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-xs'
                    : 'text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-[#3F4F43]'
                ]"
              >
                {{ f.label }}
              </button>
            </div>
          </div>

          <div v-if="filteredAssignments.length" class="overflow-x-auto rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31]">
            <table class="w-full text-left border-collapse min-w-[720px]">
              <thead>
                <tr class="bg-[#f5f7f2] dark:bg-[#232D26] text-[#004d08] dark:text-[#86EFAC] text-[11px] font-extrabold uppercase border-b border-slate-200/80 dark:border-[#3F4F43] divide-x divide-slate-200/60 dark:divide-[#3F4F43]">
                  <th class="py-3.5 px-4 w-12 text-center">#</th>
                  <th class="py-3.5 px-5">Assessment Title</th>
                  <th class="py-3.5 px-4 w-32 text-center">Category</th>
                  <th class="py-3.5 px-4 w-28 text-center">Due</th>
                  <th class="py-3.5 px-4 w-24 text-center">Score</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200/60 dark:divide-[#3F4F43] text-xs font-medium text-slate-800 dark:text-slate-200">
                <tr
                  v-for="(a, idx) in filteredAssignments"
                  :key="a.id"
                  class="hover:bg-emerald-50/30 dark:hover:bg-[#232D26]/60 transition-colors divide-x divide-slate-200/60 dark:divide-[#3F4F43]"
                >
                  <td class="py-3.5 px-4 text-center font-bold text-slate-400 dark:text-slate-500">
                    {{ String(idx + 1).padStart(2, '0') }}
                  </td>

                  <td class="py-3.5 px-5">
                    <Link :href="route('student.assignments.show', a.id)"
                      class="font-bold text-slate-900 dark:text-white hover:text-[#004d08] dark:hover:text-[#86EFAC] hover:underline transition-colors">
                      {{ a.title }}
                    </Link>
                  </td>

                  <td class="py-3.5 px-4 text-center">
                    <span :class="categoryBadgeClass(a.category)"
                      class="inline-block text-[10px] font-black uppercase px-2.5 py-1 rounded-full border whitespace-nowrap">
                      {{ categoryLabel(a.category) }}
                    </span>
                  </td>

                  <td class="py-3.5 px-4 text-center text-slate-500 dark:text-slate-400 font-semibold text-[11px]">
                    {{ formatDate(a.due_at) }}
                  </td>

                  <td class="py-3.5 px-4 text-center">
                    <span v-if="a.grade !== null && a.grade !== undefined"
                      :class="['font-black text-sm', gradeColor(a.grade)]">
                      {{ formatScore(a.grade) }}
                      <span class="text-[10px] font-normal text-slate-400">/ {{ formatScore(a.points) }}</span>
                    </span>
                    <span v-else class="inline-flex items-center gap-1 bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400 text-[10px] font-bold px-2.5 py-1 rounded-full border border-slate-200 dark:border-[#3F4F43]">
                      Not Graded
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
            <div class="text-4xl">📝</div>
            <p class="text-sm font-bold text-slate-800 dark:text-white">No assessments in this view</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              Try switching the filter or waiting for your teacher to publish an assessment.
            </p>
          </div>
        </div>

        <!-- TRANSMUTATION INFO -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40 text-lg">
              ℹ️
            </div>
            <div class="space-y-1">
              <h4 class="text-sm font-extrabold text-slate-800 dark:text-white">How your grade is computed</h4>
              <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                Each category average is computed from the ratio of earned to possible points across graded
                assessments. The final grade is the weighted sum using the school's configured weights
                (WW {{ weights.written_work ?? 0 }}% • PT {{ weights.performance_task ?? 0 }}% • QE {{ weights.quarterly_exam ?? 0 }}%).
                Remarks follow DepEd DO 8, s. 2015 descriptors.
              </p>
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

const props = defineProps({
  classroom:    { type: Object, default: () => ({}) },
  grades:       { type: Object, default: () => ({}) },
  weights:      { type: Object, default: () => ({}) },
  assignments:  { type: Array,  default: () => [] },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')
const activeFilter = ref('all')

const filters = [
  { id: 'all',              label: 'All' },
  { id: 'written_work',     label: 'Written Work' },
  { id: 'performance_task', label: 'Performance Task' },
  { id: 'quarterly_exam',   label: 'Exam' },
]

const categories = computed(() => [
  { key: 'written_work',     label: 'Written Work',     weight: props.weights?.written_work ?? 25 },
  { key: 'performance_task', label: 'Performance Task', weight: props.weights?.performance_task ?? 50 },
  { key: 'quarterly_exam',   label: 'Quarterly Exam',   weight: props.weights?.quarterly_exam ?? 25 },
])

const filteredAssignments = computed(() => {
  const list = props.assignments ?? []
  if (activeFilter.value === 'all') return list
  return list.filter(a => a.category === activeFilter.value)
})

const gradedCount = computed(() =>
  (props.assignments ?? []).filter(a => a.grade !== null && a.grade !== undefined).length
)

const finalGradeTextColor = computed(() => {
  const val = props.grades?.final_grade
  if (val === null || val === undefined) return 'text-amber-300'
  const num = parseFloat(val)
  if (isNaN(num)) return 'text-amber-300'
  if (num >= 75) return 'text-amber-300'
  return 'text-rose-300'
})

// ─── Helpers ────────────────────────────────────────
function formatScore(value) {
  if (value === null || value === undefined || value === '') return '—'
  const num = parseFloat(value)
  if (isNaN(num)) return '—'
  return num.toFixed(2)
}

function formatDate(value) {
  if (!value) return '—'
  try {
    return new Date(value).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
  } catch { return '—' }
}

function gradeColor(value) {
  if (value === null || value === undefined || value === '') return 'text-slate-400 dark:text-slate-500'
  const num = parseFloat(value)
  if (isNaN(num)) return 'text-slate-400 dark:text-slate-500'
  if (num >= 90) return 'text-[#004d08] dark:text-[#86EFAC]'
  if (num >= 85) return 'text-teal-700 dark:text-teal-400'
  if (num >= 80) return 'text-blue-700 dark:text-blue-400'
  if (num >= 75) return 'text-amber-700 dark:text-amber-400'
  return 'text-rose-600 dark:text-rose-400'
}

function progressBarColor(value) {
  if (value === null || value === undefined || value === '') return 'bg-slate-300 dark:bg-[#3F4F43]'
  const num = parseFloat(value)
  if (isNaN(num)) return 'bg-slate-300 dark:bg-[#3F4F43]'
  if (num >= 90) return 'bg-[#004d08] dark:bg-[#86EFAC]'
  if (num >= 85) return 'bg-teal-600 dark:bg-teal-400'
  if (num >= 80) return 'bg-blue-600 dark:bg-blue-400'
  if (num >= 75) return 'bg-amber-500 dark:bg-amber-400'
  return 'bg-rose-500 dark:bg-rose-400'
}

function categoryCount(categoryKey) {
  return (props.assignments ?? []).filter(a => a.category === categoryKey).length
}

function categoryLabel(cat) {
  return {
    written_work:     'Written Work',
    performance_task: 'Performance Task',
    quarterly_exam:   'Quarterly Exam',
  }[cat] || (cat || 'General')
}

function categoryBadgeClass(cat) {
  return {
    written_work:     'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    performance_task: 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40',
    quarterly_exam:   'bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-900/40',
  }[cat] || 'bg-slate-100 dark:bg-[#232D26] text-slate-700 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'
}

function remarksLabel(remarks) {
  return {
    outstanding:               'Outstanding',
    very_satisfactory:         'Very Satisfactory',
    satisfactory:              'Satisfactory',
    fairly_satisfactory:       'Fairly Satisfactory',
    did_not_meet_expectations: 'Did Not Meet',
  }[remarks] || '—'
}
</script>

<style scoped>
.animated-stroke-text { color: transparent; -webkit-text-stroke: 1.5px #ffffff; }

@keyframes fadeInDown  { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px);  } to { opacity: 1; transform: translateY(0); } }
@keyframes floatSoft   { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
@keyframes sheenMove   { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
@keyframes spinSlow    { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

.animate-fade-in-down  { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
.animate-float-soft    { animation: floatSoft 3s ease-in-out infinite; }
.animate-sheen         { animation: sheenMove 4s ease-in-out infinite; }
.animate-spin-slow     { display: inline-block; animation: spinSlow 12s linear infinite; }

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
</style>