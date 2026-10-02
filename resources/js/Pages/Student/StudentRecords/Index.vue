<template>
  <Head title="Student Records & COR - Salawag LMS" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search subjects, codes, or records..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size"
        class="print:hidden"
      />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16 print:p-0 print:m-0">

        <!-- HERO -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4 print:hidden">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="space-y-1 relative z-10">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
              <span class="text-white">STUDENT</span>
              <span class="animated-stroke-text">RECORDS</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              "Official DepEd registration, transcript, and attendance logs."
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs animate-spin-slow">★</span>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2 relative z-10">
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 flex items-center justify-center shrink-0">
                <Icon icon="academic-cap" size="xl" class="text-white" />
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                  LRN: {{ student.lrn || '—' }}<span v-if="student.school_year"> • S.Y. {{ student.school_year }}</span>
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  {{ student.name || 'Student' }}
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  {{ student.grade_level || '—' }}
                  <span v-if="student.section"> - {{ student.section }}</span>
                  <span v-if="student.adviser"> • Adviser: {{ student.adviser }}</span>
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-3 gap-2 sm:gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">Status</span>
                <div class="text-xs font-extrabold text-[#86EFAC] my-0.5">{{ student.status || '—' }}</div>
                <span class="text-[9px] text-emerald-100/70">LIS verified</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">General Avg</span>
                <div class="text-xl font-extrabold text-amber-300 my-0.5">{{ general_average ?? '—' }}</div>
                <span class="text-[9px] text-emerald-100/70">Finalized</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">Attendance</span>
                <div class="text-xl font-extrabold text-white my-0.5">
                  {{ attendance_summary.rate !== null ? attendance_summary.rate + '%' : '—' }}
                </div>
                <span class="text-[9px] text-emerald-100/70">{{ attendance_summary.presents }} days present</span>
              </div>
            </div>
          </div>
        </div>

        <!-- TABS -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6 print:border-none print:shadow-none print:p-0 print:bg-white">

          <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-200/60 dark:border-[#3F4F43] print:hidden">
            <div class="flex items-center gap-1.5 bg-[#f5f7f2] dark:bg-[#232D26] p-1.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] overflow-x-auto">
              <button v-for="tab in recordTabs" :key="tab.id" @click="selectedTab = tab.id"
                :class="[
                  'px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all shrink-0 flex items-center gap-1.5 cursor-pointer active:scale-95',
                  selectedTab === tab.id
                    ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-xs'
                    : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'
                ]">
                <Icon :icon="tab.icon" size="xs" />
                <span>{{ tab.label }}</span>
              </button>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
              <div v-if="selectedTab === 'cor'" class="flex items-center gap-2">
                <span class="text-[10px] font-black uppercase text-slate-400 dark:text-slate-500">TERM:</span>
                <select v-model="selectedTermId" @change="reloadWithTerm"
                  class="bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3 py-2 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC]">
                  <option v-for="t in terms" :key="t.id" :value="t.id">{{ t.label }}</option>
                </select>
              </div>

              <button v-if="selectedTab === 'cor'" @click="printCOR"
                class="bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-2xl transition-all shadow-xs flex items-center gap-2 cursor-pointer shrink-0 active:scale-95">
                <Icon icon="download" size="xs" />
                Print COR
              </button>
            </div>
          </div>

          <!-- TAB: COR -->
          <div v-if="selectedTab === 'cor'">
            <div class="max-w-4xl mx-auto bg-white dark:bg-[#2D3A31] border-2 border-slate-800 dark:border-slate-600 p-6 sm:p-10 shadow-xl relative overflow-hidden rounded-xl print:border-none print:shadow-none print:p-0">

              <!-- Header -->
              <div class="text-center space-y-1.5 border-b-2 border-slate-800 dark:border-slate-600 pb-4">
                <div class="flex items-center justify-between px-2">
                  <div class="w-12 h-12 flex items-center justify-center text-3xl font-bold">
                    <Icon icon="academic-cap" size="xl" class="text-[#004d08]" />
                  </div>
                  <div class="space-y-0.5 text-center">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Republic of the Philippines</p>
                    <p class="text-xs font-black uppercase text-slate-800 dark:text-slate-200">Department of Education</p>
                    <p class="text-[10px] font-bold text-slate-700 dark:text-slate-300">Region IV-A CALABARZON • Division of Cavite</p>
                    <h1 class="font-black text-lg sm:text-xl uppercase text-[#004d08] dark:text-[#86EFAC] tracking-wider pt-0.5">
                      SALAWAG SENIOR HIGH SCHOOL
                    </h1>
                    <p class="text-[9px] text-slate-500 font-semibold">School ID: 342512 • City of Dasmariñas, Cavite</p>
                  </div>
                  <div class="w-12 h-12 flex items-center justify-center text-3xl font-bold">
                    <Icon icon="school" size="xl" class="text-[#004d08]" />
                  </div>
                </div>

                <div class="pt-2">
                  <span class="inline-block bg-slate-900 text-white font-black text-xs uppercase px-4 py-0.5 tracking-widest rounded-sm">
                    CERTIFICATE OF REGISTRATION
                  </span>
                  <p class="text-[10px] font-bold text-slate-600 dark:text-slate-300 mt-1 uppercase">{{ selectedTermLabel }}</p>
                </div>
              </div>

              <!-- Learner info -->
              <div class="grid grid-cols-12 gap-y-2 gap-x-4 text-xs pt-4 pb-4 border-b border-slate-300 dark:border-slate-600">
                <div class="col-span-12 sm:col-span-8 flex">
                  <span class="font-bold text-slate-600 dark:text-slate-400 w-32 shrink-0">NAME OF LEARNER:</span>
                  <span class="font-black text-slate-900 dark:text-white uppercase border-b border-dotted border-slate-400 flex-1">{{ student.name }}</span>
                </div>
                <div class="col-span-12 sm:col-span-4 flex">
                  <span class="font-bold text-slate-600 dark:text-slate-400 w-16 shrink-0">LRN:</span>
                  <span class="font-black text-slate-900 dark:text-white border-b border-dotted border-slate-400 flex-1">{{ student.lrn }}</span>
                </div>

                <div class="col-span-12 sm:col-span-8 flex">
                  <span class="font-bold text-slate-600 dark:text-slate-400 w-32 shrink-0">TRACK & STRAND:</span>
                  <span class="font-extrabold text-slate-900 dark:text-white border-b border-dotted border-slate-400 flex-1">
                    {{ student.track || '—' }}<span v-if="student.strand"> ({{ student.strand }})</span>
                  </span>
                </div>
                <div class="col-span-12 sm:col-span-4 flex">
                  <span class="font-bold text-slate-600 dark:text-slate-400 w-16 shrink-0">GRADE:</span>
                  <span class="font-extrabold text-slate-900 dark:text-white border-b border-dotted border-slate-400 flex-1">
                    {{ student.grade_level || '—' }}<span v-if="student.section"> - {{ student.section }}</span>
                  </span>
                </div>

                <div class="col-span-12 sm:col-span-8 flex">
                  <span class="font-bold text-slate-600 dark:text-slate-400 w-32 shrink-0">CLASS ADVISER:</span>
                  <span class="font-bold text-slate-800 dark:text-slate-200 border-b border-dotted border-slate-400 flex-1">{{ student.adviser || '—' }}</span>
                </div>
                <div class="col-span-12 sm:col-span-4 flex">
                  <span class="font-bold text-slate-600 dark:text-slate-400 w-16 shrink-0">STATUS:</span>
                  <span class="font-extrabold text-emerald-800 dark:text-[#86EFAC] border-b border-dotted border-slate-400 flex-1 uppercase">{{ student.status }}</span>
                </div>
              </div>

              <!-- Subjects table -->
              <div class="py-4 space-y-2">
                <h3 class="text-xs font-black uppercase text-slate-800 dark:text-slate-200 tracking-wider">OFFICIALLY ENROLLED SUBJECTS</h3>

                <div class="overflow-x-auto">
                  <table class="w-full text-left border-collapse border border-slate-800 dark:border-slate-600 text-[11px]">
                    <thead>
                      <tr class="bg-slate-100 dark:bg-[#232D26] border-b border-slate-800 dark:border-slate-600 text-slate-900 dark:text-slate-100 uppercase font-black">
                        <th class="p-2 border-r border-slate-800 dark:border-slate-600 w-28">Code</th>
                        <th class="p-2 border-r border-slate-800 dark:border-slate-600">Descriptive Title</th>
                        <th class="p-2 border-r border-slate-800 dark:border-slate-600 text-center w-12">Units</th>
                        <th class="p-2 border-r border-slate-800 dark:border-slate-600 w-32">Day & Time</th>
                        <th class="p-2 border-r border-slate-800 dark:border-slate-600 w-28">Room</th>
                        <th class="p-2">Instructor</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in cor_classes" :key="item.id"
                        class="border-b border-slate-300 dark:border-slate-600 font-medium">
                        <td class="p-2 border-r border-slate-800 dark:border-slate-600 font-bold font-mono text-[10px]">{{ item.code }}</td>
                        <td class="p-2 border-r border-slate-800 dark:border-slate-600 font-bold text-slate-900 dark:text-slate-100">{{ item.title }}</td>
                        <td class="p-2 border-r border-slate-800 dark:border-slate-600 text-center font-bold">{{ item.units }}</td>
                        <td class="p-2 border-r border-slate-800 dark:border-slate-600 text-[10px] leading-tight">
                          <span class="font-bold block">{{ item.days }}</span>
                          <span class="text-slate-500 dark:text-slate-400">{{ item.time }}</span>
                        </td>
                        <td class="p-2 border-r border-slate-800 dark:border-slate-600 text-center font-bold">{{ item.room }}</td>
                        <td class="p-2 font-semibold text-slate-800 dark:text-slate-200">{{ item.teacher }}</td>
                      </tr>

                      <tr v-if="!cor_classes.length">
                        <td colspan="6" class="p-6 text-center text-slate-500 dark:text-slate-400 italic">
                          No subjects enrolled for this term.
                        </td>
                      </tr>
                    </tbody>
                    <tfoot v-if="cor_classes.length">
                      <tr class="bg-slate-50 dark:bg-[#232D26] border-t-2 border-slate-800 dark:border-slate-600 font-black text-slate-900 dark:text-slate-100">
                        <td colspan="2" class="p-2 border-r border-slate-800 dark:border-slate-600 text-right uppercase">Total Academic Load:</td>
                        <td class="p-2 border-r border-slate-800 dark:border-slate-600 text-center text-xs text-[#004d08] dark:text-[#86EFAC]">{{ total_units }}</td>
                        <td colspan="3" class="p-2 text-slate-500 dark:text-slate-400 font-normal italic text-[10px]">*** Nothing Follows ***</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <!-- Financial status -->
              <div class="border border-slate-400 dark:border-slate-600 p-3 bg-slate-50 dark:bg-[#232D26] rounded text-[10px] text-slate-700 dark:text-slate-300 space-y-1 my-2">
                <div class="font-black uppercase text-slate-900 dark:text-slate-100 flex justify-between">
                  <span>FINANCIAL STATUS: DEPED SHS FREE EDUCATION PROGRAM (RA 10931)</span>
                  <span class="text-emerald-800 dark:text-[#86EFAC] font-mono">FEES: PHP 0.00</span>
                </div>
                <p class="italic text-slate-600 dark:text-slate-400">
                  This learner is officially registered under the Public Senior High School program. Full subsidy applies.
                </p>
              </div>

              <!-- Signatures -->
              <div class="pt-8 grid grid-cols-3 gap-6 text-center text-xs">
                <div class="space-y-8">
                  <div class="border-b border-slate-800 dark:border-slate-500 pb-1 font-bold text-slate-800 dark:text-slate-200">{{ student.name }}</div>
                  <p class="text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400">Learner's Signature</p>
                </div>
                <div class="space-y-8">
                  <div class="border-b border-slate-800 dark:border-slate-500 pb-1 font-bold text-slate-800 dark:text-slate-200">{{ student.adviser || '—' }}</div>
                  <p class="text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400">Class Adviser</p>
                </div>
                <div class="space-y-8">
                  <div class="border-b border-slate-800 dark:border-slate-500 pb-1 font-bold text-slate-800 dark:text-slate-200">Registrar's Office</div>
                  <p class="text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400">School Registrar</p>
                </div>
              </div>

              <div class="pt-6 border-t border-slate-200 dark:border-slate-600 flex items-center justify-between text-[9px] font-mono text-slate-400 dark:text-slate-500 mt-6">
                <span>SYSTEM GENERATED VIA SALAWAG LMS</span>
                <span>ISSUED: {{ today }}</span>
              </div>
            </div>
          </div>

          <!-- TAB: GRADES -->
          <div v-else-if="selectedTab === 'grades'" class="space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Learner Progress Report (Form 138)</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Finalized subject grades with DepEd remarks</p>
              </div>
              <div v-if="general_average !== null" class="bg-emerald-100 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-900/40 text-[#004d08] dark:text-[#86EFAC] font-black text-xs px-3 py-1 rounded-xl">
                GWA: {{ general_average }}
              </div>
            </div>

            <div v-if="academic_grades.length" class="overflow-x-auto rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
              <table class="w-full text-left border-collapse text-xs min-w-[720px]">
                <thead>
                  <tr class="bg-[#f5f7f2] dark:bg-[#232D26] text-slate-700 dark:text-slate-200 uppercase font-black border-b border-slate-200 dark:border-[#3F4F43]">
                    <th class="p-3">Code</th>
                    <th class="p-3">Subject</th>
                    <th class="p-3 text-center">Term</th>
                    <th class="p-3 text-center">WW</th>
                    <th class="p-3 text-center">PT</th>
                    <th class="p-3 text-center">QE</th>
                    <th class="p-3 text-center bg-emerald-50/60 dark:bg-emerald-950/30">Final</th>
                    <th class="p-3 text-center">Remarks</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 dark:divide-[#3F4F43] font-medium text-slate-800 dark:text-slate-200">
                  <tr v-for="g in academic_grades" :key="g.class_id" class="hover:bg-emerald-50/30 dark:hover:bg-emerald-950/10">
                    <td class="p-3 font-mono font-bold text-slate-700 dark:text-slate-300">{{ g.code }}</td>
                    <td class="p-3 font-bold text-slate-900 dark:text-white">{{ g.title }}</td>
                    <td class="p-3 text-center text-[11px] font-semibold text-slate-500 dark:text-slate-400">{{ g.term }}</td>
                    <td class="p-3 text-center" :class="scoreColor(g.written_work)">{{ g.written_work ?? '—' }}</td>
                    <td class="p-3 text-center" :class="scoreColor(g.performance)">{{ g.performance ?? '—' }}</td>
                    <td class="p-3 text-center" :class="scoreColor(g.exam)">{{ g.exam ?? '—' }}</td>
                    <td class="p-3 text-center font-black text-sm bg-emerald-50/50 dark:bg-emerald-950/20" :class="scoreColor(g.final)">{{ g.final ?? '—' }}</td>
                    <td class="p-3 text-center">
                      <span v-if="g.remarks" :class="remarksBadge(g.remarks)"
                        class="inline-block text-[10px] font-black uppercase px-2 py-0.5 rounded-full border">
                        {{ remarksLabel(g.remarks) }}
                      </span>
                      <span v-else class="text-[10px] text-slate-400 font-semibold">Pending</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div v-else class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
              <div class="flex justify-center text-slate-400">
                <Icon icon="chart-bar" size="xl" />
              </div>
              <p class="text-sm font-bold text-slate-800 dark:text-white">No grades yet</p>
              <p class="text-xs text-slate-500 dark:text-slate-400">Your official grades will appear once finalized by your teachers.</p>
            </div>
          </div>

          <!-- TAB: ATTENDANCE -->
          <div v-else-if="selectedTab === 'attendance'" class="space-y-4">
            <div>
              <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Attendance Register (SF2)</h3>
              <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Monthly attendance summary across all subjects</p>
            </div>

            <div v-if="monthly_attendance.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
              <div v-for="m in monthly_attendance" :key="m.month"
                class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] space-y-2 hover:-translate-y-1 transition-transform">
                <div class="flex justify-between items-center text-xs font-black text-slate-800 dark:text-slate-100">
                  <span>{{ m.month }}</span>
                  <span class="text-emerald-700 dark:text-[#86EFAC]">{{ m.presents }}/{{ m.schoolDays }} Days</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-[#3F4F43] h-2 rounded-full overflow-hidden">
                  <div class="bg-[#004d08] dark:bg-[#86EFAC] h-full"
                    :style="{ width: (m.schoolDays ? (m.presents / m.schoolDays * 100) : 0) + '%' }"></div>
                </div>
                <div class="flex justify-between text-[10px] font-bold text-slate-500 dark:text-slate-400 pt-1">
                  <span>Absences: {{ m.absences }}</span>
                  <span>Tardy: {{ m.tardy }}</span>
                </div>
              </div>
            </div>

            <div v-else class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
              <div class="flex justify-center text-slate-400">
                <Icon icon="clipboard-check" size="xl" />
              </div>
              <p class="text-sm font-bold text-slate-800 dark:text-white">No attendance records</p>
              <p class="text-xs text-slate-500 dark:text-slate-400">Attendance will appear here once your teachers record sessions.</p>
            </div>
          </div>

        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  student:             { type: Object, default: () => ({}) },
  terms:               { type: Array,  default: () => [] },
  selected_term_id:    { type: Number, default: null },
  cor_classes:         { type: Array,  default: () => [] },
  total_units:         { type: Number, default: 0 },
  academic_grades:     { type: Array,  default: () => [] },
  general_average:     { type: [Number, String], default: null },
  monthly_attendance:  { type: Array,  default: () => [] },
  attendance_summary:  { type: Object, default: () => ({ total_records: 0, presents: 0, absences: 0, tardy: 0, excused: 0, rate: null }) },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')
const selectedTab = ref('cor')
const selectedTermId = ref(props.selected_term_id)

const recordTabs = [
  { id: 'cor',        label: 'Certificate of Registration', icon: 'document-text' },
  { id: 'grades',     label: 'Academic History',            icon: 'chart-bar' },
  { id: 'attendance', label: 'Attendance Log',              icon: 'clipboard-check' },
]

const today = new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })

const selectedTermLabel = computed(() => {
  const t = props.terms.find(t => t.id === selectedTermId.value)
  return t?.label || '—'
})

function reloadWithTerm() {
  router.get(route('student.studentrecords.index'), { term_id: selectedTermId.value }, {
    preserveState: true,
    preserveScroll: true,
  })
}

function printCOR() {
  window.print()
}

function scoreColor(val) {
  if (val === null || val === undefined) return 'text-slate-400 dark:text-slate-500'
  const num = parseFloat(val)
  if (isNaN(num)) return 'text-slate-400 dark:text-slate-500'
  if (num >= 90) return 'text-[#004d08] dark:text-[#86EFAC] font-black'
  if (num >= 80) return 'text-blue-700 dark:text-blue-400'
  if (num >= 75) return 'text-amber-700 dark:text-amber-400'
  return 'text-rose-600 dark:text-rose-400 font-black'
}

function remarksBadge(remarks) {
  return {
    passed:     'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    failed:     'bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border-rose-200 dark:border-rose-900/40',
    incomplete: 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40',
  }[remarks] || 'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'
}

function remarksLabel(remarks) {
  return {
    passed: 'Passed',
    failed: 'Failed',
    incomplete: 'Incomplete',
  }[remarks] || remarks
}
</script>

<style scoped>
.animated-stroke-text { color: transparent; -webkit-text-stroke: 1.5px #ffffff; }
@keyframes fadeInDown  { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px);  } to { opacity: 1; transform: translateY(0); } }
@keyframes sheenMove   { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
@keyframes spinSlow    { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.animate-fade-in-down  { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
.animate-sheen         { animation: sheenMove 4s ease-in-out infinite; }
.animate-spin-slow     { display: inline-block; animation: spinSlow 12s linear infinite; }

@media print {
  body { background: white !important; }
  .print\:hidden { display: none !important; }
}
</style>