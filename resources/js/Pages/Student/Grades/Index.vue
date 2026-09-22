<template>
  <Head title="Grades - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <!-- Isolated Pattern Background (15% Opacity) -->
      <div 
        class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15"
        :style="{ backgroundImage: `url(${dashboardBg})` }"
      ></div>

      <!-- Main Content Container -->
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-16">
        
        <!-- HEADER ROW: STUDENT GRADES -->
        <div class="space-y-1">
          <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none">
            <!-- Solid Emerald Fill for STUDENT -->
            <span class="text-[#005506]">STUDENT</span>
            <!-- Reverse Fill-Stroke Animation for GRADES -->
            <span class="animated-reverse-stroke-text">GRADES</span>
          </div>
          
          <p class="text-xs sm:text-sm italic font-medium text-slate-600">
            "Your journey to knowledge starts with one click."
          </p>

          <!-- Decorative Star Divider Line -->
          <div class="flex items-center gap-2 pt-1 max-w-md">
            <div class="h-[2px] w-full bg-[#005506] animate-line-expand"></div>
            <span class="text-[#005506] text-xs">★</span>
          </div>
        </div>

        <!-- SECTION: STUDENT RECORD -->
        <div class="space-y-4">
          <h3 class="text-xl sm:text-2xl font-bold text-[#005506] tracking-tight">
            Student Record
          </h3>

          <!-- TABULATOR FRAME CONTAINER -->
          <div class="bg-white/90 backdrop-blur-sm rounded-3xl border-2 border-[#005506] overflow-hidden shadow-sm">
            
            <!-- Table Master Banner -->
            <div class="bg-[#e8f5e9] py-3.5 px-6 border-b-2 border-[#005506] text-center">
              <h4 class="font-['Anton'] text-lg sm:text-xl text-[#005506] tracking-wider uppercase">
                GRADES VIEWER
              </h4>
            </div>

            <!-- Filter Controls Sub-Bar -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-[#e8f5e9]/50 border-b border-[#005506]/30 text-xs font-bold text-slate-700">
              <div class="flex items-center gap-3">
                <span class="text-[#005506] whitespace-nowrap">School Year:</span>
                <select 
                  v-model="selectedYear"
                  class="bg-white border border-[#005506]/40 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#005506]"
                >
                  <option value="2026-2027">2026 - 2027</option>
                  <option value="2025-2026">2025 - 2026</option>
                </select>
              </div>

              <div class="flex items-center gap-3 md:justify-end">
                <span class="text-[#005506] whitespace-nowrap">Semester:</span>
                <select 
                  v-model="selectedSemester"
                  class="bg-white border border-[#005506]/40 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#005506]"
                >
                  <option value="1st Semester">1st Semester</option>
                  <option value="2nd Semester">2nd Semester</option>
                </select>
              </div>
            </div>

            <!-- TABULATOR / DATA TABLE -->
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse min-w-[800px]">
                
                <!-- Table Header Row -->
                <thead>
                  <tr class="bg-white text-[#005506] text-xs font-extrabold uppercase border-b border-[#005506]/40 divide-x divide-[#005506]/30">
                    <th class="py-3.5 px-4 w-28 text-center">Code</th>
                    <th class="py-3.5 px-5">Subject Name</th>
                    <th class="py-3.5 px-3 w-20 text-center">Prelim</th>
                    <th class="py-3.5 px-3 w-20 text-center">Midterm</th>
                    <th class="py-3.5 px-3 w-24 text-center">Pre-Finals</th>
                    <th class="py-3.5 px-3 w-20 text-center">Finals</th>
                    <th class="py-3.5 px-4 w-24 text-center bg-emerald-50/80">Final Avg.</th>
                    <th class="py-3.5 px-5 w-48">Teacher</th>
                  </tr>
                </thead>

                <!-- Table Data Rows -->
                <tbody class="divide-y divide-[#005506]/20 text-xs font-medium text-slate-800">
                  <tr 
                    v-for="(row, idx) in gradeRecords" 
                    :key="idx"
                    class="hover:bg-emerald-50/40 transition-colors divide-x divide-[#005506]/20"
                  >
                    <!-- Code -->
                    <td class="py-4 px-4 text-center font-bold text-[#005506] bg-emerald-50/30">
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
                    <td class="py-4 px-4 text-center font-extrabold text-sm bg-emerald-50/80" :class="getGradeColor(row.finalAvg)">
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

      </div>

    </main>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

import dashboardBg from '@/../assets/img/dashboardbackground.png'

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
  return num >= 75 ? 'text-[#005506]' : 'text-rose-600'
}

// Fallback Sample Records
const gradeRecords = props.grades?.length ? props.grades : [
  { code: 'CORE-COMM', subject: 'Effective Communication / Mabisang Komunikasyon', prelim: '92.00', midterm: '94.00', preFinals: '90.00', finals: '95.00', finalAvg: '92.75', teacher: 'Kaylin Leffler' },
  { code: 'STEM-GENMATH', subject: 'General Mathematics', prelim: '88.00', midterm: '91.00', preFinals: '89.00', finals: '93.00', finalAvg: '90.25', teacher: 'Ruel Santos' },
  { code: 'STEM-PHYS1', subject: 'General Physics 1', prelim: '85.00', midterm: '88.00', preFinals: '90.00', finals: '92.00', finalAvg: '88.75', teacher: 'Maria Clara Cruz' },
  { code: 'CORE-EARTH', subject: 'Earth Science', prelim: '90.00', midterm: '92.00', preFinals: '91.00', finals: '94.00', finalAvg: '91.75', teacher: 'Kaylin Leffler' },
  { code: 'CORE-EMPTECH', subject: 'Empowerment Technologies', prelim: '95.00', midterm: '96.00', preFinals: '94.00', finals: '98.00', finalAvg: '95.75', teacher: 'Juan Dela Cruz' },
  { code: 'CORE-PE1', subject: 'Physical Education and Health 1', prelim: '98.00', midterm: '97.00', preFinals: '96.00', finals: '99.00', finalAvg: '97.50', teacher: 'Coach Ramirez' },
]
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');

/* REVERSE ANIMATED FILL-STROKE TEXT EFFECT FOR 'GRADES' */
.animated-reverse-stroke-text {
  color: transparent;
  -webkit-text-stroke: 1.5px #005506;
  background: linear-gradient(to right, transparent 50%, #005506 50%);
  background-size: 200% 100%;
  background-position: 100% 0;
  -webkit-background-clip: text;
  background-clip: text;
  animation: reverseFillStrokeAnim 1.4s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
}

@keyframes reverseFillStrokeAnim {
  0% {
    background-position: 100% 0;
  }
  100% {
    background-position: 0 0;
  }
}

@keyframes lineExpand {
  0% {
    width: 0%;
  }
  100% {
    width: 100%;
  }
}

.animate-line-expand {
  animation: lineExpand 1s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both;
}
</style>