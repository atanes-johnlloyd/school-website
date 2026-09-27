<template>
  <Head :title="`${classroom.subject} - Salawag LMS`" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">

      <!-- Main Content Container -->
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-16">
        
        <!-- HEADER ROW: TITLE LEFT & SUBJECT TITLE RIGHT -->
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
          
          <!-- Left Title & Subtext -->
          <div class="space-y-1">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none">
              <span class="text-[#005506]">STUDENT</span>
              <span class="animated-reverse-stroke-text">SUBJECTS</span>
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

          <!-- Right Class Title -->
          <div class="text-left md:text-right backdrop-blur-sm p-4 rounded-2xl">
            <h2 class="font-['Anton'] text-2xl sm:text-3xl md:text-4xl text-[#005506] tracking-wide uppercase">
              {{ classroom.subject || 'The Work of Rizal' }}
            </h2>
            <p v-if="classroom.teacher" class="text-xs font-bold text-slate-600 mt-0.5 flex items-center md:justify-end gap-1.5">
              <span>Instructor:</span>
              <span class="text-[#005506] bg-emerald-100/60 px-2.5 py-0.5 rounded-full text-[11px]">{{ classroom.teacher }}</span>
            </p>
          </div>

        </div>

        <!-- TAB NAVIGATION ROW -->
        <div class="flex items-center gap-6 sm:gap-8 border-b border-[#005506]/20 pb-2 overflow-x-auto">
          
          <!-- Tab 1: Announcement -->
          <button 
            @click="activeTab = 'announcements'"
            :class="[
              'text-sm sm:text-base font-extrabold transition-all relative pb-3 whitespace-nowrap',
              activeTab === 'announcements' 
                ? 'text-[#005506]' 
                : 'text-slate-400 hover:text-slate-700'
            ]"
          >
            Announcements
            <div 
              v-if="activeTab === 'announcements'" 
              class="absolute bottom-0 left-0 right-0 h-1 bg-[#005506] rounded-full"
            ></div>
          </button>

          <!-- Tab 2: Assignment -->
          <button 
            @click="activeTab = 'assignments'"
            :class="[
              'text-sm sm:text-base font-extrabold transition-all relative pb-3 whitespace-nowrap',
              activeTab === 'assignments' 
                ? 'text-[#005506]' 
                : 'text-slate-400 hover:text-slate-700'
            ]"
          >
            Assignments
            <div 
              v-if="activeTab === 'assignments'" 
              class="absolute bottom-0 left-0 right-0 h-1 bg-[#005506] rounded-full"
            ></div>
          </button>

          <!-- Tab 3: Materials -->
          <button 
            @click="activeTab = 'materials'"
            :class="[
              'text-sm sm:text-base font-extrabold transition-all relative pb-3 whitespace-nowrap',
              activeTab === 'materials' 
                ? 'text-[#005506]' 
                : 'text-slate-400 hover:text-slate-700'
            ]"
          >
            Materials
            <div 
              v-if="activeTab === 'materials'" 
              class="absolute bottom-0 left-0 right-0 h-1 bg-[#005506] rounded-full"
            ></div>
          </button>

          <!-- Tab 4: Grades -->
          <button 
            @click="activeTab = 'grades'"
            :class="[
              'text-sm sm:text-base font-extrabold transition-all relative pb-3 whitespace-nowrap',
              activeTab === 'grades' 
                ? 'text-[#005506]' 
                : 'text-slate-400 hover:text-slate-700'
            ]"
          >
            Grades
            <div 
              v-if="activeTab === 'grades'" 
              class="absolute bottom-0 left-0 right-0 h-1 bg-[#005506] rounded-full"
            ></div>
          </button>

        </div>

        <!-- TAB CONTENT 1: ANNOUNCEMENTS -->
        <div v-if="activeTab === 'announcements'" class="space-y-6 animate-fade-in">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div 
              v-for="item in announcementsList" 
              :key="item.id"
              class="bg-[#fbfdf9] rounded-3xl p-6 shadow-xs border border-slate-200/80 min-h-[220px] flex flex-col justify-between hover:shadow-md transition-all group"
            >
              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <span class="text-[10px] font-extrabold uppercase bg-emerald-100/80 text-[#005506] px-3 py-1 rounded-full">
                    Notice
                  </span>
                  <span class="text-[11px] font-semibold text-slate-400">{{ item.date }}</span>
                </div>
                <h4 class="font-extrabold text-slate-900 text-base leading-snug group-hover:text-[#005506] transition-colors">
                  {{ item.title }}
                </h4>
                <p class="text-xs text-slate-600 line-clamp-4 leading-relaxed font-medium">
                  {{ item.content }}
                </p>
              </div>

              <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-semibold">
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full bg-[#005506] text-white text-[10px] flex items-center justify-center font-bold">
                    {{ item.postedBy.charAt(0) }}
                  </div>
                  <span>{{ item.postedBy }}</span>
                </div>
                <span class="text-emerald-700 font-bold group-hover:translate-x-0.5 transition-transform">Read →</span>
              </div>
            </div>

          </div>
        </div>

        <!-- TAB CONTENT 2: ASSIGNMENT -->
        <div v-if="activeTab === 'assignments'" class="space-y-6 animate-fade-in">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div 
              v-for="item in assignmentsList" 
              :key="item.id"
              class="bg-[#fbfdf9] rounded-3xl p-6 shadow-xs border border-slate-200/80 flex flex-col justify-between relative hover:shadow-md transition-all group"
            >
              <!-- Top Header & Status Badge -->
              <div class="flex items-start justify-between gap-2 mb-4">
                <div class="space-y-1">
                  <h4 class="font-extrabold text-slate-900 text-base leading-snug group-hover:text-[#005506] transition-colors">
                    {{ item.title }}
                  </h4>
                  <p class="text-xs font-semibold text-slate-400">
                    Due: {{ item.dueDate }}
                  </p>
                </div>

                <!-- Status Pill Badge -->
                <span 
                  v-if="item.status === 'Graded'" 
                  class="px-3 py-1 rounded-full text-[11px] font-extrabold border border-emerald-600 text-emerald-800 bg-emerald-50 shrink-0 shadow-2xs"
                >
                  Graded
                </span>
                <span 
                  v-else-if="item.status === 'Pending'" 
                  class="px-3 py-1 rounded-full text-[11px] font-extrabold border border-amber-400 text-amber-800 bg-amber-50 shrink-0 shadow-2xs"
                >
                  Pending
                </span>
                <span 
                  v-else-if="item.status === 'Missed'" 
                  class="px-3 py-1 rounded-full text-[11px] font-extrabold border border-rose-400 text-rose-800 bg-rose-50 shrink-0 shadow-2xs"
                >
                  Missed
                </span>
              </div>

              <!-- Divider & Grade Info -->
              <div class="pt-4 border-t border-slate-200/80 flex items-center justify-between">
                <div class="bg-[#f5f7f2] px-3 py-1.5 rounded-xl border border-slate-200/70">
                  <span class="text-[11px] font-bold text-slate-500">Score: </span>
                  <span class="text-xs font-extrabold text-slate-900">
                    {{ item.grade ? item.grade : '--' }} / {{ item.totalPoints }}
                  </span>
                </div>

                <Link 
                  :href="route('student.assignments.show', item.id)"
                  class="px-4 py-2 bg-[#005506] hover:bg-[#003805] text-white text-xs font-bold rounded-xl transition-all shadow-2xs active:scale-95"
                >
                  View Details
                </Link>
              </div>
            </div>

          </div>
        </div>

        <!-- TAB CONTENT 3: MATERIALS -->
        <div v-if="activeTab === 'materials'" class="space-y-6 animate-fade-in">
          
          <!-- Section A: POWERPOINT -->
          <div class="bg-[#fbfdf9] rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-xl bg-emerald-100 text-[#005506] flex items-center justify-center font-bold">
                📊
              </div>
              <h3 class="font-extrabold text-slate-800 text-base uppercase tracking-wider">
                Presentation Decks (PowerPoint)
              </h3>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
              <div 
                v-for="n in 5" 
                :key="`ppt-${n}`"
                class="bg-white rounded-2xl border border-slate-200/80 p-4 text-center shadow-xs hover:border-[#005506] hover:shadow-md transition-all cursor-pointer group"
              >
                <div class="h-16 bg-[#005506] rounded-xl flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                  <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14H7v-2h3v2zm0-4H7v-2h3v2zm0-4H7V7h3v2zm7 8h-5V7h5v10z"/>
                  </svg>
                </div>
                <span class="text-xs font-extrabold text-slate-800 block">WEEK {{ n }} DECK</span>
                <span class="text-[10px] text-slate-400 font-medium">PPTX • 4.2 MB</span>
              </div>
            </div>
          </div>

          <!-- Section B: VIDEO LESSONS -->
          <div class="bg-[#fbfdf9] rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold">
                🎬
              </div>
              <h3 class="font-extrabold text-slate-800 text-base uppercase tracking-wider">
                Recorded Video Lectures
              </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
              <div 
                v-for="n in 3" 
                :key="`video-${n}`"
                class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:border-[#005506] hover:shadow-md transition-all cursor-pointer group flex items-center gap-4"
              >
                <div class="w-16 h-14 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-[#005506] transition-colors">
                  <div class="w-8 h-8 rounded-full bg-white text-[#005506] flex items-center justify-center shadow-xs">
                    <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </div>
                </div>
                <div>
                  <span class="text-xs font-extrabold text-slate-800 block">Lecture Stream #{{ n }}</span>
                  <span class="text-[11px] text-slate-500 font-medium">Chapter {{ n }} Breakdown</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Section C: ONLINE CLASS LINK -->
          <div class="bg-[#fbfdf9] rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center font-bold">
                🔗
              </div>
              <h3 class="font-extrabold text-slate-800 text-base uppercase tracking-wider">
                Virtual Classroom Link
              </h3>
            </div>

            <div class="max-w-md bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs flex items-center justify-between hover:border-[#005506] transition-all cursor-pointer">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-[#005506] text-white rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <span class="text-xs font-extrabold text-slate-900 block">Google Meet / Zoom Room</span>
                  <span class="text-[10px] text-emerald-600 font-bold">Active • Office Hours Ready</span>
                </div>
              </div>
              <span class="text-xs font-bold text-[#005506] bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                Join Room
              </span>
            </div>
          </div>

        </div>

        <!-- TAB CONTENT 4: GRADES OVERVIEW -->
        <div v-if="activeTab === 'grades'" class="space-y-6 animate-fade-in">
          
          <!-- Summary Header Stats Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Card 1: Cumulative Grade -->
            <div class="bg-[#fbfdf9] rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-[#005506] flex items-center justify-center text-xl font-bold shrink-0">
                🏆
              </div>
              <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Overall Grade</p>
                <div class="flex items-baseline gap-1.5">
                  <span class="text-2xl font-black text-slate-900">{{ cumulativePercentage }}%</span>
                  <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md">Passing</span>
                </div>
              </div>
            </div>

            <!-- Card 2: Points Ratio -->
            <div class="bg-[#fbfdf9] rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-800 flex items-center justify-center text-xl font-bold shrink-0">
                🎯
              </div>
              <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Points</p>
                <p class="text-2xl font-black text-slate-900">
                  {{ totalEarnedPoints }} <span class="text-xs font-semibold text-slate-400">/ {{ totalMaxPoints }}</span>
                </p>
              </div>
            </div>

            <!-- Card 3: Completion Count -->
            <div class="bg-[#fbfdf9] rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl bg-sky-100 text-sky-800 flex items-center justify-center text-xl font-bold shrink-0">
                📝
              </div>
              <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Submitted</p>
                <p class="text-2xl font-black text-slate-900">
                  {{ completedAssignmentsCount }} <span class="text-xs font-semibold text-slate-400">/ {{ assignmentsList.length }} tasks</span>
                </p>
              </div>
            </div>

            <!-- Card 4: Status Breakdown -->
            <div class="bg-[#fbfdf9] rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl font-bold shrink-0">
                ⚡
              </div>
              <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pending Grading</p>
                <p class="text-2xl font-black text-slate-900">
                  {{ pendingAssignmentsCount }} <span class="text-xs font-semibold text-slate-400">items</span>
                </p>
              </div>
            </div>

          </div>

          <!-- Detailed Grades Table -->
          <div class="bg-[#fbfdf9] rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-200/60 flex items-center justify-between">
              <div>
                <h3 class="font-extrabold text-slate-900 text-base">Grading Assessment Breakdown</h3>
                <p class="text-xs font-semibold text-slate-400">View individual scores, percentage equivalents, and instructor evaluation dates.</p>
              </div>
              <div class="text-right">
                <span class="text-xs font-bold text-slate-500">Subject Scale: </span>
                <span class="text-xs font-extrabold text-[#005506] bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">1.0 - 5.0 Scale</span>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-slate-50/70 border-b border-slate-200/60 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                    <th class="py-3.5 px-6">Assessment Name</th>
                    <th class="py-3.5 px-4">Due Date</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-center">Score</th>
                    <th class="py-3.5 px-4 text-center">Percentage</th>
                    <th class="py-3.5 px-6 text-right">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                  <tr 
                    v-for="item in assignmentsList" 
                    :key="`grade-${item.id}`"
                    class="hover:bg-emerald-50/40 transition-colors"
                  >
                    <!-- Title & Type -->
                    <td class="py-4 px-6">
                      <p class="font-extrabold text-slate-900 text-sm">{{ item.title }}</p>
                      <p class="text-[10px] text-slate-400 font-semibold">Individual Submissions</p>
                    </td>

                    <!-- Due Date -->
                    <td class="py-4 px-4 text-slate-500 font-semibold">
                      {{ item.dueDate }}
                    </td>

                    <!-- Status Pill -->
                    <td class="py-4 px-4">
                      <span 
                        v-if="item.status === 'Graded'" 
                        class="px-2.5 py-1 rounded-full text-[10px] font-extrabold border border-emerald-600 text-emerald-800 bg-emerald-50 inline-block"
                      >
                        Graded
                      </span>
                      <span 
                        v-else-if="item.status === 'Pending'" 
                        class="px-2.5 py-1 rounded-full text-[10px] font-extrabold border border-amber-400 text-amber-800 bg-amber-50 inline-block"
                      >
                        Pending Evaluation
                      </span>
                      <span 
                        v-else-if="item.status === 'Missed'" 
                        class="px-2.5 py-1 rounded-full text-[10px] font-extrabold border border-rose-400 text-rose-800 bg-rose-50 inline-block"
                      >
                        Unsubmitted
                      </span>
                    </td>

                    <!-- Score -->
                    <td class="py-4 px-4 text-center font-extrabold">
                      <span v-if="item.grade !== null" class="text-slate-900 text-sm">
                        {{ item.grade }} <span class="text-slate-400 text-xs font-medium">/ {{ item.totalPoints }}</span>
                      </span>
                      <span v-else class="text-slate-300 font-bold">-- / {{ item.totalPoints }}</span>
                    </td>

                    <!-- Computed Percentage -->
                    <td class="py-4 px-4 text-center">
                      <span v-if="item.grade !== null" class="font-black text-emerald-800 bg-emerald-100/80 px-2.5 py-1 rounded-lg">
                        {{ ((parseFloat(item.grade) / parseFloat(item.totalPoints)) * 100).toFixed(0) }}%
                      </span>
                      <span v-else class="text-slate-300 font-bold">--</span>
                    </td>

                    <!-- View Action -->
                    <td class="py-4 px-6 text-right">
                      <Link 
                        :href="route('student.assignments.show', item.id)"
                        class="text-[#005506] font-extrabold hover:underline"
                      >
                        Review
                      </Link>
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
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

const props = defineProps({
  classroom: {
    type: Object,
    default: () => ({
      id: 1,
      subject: 'The Work of Rizal',
      teacher: 'Kaylin Leffler',
    }),
  },
  announcements: Array,
  assignments: Array,
})

// Active tab state: 'announcements' | 'assignments' | 'materials' | 'grades'
const activeTab = ref('announcements')

// Fallback Sample Data
const announcementsList = props.announcements?.length ? props.announcements : [
  { id: 1, title: 'Read Chapter 3 by Friday', content: 'Please review the life of Rizal in Europe for our upcoming quiz.', date: 'Oct 24, 2026', postedBy: 'Kaylin Leffler' },
  { id: 2, title: 'Group Project Orientation', content: 'Form groups of 5 for the final term presentation on Noli Me Tangere.', date: 'Oct 20, 2026', postedBy: 'Kaylin Leffler' },
  { id: 3, title: 'Consultation Hours Updated', content: 'Office hours are shifted to Thursdays 2:00 PM - 4:00 PM.', date: 'Oct 15, 2026', postedBy: 'Kaylin Leffler' },
]

const assignmentsList = props.assignments?.length ? props.assignments : [
  { id: 1, title: 'Assignment 1', dueDate: '9/22/2026, 12:30 AM', totalPoints: '100.00', grade: '99.00', status: 'Graded' },
  { id: 2, title: 'Assignment 2', dueDate: '9/28/2026, 11:59 PM', totalPoints: '100.00', grade: null, status: 'Pending' },
  { id: 3, title: 'Assignment 3', dueDate: '9/15/2026, 11:59 PM', totalPoints: '50.00', grade: '0.00', status: 'Missed' },
  { id: 4, title: 'Assignment 4', dueDate: '10/05/2026, 12:00 AM', totalPoints: '100.00', grade: null, status: 'Pending' },
  { id: 5, title: 'Assignment 5', dueDate: '10/12/2026, 12:00 AM', totalPoints: '100.00', grade: null, status: 'Pending' },
  { id: 6, title: 'Assignment 6', dueDate: '10/19/2026, 12:00 AM', totalPoints: '100.00', grade: null, status: 'Pending' },
]

// Dynamic Grade Computations
const totalEarnedPoints = computed(() => {
  return assignmentsList
    .reduce((acc, curr) => acc + (curr.grade ? parseFloat(curr.grade) : 0), 0)
    .toFixed(2)
})

const totalMaxPoints = computed(() => {
  return assignmentsList
    .filter(a => a.status === 'Graded' || a.status === 'Missed')
    .reduce((acc, curr) => acc + parseFloat(curr.totalPoints), 0)
    .toFixed(2)
})

const cumulativePercentage = computed(() => {
  const max = parseFloat(totalMaxPoints.value)
  if (!max) return '0.0'
  return ((parseFloat(totalEarnedPoints.value) / max) * 100).toFixed(1)
})

const completedAssignmentsCount = computed(() => {
  return assignmentsList.filter(a => a.status === 'Graded').length
})

const pendingAssignmentsCount = computed(() => {
  return assignmentsList.filter(a => a.status === 'Pending').length
})
</script>

<style scoped>

/* REVERSE ANIMATED FILL-STROKE TEXT EFFECT FOR 'SUBJECTS' */
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

.animate-fade-in {
  animation: fadeIn 0.35s ease-in-out both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(6px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>