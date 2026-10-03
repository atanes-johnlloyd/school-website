<template>

  <Head :title="`Attendance — ${classroom.subject || 'Class'} - Salawag LMS`" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop searchPlaceholder="Search dates or notes..." @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size" />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- Hero Banner -->
        <div
          class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
          <!-- Background Image Layer (Attendance & Session Logging Theme) -->
          <img
            :src="heroImage || 'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?q=80&w=1600&auto=format&fit=crop'"
            alt="Session Log Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <!-- Animated Dark Green Multiply Overlay -->
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
          <div class="relative z-10 w-full space-y-4 sm:space-y-6">
            <!-- Top Row: Badges, Title & Back Link -->
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
              <div class="space-y-2 max-w-2xl">
                <!-- Top Capsule Badges -->
                <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                  <span
                    class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                    <span>📋</span> ATTENDANCE
                  </span>

                  <span
                    class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Session Log
                  </span>
                </div>

                <!-- Title & Subtitle Section -->
                <div class="space-y-1">
                  <h1
                    class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                    <span>SESSION</span>
                    <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">LOG</span>
                  </h1>
                  <p v-if="classroom.section"
                    class="text-white/90 text-xs sm:text-sm leading-relaxed font-medium italic">
                    {{ classroom.section }}
                  </p>
                </div>
              </div>

              <!-- Navigation Link Button -->
              <Link :href="route('student.attendance.index')"
                class="inline-flex items-center justify-center gap-2 bg-black/30 hover:bg-black/40 backdrop-blur-md text-white border border-white/20 font-bold px-4 py-2.5 rounded-xl transition-all active:scale-95 text-xs sm:text-sm cursor-pointer shadow-sm shrink-0 self-start">
                <Icon icon="arrow-left" size="xs" />
                <span>All Attendance</span>
              </Link>
            </div>

            <!-- Bottom Row: Session Details & Stat Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2">
              <!-- Classroom / Subject Card -->
              <div
                class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4 bg-black/20 backdrop-blur-md border border-white/10 p-4 rounded-2xl shadow-sm">
                <div
                  class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl border border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center text-white transition-transform hover:scale-105 duration-300">
                  <Icon icon="clipboard-check" size="xl" class="text-white" />
                </div>
                <div class="space-y-1 text-center sm:text-left">
                  <div
                    class="inline-block bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-[11px] font-black px-3 py-0.5 rounded-full shadow-xs uppercase tracking-wide">
                    {{ summary.total_sessions }} {{ summary.total_sessions === 1 ? 'session' : 'sessions' }}
                  </div>
                  <h2 class="font-black text-base sm:text-xl text-white tracking-tight leading-snug drop-shadow-xs">
                    {{ classroom.subject || 'Untitled Subject' }}
                  </h2>
                  <p class="text-xs text-white/80 font-medium">
                    Instructor: <span class="text-emerald-300 font-bold">{{ classroom.teacher || '—' }}</span>
                  </p>
                </div>
              </div>

              <!-- Attendance Metrics Grid -->
              <div class="lg:col-span-5 grid grid-cols-3 gap-2 sm:gap-3">
                <!-- Attendance Rate Stat Card -->
                <div
                  class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between hover:-translate-y-1 hover:bg-black/40 transition-all duration-300 shadow-sm">
                  <span class="text-[10px] font-bold text-white/80">Rate</span>
                  <div class="text-xl font-black text-[#86EFAC] my-0.5 drop-shadow-xs">
                    {{ summary.attendance_rate !== null ? summary.attendance_rate + '%' : '—' }}
                  </div>
                  <span class="text-[9px] text-white/70 font-medium">Overall</span>
                </div>

                <!-- Days Present Stat Card -->
                <div
                  class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between hover:-translate-y-1 hover:bg-black/40 transition-all duration-300 shadow-sm">
                  <span class="text-[10px] font-bold text-white/80">Present</span>
                  <div class="text-xl font-black text-white my-0.5 drop-shadow-xs">{{ summary.present }}</div>
                  <span class="text-[9px] text-white/70 font-medium">Days</span>
                </div>

                <!-- Days Absent Stat Card -->
                <div
                  class="bg-black/30 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between hover:-translate-y-1 hover:bg-black/40 transition-all duration-300 shadow-sm">
                  <span class="text-[10px] font-bold text-white/80">Absent</span>
                  <div class="text-xl font-black text-rose-300 my-0.5 drop-shadow-xs">{{ summary.absent }}</div>
                  <span class="text-[9px] text-white/70 font-medium">Unexcused</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="animate-fade-slide-up grid grid-cols-2 sm:grid-cols-4 gap-4">
          <div
            class="bg-white dark:bg-[#2D3A31] rounded-2xl p-5 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
            <div class="flex justify-center text-[#004d08] dark:text-[#86EFAC]">
              <Icon icon="check-circle" size="lg" />
            </div>
            <div class="text-2xl font-black text-[#004d08] dark:text-[#86EFAC]">{{ summary.present }}</div>
            <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400">Present</span>
          </div>
          <div
            class="bg-white dark:bg-[#2D3A31] rounded-2xl p-5 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
            <div class="flex justify-center text-amber-600 dark:text-amber-400">
              <Icon icon="clock" size="lg" />
            </div>
            <div class="text-2xl font-black text-amber-700 dark:text-amber-400">{{ summary.late }}</div>
            <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400">Late</span>
          </div>
          <div
            class="bg-white dark:bg-[#2D3A31] rounded-2xl p-5 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
            <div class="flex justify-center text-rose-600 dark:text-rose-400">
              <Icon icon="x-circle" size="lg" />
            </div>
            <div class="text-2xl font-black text-rose-600 dark:text-rose-400">{{ summary.absent }}</div>
            <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400">Absent</span>
          </div>
          <div
            class="bg-white dark:bg-[#2D3A31] rounded-2xl p-5 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
            <div class="flex justify-center text-blue-600 dark:text-blue-400">
              <Icon icon="info" size="lg" />
            </div>
            <div class="text-2xl font-black text-blue-700 dark:text-blue-400">{{ summary.excused }}</div>
            <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400">Excused</span>
          </div>
        </div>

        <!-- SESSION LOG -->
        <div
          class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

          <div
            class="flex items-center justify-between gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43] flex-wrap">
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

            <div
              class="flex items-center gap-1.5 bg-[#f5f7f2] dark:bg-[#232D26] p-1.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] overflow-x-auto">
              <button v-for="f in filters" :key="f.id" @click="activeFilter = f.id" :class="[
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
            <div v-for="r in filteredRecords" :key="r.date" :class="[
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
  summary: { type: Object, default: () => ({ total_sessions: 0, present: 0, absent: 0, late: 0, excused: 0, attendance_rate: null }) },
  records: { type: Array, default: () => [] },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')
const activeFilter = ref('all')

const filters = [
  { id: 'all', label: 'All' },
  { id: 'present', label: 'Present' },
  { id: 'late', label: 'Late' },
  { id: 'absent', label: 'Absent' },
  { id: 'excused', label: 'Excused' },
]

const filteredRecords = computed(() => {
  if (activeFilter.value === 'all') return props.records
  return props.records.filter(r => r.status === activeFilter.value)
})

function statusIcon(status) {
  return {
    present: 'check-circle',
    late: 'clock',
    absent: 'x-circle',
    excused: 'info',
  }[status] || 'info'
}

function statusIconBg(status) {
  return {
    present: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    late: 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900/40',
    absent: 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-900/40',
    excused: 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-900/40',
  }[status] || 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]'
}

function statusBorder(status) {
  return {
    present: 'border-slate-200/70 dark:border-[#3F4F43]',
    late: 'border-amber-200/70 dark:border-amber-900/40',
    absent: 'border-rose-200/70 dark:border-rose-900/40',
    excused: 'border-blue-200/70 dark:border-blue-900/40',
  }[status] || 'border-slate-200/70 dark:border-[#3F4F43]'
}

function statusBadge(status) {
  return {
    present: 'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    late: 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40',
    absent: 'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900/40',
    excused: 'bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-900/40',
  }[status] || 'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'
}

function statusLabel(status) {
  return {
    present: 'Present',
    late: 'Late',
    absent: 'Absent',
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

.animate-sheen {
  animation: sheenMove 4s ease-in-out infinite;
}

.animate-spin-slow {
  display: inline-block;
  animation: spinSlow 12s linear infinite;
}
</style>