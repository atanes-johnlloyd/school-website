<template>
  <Head :title="`Attendance — ${classroom.subject || 'Class'} - Salawag LMS`" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search dates or notes..."
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
                <span class="text-white">SESSION</span>
                <span class="animated-stroke-text">LOG</span>
              </div>
              <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
                {{ classroom.section || '' }}
              </p>
            </div>

            <Link :href="route('student.attendance.index')"
              class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 self-start">
              <Icon icon="arrow-left" size="xs" />
              All Attendance
            </Link>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2 relative z-10">
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 flex items-center justify-center shrink-0">
                <Icon icon="clipboard-check" size="xl" class="text-white" />
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                  {{ summary.total_sessions }} {{ summary.total_sessions === 1 ? 'session' : 'sessions' }}
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  {{ classroom.subject || 'Untitled Subject' }}
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  {{ classroom.teacher || '—' }}
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-3 gap-2 sm:gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">Rate</span>
                <div class="text-xl font-extrabold text-[#86EFAC] my-0.5">
                  {{ summary.attendance_rate !== null ? summary.attendance_rate + '%' : '—' }}
                </div>
                <span class="text-[9px] text-emerald-100/70">Overall</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">Present</span>
                <div class="text-xl font-extrabold text-white my-0.5">{{ summary.present }}</div>
                <span class="text-[9px] text-emerald-100/70">Days</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">Absent</span>
                <div class="text-xl font-extrabold text-rose-300 my-0.5">{{ summary.absent }}</div>
                <span class="text-[9px] text-emerald-100/70">Unexcused</span>
              </div>
            </div>
          </div>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="animate-fade-slide-up grid grid-cols-2 sm:grid-cols-4 gap-4">
          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-5 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
            <div class="flex justify-center text-[#004d08] dark:text-[#86EFAC]">
              <Icon icon="check-circle" size="lg" />
            </div>
            <div class="text-2xl font-black text-[#004d08] dark:text-[#86EFAC]">{{ summary.present }}</div>
            <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400">Present</span>
          </div>
          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-5 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
            <div class="flex justify-center text-amber-600 dark:text-amber-400">
              <Icon icon="clock" size="lg" />
            </div>
            <div class="text-2xl font-black text-amber-700 dark:text-amber-400">{{ summary.late }}</div>
            <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400">Late</span>
          </div>
          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-5 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
            <div class="flex justify-center text-rose-600 dark:text-rose-400">
              <Icon icon="x-circle" size="lg" />
            </div>
            <div class="text-2xl font-black text-rose-600 dark:text-rose-400">{{ summary.absent }}</div>
            <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400">Absent</span>
          </div>
          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-5 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
            <div class="flex justify-center text-blue-600 dark:text-blue-400">
              <Icon icon="info" size="lg" />
            </div>
            <div class="text-2xl font-black text-blue-700 dark:text-blue-400">{{ summary.excused }}</div>
            <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400">Excused</span>
          </div>
        </div>

        <!-- SESSION LOG -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

          <div class="flex items-center justify-between gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43] flex-wrap">
            <div class="flex items-center gap-3">
              <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
              <div>
                <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-none">
                  Daily Session Log
                </h3>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                  Every recorded session, newest first
                </p>
              </div>
            </div>

            <div class="flex items-center gap-1.5 bg-[#f5f7f2] dark:bg-[#232D26] p-1.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] overflow-x-auto">
              <button v-for="f in filters" :key="f.id" @click="activeFilter = f.id"
                :class="[
                  'px-3 py-1.5 rounded-xl text-xs font-extrabold transition-all shrink-0 active:scale-95',
                  activeFilter === f.id
                    ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-xs'
                    : 'text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-[#3F4F43]'
                ]">
                {{ f.label }}
              </button>
            </div>
          </div>

          <div v-if="filteredRecords.length" class="space-y-2">
            <div v-for="r in filteredRecords" :key="r.date"
              :class="[
                'bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 border flex items-center justify-between gap-4 transition-all hover:-translate-x-1',
                statusBorder(r.status)
              ]">
              <div class="flex items-center gap-4 min-w-0">
                <div :class="statusIconBg(r.status)"
                  class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 border">
                  <Icon :icon="statusIcon(r.status)" size="md" />
                </div>

                <div class="min-w-0">
                  <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-extrabold text-slate-900 dark:text-white">{{ formatDate(r.date) }}</span>
                    <span :class="statusBadge(r.status)"
                      class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border">
                      {{ statusLabel(r.status) }}
                    </span>
                  </div>
                  <p v-if="r.notes" class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 font-medium truncate">
                    {{ r.notes }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="records.length"
            class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
            <div class="flex justify-center text-slate-400">
              <Icon icon="filter" size="xl" />
            </div>
            <p class="text-sm font-bold text-slate-800 dark:text-white">No {{ activeFilter }} records</p>
            <button @click="activeFilter = 'all'"
              class="bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold px-4 py-2 rounded-xl hover:bg-[#003805] transition-colors">
              Show All
            </button>
          </div>

          <div v-else
            class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
            <div class="flex justify-center text-slate-400">
              <Icon icon="clipboard-check" size="xl" />
            </div>
            <p class="text-sm font-bold text-slate-800 dark:text-white">No sessions recorded</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              This subject has no attendance records yet.
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
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  classroom: { type: Object, default: () => ({}) },
  summary:   { type: Object, default: () => ({ total_sessions: 0, present: 0, absent: 0, late: 0, excused: 0, attendance_rate: null }) },
  records:   { type: Array,  default: () => [] },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')
const activeFilter = ref('all')

const filters = [
  { id: 'all',     label: 'All' },
  { id: 'present', label: 'Present' },
  { id: 'late',    label: 'Late' },
  { id: 'absent',  label: 'Absent' },
  { id: 'excused', label: 'Excused' },
]

const filteredRecords = computed(() => {
  if (activeFilter.value === 'all') return props.records
  return props.records.filter(r => r.status === activeFilter.value)
})

function statusIcon(status) {
  return {
    present: 'check-circle',
    late:    'clock',
    absent:  'x-circle',
    excused: 'info',
  }[status] || 'info'
}

function statusIconBg(status) {
  return {
    present: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    late:    'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900/40',
    absent:  'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-900/40',
    excused: 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-900/40',
  }[status] || 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]'
}

function statusBorder(status) {
  return {
    present: 'border-slate-200/70 dark:border-[#3F4F43]',
    late:    'border-amber-200/70 dark:border-amber-900/40',
    absent:  'border-rose-200/70 dark:border-rose-900/40',
    excused: 'border-blue-200/70 dark:border-blue-900/40',
  }[status] || 'border-slate-200/70 dark:border-[#3F4F43]'
}

function statusBadge(status) {
  return {
    present: 'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    late:    'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40',
    absent:  'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900/40',
    excused: 'bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-900/40',
  }[status] || 'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'
}

function statusLabel(status) {
  return {
    present: 'Present',
    late:    'Late',
    absent:  'Absent',
    excused: 'Excused',
  }[status] || status
}

function formatDate(value) {
  if (!value) return '—'
  try {
    return new Date(value + 'T00:00:00').toLocaleDateString('en-US', {
      weekday: 'short', month: 'short', day: 'numeric', year: 'numeric',
    })
  } catch { return value }
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
</style>