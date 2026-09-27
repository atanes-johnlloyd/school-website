<template>
  <Head title="Student Records & COR - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0 print:hidden">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <!-- Main Content Container -->
      <div class="relative z-10 p-6 md:p-8 space-y-6 flex-1 pb-16 print:p-0 print:m-0">
        
        <!-- HERO HEADER BANNER (Hidden during print) -->
        <div class="w-full bg-[#004d08] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] relative overflow-hidden space-y-4 print:hidden">
          
          <!-- Header Title Block -->
          <div class="space-y-1">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none">
              <span class="text-white">STUDENT</span>
              <span class="animated-stroke-text">RECORDS & COR</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              "Official DepEd Registration Credentials, Form 137 Transcript, & Attendance Logs."
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs">★</span>
            </div>
          </div>

          <!-- HERO META INFO & TELEMETRY GRID -->
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2">
            
            <!-- Left Info Block -->
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center text-3xl">
                🎓
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                  LRN: {{ student.lrn }} • {{ selectedTerm.name }}
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  {{ student.name }}
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  {{ student.gradeLevel }} {{ student.trackStrand }} • Section {{ student.section }} • Adviser: {{ student.adviser }}
                </p>
              </div>
            </div>

            <!-- Right Telemetry Counters -->
            <div class="lg:col-span-5 grid grid-cols-3 gap-2 sm:gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">LIS Status</span>
                <div class="text-sm sm:text-base font-extrabold text-emerald-300 my-0.5 flex items-center gap-1">
                  <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                  Enrolled
                </div>
                <span class="text-[9px] text-emerald-100/70">Verified</span>
              </div>

              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">Current GWA</span>
                <div class="text-sm sm:text-base font-extrabold text-amber-300 my-0.5">
                  {{ generalWeightedAverage }}
                </div>
                <span class="text-[9px] text-emerald-100/70">With Honors</span>
              </div>

              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">Attendance Rate</span>
                <div class="text-sm sm:text-base font-extrabold text-white my-0.5">
                  {{ attendanceSummary.rate }}%
                </div>
                <span class="text-[9px] text-emerald-100/70">{{ attendanceSummary.presents }} Days Present</span>
              </div>
            </div>

          </div>

        </div>

        <!-- MAIN CONTENT WORKSPACE -->
        <div class="rounded-3xl bg-[#fbfdf9] border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6 print:border-none print:shadow-none print:p-0 print:bg-white">
          
          <!-- TAB SWITCHER & TERM SELECTOR BAR (Hidden during print) -->
          <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-200/60 print:hidden">
            
            <!-- Main Navigation Tabs -->
            <div class="flex items-center gap-1.5 bg-[#f5f7f2] p-1.5 rounded-2xl border border-slate-200/80 overflow-x-auto">
              <button 
                v-for="tab in recordTabs" 
                :key="tab.id"
                @click="selectedTab = tab.id"
                :class="[
                  'px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all shrink-0 flex items-center gap-1.5 cursor-pointer',
                  selectedTab === tab.id 
                    ? 'bg-[#004d08] text-white shadow-xs' 
                    : 'text-slate-700 hover:text-slate-900'
                ]"
              >
                <span>{{ tab.icon }}</span>
                <span>{{ tab.label }}</span>
              </button>
            </div>

            <!-- Term Switcher & Actions -->
            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
              <div class="flex items-center gap-2">
                <span class="text-[10px] font-black uppercase text-slate-400">TERM:</span>
                <select 
                  v-model="selectedTermId" 
                  class="bg-[#f5f7f2] border border-slate-200/80 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#004d08]"
                >
                  <option v-for="t in academicTerms" :key="t.id" :value="t.id">
                    {{ t.name }}
                  </option>
                </select>
              </div>

              <button 
                v-if="selectedTab === 'cor'"
                @click="printCOR" 
                class="bg-[#004d08] hover:bg-[#003805] text-white text-xs font-black px-4 py-2 rounded-2xl transition-all shadow-xs flex items-center gap-2 cursor-pointer shrink-0"
              >
                <span>🖨️</span> Print COR
              </button>
            </div>
          </div>

          <!-- ANIMATED DUAL COLUMN GRID LAYOUT -->
          <div 
            class="grid-drawer-wrapper items-start"
            :class="{ 'drawer-active': isDrawerOpen }"
          >
            
            <!-- LEFT MAIN CONTENT CONTAINER -->
            <div class="min-w-0 space-y-6">
              
              <!-- TAB 1: CERTIFICATE OF REGISTRATION (COR) -->
              <div v-if="selectedTab === 'cor'" class="space-y-6">
                
                <!-- PRINTABLE FORM CONTAINER -->
                <div class="max-w-4xl mx-auto bg-white border-2 border-slate-800 p-6 sm:p-10 shadow-xl relative overflow-hidden rounded-xl print:border-none print:shadow-none print:p-0">
                  
                  <!-- WATERMARK BACKGROUND SEAL -->
                  <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
                    <span class="text-[240px] font-black">🏫</span>
                  </div>

                  <!-- DOCUMENT HEADER -->
                  <div class="text-center space-y-1.5 border-b-2 border-slate-800 pb-4">
                    <div class="flex items-center justify-between px-2">
                      <div class="w-12 h-12 text-3xl font-bold flex items-center justify-center">🏛️</div>
                      <div class="space-y-0.5 text-center">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Republic of the Philippines</p>
                        <p class="text-xs font-black uppercase text-slate-800">Department of Education</p>
                        <p class="text-[10px] font-bold text-slate-700">Region IV-A CALABARZON • Division of Cavite</p>
                        <h1 class="font-black text-lg sm:text-xl uppercase text-[#004d08] tracking-wider pt-0.5">
                          SALAWAG SENIOR HIGH SCHOOL
                        </h1>
                        <p class="text-[9px] text-slate-500 font-semibold">School ID: 301284 • City of Dasmariñas, Cavite</p>
                      </div>
                      <div class="w-12 h-12 text-3xl font-bold flex items-center justify-center">🎓</div>
                    </div>

                    <div class="pt-2">
                      <span class="inline-block bg-slate-900 text-white font-black text-xs uppercase px-4 py-0.5 tracking-widest rounded-sm">
                        CERTIFICATE OF REGISTRATION
                      </span>
                      <p class="text-[10px] font-bold text-slate-600 mt-1 uppercase">{{ selectedTerm.name }}</p>
                    </div>
                  </div>

                  <!-- LEARNER INFORMATION MATRIX -->
                  <div class="grid grid-cols-12 gap-y-2 gap-x-4 text-xs pt-4 pb-4 border-b border-slate-300">
                    <div class="col-span-12 sm:col-span-8 flex">
                      <span class="font-bold text-slate-600 w-32 shrink-0">NAME OF LEARNER:</span>
                      <span class="font-black text-slate-900 uppercase border-b border-dotted border-slate-400 flex-1">{{ student.name }}</span>
                    </div>
                    <div class="col-span-12 sm:col-span-4 flex">
                      <span class="font-bold text-slate-600 w-16 shrink-0">LRN:</span>
                      <span class="font-black text-slate-900 border-b border-dotted border-slate-400 flex-1">{{ student.lrn }}</span>
                    </div>

                    <div class="col-span-12 sm:col-span-8 flex">
                      <span class="font-bold text-slate-600 w-32 shrink-0">TRACK & STRAND:</span>
                      <span class="font-extrabold text-slate-900 border-b border-dotted border-slate-400 flex-1">{{ student.trackStrand }}</span>
                    </div>
                    <div class="col-span-12 sm:col-span-4 flex">
                      <span class="font-bold text-slate-600 w-16 shrink-0">GRADE:</span>
                      <span class="font-extrabold text-slate-900 border-b border-dotted border-slate-400 flex-1">{{ student.gradeLevel }} - {{ student.section }}</span>
                    </div>

                    <div class="col-span-12 sm:col-span-8 flex">
                      <span class="font-bold text-slate-600 w-32 shrink-0">CLASS ADVISER:</span>
                      <span class="font-bold text-slate-800 border-b border-dotted border-slate-400 flex-1">{{ student.adviser }}</span>
                    </div>
                    <div class="col-span-12 sm:col-span-4 flex">
                      <span class="font-bold text-slate-600 w-16 shrink-0">STATUS:</span>
                      <span class="font-extrabold text-emerald-800 border-b border-dotted border-slate-400 flex-1 uppercase">Officially Enrolled</span>
                    </div>
                  </div>

                  <!-- ENROLLED SUBJECTS TABLE -->
                  <div class="py-4 space-y-2">
                    <div class="flex items-center justify-between">
                      <h3 class="text-xs font-black uppercase text-slate-800 tracking-wider">OFFICIALLY ENROLLED SUBJECT MATRIX</h3>
                      <span class="text-[10px] text-slate-500 italic print:hidden">Click any subject row to inspect syllabus details</span>
                    </div>
                    
                    <table class="w-full text-left border-collapse border border-slate-800 text-[11px]">
                      <thead>
                        <tr class="bg-slate-100 border-b border-slate-800 text-slate-900 uppercase font-black">
                          <th class="p-2 border-r border-slate-800 w-28">Code</th>
                          <th class="p-2 border-r border-slate-800">Descriptive Subject Title</th>
                          <th class="p-2 border-r border-slate-800 text-center w-12">Units</th>
                          <th class="p-2 border-r border-slate-800 w-28">Days & Time</th>
                          <th class="p-2 border-r border-slate-800 w-14 text-center">Room</th>
                          <th class="p-2">Instructor</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr 
                          v-for="item in currentTermClasses" 
                          :key="item.id" 
                          @click="openDrawer(item)"
                          class="border-b border-slate-300 hover:bg-emerald-50/60 transition-colors cursor-pointer font-medium"
                        >
                          <td class="p-2 border-r border-slate-800 font-bold font-mono text-[10px]">{{ item.code }}</td>
                          <td class="p-2 border-r border-slate-800 font-bold text-slate-900">{{ item.name }}</td>
                          <td class="p-2 border-r border-slate-800 text-center font-bold">{{ item.units }}</td>
                          <td class="p-2 border-r border-slate-800 text-[10px] leading-tight">
                            <span class="font-bold block">{{ item.days }}</span>
                            <span class="text-slate-500">{{ item.time }}</span>
                          </td>
                          <td class="p-2 border-r border-slate-800 text-center font-bold">{{ item.room }}</td>
                          <td class="p-2 font-semibold text-slate-800">{{ item.teacher }}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr class="bg-slate-50 border-t-2 border-slate-800 font-black text-slate-900">
                          <td colspan="2" class="p-2 border-r border-slate-800 text-right uppercase">Total Academic Load Units:</td>
                          <td class="p-2 border-r border-slate-800 text-center text-xs font-black text-[#004d08]">{{ totalUnits }}</td>
                          <td colspan="3" class="p-2 text-slate-500 font-normal italic text-[10px]">*** Nothing Follows ***</td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>

                  <!-- FINANCIAL STATEMENT (PUBLIC SCHOOL SUBSIDY) -->
                  <div class="border border-slate-400 p-3 bg-slate-50 rounded text-[10px] text-slate-700 space-y-1 my-2">
                    <div class="font-black uppercase text-slate-900 flex justify-between">
                      <span>FINANCIAL STATUS: DEPED SHS FREE EDUCATION PROGRAM (RA 10931)</span>
                      <span class="text-emerald-800 font-mono">FEES ASSESSED: PHP 0.00</span>
                    </div>
                    <p class="italic text-slate-600">
                      This learner is officially registered under the Public Senior High School program. Full subsidy applies; no tuition or auxiliary fees are collectible.
                    </p>
                  </div>

                  <!-- SIGNATURES BLOCK -->
                  <div class="pt-8 grid grid-cols-3 gap-6 text-center text-xs">
                    <div class="space-y-8">
                      <div class="border-b border-slate-800 pb-1 font-bold text-slate-800">{{ student.name }}</div>
                      <p class="text-[10px] font-bold uppercase text-slate-500">Learner's Signature</p>
                    </div>

                    <div class="space-y-8">
                      <div class="border-b border-slate-800 pb-1 font-bold text-slate-800">{{ student.adviser }}</div>
                      <p class="text-[10px] font-bold uppercase text-slate-500">Class Adviser</p>
                    </div>

                    <div class="space-y-8">
                      <div class="border-b border-slate-800 pb-1 font-bold text-slate-800">Atty. Ramon V. Cruz</div>
                      <p class="text-[10px] font-bold uppercase text-slate-500">School Registrar</p>
                    </div>
                  </div>

                  <!-- FOOTER CODE -->
                  <div class="pt-6 border-t border-slate-200 flex items-center justify-between text-[9px] font-mono text-slate-400 mt-6">
                    <span>SYSTEM GENERATED VIA SALAWAG LMS • LIS SYNC VERIFIED</span>
                    <span>ISSUED: MARCH 28, 2026 • {{ selectedTerm.name }}</span>
                  </div>

                </div>

              </div>

              <!-- TAB 2: DEPED FORM 137 / ACADEMIC HISTORY -->
              <div v-else-if="selectedTab === 'grades'" class="space-y-6">
                
                <div class="bg-[#f5f7f2] rounded-3xl p-6 border border-slate-200/80 space-y-4">
                  <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                    <div>
                      <h3 class="font-extrabold text-slate-900 text-base">Learner Progress Report (DepEd Form 138 / SF10)</h3>
                      <p class="text-xs text-slate-500 font-medium">Quarterly Academic Performance Breakdown</p>
                    </div>
                    <div class="bg-emerald-100 border border-emerald-200 text-[#004d08] font-black text-xs px-3 py-1 rounded-xl">
                      GWA: {{ generalWeightedAverage }}
                    </div>
                  </div>

                  <!-- GRADES TABLE -->
                  <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                      <thead>
                        <tr class="bg-white text-slate-700 uppercase font-black border-b border-slate-200">
                          <th class="p-3">Subject Code</th>
                          <th class="p-3">Subject Title</th>
                          <th class="p-3 text-center">Q1</th>
                          <th class="p-3 text-center">Q2</th>
                          <th class="p-3 text-center">Q3</th>
                          <th class="p-3 text-center">Q4</th>
                          <th class="p-3 text-center font-bold">Final Grade</th>
                          <th class="p-3 text-center">Remarks</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-slate-200/80 font-medium">
                        <tr v-for="grade in academicGrades" :key="grade.code" class="hover:bg-white/80">
                          <td class="p-3 font-mono font-bold text-slate-700">{{ grade.code }}</td>
                          <td class="p-3 font-bold text-slate-900">{{ grade.title }}</td>
                          <td class="p-3 text-center">{{ grade.q1 || '-' }}</td>
                          <td class="p-3 text-center">{{ grade.q2 || '-' }}</td>
                          <td class="p-3 text-center">{{ grade.q3 || '-' }}</td>
                          <td class="p-3 text-center">{{ grade.q4 || '-' }}</td>
                          <td class="p-3 text-center font-black text-[#004d08] text-sm bg-emerald-50/50">
                            {{ grade.final || calculateSubjectAverage(grade) }}
                          </td>
                          <td class="p-3 text-center">
                            <span class="bg-emerald-100 text-emerald-800 font-extrabold text-[10px] px-2 py-0.5 rounded-full uppercase">
                              Passed
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                </div>

              </div>

              <!-- TAB 3: DAILY ATTENDANCE LOG (SF2 SYNCHRONIZED) -->
              <div v-else-if="selectedTab === 'attendance'" class="space-y-6">
                
                <div class="bg-[#f5f7f2] rounded-3xl p-6 border border-slate-200/80 space-y-5">
                  <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                    <div>
                      <h3 class="font-extrabold text-slate-900 text-base">School Form 2 (SF2) Attendance Register</h3>
                      <p class="text-xs text-slate-500 font-medium">Synchronized daily attendance logs for active school year</p>
                    </div>
                    <span class="text-xs font-bold text-[#004d08] bg-white border border-slate-200 px-3 py-1 rounded-xl">
                      Total Days: {{ attendanceSummary.totalDays }}
                    </span>
                  </div>

                  <!-- Monthly Attendance Matrix Cards -->
                  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div v-for="m in monthlyAttendance" :key="m.month" class="bg-white rounded-2xl p-4 border border-slate-200/80 space-y-2">
                      <div class="flex justify-between items-center text-xs font-black text-slate-800">
                        <span>{{ m.month }}</span>
                        <span class="text-emerald-700">{{ m.presents }}/{{ m.schoolDays }} Days</span>
                      </div>
                      <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-[#004d08] h-full" :style="{ width: (m.presents / m.schoolDays * 100) + '%' }"></div>
                      </div>
                      <div class="flex justify-between text-[10px] font-bold text-slate-500 pt-1">
                        <span>Absences: {{ m.absences }}</span>
                        <span>Tardy: {{ m.tardy }}</span>
                      </div>
                    </div>
                  </div>

                </div>

              </div>

            </div>

            <!-- RIGHT COLUMN: SLIDING DRAWER FOR INSPECTED SUBJECT -->
            <div class="drawer-column min-w-0 print:hidden">
              <div 
                v-if="activeDrawerClass" 
                class="bg-[#f5f7f2] border border-slate-200/90 rounded-3xl p-5 sm:p-6 shadow-xl space-y-5 sticky top-6 drawer-inner-content"
              >
                <!-- Drawer Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                  <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-pulse"></span>
                    <span class="text-xs font-black uppercase text-[#004d08] tracking-wider">SUBJECT & SYLLABUS DETAILS</span>
                  </div>
                  <button 
                    @click="closeDrawer" 
                    class="w-7 h-7 rounded-full bg-white hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs transition-all cursor-pointer hover:scale-105 active:scale-95"
                  >
                    ✕
                  </button>
                </div>

                <!-- Subject Title -->
                <div class="flex items-start justify-between gap-3">
                  <div>
                    <span class="text-[10px] font-black uppercase text-[#004d08] bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200">
                      {{ activeDrawerClass.code }}
                    </span>
                    <h3 class="font-black text-slate-900 text-lg leading-snug mt-1">{{ activeDrawerClass.name }}</h3>
                    <p class="text-xs font-bold text-slate-600">Instructor: {{ activeDrawerClass.teacher }}</p>
                  </div>
                  <div class="bg-emerald-100 text-[#004d08] px-3 py-1 rounded-xl text-center shrink-0 border border-emerald-200/80">
                    <span class="text-xs font-extrabold block">{{ activeDrawerClass.units }} Units</span>
                    <span class="text-[9px] font-bold uppercase text-emerald-700">Credit Load</span>
                  </div>
                </div>

                <!-- Schedule Breakdown -->
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 space-y-3 shadow-2xs">
                  <div class="text-xs font-extrabold text-slate-700 border-b border-slate-100 pb-1.5 flex justify-between">
                    <span>SCHEDULE DETAILS</span>
                    <span class="text-[#004d08]">DepEd Senior High</span>
                  </div>
                  <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                      <span class="text-[10px] font-bold text-slate-400 block">Class Days</span>
                      <span class="font-bold text-slate-800">{{ activeDrawerClass.days }}</span>
                    </div>
                    <div>
                      <span class="text-[10px] font-bold text-slate-400 block">Class Time</span>
                      <span class="font-bold text-slate-800">{{ activeDrawerClass.time }}</span>
                    </div>
                    <div>
                      <span class="text-[10px] font-bold text-slate-400 block">Room / Venue</span>
                      <span class="font-bold text-slate-800">Room {{ activeDrawerClass.room }}</span>
                    </div>
                  </div>
                </div>

                <!-- Action Button -->
                <button class="w-full bg-[#004d08] hover:bg-[#003805] text-white text-xs font-bold py-3 rounded-xl transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                  📥 Download Subject Syllabus PDF
                </button>
              </div>
            </div>

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
  activeTerm: String,
})

const selectedTab = ref('cor')
const selectedTermId = ref(1)
const isDrawerOpen = ref(false)
const activeDrawerClass = ref(null)

const recordTabs = [
  { id: 'cor', label: 'Certificate of Registration (COR)', icon: '📜' },
  { id: 'grades', label: 'Academic History & Form 137', icon: '📊' },
  { id: 'attendance', label: 'Attendance Log (SF2)', icon: '📅' }
]

const academicTerms = [
  { id: 1, name: 'S.Y. 2026-2027 • 1st Semester' },
  { id: 2, name: 'S.Y. 2025-2026 • 2nd Semester' },
  { id: 3, name: 'S.Y. 2025-2026 • 1st Semester' }
]

const student = ref({
  name: 'JUAN DELA CRUZ',
  lrn: '123456789012',
  gradeLevel: 'Grade 12',
  trackStrand: 'STEM (Science, Tech, Engineering & Math)',
  section: 'Rizal',
  adviser: 'Dr. Josefa Garcia'
})

const termsData = {
  1: [
    { id: 1, code: 'STEM-PHYS2', name: 'General Physics 2', units: 4, teacher: 'Engr. Benjamin Bautista', days: 'Mon / Wed', time: '08:00 AM - 10:00 AM', room: '302' },
    { id: 2, code: 'STEM-CALC', name: 'Basic Calculus', units: 4, teacher: 'Sir Marlon Aquino', days: 'Tue / Thu', time: '10:00 AM - 12:00 PM', room: '304' },
    { id: 3, code: 'APPL-RES2', name: 'Practical Research II', units: 3, teacher: 'Dr. Josefa Garcia', days: 'Mon / Wed', time: '01:00 PM - 02:30 PM', room: 'Lab 2' },
    { id: 4, code: 'CORE-FILI', name: 'Pagsulat sa Filipino sa Piling Larang', units: 3, teacher: 'Gng. Rosario Santos', days: 'Tue / Thu', time: '01:00 PM - 02:30 PM', room: '102' },
    { id: 5, code: 'CORE-MIL', name: 'Media & Information Literacy', units: 3, teacher: 'Ms. Clara Alonzo', days: 'Friday', time: '08:00 AM - 11:00 AM', room: 'AVR 1' }
  ],
  2: [
    { id: 6, code: 'STEM-PHYS1', name: 'General Physics 1', units: 4, teacher: 'Engr. Benjamin Bautista', days: 'Mon / Wed', time: '08:00 AM - 10:00 AM', room: '302' },
    { id: 7, code: 'STEM-PRECALC', name: 'Pre-Calculus', units: 4, teacher: 'Sir Marlon Aquino', days: 'Tue / Thu', time: '10:00 AM - 12:00 PM', room: '304' },
    { id: 8, code: 'APPL-RES1', name: 'Practical Research I', units: 3, teacher: 'Dr. Josefa Garcia', days: 'Mon / Wed', time: '01:00 PM - 02:30 PM', room: 'Lab 2' }
  ]
}

const academicGrades = ref([
  { code: 'STEM-PHYS2', title: 'General Physics 2', q1: 92, q2: 94, q3: null, q4: null },
  { code: 'STEM-CALC', title: 'Basic Calculus', q1: 89, q2: 91, q3: null, q4: null },
  { code: 'APPL-RES2', title: 'Practical Research II', q1: 95, q2: 96, q3: null, q4: null },
  { code: 'CORE-FILI', title: 'Pagsulat sa Filipino', q1: 90, q2: 92, q3: null, q4: null },
  { code: 'CORE-MIL', title: 'Media & Information Literacy', q1: 93, q2: 95, q3: null, q4: null }
])

const monthlyAttendance = ref([
  { month: 'November 2026', schoolDays: 20, presents: 20, absences: 0, tardy: 0 },
  { month: 'December 2026', schoolDays: 15, presents: 14, absences: 1, tardy: 0 },
  { month: 'January 2027', schoolDays: 21, presents: 21, absences: 0, tardy: 1 },
  { month: 'February 2027', schoolDays: 19, presents: 18, absences: 1, tardy: 0 }
])

const selectedTerm = computed(() => academicTerms.find(t => t.id === selectedTermId.value))
const currentTermClasses = computed(() => termsData[selectedTermId.value] || termsData[1])
const totalUnits = computed(() => currentTermClasses.value.reduce((sum, item) => sum + item.units, 0))

const generalWeightedAverage = computed(() => {
  const finals = academicGrades.value.map(g => calculateSubjectAverage(g)).filter(n => n > 0)
  if (finals.length === 0) return '93.25'
  return (finals.reduce((a, b) => a + b, 0) / finals.length).toFixed(2)
})

const attendanceSummary = computed(() => {
  const totalDays = monthlyAttendance.value.reduce((a, b) => a + b.schoolDays, 0)
  const presents = monthlyAttendance.value.reduce((a, b) => a + b.presents, 0)
  const rate = ((presents / totalDays) * 100).toFixed(1)
  return { totalDays, presents, rate }
})

const calculateSubjectAverage = (grade) => {
  const quarters = [grade.q1, grade.q2, grade.q3, grade.q4].filter(q => q !== null)
  if (quarters.length === 0) return 0
  return Math.round(quarters.reduce((a, b) => a + b, 0) / quarters.length)
}

const openDrawer = (item) => {
  activeDrawerClass.value = item
  isDrawerOpen.value = true
}

const closeDrawer = () => {
  isDrawerOpen.value = false
  setTimeout(() => {
    activeDrawerClass.value = null
  }, 500)
}

const printCOR = () => {
  window.print()
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800;900&display=swap');

.animated-stroke-text {
  color: transparent;
  -webkit-text-stroke: 1.5px #ffffff;
}

/* 60FPS GRID DRAWER ANIMATION */
.grid-drawer-wrapper {
  display: grid;
  grid-template-columns: 1fr 0fr;
  gap: 0px;
  transition: grid-template-columns 0.4s cubic-bezier(0.4, 0, 0.2, 1), gap 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: grid-template-columns, gap;
}

@media (min-width: 1024px) {
  .grid-drawer-wrapper.drawer-active {
    grid-template-columns: 7fr 5fr;
    gap: 24px;
    transition: grid-template-columns 0.8s cubic-bezier(0.16, 1, 0.3, 1), gap 0.8s cubic-bezier(0.16, 1, 0.3, 1);
  }
}

.drawer-column {
  overflow: hidden;
}

.drawer-inner-content {
  opacity: 0;
  transform: translateX(32px) scale(0.97);
  transition: opacity 0.25s ease-out, transform 0.25s ease-out;
  will-change: opacity, transform;
}

.drawer-active .drawer-inner-content {
  opacity: 1;
  transform: translateX(0) scale(1);
  transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.25s, transform 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.25s;
}

@media print {
  body { background: white !important; }
  .print\:hidden { display: none !important; }
  main { min-height: auto !important; }
}
</style>