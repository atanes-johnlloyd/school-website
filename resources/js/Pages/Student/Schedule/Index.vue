<template>
  <Head title="Schedule - Salawag LMS" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search weekly schedule, subjects, teachers, or rooms..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size"
      />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- HERO -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="space-y-1 relative z-10">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
              <span class="text-white">STUDENT</span>
              <span class="animated-stroke-text">SCHEDULE</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              "Your journey to knowledge starts with one click."
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs animate-spin-slow">★</span>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2 relative z-10">
            <template v-if="live_class">
              <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 flex items-center justify-center shrink-0 relative">
                  <Icon icon="clock" size="xl" class="text-white" />
                  <span class="absolute top-2 right-2 w-3 h-3 bg-amber-400 rounded-full animate-ping border-2 border-[#004d08]"></span>
                </div>
                <div class="space-y-1 text-center sm:text-left">
                  <div class="inline-flex items-center gap-1.5 bg-amber-400/20 backdrop-blur-sm text-amber-300 text-[11px] font-extrabold px-3 py-0.5 rounded-full border border-amber-400/30 uppercase tracking-wide">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                    Live Now
                  </div>
                  <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                    {{ live_class.subject }}
                  </h2>
                  <p class="text-xs text-emerald-100/80 font-medium">
                    {{ live_class.instructor }} <span v-if="live_class.room"> • {{ live_class.room }}</span> • {{ live_class.time_start }} – {{ live_class.time_end }}
                  </p>
                </div>
              </div>

              <div class="lg:col-span-5 grid grid-cols-2 gap-3">
                <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                  <span class="text-[11px] font-medium text-emerald-100/80">In Session</span>
                  <div class="text-lg font-extrabold text-amber-300 my-0.5 truncate">{{ live_class.subject_code }}</div>
                  <span class="text-[10px] text-emerald-100/70">Ends at {{ live_class.time_end }}</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                  <span class="text-[11px] font-medium text-emerald-100/80">Next Class</span>
                  <div class="text-lg font-extrabold text-white my-0.5 truncate">
                    {{ next_class ? next_class.time_start : '—' }}
                  </div>
                  <span class="text-[10px] text-emerald-100/70 truncate">
                    {{ next_class ? next_class.subject : 'No more today' }}
                  </span>
                </div>
              </div>
            </template>

            <template v-else>
              <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 flex items-center justify-center shrink-0">
                  <Icon icon="calendar" size="xl" class="text-white" />
                </div>
                <div class="space-y-1 text-center sm:text-left">
                  <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                    {{ active_term || 'S.Y. —' }}
                  </div>
                  <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                    Weekly Academic Timetable
                  </h2>
                  <p class="text-xs text-emerald-100/70 font-medium">
                    {{ total_classes }} {{ total_classes === 1 ? 'class' : 'classes' }} scheduled
                  </p>
                </div>
              </div>

              <div class="lg:col-span-5 grid grid-cols-2 gap-3">
                <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                  <span class="text-[11px] font-medium text-emerald-100/80">Today's Classes</span>
                  <div class="text-xl font-extrabold text-amber-300 my-0.5">{{ today_classes_count }}</div>
                  <span class="text-[10px] text-emerald-100/70 capitalize">{{ today_name }}</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                  <span class="text-[11px] font-medium text-emerald-100/80">Next Class</span>
                  <div class="text-xl font-extrabold text-white my-0.5 truncate">
                    {{ next_class ? next_class.time_start : '—' }}
                  </div>
                  <span class="text-[10px] text-emerald-100/70 truncate">
                    {{ next_class ? next_class.subject : 'No more today' }}
                  </span>
                </div>
              </div>
            </template>
          </div>
        </div>

        <!-- TIMETABLE WORKSPACE -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

          <!-- FILTER BAR -->
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="flex items-center gap-1 bg-[#f5f7f2] dark:bg-[#232D26] p-1 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] overflow-x-auto">
              <span class="text-[10px] font-extrabold uppercase text-slate-500 dark:text-slate-400 px-2">FILTER DAY:</span>
              <button v-for="filter in dayFilters" :key="filter.id" @click="selectedDayFilter = filter.id"
                :class="[
                  'px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap active:scale-95 flex items-center gap-1.5',
                  selectedDayFilter === filter.id
                    ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-xs'
                    : 'text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-[#3F4F43]'
                ]">
                <span>{{ filter.label }}</span>
                <span v-if="filter.id === today_name" class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
              </button>
            </div>

            <div class="relative flex-1 sm:w-64">
              <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 dark:text-slate-500">
                <Icon icon="search" size="sm" />
              </span>
              <input v-model="searchQuery" type="text" placeholder="Filter subject, teacher, room..."
                class="w-full bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl pl-8 pr-8 py-2 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC]" />
              <button v-if="searchQuery" @click="searchQuery = ''"
                class="absolute inset-y-0 right-2.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <Icon icon="x" size="xs" />
              </button>
            </div>
          </div>

          <!-- EMPTY STATE -->
          <div v-if="displayRows.length === 0"
            class="py-12 text-center space-y-3 bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl border border-dashed border-slate-300 dark:border-[#3F4F43]">
            <div class="flex justify-center text-slate-400">
              <Icon icon="calendar" size="xl" />
            </div>
            <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">
              {{ searchQuery ? 'No Matching Classes Found' : 'No Schedule Yet' }}
            </h4>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              {{ searchQuery ? `Nothing matched "${searchQuery}".` : 'Your timetable will appear once the registrar publishes schedules.' }}
            </p>
            <button v-if="searchQuery" @click="resetFilters"
              class="bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold px-4 py-2 rounded-xl hover:bg-[#003805] transition-colors">
              Reset Search
            </button>
          </div>

          <!-- TIMETABLE GRID -->
          <div v-else class="overflow-x-auto pb-4">
            <div class="grid gap-3 transition-all duration-300"
              :class="selectedDayFilter === 'all' ? 'min-w-[1100px] grid-cols-12' : 'w-full grid-cols-12'">

              <!-- HEADER ROW -->
              <div class="col-span-2 bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 flex items-center justify-center text-center font-extrabold text-xs text-[#004d08] dark:text-[#86EFAC] uppercase tracking-wider border border-slate-200/60 dark:border-[#3F4F43]">
                TIME SLOT
              </div>

              <div v-for="day in visibleColumns" :key="day.id"
                :class="[
                  selectedDayFilter === 'all' ? 'col-span-2' : 'col-span-10',
                  'rounded-2xl p-3 text-center flex flex-col justify-center border transition-all',
                  day.isToday
                    ? 'bg-emerald-50 dark:bg-emerald-950/40 border-[#004d08] dark:border-[#86EFAC] shadow-xs'
                    : 'bg-[#f5f7f2] dark:bg-[#232D26] border-slate-200/60 dark:border-[#3F4F43]'
                ]">
                <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center justify-center gap-1.5">
                  <span>{{ day.name }}</span>
                  <span v-if="day.isToday" class="w-2 h-2 rounded-full bg-[#004d08] dark:bg-[#86EFAC]"></span>
                </h3>
                <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 block leading-tight">
                  {{ day.subtext }}
                </span>
              </div>

              <!-- ROWS -->
              <template v-for="slot in displayRows" :key="slot.slot_id || `banner-${slot.bannerTitle}`">

                <template v-if="slot.isBanner">
                  <div class="col-span-2 bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 flex flex-col items-center justify-center text-center font-extrabold text-xs text-slate-700 dark:text-slate-300 space-y-1 border border-slate-200/60 dark:border-[#3F4F43]">
                    <span>{{ slot.timeStart }}</span>
                    <span class="text-slate-400 dark:text-slate-500 font-normal">—</span>
                    <span>{{ slot.timeEnd }}</span>
                  </div>
                  <div class="col-span-10 bg-[#f7f6f0] dark:bg-[#1C261F] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between px-6">
                    <div class="flex items-center gap-3">
                      <Icon icon="sparkles" size="lg" class="text-amber-500" />
                      <div>
                        <h4 class="font-black text-slate-900 dark:text-white text-xs sm:text-sm tracking-tight">
                          {{ slot.bannerTitle }}
                        </h4>
                        <p class="text-xs text-slate-600 dark:text-slate-400 font-medium">
                          {{ slot.bannerSubtitle }}
                        </p>
                      </div>
                    </div>
                    <span class="bg-white dark:bg-[#2D3A31] text-[#004d08] dark:text-[#86EFAC] text-xs font-black px-3.5 py-1.5 rounded-full border border-slate-200 dark:border-[#3F4F43] shadow-2xs">
                      {{ slot.bannerBadge }}
                    </span>
                  </div>
                </template>

                <template v-else>
                  <!-- Time label -->
                  <div class="col-span-2 bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 flex flex-col items-center justify-center text-center font-extrabold text-xs text-slate-700 dark:text-slate-300 space-y-1 border border-slate-200/60 dark:border-[#3F4F43]">
                    <span>{{ slot.time_start }}</span>
                    <span class="text-slate-400 dark:text-slate-500 font-normal">—</span>
                    <span>{{ slot.time_end }}</span>
                  </div>

                  <!-- Day cells -->
                  <template v-for="day in visibleColumns" :key="day.id">
                    <div v-if="!slot.days[day.id]"
                      :class="[
                        selectedDayFilter === 'all' ? 'col-span-2' : 'col-span-10',
                        'bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/60 dark:border-[#3F4F43] rounded-2xl p-4 flex items-center justify-center min-h-[120px]'
                      ]">
                      <span class="text-lg leading-none text-slate-400 dark:text-slate-600">—</span>
                    </div>

                    <div v-else
                      :class="[
                        selectedDayFilter === 'all' ? 'col-span-2' : 'col-span-10',
                        isActive(slot.days[day.id])
                          ? 'bg-amber-500/10 border-2 border-amber-400 shadow-xs'
                          : 'bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43]',
                        'rounded-2xl p-4 flex flex-col justify-between space-y-3 transition-all hover:-translate-y-1 hover:shadow-md'
                      ]">
                      <div class="space-y-1.5">
                        <div class="flex items-center justify-between gap-1">
                          <span :class="[
                            isActive(slot.days[day.id])
                              ? 'bg-amber-400 text-slate-900 font-black'
                              : 'bg-white dark:bg-[#2D3A31] text-[#004d08] dark:text-[#86EFAC] font-extrabold border border-slate-200 dark:border-[#3F4F43]',
                            'text-[10px] px-2 py-0.5 rounded-md uppercase truncate'
                          ]">
                            {{ slot.days[day.id].subject_code || 'SUBJECT' }}
                          </span>
                          <span v-if="isActive(slot.days[day.id])" class="text-[9px] font-black bg-amber-400 text-slate-900 px-1.5 py-0.5 rounded animate-pulse">
                            LIVE
                          </span>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm leading-snug line-clamp-2">
                          {{ slot.days[day.id].subject }}
                        </h4>
                        <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold truncate">
                          {{ slot.days[day.id].instructor || '—' }}
                        </p>
                      </div>

                      <div v-if="slot.days[day.id].room" class="bg-white dark:bg-[#2D3A31] rounded-xl p-2 text-[11px] font-bold text-slate-700 dark:text-slate-300 inline-flex items-center gap-1.5 border border-slate-200/60 dark:border-[#3F4F43]">
                        <Icon icon="school" size="xs" class="text-slate-400" />
                        {{ slot.days[day.id].room }}
                      </div>
                    </div>
                  </template>
                </template>

              </template>

            </div>
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
  slots:         { type: Array,  default: () => [] },
  schedules:     { type: Array,  default: () => [] },
  today_name:    { type: String, default: '' },
  live_class:    { type: Object, default: null },
  next_class:    { type: Object, default: null },
  active_term:   { type: String, default: null },
  total_classes: { type: Number, default: 0 },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')
const selectedDayFilter = ref('all')
const searchQuery = ref('')

const dayOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']

const columns = computed(() => dayOrder.map(d => ({
  id: d,
  name: d.charAt(0).toUpperCase() + d.slice(1),
  subtext: d === props.today_name ? 'Today' : '',
  isToday: d === props.today_name,
})))

const visibleColumns = computed(() => {
  if (selectedDayFilter.value === 'all') return columns.value
  return columns.value.filter(c => c.id === selectedDayFilter.value)
})

const dayFilters = computed(() => {
  const base = [{ id: 'all', label: 'All Days' }]
  dayOrder.forEach(d => {
    base.push({
      id: d,
      label: d === props.today_name
        ? d.charAt(0).toUpperCase() + d.slice(1, 3) + ' (Today)'
        : d.charAt(0).toUpperCase() + d.slice(1, 3),
    })
  })
  return base
})

const today_classes_count = computed(() =>
  props.schedules.filter(s => s.day === props.today_name).length
)

// ─── Display rows: slots + injected lunch banner ─────────
const LUNCH_BANNER = {
  isBanner: true,
  timeStart: '11:45 AM',
  timeEnd: '01:00 PM',
  bannerIcon: 'sparkles',
  bannerTitle: 'Campus Lunch Recess',
  bannerSubtitle: 'Canteen, library, and student hub are open',
  bannerBadge: '75 minutes',
}

const displayRows = computed(() => {
  const q = searchQuery.value.trim().toLowerCase()

  // Filter slots by search
  const filteredSlots = props.slots
    .map(slot => {
      const matchingDays = {}

      Object.entries(slot.days || {}).forEach(([day, item]) => {
        if (selectedDayFilter.value !== 'all' && day !== selectedDayFilter.value) return
        if (!q) {
          matchingDays[day] = item
          return
        }
        const haystack = [
          item.subject,
          item.subject_code,
          item.instructor,
          item.room,
        ].filter(Boolean).join(' ').toLowerCase()
        if (haystack.includes(q)) matchingDays[day] = item
      })

      return {
        ...slot,
        days: matchingDays,
        hasMatch: Object.keys(matchingDays).length > 0,
      }
    })
    .filter(s => s.hasMatch)

  // Build display list with lunch banner inserted before the first afternoon slot
  const rows = []
  let lunchInserted = false

  for (const slot of filteredSlots) {
    if (!lunchInserted && slot.time_start >= '12:00') {
      rows.push(LUNCH_BANNER)
      lunchInserted = true
    }
    rows.push(slot)
  }

  if (!lunchInserted && filteredSlots.length > 0) {
    rows.push(LUNCH_BANNER)
  }

  return rows
})

function isActive(item) {
  if (!item || item.day !== props.today_name) return false
  const now = new Date()
  const cur = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`
  return cur >= item.time_start && cur <= item.time_end
}

function resetFilters() {
  searchQuery.value = ''
  selectedDayFilter.value = 'all'
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

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
</style>