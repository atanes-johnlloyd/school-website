<template>
  <Head title="Grades - Salawag LMS" />

  <div
    :class="[
      'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
      `text-scale-${fontSizeMode}`
    ]"
  >
    <!-- Responsive Mobile Drawer & Sticky Sidebar Component -->
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      
      <!-- Connected Navigation Top Bar with Hamburger Trigger -->
      <navbartop 
        searchPlaceholder="Search subjects, evaluation quarters, or teacher names..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size" 
      />

      <!-- Main Content Container -->
      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">
        
        <!-- HERO HEADER BANNER (ENTRANCE ANIMATION) -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <!-- Animated Background Gradient Sheen -->
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="space-y-1 relative z-10">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
              <span class="text-white">STUDENT</span>
              <span class="animated-stroke-text">GRADES</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              "Your journey to knowledge starts with one click."
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs animate-spin-slow">★</span>
            </div>
          </div>

          <!-- Hero Meta Info & Telemetry Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2 relative z-10">
            
            <!-- Left Info Block -->
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center text-3xl transition-transform hover:scale-105 duration-300">
                📊
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10 animate-float-soft">
                  {{ selectedYear }} • {{ selectedSemester }}
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  Academic Performance Record
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  Grade 12 STEM - Section Rizal • Official Transcript Preview
                </p>
              </div>
            </div>

            <!-- Right Quick Telemetry Counters -->
            <div class="lg:col-span-5 grid grid-cols-2 gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                <span class="text-[11px] font-medium text-emerald-100/80">General Average</span>
                <div class="text-xl font-extrabold text-amber-300 my-0.5">{{ overallAvg }}</div>
                <span class="text-[10px] text-emerald-100/70">With High Honors</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                <span class="text-[11px] font-medium text-emerald-100/80">Total Subjects</span>
                <div class="text-xl font-extrabold text-white my-0.5">{{ gradeRecords.length }}</div>
                <span class="text-[10px] text-emerald-100/70">Enrolled Courses</span>
              </div>
            </div>

          </div>
        </div>

        <!-- MAIN GRADES SECTION CONTAINER (SLIDE ENTRANCE) -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">
          
          <!-- Header Bar & Controls -->
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="flex items-center gap-3">
              <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
              <div>
                <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-none">
                  Student Record
                </h3>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                  Grade breakdowns and evaluation per term
                </p>
              </div>
            </div>

            <!-- Term Selection Dropdowns -->
            <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700 dark:text-slate-200">
              <div class="flex items-center gap-2 bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl px-3.5 py-2">
                <span class="text-[#004d08] dark:text-[#86EFAC] whitespace-nowrap">School Year:</span>
                <select 
                  v-model="selectedYear"
                  class="bg-transparent font-bold text-slate-800 dark:text-slate-100 focus:outline-none cursor-pointer"
                >
                  <option value="2026-2027" class="dark:bg-[#232D26]">2026 - 2027</option>
                  <option value="2025-2026" class="dark:bg-[#232D26]">2025 - 2026</option>
                </select>
              </div>

              <div class="flex items-center gap-2 bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl px-3.5 py-2">
                <span class="text-[#004d08] dark:text-[#86EFAC] whitespace-nowrap">Semester:</span>
                <select 
                  v-model="selectedSemester"
                  class="bg-transparent font-bold text-slate-800 dark:text-slate-100 focus:outline-none cursor-pointer"
                >
                  <option value="1st Semester" class="dark:bg-[#232D26]">1st Semester</option>
                  <option value="2nd Semester" class="dark:bg-[#232D26]">2nd Semester</option>
                </select>
              </div>
            </div>
          </div>

          <!-- TABULATOR / DATA TABLE -->
          <div class="overflow-x-auto rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31] shadow-xs">
            <table class="w-full text-left border-collapse min-w-[800px]">
              
              <!-- Table Header Row -->
              <thead>
                <tr class="bg-[#f5f7f2] dark:bg-[#232D26] text-[#004d08] dark:text-[#86EFAC] text-xs font-extrabold uppercase border-b border-slate-200/80 dark:border-[#3F4F43] divide-x divide-slate-200/60 dark:divide-[#3F4F43]">
                  <th class="py-4 px-4 w-28 text-center">Code</th>
                  <th class="py-4 px-5">Subject Name</th>
                  <th class="py-4 px-3 w-20 text-center">Prelim</th>
                  <th class="py-4 px-3 w-20 text-center">Midterm</th>
                  <th class="py-4 px-3 w-24 text-center">Pre-Finals</th>
                  <th class="py-4 px-3 w-20 text-center">Finals</th>
                  <th class="py-4 px-4 w-24 text-center bg-emerald-50/80 dark:bg-emerald-950/40">Final Avg.</th>
                  <th class="py-4 px-5 w-48">Teacher</th>
                </tr>
              </thead>

              <!-- Table Data Rows -->
              <tbody class="divide-y divide-slate-200/60 dark:divide-[#3F4F43] text-xs font-medium text-slate-800 dark:text-slate-200">
                <tr 
                  v-for="(row, idx) in gradeRecords" 
                  :key="idx"
                  class="hover:bg-emerald-50/30 dark:hover:bg-[#232D26]/60 transition-colors divide-x divide-slate-200/60 dark:divide-[#3F4F43]"
                >
                  <!-- Code -->
                  <td class="py-4 px-4 text-center font-bold text-[#004d08] dark:text-[#86EFAC] bg-slate-50/50 dark:bg-[#232D26]/40">
                    {{ row.code }}
                  </td>

                  <!-- Subject Name -->
                  <td class="py-4 px-5 font-bold text-slate-900 dark:text-white">
                    {{ row.subject }}
                  </td>

                  <!-- Prelim -->
                  <td class="py-4 px-3 text-center font-bold" :class="getGradeColor(row.prelim)">
                    {{ row.prelim ?? '--' }}
                  </td>

                  <!-- Midterm -->
                  <td class="py-4 px-3 text-center font-bold" :class="getGradeColor(row.midterm)">
                    {{ row.midterm ?? '--' }}
                  </td>

                  <!-- Pre-Finals -->
                  <td class="py-4 px-3 text-center font-bold" :class="getGradeColor(row.preFinals)">
                    {{ row.preFinals ?? '--' }}
                  </td>

                  <!-- Finals -->
                  <td class="py-4 px-3 text-center font-bold" :class="getGradeColor(row.finals)">
                    {{ row.finals ?? '--' }}
                  </td>

                  <!-- Final Avg -->
                  <td class="py-4 px-4 text-center font-extrabold text-sm bg-emerald-50/50 dark:bg-emerald-950/30" :class="getGradeColor(row.finalAvg)">
                    {{ row.finalAvg ?? '--' }}
                  </td>

                  <!-- Teacher -->
                  <td class="py-4 px-5 text-slate-600 dark:text-slate-400 font-semibold truncate">
                    {{ row.teacher }}
                  </td>
                </tr>
              </tbody>

            </table>
          </div>

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'

const props = defineProps({
  grades: Array,
  schoolYear: String,
  semester: String,
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')

const selectedYear = ref(props.schoolYear || '2026-2027')
const selectedSemester = ref(props.semester || '1st Semester')

// Color helper for passing/failing marks
const getGradeColor = (val) => {
  if (val === null || val === undefined) return 'text-slate-400 dark:text-slate-500'
  const num = parseFloat(val)
  if (isNaN(num)) return 'text-slate-400 dark:text-slate-500'
  return num >= 75 ? 'text-[#004d08] dark:text-[#86EFAC]' : 'text-rose-600 dark:text-rose-400'
}

// Fallback Sample Records
const gradeRecords = computed(() => props.grades?.length ? props.grades : [
  { code: 'CORE-COMM', subject: 'Effective Communication / Mabisang Komunikasyon', prelim: '92.00', midterm: '94.00', preFinals: '90.00', finals: '95.00', finalAvg: '92.75', teacher: 'Kaylin Leffler' },
  { code: 'STEM-GENMATH', subject: 'General Mathematics', prelim: '88.00', midterm: '91.00', preFinals: '89.00', finals: '93.00', finalAvg: '90.25', teacher: 'Ruel Santos' },
  { code: 'STEM-PHYS1', subject: 'General Physics 1', prelim: '85.00', midterm: '88.00', preFinals: '90.00', finals: '92.00', finalAvg: '88.75', teacher: 'Maria Clara Cruz' },
  { code: 'CORE-EARTH', subject: 'Earth Science', prelim: '90.00', midterm: '92.00', preFinals: '91.00', finals: '94.00', finalAvg: '91.75', teacher: 'Kaylin Leffler' },
  { code: 'CORE-EMPTECH', subject: 'Empowerment Technologies', prelim: '95.00', midterm: '96.00', preFinals: '94.00', finals: '98.00', finalAvg: '95.75', teacher: 'Juan Dela Cruz' },
  { code: 'CORE-PE1', subject: 'Physical Education and Health 1', prelim: '98.00', midterm: '97.00', preFinals: '96.00', finals: '99.00', finalAvg: '97.50', teacher: 'Coach Ramirez' },
])

const overallAvg = computed(() => {
  const records = gradeRecords.value
  if (!records.length) return '0.00'
  const total = records.reduce((acc, curr) => acc + parseFloat(curr.finalAvg || 0), 0)
  return (total / records.length).toFixed(1)
})
</script>

<style scoped>
.animated-stroke-text {
  color: transparent;
  -webkit-text-stroke: 1.5px #ffffff;
}

/* ASYMMETRICAL KEYFRAME ANIMATIONS */
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
  0%, 100% {
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

/* TEXT SCALING OVERRIDES */
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