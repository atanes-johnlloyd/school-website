<template>
  <Head :title="`Attendance Sessions - ${classroom?.subject ?? 'Class'}`" />

  <AuthenticatedLayout searchPlaceholder="Search sessions, dates…">

    <div class="space-y-4 sm:space-y-6">

      <!-- HERO -->
      <div v-observe
        class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[180px] sm:min-h-[220px] flex flex-col justify-center anim-fade-down">
        <img :src="heroImage" alt="Attendance Sessions Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 flex flex-col justify-center space-y-3">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <span class="bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-xs font-black uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="clipboard-check" size="xs" />
              All Sessions
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="users" size="xs" />
              {{ enrolled_students }} enrolled
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="calendar" size="xs" />
              {{ sessions.length }} recorded
            </span>
          </div>

          <div class="space-y-1.5 max-w-3xl">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-white tracking-tight leading-tight">
              {{ classroom?.subject || 'Attendance Sessions' }}
            </h2>
            <p class="text-white/90 text-xs sm:text-sm font-medium">
              {{ classroom?.section || '—' }} • Full historical record of daily attendance.
            </p>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <Link :href="route('teacher.attendance.index', { class_id: classroom?.id })"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              <Icon icon="arrow-left" size="xs" />
              Back to Daily View
            </Link>
          </div>
        </div>
      </div>

      <!-- SUMMARY CARDS -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3" style="animation-delay: 100ms;">
          <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Sessions</span>
          <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ sessions.length }}</div>
          <p class="text-[11px] font-medium text-slate-500">Days recorded</p>
        </div>

        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3" style="animation-delay: 150ms;">
          <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Present Total</span>
          <div class="text-2xl sm:text-3xl font-black text-[#005506] dark:text-[#86EFAC]">{{ totals.present }}</div>
          <p class="text-[11px] font-medium text-slate-500">Marks recorded</p>
        </div>

        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3" style="animation-delay: 200ms;">
          <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Late / Absent</span>
          <div class="text-2xl sm:text-3xl font-black text-amber-700 dark:text-amber-400">
            {{ totals.late }}<span class="text-slate-400 dark:text-slate-500 font-normal text-lg"> / </span><span class="text-rose-600 dark:text-rose-400">{{ totals.absent }}</span>
          </div>
          <p class="text-[11px] font-medium text-slate-500">Across all days</p>
        </div>

        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3" style="animation-delay: 250ms;">
          <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Attendance Rate</span>
          <div class="text-2xl sm:text-3xl font-black text-[#005506] dark:text-[#86EFAC]">{{ overallRate }}%</div>
          <p class="text-[11px] font-medium text-slate-500">Present + Late / Marked</p>
        </div>
      </div>

      <!-- SESSIONS TABLE -->
      <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4" style="animation-delay: 300ms;">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-[#3F4F43]">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="calendar" size="md" />
            </div>
            <div>
              <h3 class="text-sm sm:text-lg font-extrabold text-slate-800 dark:text-white">All Attendance Sessions</h3>
              <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                Click any session to open and edit it
              </p>
            </div>
          </div>

          <div class="relative w-full sm:w-72">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
              <Icon icon="search" size="sm" />
            </span>
            <input v-model="searchQuery" type="text" placeholder="Search date…"
              class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-xs font-semibold text-slate-700 dark:text-slate-200" />
          </div>
        </div>

        <div v-if="filteredSessions.length"
          class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
          <table class="w-full min-w-[720px] text-left border-collapse">
            <thead>
              <tr class="bg-[#F9F7F1] dark:bg-[#232D26] text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-[#3F4F43]">
                <th class="py-3 px-4 w-12 text-center">#</th>
                <th class="py-3 px-4">Date</th>
                <th class="py-3 px-4 text-center">Present</th>
                <th class="py-3 px-4 text-center">Late</th>
                <th class="py-3 px-4 text-center">Absent</th>
                <th class="py-3 px-4 text-center">Excused</th>
                <th class="py-3 px-4 text-center">Total</th>
                <th class="py-3 px-4 text-right">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-200">
              <tr v-for="(s, idx) in filteredSessions" :key="s.date"
                class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors cursor-pointer"
                @click="openSession(s.date)">
                <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ String(idx + 1).padStart(2, '0') }}</td>
                <td class="py-3.5 px-4">
                  <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-white dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center shrink-0">
                      <Icon icon="calendar" size="sm" class="text-[#005506] dark:text-[#86EFAC]" />
                    </div>
                    <div>
                      <p class="font-extrabold text-slate-800 dark:text-white">{{ formatLongDate(s.date) }}</p>
                      <p class="text-[10px] font-mono text-slate-500">{{ s.date }}</p>
                    </div>
                  </div>
                </td>
                <td class="py-3.5 px-4 text-center font-black text-[#005506] dark:text-[#86EFAC]">{{ s.present }}</td>
                <td class="py-3.5 px-4 text-center font-bold text-amber-700 dark:text-amber-400">{{ s.late }}</td>
                <td class="py-3.5 px-4 text-center font-bold text-rose-600 dark:text-rose-400">{{ s.absent }}</td>
                <td class="py-3.5 px-4 text-center font-bold text-sky-700 dark:text-sky-400">{{ s.excused }}</td>
                <td class="py-3.5 px-4 text-center font-bold text-slate-600 dark:text-slate-300">{{ s.total }}</td>
                <td class="py-3.5 px-4 text-right">
                  <button @click.stop="openSession(s.date)"
                    class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-[11px] font-black px-3.5 py-1.5 rounded-lg shadow-sm transition-transform active:scale-95">
                    Open
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
          <Icon icon="calendar" size="xl" class="text-slate-400 mx-auto" />
          <p class="text-sm font-bold text-slate-800 dark:text-white">
            {{ searchQuery ? 'No sessions match your search' : 'No attendance sessions yet' }}
          </p>
          <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ searchQuery ? 'Try a different date.' : 'Start marking attendance from the Daily View.' }}
          </p>
        </div>
      </div>

    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/attendace.png'

/* ═══════════════════════════════════════════════════════ */
const props = defineProps({
  classroom:          { type: Object, default: () => ({}) },
  sessions:           { type: Array,  default: () => [] },
  enrolled_students:  { type: Number, default: 0 },
})

const searchQuery = ref('')

/* ─── Date parsing (tolerant of Carbon objects / ISO strings / {date:''} envelopes) ─── */
function toDateKey(v) {
  if (!v) return null
  if (typeof v === 'object' && v !== null) {
    v = v.date ?? v.value ?? String(v)
  }
  const s = String(v).trim()
  const m = s.match(/^(\d{4}-\d{2}-\d{2})/)
  return m ? m[1] : null
}

/* ─── Totals across all sessions ─── */
const totals = computed(() => {
  const t = { present: 0, late: 0, absent: 0, excused: 0, total: 0 }
  props.sessions.forEach(s => {
    t.present += s.present || 0
    t.late    += s.late    || 0
    t.absent  += s.absent  || 0
    t.excused += s.excused || 0
    t.total   += s.total   || 0
  })
  return t
})

const overallRate = computed(() => {
  const denom = totals.value.present + totals.value.late + totals.value.absent
  if (denom === 0) return 0
  return Math.round(((totals.value.present + totals.value.late) / denom) * 1000) / 10
})

/* ─── Search filter ─── */
const filteredSessions = computed(() => {
  const q = searchQuery.value.trim().toLowerCase()
  if (! q) return props.sessions
  return props.sessions.filter(s => {
    const key = toDateKey(s.date) || ''
    const long = formatLongDate(s.date).toLowerCase()
    return key.includes(q) || long.includes(q)
  })
})

/* ─── Navigation ─── */
function openSession(dateKey) {
  const clean = toDateKey(dateKey)
  if (! clean || ! props.classroom?.id) return
  router.visit(route('teacher.attendance.index', {
    class_id: props.classroom.id,
    date:     clean,
  }))
}

/* ─── Formatting ─── */
function formatLongDate(v) {
  const key = toDateKey(v)
  if (! key) return '—'
  const d = new Date(key + 'T00:00:00')
  if (isNaN(d.getTime())) return String(v ?? '—')
  return d.toLocaleDateString('en-US', {
    weekday: 'long', month: 'long', day: 'numeric', year: 'numeric',
  })
}

/* ─── Animations ─── */
const vObserve = {
  mounted(el) {
    el.classList.add('not-visible')
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) el.classList.add('is-animated')
      else el.classList.remove('is-animated')
    }, { threshold: 0.1 })
    observer.observe(el)
  },
}
</script>

<style scoped>
@keyframes pulse-opacity { 0%, 100% { opacity: 0.88; } 50% { opacity: 0.65; } }
.animate-overlay { animation: pulse-opacity 6s infinite ease-in-out; }

@keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInDown  { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up  { animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>