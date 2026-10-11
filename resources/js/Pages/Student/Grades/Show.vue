<template>
  <Head :title="`Grades — ${classroom.subject || 'Subject'} - Salawag LMS`" />

  <StudentLayout>
    <div class="space-y-6 pb-10 font-['Inter'] min-w-0">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[260px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <img
          src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=1600&auto=format&fit=crop"
          alt="Subject Grades Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

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
                  SUBJECT GRADES
                </div>
              </div>

              <div class="space-y-1">
                <h1 class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                  <span>SUBJECT</span>
                  <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">GRADES</span>
                </h1>
                <p class="text-white/90 text-xs sm:text-sm leading-relaxed font-medium italic">
                  "Category breakdown and per-assessment scores."
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
                <Icon icon="chart-bar" size="lg" class="text-white" />
              </div>

              <div class="space-y-0.5 min-w-0">
                <div class="inline-block bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-[11px] font-black px-3 py-0.5 rounded-full shadow-xs uppercase tracking-wide">
                  {{ classroom.section || '—' }}
                </div>
                <h2 class="text-base sm:text-xl font-black text-white tracking-tight leading-snug truncate">
                  {{ classroom.subject || 'Untitled Subject' }}
                </h2>
                <p class="text-white/90 text-xs font-medium truncate">
                  Teacher: <span class="font-bold">{{ classroom.teacher || '—' }}</span>
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-2 gap-2.5 sm:gap-3 w-full">
              <div class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl p-3 sm:p-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between shadow-sm min-h-[75px]">
                <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">Final Grade</span>
                <div :class="['text-lg sm:text-2xl font-black leading-tight my-0.5', finalGradeTextColor]">
                  <template v-if="grades.final_grade !== null && grades.final_grade !== undefined">
                    {{ formatScore(grades.final_grade) }}
                  </template>
                  <span v-else class="text-xs font-bold uppercase tracking-wider">In Progress</span>
                </div>
                <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium truncate">{{ remarksLabel(grades.remarks) }}</span>
              </div>

              <div class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl p-3 sm:p-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between shadow-sm min-h-[75px]">
                <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">Assessments</span>
                <div class="text-lg sm:text-2xl font-black text-white leading-tight my-0.5">
                  {{ gradedCount }}/{{ assignments.length }}
                </div>
                <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium truncate">Graded items</span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- CATEGORY BREAKDOWN -->
      <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
          <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
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
          <div v-for="cat in categories" :key="cat.key"
            class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-5 border border-slate-200/80 dark:border-[#3F4F43] space-y-3 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
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
              <div class="h-full rounded-full transition-all duration-500"
                :class="progressBarColor(grades[cat.key])"
                :style="{ width: `${Math.min(parseFloat(grades[cat.key] || 0), 100)}%` }"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ASSESSMENTS LIST -->
      <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
          <div class="flex items-center gap-3">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
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
            <button v-for="f in filters" :key="f.id" @click="activeFilter = f.id"
              :class="[
                'px-3 py-1.5 rounded-xl text-xs font-extrabold transition-all whitespace-nowrap active:scale-95 cursor-pointer',
                activeFilter === f.id
                  ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-xs'
                  : 'text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-[#3F4F43]'
              ]">
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
              <tr v-for="(a, idx) in filteredAssignments" :key="a.id"
                class="hover:bg-emerald-50/30 dark:hover:bg-[#232D26]/60 transition-colors divide-x divide-slate-200/60 dark:divide-[#3F4F43]">
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
          <div class="flex justify-center text-slate-400">
            <Icon icon="clipboard-list" size="xl" />
          </div>
          <p class="text-sm font-bold text-slate-800 dark:text-white">No assessments in this view</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">
            Try switching the filter or waiting for your teacher to publish an assessment.
          </p>
        </div>
      </div>

      <!-- TRANSMUTATION INFO -->
      <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm">
        <div class="flex items-start gap-3">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
            <Icon icon="info" size="md" />
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
  </StudentLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import StudentLayout from '@/Layouts/StudentLayout.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  classroom:   { type: Object, default: () => ({}) },
  grades:      { type: Object, default: () => ({}) },
  weights:     { type: Object, default: () => ({}) },
  assignments: { type: Array,  default: () => [] },
})

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
  if (val === null || val === undefined) return 'text-emerald-100/80'
  const num = parseFloat(val)
  if (isNaN(num)) return 'text-emerald-100/80'
  if (num >= 75) return 'text-amber-300'
  return 'text-rose-300'
})

function goBack() {
  if (window.history.length > 2 && document.referrer) {
    window.history.back()
    return
  }
  router.visit(route('student.grades.index'))
}

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