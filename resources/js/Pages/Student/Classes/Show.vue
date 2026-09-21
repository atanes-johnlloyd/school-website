<template>
  <Head :title="`${classroom.subject} - Salawag LMS`" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <!-- Isolated Background Pattern (15% Opacity) -->
      <div 
        class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15"
        :style="{ backgroundImage: `url(${dashboardBg})` }"
      ></div>

      <!-- Main Content Container -->
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-10">
        
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

          <!-- Right Class Title (e.g. "The Work of Rizal") -->
          <div class="text-right">
            <h2 class="font-['Anton'] text-2xl sm:text-3xl md:text-4xl text-[#005506] tracking-wide uppercase">
              {{ classroom.subject || 'The Work of Rizal' }}
            </h2>
            <p v-if="classroom.teacher" class="text-xs font-semibold text-slate-500 mt-1">
              Teacher: {{ classroom.teacher }}
            </p>
          </div>

        </div>

        <!-- TAB NAVIGATION ROW -->
        <div class="flex items-center gap-8 border-b border-[#005506]/20 pb-2">
          
          <!-- Tab 1: Announcement -->
          <button 
            @click="activeTab = 'announcements'"
            :class="[
              'text-sm sm:text-base font-bold transition-all relative pb-2',
              activeTab === 'announcements' 
                ? 'text-[#005506]' 
                : 'text-slate-400 hover:text-slate-600'
            ]"
          >
            Announcement
            <div 
              v-if="activeTab === 'announcements'" 
              class="absolute bottom-0 left-0 right-0 h-1 bg-[#005506] rounded-full"
            ></div>
          </button>

          <!-- Tab 2: Assignment -->
          <button 
            @click="activeTab = 'assignments'"
            :class="[
              'text-sm sm:text-base font-bold transition-all relative pb-2',
              activeTab === 'assignments' 
                ? 'text-[#005506]' 
                : 'text-slate-400 hover:text-slate-600'
            ]"
          >
            Assignment
            <div 
              v-if="activeTab === 'assignments'" 
              class="absolute bottom-0 left-0 right-0 h-1 bg-[#005506] rounded-full"
            ></div>
          </button>

          <!-- Tab 3: Materials -->
          <button 
            @click="activeTab = 'materials'"
            :class="[
              'text-sm sm:text-base font-bold transition-all relative pb-2',
              activeTab === 'materials' 
                ? 'text-[#005506]' 
                : 'text-slate-400 hover:text-slate-600'
            ]"
          >
            Materials
            <div 
              v-if="activeTab === 'materials'" 
              class="absolute bottom-0 left-0 right-0 h-1 bg-[#005506] rounded-full"
            ></div>
          </button>

          <!-- Tab 4: Grades (Under Construction) -->
          <button 
            @click="activeTab = 'grades'"
            :class="[
              'text-sm sm:text-base font-bold transition-all relative pb-2',
              activeTab === 'grades' 
                ? 'text-[#005506]' 
                : 'text-slate-400 hover:text-slate-600'
            ]"
          >
            Grades
            <div 
              v-if="activeTab === 'grades'" 
              class="absolute bottom-0 left-0 right-0 h-1 bg-[#005506] rounded-full"
            ></div>
          </button>

        </div>

        <!-- TAB CONTENT 1: ANNOUNCEMENT -->
        <div v-if="activeTab === 'announcements'" class="space-y-6 animate-fade-in">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div 
              v-for="item in announcementsList" 
              :key="item.id"
              class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/80 min-h-[220px] flex flex-col justify-between hover:shadow-md transition-shadow"
            >
              <div>
                <h4 class="font-bold text-slate-800 text-base leading-snug">
                  {{ item.title }}
                </h4>
                <p class="text-xs text-slate-500 mt-2 line-clamp-4 leading-relaxed">
                  {{ item.content }}
                </p>
              </div>
              <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
                <span>{{ item.date }}</span>
                <span>{{ item.postedBy }}</span>
              </div>
            </div>

          </div>
        </div>

        <!-- TAB CONTENT 2: ASSIGNMENT -->
        <div v-if="activeTab === 'assignments'" class="space-y-6 animate-fade-in">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div 
              v-for="item in assignmentsList" 
              :key="item.id"
              class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/80 min-h-[160px] flex flex-col justify-between relative hover:shadow-md transition-shadow"
            >
              <!-- Status Pill Badge (Top Right) -->
              <div class="absolute top-6 right-6">
                <span 
                  v-if="item.status === 'Graded'" 
                  class="px-3 py-1 rounded-full text-xs font-bold border border-emerald-600 text-emerald-700 bg-emerald-50"
                >
                  Graded
                </span>
                <span 
                  v-else-if="item.status === 'Pending'" 
                  class="px-3 py-1 rounded-full text-xs font-bold border border-amber-400 text-amber-700 bg-amber-50"
                >
                  Pending
                </span>
                <span 
                  v-else-if="item.status === 'Missed'" 
                  class="px-3 py-1 rounded-full text-xs font-bold border border-rose-400 text-rose-700 bg-rose-50"
                >
                  Missed
                </span>
              </div>

              <!-- Title & Meta -->
              <div class="space-y-1.5 pr-20">
                <h4 class="font-bold text-slate-900 text-lg">
                  {{ item.title }}
                </h4>
                <p class="text-xs font-medium text-slate-500">
                  Due {{ item.dueDate }} - {{ item.totalPoints }}pts
                </p>
              </div>

              <!-- Divider & Grade Info -->
              <div class="pt-3 border-t border-slate-200/80 flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-600">
                  Grade: 
                  <span class="font-bold text-slate-900 ml-1">
                    {{ item.grade ? item.grade : '--' }} / {{ item.totalPoints }}
                  </span>
                </p>

                <Link 
                  :href="route('student.assignments.show', item.id)"
                  class="text-xs font-bold text-[#005506] hover:underline"
                >
                  View Details →
                </Link>
              </div>
            </div>

          </div>
        </div>

        <!-- TAB CONTENT 3: MATERIALS -->
        <div v-if="activeTab === 'materials'" class="space-y-8 animate-fade-in">
          
          <!-- Section A: POWER POINT -->
          <div class="flex items-start gap-6">
            <div class="w-32 shrink-0 font-extrabold text-slate-800 text-sm uppercase tracking-wide pt-2">
              POWER<br />POINT
            </div>
            <div class="flex-1 flex items-center gap-4 overflow-x-auto pb-2 custom-scrollbar">
              <div 
                v-for="n in 5" 
                :key="`ppt-${n}`"
                class="w-44 shrink-0 bg-white/90 backdrop-blur-sm rounded-2xl border border-slate-200/80 p-3 text-center shadow-sm hover:shadow-md transition-shadow cursor-pointer"
              >
                <div class="h-16 bg-[#2d6a33] rounded-xl flex items-center justify-center mb-2">
                  <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14H7v-2h3v2zm0-4H7v-2h3v2zm0-4H7V7h3v2zm7 8h-5V7h5v10z"/>
                  </svg>
                </div>
                <span class="text-xs font-bold text-slate-700">WEEK {{ n }}</span>
              </div>
            </div>
          </div>

          <!-- Section B: VIDEO LESSON -->
          <div class="flex items-start gap-6">
            <div class="w-32 shrink-0 font-extrabold text-slate-800 text-sm uppercase tracking-wide pt-2">
              VIDEO<br />LESSON
            </div>
            <div class="flex-1 flex items-center gap-4 overflow-x-auto pb-2 custom-scrollbar">
              <div 
                v-for="n in 3" 
                :key="`video-${n}`"
                class="w-64 shrink-0 bg-white/90 backdrop-blur-sm rounded-2xl border border-slate-200/80 p-3 text-center shadow-sm hover:shadow-md transition-shadow cursor-pointer"
              >
                <div class="h-16 bg-[#c8e6c9] rounded-xl flex items-center justify-center mb-2">
                  <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 text-[#005506] ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </div>
                </div>
                <span class="text-xs font-bold text-slate-700">WEEK {{ n }}</span>
              </div>
            </div>
          </div>

          <!-- Section C: ONLINE CLASS LINK -->
          <div class="flex items-start gap-6">
            <div class="w-32 shrink-0 font-extrabold text-slate-800 text-sm uppercase tracking-wide pt-2">
              ONLINE<br />CLASS<br />LINK
            </div>
            <div class="w-44 shrink-0 bg-white/90 backdrop-blur-sm rounded-2xl border border-slate-200/80 p-3 text-center shadow-sm hover:shadow-md transition-shadow cursor-pointer">
              <div class="h-16 bg-[#1b431e] rounded-xl flex items-center justify-center mb-2">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
              </div>
              <span class="text-xs font-bold text-slate-700">ONLINE LINK</span>
            </div>
          </div>

        </div>

        <!-- TAB CONTENT 4: GRADES (DESIGN IN PROGRESS PLACEHOLDER) -->
        <div v-if="activeTab === 'grades'" class="space-y-6 animate-fade-in">
          <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-12 text-center border border-slate-200/60 shadow-sm space-y-3">
            <div class="w-14 h-14 rounded-full bg-emerald-50 text-[#005506] mx-auto flex items-center justify-center">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a2 2 0 01-2 2 2 2 0 01-2-2V4zM4 7a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V7z" />
              </svg>
            </div>
            <h3 class="font-bold text-slate-800 text-lg">Grades View Under Construction</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">
              This module is currently being finalized. Your academic evaluations will appear here soon.
            </p>
          </div>
        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

import dashboardBg from '@/../assets/img/dashboardbackground.png'

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
  { id: 1, title: 'Assignment 1', dueDate: '9/22/2026, 12:30:00 AM', totalPoints: '100.00', grade: '99.00', status: 'Graded' },
  { id: 2, title: 'Assignment 2', dueDate: '9/28/2026, 11:59:00 PM', totalPoints: '100.00', grade: null, status: 'Pending' },
  { id: 3, title: 'Assignment 3', dueDate: '9/15/2026, 11:59:00 PM', totalPoints: '50.00', grade: '0.00', status: 'Missed' },
  { id: 4, title: 'Assignment 4', dueDate: '10/05/2026, 12:00:00 AM', totalPoints: '100.00', grade: null, status: 'Pending' },
  { id: 5, title: 'Assignment 5', dueDate: '10/12/2026, 12:00:00 AM', totalPoints: '100.00', grade: null, status: 'Pending' },
  { id: 6, title: 'Assignment 6', dueDate: '10/19/2026, 12:00:00 AM', totalPoints: '100.00', grade: null, status: 'Pending' },
]
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');

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

/* Custom Scrollbars */
.custom-scrollbar::-webkit-scrollbar {
  height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(0, 85, 6, 0.05);
  border-radius: 8px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(0, 85, 6, 0.25);
  border-radius: 8px;
}
</style>