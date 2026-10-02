<template>

  <Head title="Grades - Salawag LMS" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop searchPlaceholder="Search subjects, teacher names, or remarks..." @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size" />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- HERO -->
        <div v-observe
          class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[260px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
          <!-- Background Image Layer with Fallback Unsplash Image -->
          <img
            :src="heroImage || 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=1600&auto=format&fit=crop'"
            alt="Student Grades Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <!-- Animated Green Overlay (Blend mode matched to system dark green) -->
          <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply">
          </div>

          <!-- Ambient Light Glow Highlights -->
          <div
            class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0">
          </div>
          <div
            class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0">
          </div>

          <!-- Content Container -->
          <div class="relative z-10 w-full space-y-3 sm:space-y-4">

            <!-- Top Badge Container -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
              <div
                class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                <span>📊</span> STUDENT GRADEMATRIX
              </div>

              <div
                class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                {{ active_term || 'Active Term Cycle' }}
              </div>
            </div>

            <!-- Main Title & Quote Section -->
            <div class="space-y-1 sm:space-y-1.5 max-w-3xl">
              <div
                class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                <span>STUDENT</span>
                <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">GRADES</span>
              </div>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium italic">
                "Your journey to knowledge starts with one click."
              </p>
            </div>

            <!-- Details Row & Stat Cards Grid -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-1">
              <!-- Left Subheader / Icon Info -->
              <div class="flex items-center gap-3 sm:gap-4">
                <div
                  class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl border-2 border-white/30 bg-white/10 backdrop-blur-sm overflow-hidden shrink-0 shadow-lg flex items-center justify-center text-2xl sm:text-3xl transition-transform hover:scale-105 duration-300">
                  📊
                </div>

                <div class="space-y-0.5 min-w-0">
                  <h2 class="text-base sm:text-xl md:text-2xl font-black text-white tracking-tight leading-tight">
                    Academic Performance Record
                  </h2>
                  <p class="text-white/90 text-[11px] sm:text-sm font-medium">
                    Official transcript preview • <span class="font-black text-[#F9C20C]">{{ gradeRecords.length
                      }}</span> {{ gradeRecords.length === 1 ? 'subject' : 'subjects' }}
                  </p>
                </div>
              </div>

              <!-- Stat Cards Grid (Right-Aligned / Stretched) -->
              <div class="grid grid-cols-2 gap-2.5 sm:gap-3 shrink-0 sm:w-80">
                <!-- General Average -->
                <div
                  class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl py-2.5 sm:py-3 px-3.5 sm:px-4 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between">
                  <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">
                    General Average
                  </span>
                  <div class="text-lg sm:text-2xl font-black text-[#F9C20C] leading-tight mt-0.5">
                    {{ generalAverageDisplay }}
                  </div>
                  <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium mt-0.5 truncate">
                    {{ honorLabel }}
                  </span>
                </div>

                <!-- Complete Subjects -->
                <div
                  class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl py-2.5 sm:py-3 px-3.5 sm:px-4 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between">
                  <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">
                    Complete Subjects
                  </span>
                  <div class="text-lg sm:text-2xl font-black text-white leading-tight mt-0.5">
                    {{ completeCount }}/{{ gradeRecords.length }}
                  </div>
                  <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium mt-0.5 truncate">
                    All categories graded
                  </span>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- GRADES TABLE -->
        <div
          class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

          <div
            class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="flex items-center gap-3">
              <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
              <div>
                <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-none">
                  Report Card
                </h3>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                  Category breakdown per subject • DepEd DO 8, s. 2015 compliant
                </p>
              </div>
            </div>

            <div
              class="flex items-center gap-2 bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl px-3.5 py-2 text-xs font-bold text-slate-700 dark:text-slate-200">
              <span class="text-[#004d08] dark:text-[#86EFAC]">Term:</span>
              <span>{{ active_term || '—' }}</span>
            </div>
          </div>

          <div v-if="gradeRecords.length"
            class="overflow-x-auto rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] shadow-xs">
            <table class="w-full text-left border-collapse min-w-[860px]">
              <thead>
                <tr
                  class="bg-[#f5f7f2] dark:bg-[#232D26] text-[#004d08] dark:text-[#86EFAC] text-xs font-extrabold uppercase border-b border-slate-200/80 dark:border-[#3F4F43] divide-x divide-slate-200/60 dark:divide-[#3F4F43]">
                  <th class="py-4 px-4 w-28 text-center">Code</th>
                  <th class="py-4 px-5">Subject Name</th>
                  <th class="py-4 px-3 w-28 text-center">Written<br><span
                      class="text-[9px] font-bold opacity-70">25%</span></th>
                  <th class="py-4 px-3 w-32 text-center">Performance<br><span
                      class="text-[9px] font-bold opacity-70">50%</span></th>
                  <th class="py-4 px-3 w-28 text-center">Exam<br><span
                      class="text-[9px] font-bold opacity-70">25%</span></th>
                  <th class="py-4 px-4 w-24 text-center bg-emerald-50/80 dark:bg-emerald-950/40">Final</th>
                  <th class="py-4 px-4 w-48 text-center">Remarks</th>
                  <th class="py-4 px-5 w-48">Teacher</th>
                </tr>
              </thead>

              <tbody
                class="divide-y divide-slate-200/60 dark:divide-[#3F4F43] text-xs font-medium text-slate-800 dark:text-slate-200">
                <tr v-for="row in gradeRecords" :key="row.class_id"
                  class="hover:bg-emerald-50/30 dark:hover:bg-[#232D26]/60 transition-colors divide-x divide-slate-200/60 dark:divide-[#3F4F43]">
                  <td
                    class="py-4 px-4 text-center font-bold text-[#004d08] dark:text-[#86EFAC] bg-slate-50/50 dark:bg-[#232D26]/40">
                    {{ row.subject_code || '—' }}
                  </td>

                  <td class="py-4 px-5 font-bold text-slate-900 dark:text-white">
                    <Link :href="route('student.classes.grades.show', row.class_id)"
                      class="hover:text-[#004d08] dark:hover:text-[#86EFAC] hover:underline transition-colors">
                      {{ row.subject || 'Untitled Subject' }}
                    </Link>
                    <span class="block text-[10px] font-semibold text-slate-400 dark:text-slate-500 mt-0.5">
                      {{ row.section || '—' }}
                    </span>
                  </td>

                  <td class="py-4 px-3 text-center font-bold" :class="gradeColor(row.written_work)">
                    {{ formatScore(row.written_work) }}
                  </td>

                  <td class="py-4 px-3 text-center font-bold" :class="gradeColor(row.performance_task)">
                    {{ formatScore(row.performance_task) }}
                  </td>

                  <td class="py-4 px-3 text-center font-bold" :class="gradeColor(row.quarterly_exam)">
                    {{ formatScore(row.quarterly_exam) }}
                  </td>

                  <td class="py-4 px-4 text-center font-extrabold text-base bg-emerald-50/50 dark:bg-emerald-950/30"
                    :class="gradeColor(row.final_grade)">
                    {{ formatScore(row.final_grade) }}
                  </td>

                  <td class="py-4 px-4 text-center">
                    <span v-if="row.remarks" :class="remarksBadgeClass(row.remarks)"
                      class="inline-flex items-center gap-1 text-[10px] font-extrabold px-2.5 py-1 rounded-full border uppercase tracking-wider whitespace-nowrap">
                      {{ remarksLabel(row.remarks) }}
                    </span>
                    <span v-else-if="!row.is_complete"
                      class="inline-flex items-center gap-1 bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400 text-[10px] font-bold px-2.5 py-1 rounded-full border border-slate-200 dark:border-[#3F4F43] whitespace-nowrap">
                      In Progress
                    </span>
                    <span v-else class="text-slate-400 text-[11px]">—</span>
                  </td>

                  <td class="py-4 px-5 text-slate-600 dark:text-slate-400 font-semibold truncate">
                    {{ row.teacher || '—' }}
                  </td>
                </tr>
              </tbody>

              <tfoot>
                <tr
                  class="bg-[#f5f7f2] dark:bg-[#232D26] text-xs font-extrabold text-slate-900 dark:text-white border-t-2 border-slate-300 dark:border-[#3F4F43]">
                  <td colspan="5" class="py-4 px-5 text-right uppercase tracking-wider">
                    General Average:
                  </td>
                  <td
                    class="py-4 px-4 text-center text-lg font-black bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC]">
                    {{ generalAverageDisplay }}
                  </td>
                  <td colspan="2" class="py-4 px-5 text-[11px] font-semibold text-slate-500 dark:text-slate-400 italic">
                    {{ honorLabel }}
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>

          <div v-else
            class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
            <div class="text-4xl">📊</div>
            <p class="text-sm font-bold text-slate-800 dark:text-white">No grades yet</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              Your grades will appear here once your teacher begins grading assessments.
            </p>
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
  active_term: { type: String, default: null },
  classes: { type: Array, default: () => [] },
  general_average: { type: [Number, String], default: null },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')

const gradeRecords = computed(() => props.classes ?? [])

const completeCount = computed(() =>
  gradeRecords.value.filter(r => r.is_complete).length
)

const generalAverageDisplay = computed(() => {
  const avg = props.general_average
  if (avg === null || avg === undefined || avg === '') return '—'
  const num = parseFloat(avg)
  if (isNaN(num)) return '—'
  return num.toFixed(2)
})

const honorLabel = computed(() => {
  const avg = props.general_average
  if (avg === null || avg === undefined || avg === '') return 'No grade yet'
  const num = parseFloat(avg)
  if (isNaN(num)) return 'No grade yet'
  if (num >= 98) return 'With Highest Honors'
  if (num >= 95) return 'With High Honors'
  if (num >= 90) return 'With Honors'
  if (num >= 75) return 'Passing'
  return 'Needs Improvement'
})

// ─── Formatting helpers ─────────────────────────────
function formatScore(value) {
  if (value === null || value === undefined || value === '') return '—'
  const num = parseFloat(value)
  if (isNaN(num)) return '—'
  return num.toFixed(2)
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

function remarksLabel(remarks) {
  return {
    outstanding: 'Outstanding',
    very_satisfactory: 'Very Satisfactory',
    satisfactory: 'Satisfactory',
    fairly_satisfactory: 'Fairly Satisfactory',
    did_not_meet_expectations: 'Did Not Meet',
  }[remarks] || remarks
}

function remarksBadgeClass(remarks) {
  return {
    outstanding: 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    very_satisfactory: 'bg-teal-100 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border-teal-200 dark:border-teal-900/40',
    satisfactory: 'bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-900/40',
    fairly_satisfactory: 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40',
    did_not_meet_expectations: 'bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border-rose-200 dark:border-rose-900/40',
  }[remarks] || 'bg-slate-100 text-slate-700 border-slate-200'
}
</script>

<style scoped>
.animated-stroke-text {
  color: transparent;
  -webkit-text-stroke: 1.5px #ffffff;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeSlideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes floatSoft {

  0%,
  100% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(-4px);
  }
}

@keyframes sheenMove {
  0% {
    transform: translateX(-100%);
  }

  100% {
    transform: translateX(200%);
  }
}

@keyframes spinSlow {
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
}

.animate-fade-in-down {
  animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fade-slide-up {
  animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
}

.animate-float-soft {
  animation: floatSoft 3s ease-in-out infinite;
}

.animate-sheen {
  animation: sheenMove 4s ease-in-out infinite;
}

.animate-spin-slow {
  display: inline-block;
  animation: spinSlow 12s linear infinite;
}

.text-scale-sm :deep(.text-xs) {
  font-size: 0.65rem !important;
  line-height: 0.85rem !important;
}

.text-scale-sm :deep(.text-sm) {
  font-size: 0.75rem !important;
  line-height: 1rem !important;
}

.text-scale-sm :deep(.text-base) {
  font-size: 0.875rem !important;
  line-height: 1.25rem !important;
}

.text-scale-lg :deep(.text-xs) {
  font-size: 0.875rem !important;
  line-height: 1.25rem !important;
}

.text-scale-lg :deep(.text-sm) {
  font-size: 1rem !important;
  line-height: 1.5rem !important;
}

.text-scale-lg :deep(.text-base) {
  font-size: 1.125rem !important;
  line-height: 1.75rem !important;
}
</style>