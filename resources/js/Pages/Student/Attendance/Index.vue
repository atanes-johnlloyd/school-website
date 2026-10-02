<template>

  <Head title="Attendance - Salawag LMS" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop searchPlaceholder="Search attendance records, subjects, or teachers..."
        @open-sidebar="isSidebarOpen = true" @font-size-changed="(size) => fontSizeMode = size" />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- HERO -->
        <div v-observe
          class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[260px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
          <!-- Background Image Layer with Fallback Unsplash Image -->
          <img
            :src="heroImage || 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1600&auto=format&fit=crop'"
            alt="Attendance Summary Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

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
                <span>📋</span> ATTENDANCE MATRIX
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
                <span>ATTENDANCE</span>
                <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">SUMMARY</span>
              </div>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium italic">
                "Consistent presence, stronger performance."
              </p>
            </div>

            <!-- Details Row & Stat Cards Grid -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-1">
              <!-- Left Subheader / Icon Info -->
              <div class="flex items-center gap-3 sm:gap-4">
                <div
                  class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl border-2 border-white/30 bg-white/10 backdrop-blur-sm overflow-hidden shrink-0 shadow-lg flex items-center justify-center transition-transform hover:scale-105 duration-300">
                  <Icon icon="clipboard-check" size="lg" class="text-white" />
                </div>

                <div class="space-y-0.5 min-w-0">
                  <h2 class="text-base sm:text-xl md:text-2xl font-black text-white tracking-tight leading-tight">
                    Attendance Overview
                  </h2>
                  <p class="text-white/90 text-[11px] sm:text-sm font-medium">
                    <span class="font-black text-[#F9C20C]">{{ classes.length }}</span> {{ classes.length === 1 ?
                    'class' : 'classes' }} tracked this term
                  </p>
                </div>
              </div>

              <!-- Stat Cards Grid (3 Columns / Right-Aligned) -->
              <div class="grid grid-cols-3 gap-2 sm:gap-3 shrink-0 sm:w-96">
                <!-- Present -->
                <div
                  class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl py-2.5 sm:py-3 px-3 sm:px-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between">
                  <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">
                    Present
                  </span>
                  <div class="text-lg sm:text-2xl font-black text-[#86EFAC] leading-tight mt-0.5">
                    {{ totals.present }}
                  </div>
                  <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium mt-0.5 truncate">
                    Days
                  </span>
                </div>

                <!-- Late -->
                <div
                  class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl py-2.5 sm:py-3 px-3 sm:px-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between">
                  <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">
                    Late
                  </span>
                  <div class="text-lg sm:text-2xl font-black text-[#F9C20C] leading-tight mt-0.5">
                    {{ totals.late }}
                  </div>
                  <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium mt-0.5 truncate">
                    Instances
                  </span>
                </div>

                <!-- Absent -->
                <div
                  class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl py-2.5 sm:py-3 px-3 sm:px-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between">
                  <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">
                    Absent
                  </span>
                  <div class="text-lg sm:text-2xl font-black text-rose-300 leading-tight mt-0.5">
                    {{ totals.absent }}
                  </div>
                  <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium mt-0.5 truncate">
                    Excused: {{ totals.excused }}
                  </span>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- WORKSPACE -->
        <div
          class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

          <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
            <div>
              <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-none">
                Per-Class Attendance
              </h3>
              <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                Select a class to see the full session log
              </p>
            </div>
          </div>

          <div v-if="!classes.length"
            class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
            <div class="flex justify-center text-slate-400">
              <Icon icon="clipboard-check" size="xl" />
            </div>
            <p class="text-sm font-bold text-slate-800 dark:text-white">No attendance records yet</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              Your attendance will appear here once your teachers begin recording sessions.
            </p>
          </div>

          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <Link v-for="c in classes" :key="c.class_id" :href="route('student.classes.attendance.show', c.class_id)"
              class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-5 border border-slate-200/80 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 hover:-translate-y-1 hover:shadow-md hover:border-[#004d08] dark:hover:border-[#86EFAC] transition-all group">

              <div class="space-y-3">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                  <span
                    class="text-[10px] font-extrabold uppercase text-[#004d08] dark:text-[#86EFAC] bg-white dark:bg-[#2D3A31] px-2.5 py-0.5 rounded-md border border-slate-200 dark:border-[#3F4F43]">
                    {{ c.subject_code || 'SUBJECT' }}
                  </span>
                  <span :class="rateBadgeClass(c.attendance_rate)">
                    {{ c.attendance_rate !== null ? c.attendance_rate + '%' : '—' }}
                  </span>
                </div>

                <div class="space-y-1">
                  <h4
                    class="font-extrabold text-slate-900 dark:text-white text-base leading-snug group-hover:text-[#004d08] dark:group-hover:text-[#86EFAC] transition-colors line-clamp-2">
                    {{ c.subject || 'Untitled Subject' }}
                  </h4>
                  <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold truncate">
                    {{ c.teacher || '—' }} <span v-if="c.section"> • {{ c.section }}</span>
                  </p>
                </div>

                <div class="grid grid-cols-4 gap-1 text-center">
                  <div
                    class="bg-white dark:bg-[#2D3A31] rounded-xl p-2 border border-slate-200/60 dark:border-[#3F4F43]">
                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Present</span>
                    <span class="text-sm font-black text-[#004d08] dark:text-[#86EFAC]">{{ c.present }}</span>
                  </div>
                  <div
                    class="bg-white dark:bg-[#2D3A31] rounded-xl p-2 border border-slate-200/60 dark:border-[#3F4F43]">
                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Late</span>
                    <span class="text-sm font-black text-amber-700 dark:text-amber-400">{{ c.late }}</span>
                  </div>
                  <div
                    class="bg-white dark:bg-[#2D3A31] rounded-xl p-2 border border-slate-200/60 dark:border-[#3F4F43]">
                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Absent</span>
                    <span class="text-sm font-black text-rose-600 dark:text-rose-400">{{ c.absent }}</span>
                  </div>
                  <div
                    class="bg-white dark:bg-[#2D3A31] rounded-xl p-2 border border-slate-200/60 dark:border-[#3F4F43]">
                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Excused</span>
                    <span class="text-sm font-black text-blue-700 dark:text-blue-400">{{ c.excused }}</span>
                  </div>
                </div>
              </div>

              <div
                class="pt-3 border-t border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between text-[11px] font-bold text-slate-500 dark:text-slate-400">
                <span>{{ c.total_sessions }} {{ c.total_sessions === 1 ? 'session' : 'sessions' }}</span>
                <span
                  class="text-[#004d08] dark:text-[#86EFAC] inline-flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                  View Log
                  <Icon icon="arrow-right" size="xs" />
                </span>
              </div>
            </Link>
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
  active_term: { type: String, default: null },
  classes: { type: Array, default: () => [] },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')

const totals = computed(() => props.classes.reduce((acc, c) => ({
  present: acc.present + (c.present || 0),
  late: acc.late + (c.late || 0),
  absent: acc.absent + (c.absent || 0),
  excused: acc.excused + (c.excused || 0),
}), { present: 0, late: 0, absent: 0, excused: 0 }))

function rateBadgeClass(rate) {
  if (rate === null || rate === undefined) return 'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]'
  if (rate >= 95) return 'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40'
  if (rate >= 85) return 'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40'
  return 'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full border bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border-rose-200 dark:border-rose-900/40'
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