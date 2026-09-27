<template>
  <Head title="Grades - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <!-- Main Content Container -->
      <div class="relative z-10 p-6 md:p-8 space-y-6 flex-1 pb-16">
        
        <!-- HERO HEADER BANNER -->
        <div class="w-full bg-[#004d08] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] relative overflow-hidden space-y-4">
          <div class="space-y-1">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none">
              <span class="text-white">STUDENT</span>
              <span class="animated-stroke-text">GRADES</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              "Your journey to knowledge starts with one click."
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs">★</span>
            </div>
          </div>

          <!-- Hero Meta Info & Telemetry Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2">
            
            <!-- Left Info Block -->
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center text-3xl">
                📊
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
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
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80">General Average</span>
                <div class="text-xl font-extrabold text-amber-300 my-0.5">{{ overallAvg }}</div>
                <span class="text-[10px] text-emerald-100/70">With High Honors</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80">Total Subjects</span>
                <div class="text-xl font-extrabold text-white my-0.5">{{ gradeRecords.length }}</div>
                <span class="text-[10px] text-emerald-100/70">Enrolled Courses</span>
              </div>
            </div>

          </div>
        </div>

        <!-- MAIN GRADES SECTION CONTAINER -->
        <div class="rounded-3xl bg-[#fbfdf9] border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
          
          <!-- Header Bar & Controls -->
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200/60">
            <div class="flex items-center gap-3">
              <div class="w-2.5 h-7 bg-[#004d08] rounded-full"></div>
              <div>
                <h3 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-none">
                  Student Record
                </h3>
                <p class="text-xs font-semibold text-slate-500 mt-1">
                  Grade breakdowns and evaluation per term
                </p>
              </div>
            </div>

            <!-- Term Selection Dropdowns -->
            <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-700">
              <div class="flex items-center gap-2 bg-[#f5f7f2] border border-slate-200/80 rounded-2xl px-3.5 py-2">
                <span class="text-[#004d08] whitespace-nowrap">School Year:</span>
                <select 
                  v-model="selectedYear"
                  class="bg-transparent font-bold text-slate-800 focus:outline-none cursor-pointer"
                >
                  <option value="2026-2027">2026 - 2027</option>
                  <option value="2025-2026">2025 - 2026</option>
                </select>
              </div>

              <div class="flex items-center gap-2 bg-[#f5f7f2] border border-slate-200/80 rounded-2xl px-3.5 py-2">
                <span class="text-[#004d08] whitespace-nowrap">Semester:</span>
                <select 
                  v-model="selectedSemester"
                  class="bg-transparent font-bold text-slate-800 focus:outline-none cursor-pointer"
                >
                  <option value="1st Semester">1st Semester</option>
                  <option value="2nd Semester">2nd Semester</option>
                </select>
              </div>
            </div>
          </div>

          <!-- TABULATOR / DATA TABLE -->
          <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <table class="w-full text-left border-collapse min-w-[800px]">
              
              <!-- Table Header Row -->
              <thead>
                <tr class="bg-[#f5f7f2] text-[#004d08] text-xs font-extrabold uppercase border-b border-slate-200/80 divide-x divide-slate-200/60">
                  <th class="py-4 px-4 w-28 text-center">Code</th>
                  <th class="py-4 px-5">Subject Name</th>
                  <th class="py-4 px-3 w-20 text-center">Prelim</th>
                  <th class="py-4 px-3 w-20 text-center">Midterm</th>
                  <th class="py-4 px-3 w-24 text-center">Pre-Finals</th>
                  <th class="py-4 px-3 w-20 text-center">Finals</th>
                  <th class="py-4 px-4 w-24 text-center bg-emerald-50/80">Final Avg.</th>
                  <th class="py-4 px-5 w-48">Teacher</th>
                </tr>
              </thead>

              <!-- Table Data Rows -->
              <tbody class="divide-y divide-slate-200/60 text-xs font-medium text-slate-800">
                <tr 
                  v-for="(row, idx) in gradeRecords" 
                  :key="idx"
                  class="hover:bg-emerald-50/30 transition-colors divide-x divide-slate-200/60"
                >
                  <!-- Code -->
                  <td class="py-4 px-4 text-center font-bold text-[#004d08] bg-slate-50/50">
                    {{ row.code }}
                  </td>

                  <!-- Subject Name -->
                  <td class="py-4 px-5 font-bold text-slate-900">
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
                  <td class="py-4 px-4 text-center font-extrabold text-sm bg-emerald-50/50" :class="getGradeColor(row.finalAvg)">
                    {{ row.finalAvg ?? '--' }}
                  </td>

                  <!-- Teacher -->
                  <td class="py-4 px-5 text-slate-600 font-semibold truncate">
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

const props = defineProps({
  grades: Array,
  schoolYear: String,
  semester: String,
})

const selectedYear = ref(props.schoolYear || '2026-2027')
const selectedSemester = ref(props.semester || '1st Semester')

// Color helper for passing/failing marks
const getGradeColor = (val) => {
  if (val === null || val === undefined) return 'text-slate-400'
  const num = parseFloat(val)
  if (isNaN(num)) return 'text-slate-400'
  return num >= 75 ? 'text-[#004d08]' : 'text-rose-600'
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
</style>