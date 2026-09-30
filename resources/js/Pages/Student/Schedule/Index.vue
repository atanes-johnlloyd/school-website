<template>
  <Head title="Class Schedule - Salawag LMS" />

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
        searchPlaceholder="Search weekly schedule, subjects, teachers, or rooms..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size" 
      />

      <!-- Main Content Container -->
      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">
        
        <!-- HERO HEADER BANNER (ENTRANCE ANIMATION) -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <!-- Animated Background Gradient Sheen -->
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <!-- Shared Header Title Block -->
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

          <!-- HERO META INFO & TELEMETRY GRID -->
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2 relative z-10">
            
            <!-- CONDITION 1: ACTIVE LIVE CLASS -->
            <template v-if="activeLiveClass">
              <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center text-3xl relative transition-transform hover:scale-105 duration-300">
                  🕒
                  <span class="absolute top-2 right-2 w-3 h-3 bg-amber-400 rounded-full animate-ping border-2 border-[#004d08]"></span>
                </div>
                <div class="space-y-1 text-center sm:text-left">
                  <div class="inline-flex items-center gap-1.5 bg-amber-400/20 backdrop-blur-sm text-amber-300 text-[11px] font-extrabold px-3 py-0.5 rounded-full border border-amber-400/30 uppercase tracking-wide animate-float-soft">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                    Live Campus Pulse • In Session
                  </div>
                  <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                    {{ activeLiveClass.title }}
                  </h2>
                  <p class="text-xs text-emerald-100/80 font-medium">
                    Instructor: {{ activeLiveClass.instructor }} • Rm {{ activeLiveClass.room }} • {{ activeLiveClass.time }}
                  </p>
                </div>
              </div>

              <div class="lg:col-span-5 grid grid-cols-2 gap-3">
                <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                  <span class="text-[11px] font-medium text-emerald-100/80">Current Status</span>
                  <div class="text-lg sm:text-xl font-extrabold text-amber-300 my-0.5 truncate">
                    {{ activeLiveClass.status }}
                  </div>
                  <span class="text-[10px] text-emerald-100/70 truncate">Ends at {{ activeLiveClass.endTime }}</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                  <span class="text-[11px] font-medium text-emerald-100/80">Next Class</span>
                  <div class="text-lg sm:text-xl font-extrabold text-white my-0.5 truncate">
                    {{ activeLiveClass.nextTime }}
                  </div>
                  <span class="text-[10px] text-emerald-100/70 truncate">{{ activeLiveClass.nextTitle }}</span>
                </div>
              </div>
            </template>

            <!-- CONDITION 2: STANDARD / INACTIVE CLASS STATE -->
            <template v-else>
              <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center text-3xl transition-transform hover:scale-105 duration-300">
                  🗓️
                </div>
                <div class="space-y-1 text-center sm:text-left">
                  <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10 animate-float-soft">
                    {{ activeTerm || 'S.Y. 2026-2027 • 1st Semester' }}
                  </div>
                  <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                    Weekly Academic Timetable
                  </h2>
                  <p class="text-xs text-emerald-100/70 font-medium">
                    Grade 12 STEM - Section Rizal • {{ totalWeeklyHours }} Hours Weekly Load
                  </p>
                </div>
              </div>

              <div class="lg:col-span-5 grid grid-cols-2 gap-3">
                <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                  <span class="text-[11px] font-medium text-emerald-100/80">Today's Classes</span>
                  <div class="text-xl font-extrabold text-amber-300 my-0.5">{{ todaysClassesCount }} Periods</div>
                  <span class="text-[10px] text-emerald-100/70">{{ activeDayName }} Schedule</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                  <span class="text-[11px] font-medium text-emerald-100/80">Next Class</span>
                  <div class="text-xl font-extrabold text-white my-0.5 truncate">{{ nextClassTime }}</div>
                  <span class="text-[10px] text-emerald-100/70 truncate">{{ nextClassName }}</span>
                </div>
              </div>
            </template>

          </div>

        </div>

        <!-- MAIN SCHEDULE BENTO CONTAINER (SLIDE ENTRANCE) -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">
          
          <!-- FILTER & TIMETABLE CONTROL BAR -->
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200/60 dark:border-[#3F4F43]">
            
            <div class="flex items-center gap-2 bg-[#f5f7f2] dark:bg-[#232D26] p-1.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] w-max">
              <button 
                @click="scheduleType = 'regular'"
                :class="[
                  'px-4 py-2 rounded-xl text-xs font-extrabold transition-all active:scale-95',
                  scheduleType === 'regular' 
                    ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-xs' 
                    : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'
                ]"
              >
                Regular Weekly Timetable
              </button>
              <button 
                @click="scheduleType = 'exams'"
                :class="[
                  'px-4 py-2 rounded-xl text-xs font-bold transition-all active:scale-95',
                  scheduleType === 'exams' 
                    ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-xs' 
                    : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'
                ]"
              >
                Q3 Periodic Exams (Mar 18–20)
              </button>
            </div>

            <!-- Day Filter Chips & Search Bar -->
            <div class="flex flex-wrap items-center gap-3">
              <div class="flex items-center gap-1 bg-[#f5f7f2] dark:bg-[#232D26] p-1 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] overflow-x-auto">
                <span class="text-[10px] font-extrabold uppercase text-slate-500 dark:text-slate-400 px-2">FILTER DAY:</span>
                <button 
                  v-for="filter in dayFilters" 
                  :key="filter.id"
                  @click="selectedDayFilter = filter.id"
                  :class="[
                    'px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap active:scale-95',
                    selectedDayFilter === filter.id 
                      ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-xs' 
                      : 'text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-[#3F4F43]'
                  ]"
                >
                  {{ filter.label }}
                </button>
              </div>

              <!-- Functional Search Input with Clear Button -->
              <div class="relative flex-1 sm:w-64">
                <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 dark:text-slate-500 text-xs">
                  🔍
                </span>
                <input 
                  v-model="searchQuery"
                  type="text" 
                  placeholder="Filter subject, teacher, room..." 
                  class="w-full bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl pl-8 pr-8 py-2 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC]"
                />
                <button 
                  v-if="searchQuery" 
                  @click="searchQuery = ''"
                  class="absolute inset-y-0 right-2.5 flex items-center text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 text-xs font-bold"
                >
                  ✕
                </button>
              </div>
            </div>

          </div>

          <!-- DYNAMIC MASTER TIMETABLE GRID -->
          <div class="overflow-x-auto pb-4">
            
            <!-- EMPTY STATE WHEN SEARCH HAS NO MATCHES -->
            <div v-if="filteredTimetable.length === 0" class="py-12 text-center space-y-3 bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl border border-dashed border-slate-300 dark:border-[#3F4F43]">
              <div class="text-3xl">🔍</div>
              <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">No Matching Classes Found</h4>
              <p class="text-xs text-slate-500 dark:text-slate-400">
                No scheduled periods matched "<span class="font-semibold text-slate-700 dark:text-slate-300">{{ searchQuery }}</span>".
              </p>
              <button 
                @click="resetFilters" 
                class="bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold px-4 py-2 rounded-xl hover:bg-[#003805] transition-colors active:scale-95"
              >
                Reset Search & Filters
              </button>
            </div>

            <!-- TIMETABLE GRID -->
            <div 
              v-else
              class="grid gap-3 transition-all duration-300"
              :class="selectedDayFilter === 'all' ? 'min-w-[1100px] grid-cols-12' : 'w-full grid-cols-12'"
            >
              
              <!-- HEADER ROW -->
              <div class="col-span-2 bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 flex items-center justify-center text-center font-extrabold text-xs text-[#004d08] dark:text-[#86EFAC] uppercase tracking-wider border border-slate-200/60 dark:border-[#3F4F43]">
                TIME SLOT
              </div>

              <!-- Visible Day Columns -->
              <div 
                v-for="day in visibleColumns" 
                :key="day.id"
                :class="[
                  selectedDayFilter === 'all' ? 'col-span-2' : 'col-span-10',
                  'rounded-2xl p-3 text-center flex flex-col justify-center border transition-all',
                  day.isToday ? 'bg-emerald-50 dark:bg-emerald-950/40 border-[#004d08] dark:border-[#86EFAC] shadow-xs' : 'bg-[#f5f7f2] dark:bg-[#232D26] border-slate-200/60 dark:border-[#3F4F43]'
                ]"
              >
                <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center justify-center gap-1.5">
                  <span>{{ day.name }}</span>
                  <span v-if="day.isToday" class="w-2 h-2 rounded-full bg-[#004d08] dark:bg-[#86EFAC]"></span>
                </h3>
                <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 block leading-tight">
                  {{ selectedDayFilter === 'all' ? day.subtext : `${day.subtext} • Focused Single Day Detailed View` }}
                </span>
              </div>

              <!-- TIMETABLE ROWS -->
              <template v-for="slot in filteredTimetable" :key="slot.slotId">
                
                <!-- Time Slot Label Box -->
                <div class="col-span-2 bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 flex flex-col items-center justify-center text-center font-extrabold text-xs text-slate-700 dark:text-slate-300 space-y-1 border border-slate-200/60 dark:border-[#3F4F43]">
                  <span>{{ slot.timeStart }}</span>
                  <span class="text-slate-400 dark:text-slate-500 font-normal">—</span>
                  <span>{{ slot.timeEnd }}</span>
                </div>

                <!-- Full-Width Recess/Lunch Row -->
                <template v-if="slot.isBanner">
                  <div class="col-span-10 bg-[#f7f6f0] dark:bg-[#1C261F] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between px-6">
                    <div class="flex items-center gap-3">
                      <span class="text-2xl">{{ slot.bannerIcon }}</span>
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

                <!-- Regular Class Cells & Custom Empty Placeholders -->
                <template v-else>
                  <template v-for="item in slot.days" :key="item.dayId">
                    
                    <!-- GREY PLACEHOLDER CELL WITH CENTERED DASH -->
                    <div 
                      v-if="item.isEmpty" 
                      :class="[
                        selectedDayFilter === 'all' ? 'col-span-2' : 'col-span-10',
                        'bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/60 dark:border-[#3F4F43] rounded-2xl p-4 flex items-center justify-center min-h-[120px]'
                      ]"
                    >
                      <span class="text-lg leading-none text-slate-400 dark:text-slate-600">—</span>
                    </div>

                    <!-- MATCHING CLASS CARD -->
                    <div 
                      v-else
                      :class="[
                        selectedDayFilter === 'all' ? 'col-span-2' : 'col-span-10',
                        item.isActive ? 'bg-amber-500/10 border-2 border-amber-400 shadow-xs' : 'bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43]',
                        'rounded-2xl p-4 flex flex-col justify-between space-y-3 transition-all hover:-translate-y-1 hover:shadow-md'
                      ]"
                    >
                      <!-- Top Tag & Subject Title -->
                      <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                          <span :class="[
                            item.isActive ? 'bg-amber-400 text-slate-900 font-black' : 'bg-white dark:bg-[#2D3A31] text-[#004d08] dark:text-[#86EFAC] font-extrabold border border-slate-200 dark:border-[#3F4F43]',
                            'text-[10px] px-2 py-0.5 rounded-md uppercase'
                          ]">
                            {{ item.tag }}
                          </span>
                          <span v-if="item.statusBadge" class="text-[10px] font-bold text-emerald-800 dark:text-[#86EFAC] bg-emerald-100 dark:bg-[#3F4F43] px-2 py-0.5 rounded">
                            {{ item.statusBadge }}
                          </span>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm leading-snug">{{ item.subject }}</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold">{{ item.instructor }}</p>
                      </div>

                      <!-- Compact Info (Shows in ALL Days View) -->
                      <div v-if="selectedDayFilter === 'all'" class="bg-white dark:bg-[#2D3A31] rounded-xl p-2 text-[11px] text-slate-600 dark:text-slate-300 font-medium space-y-0.5 border border-slate-200/60 dark:border-[#3F4F43]">
                        <div class="flex items-center gap-1 font-bold text-slate-800 dark:text-slate-200">
                          <span>{{ item.room }}</span>
                        </div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400">({{ item.topic }})</div>
                      </div>

                      <!-- EXPANDED SINGLE-DAY INFORMATION & SHORTCUTS -->
                      <div v-else class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] space-y-3 mt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs border-b border-slate-100 dark:border-[#3F4F43] pb-3">
                          <div>
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Location & Room</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ item.room }}</span>
                          </div>
                          <div>
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Lesson Module</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ item.topic }}</span>
                          </div>
                          <div>
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Attendance Status</span>
                            <span class="font-bold text-emerald-600 dark:text-[#86EFAC] flex items-center gap-1">
                              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> Present / Logged
                            </span>
                          </div>
                        </div>

                        <!-- Action Shortcuts -->
                        <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                          <div class="flex items-center gap-2">
                            <button class="bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold px-3 py-1.5 rounded-xl hover:bg-[#003805] transition-colors flex items-center gap-1.5 active:scale-95">
                              📄 View Course Materials
                            </button>
                            <button class="bg-emerald-50 dark:bg-[#3F4F43] text-[#004d08] dark:text-[#86EFAC] text-xs font-bold px-3 py-1.5 rounded-xl hover:bg-emerald-100 transition-colors border border-emerald-200/60 dark:border-[#3F4F43] flex items-center gap-1.5 active:scale-95">
                              ✏️ Submit Activity
                            </button>
                          </div>
                          <button class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white underline">
                            Contact Teacher
                          </button>
                        </div>
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
import { Head } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'

const props = defineProps({
  activeTerm: String,
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')

const scheduleType = ref('regular')
const selectedDayFilter = ref('all')
const searchQuery = ref('')
const activeDayName = 'Tuesday'
const todaysClassesCount = 3
const nextClassTime = '07:30 - 09:30 AM'
const nextClassName = 'General Physics 2 Lab'
const totalWeeklyHours = 22.5

const daysOrder = ['mon', 'tue', 'wed', 'thu', 'fri']

const activeLiveClass = ref({
  title: 'Basic Calculus Problem Solving',
  status: '42m Remaining',
  instructor: 'Mrs. Elena Mendoza',
  room: '302',
  time: '09:45 AM – 11:45 AM',
  endTime: '11:45 AM',
  nextTitle: 'Campus Lunch Recess',
  nextTime: '11:45 AM - 01:00 PM'
})

const dayFilters = [
  { id: 'all', label: 'All Days' },
  { id: 'mon', label: 'Mon' },
  { id: 'tue', label: 'Tue (Today)' },
  { id: 'wed', label: 'Wed' },
  { id: 'thu', label: 'Thu' },
  { id: 'fri', label: 'Fri' },
]

const columns = [
  { id: 'mon', name: 'Monday', subtext: 'Day 1', isToday: false },
  { id: 'tue', name: 'Tuesday •', subtext: 'Today', isToday: true },
  { id: 'wed', name: 'Wednesday', subtext: 'Day 3', isToday: false },
  { id: 'thu', name: 'Thursday', subtext: 'Day 4', isToday: false },
  { id: 'fri', name: 'Friday', subtext: 'Wellness & Club', isToday: false },
]

const visibleColumns = computed(() => {
  if (selectedDayFilter.value === 'all') return columns
  return columns.filter(c => c.id === selectedDayFilter.value)
})

const timetable = [
  {
    slotId: 1,
    timeStart: '07:30 AM',
    timeEnd: '09:30 AM',
    days: [
      { dayId: 'mon', tag: 'STEM Core', subject: 'General Physics 2', instructor: 'Engr. Benjamin Bautista', room: '🔬 Sci-Lab 2', topic: 'Bldg B • Lecture' },
      { dayId: 'tue', tag: 'Lab Hands-on', subject: 'General Physics 2 Lab', instructor: 'Engr. Benjamin Bautista', room: '🔬 Lab 204', topic: '2nd Flr • Experiment 4', statusBadge: 'Completed' },
      { dayId: 'wed', tag: 'STEM Core', subject: 'General Physics 2', instructor: 'Engr. Benjamin Bautista', room: '🔬 Sci-Lab 2', topic: 'Electromagnetism' },
      { dayId: 'thu', tag: 'Lab Hands-on', subject: 'General Physics 2 Lab', instructor: 'Engr. Benjamin Bautista', room: '🔬 Lab 204', topic: 'Circuits Prac' },
      { dayId: 'fri', tag: 'Wellness (08:00-10:00)', subject: 'PE & Health 4', instructor: 'Coach Danilo Mendoza', room: '🏀 Main Gym', topic: 'Badminton' }
    ]
  },
  {
    slotId: 2,
    timeStart: '09:45 AM',
    timeEnd: '11:45 AM',
    days: [
      { dayId: 'mon', tag: 'STEM Major', subject: 'Basic Calculus', instructor: 'Mrs. Elena Mendoza', room: '🏫 Room 302', topic: 'Limits & Deriv' },
      { dayId: 'tue', tag: 'Now In Session', subject: 'Basic Calculus Prob. Solving', instructor: 'Mrs. Elena Mendoza', room: '📍 Rm 302 (Annex)', topic: 'Integration Practice', isActive: true },
      { dayId: 'wed', tag: 'STEM Major', subject: 'Basic Calculus', instructor: 'Mrs. Elena Mendoza', room: '🏫 Room 302', topic: 'Anti-derivatives' },
      { dayId: 'thu', tag: 'Problem Set', subject: 'Basic Calculus Prob. Solving', instructor: 'Mrs. Elena Mendoza', room: '🏫 Room 302', topic: 'Quiz Prep' },
      { dayId: 'fri', tag: '10:15 - 11:45 AM', subject: 'Homeroom & SSG', instructor: 'Dr. Garcia / Mrs. Mercado', room: '👥 Room 302', topic: 'Assembly' }
    ]
  },
  {
    slotId: 3,
    isBanner: true,
    timeStart: '11:45 AM',
    timeEnd: '01:00 PM',
    bannerIcon: '🍔',
    bannerTitle: 'Campus Lunch Recess • Bayanihan Peer Tutoring Hub',
    bannerSubtitle: 'Student Hub, Canteen Pavilion & Library Learning Commons open for collaborative study',
    bannerBadge: '75 Minutes'
  },
  {
    slotId: 4,
    timeStart: '01:00 PM',
    timeEnd: '02:30 PM',
    days: [
      { dayId: 'mon', tag: 'Core Subject', subject: 'Media & Info Literacy', instructor: 'Mr. Arthur Alcantara', room: '📺 Media Studio 1', topic: 'Digital Media' },
      { dayId: 'tue', tag: 'Applied Track', subject: 'Pagsulat sa Filipino', instructor: 'G. Aris Macapagal', room: '📖 Rm 302', topic: 'Akademikong Sulatin' },
      { dayId: 'wed', tag: 'Core Subject', subject: 'Media & Info Literacy', instructor: 'Mr. Arthur Alcantara', room: '📺 Media Studio 1', topic: 'Podcast Lab' },
      { dayId: 'thu', tag: 'Applied Track', subject: 'Pagsulat sa Filipino', instructor: 'G. Aris Macapagal', room: '📖 Rm 302', topic: 'Bionote & Sintesis' },
      { dayId: 'fri', tag: 'Open Lab • 01:00-03:00', subject: 'Robotics & Maker Hub', instructor: 'STEM Guild Mentors', room: '🤖 FabLab Hub', topic: 'Open Hours' }
    ]
  },
  {
    slotId: 5,
    timeStart: '02:45 PM',
    timeEnd: '04:45 PM',
    days: [
      { dayId: 'mon', tag: 'Capstone Specialized', subject: 'Practical Research II', instructor: 'Dr. Josefa Garcia', room: '📍 AVR 1 (Annex 3rd Flr)', topic: 'Data Defense' },
      { dayId: 'tue', tag: 'Capstone Specialized', subject: 'PR II Capstone Work', instructor: 'Dr. Josefa Garcia', room: '💡 Innovation Hub', topic: 'Group Consult' },
      { dayId: 'wed', tag: 'Capstone Specialized', subject: 'Practical Research II', instructor: 'Dr. Josefa Garcia', room: '📍 AVR 1', topic: 'Methodology' },
      { dayId: 'thu', tag: 'Capstone Specialized', subject: 'PR II Capstone Work', instructor: 'Dr. Josefa Garcia', room: '💡 Innovation Hub', topic: 'Manuscript Rev' },
      { dayId: 'fri', tag: 'Dismissal / Consultation', subject: 'Faculty Office Hours', instructor: 'Advisers & Subject Teachers', room: '📂 By Appointment', topic: 'Consultation' }
    ]
  }
]

// DYNAMIC FILTERING (DAY FILTER + SEARCH QUERY + POSITION PRESERVATION)
const filteredTimetable = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()

  return timetable
    .map(slot => {
      if (slot.isBanner) {
        if (!query) return slot
        const bannerText = `${slot.bannerTitle} ${slot.bannerSubtitle}`.toLowerCase()
        return bannerText.includes(query) ? slot : null
      }

      // Check if any class in this time slot matches the active search query
      const hasMatchInSlot = slot.days.some(item => {
        if (selectedDayFilter.value !== 'all' && item.dayId !== selectedDayFilter.value) {
          return false
        }
        if (!query) return true
        return (
          item.subject.toLowerCase().includes(query) ||
          item.instructor.toLowerCase().includes(query) ||
          item.room.toLowerCase().includes(query) ||
          item.tag.toLowerCase().includes(query) ||
          (item.topic && item.topic.toLowerCase().includes(query))
        )
      })

      if (!hasMatchInSlot) return null

      // If 'All Days' mode is active, construct fixed Mon-Fri array to maintain grid columns
      if (selectedDayFilter.value === 'all') {
        const fullDays = daysOrder.map(dayId => {
          const item = slot.days.find(d => d.dayId === dayId)
          if (!item) return { dayId, isEmpty: true }

          const isMatch = !query || (
            item.subject.toLowerCase().includes(query) ||
            item.instructor.toLowerCase().includes(query) ||
            item.room.toLowerCase().includes(query) ||
            item.tag.toLowerCase().includes(query) ||
            (item.topic && item.topic.toLowerCase().includes(query))
          )

          return isMatch ? { ...item, isEmpty: false } : { dayId, isEmpty: true }
        })

        return { ...slot, days: fullDays }
      }

      // Single Day Filter Active
      const singleDay = slot.days.filter(d => d.dayId === selectedDayFilter.value)
      return { ...slot, days: singleDay }
    })
    .filter(Boolean)
})

const resetFilters = () => {
  searchQuery.value = ''
  selectedDayFilter.value = 'all'
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800;900&display=swap');

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