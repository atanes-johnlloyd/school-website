<template>
  <Head title="Assessments - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <!-- Isolated Pattern Background (15% Opacity) -->
      <div 
        class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15"
        :style="{ backgroundImage: `url(${dashboardBg})` }"
      ></div>

      <!-- Main Content Container -->
      <div class="relative z-10 p-6 md:p-10 space-y-10 flex-1 pb-16">
        
        <!-- HEADER ROW: STUDENT ASSESSMENTS -->
        <div class="space-y-1">
          <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none">
            <!-- Solid Emerald Fill for STUDENT -->
            <span class="text-[#005506]">STUDENT</span>
            <!-- Reverse Fill-Stroke Animation for ASSESSMENTS -->
            <span class="animated-reverse-stroke-text">ASSESSMENTS</span>
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

        <!-- SECTION 1: UPCOMING DEADLINES -->
        <div class="space-y-4">
          <h3 class="text-xl sm:text-2xl font-bold text-[#005506] tracking-tight border-b border-[#005506]/20 pb-2">
            Upcoming Deadlines
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="item in upcomingList" 
              :key="item.id"
              class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/80 p-5 pl-7 flex flex-col justify-between min-h-[160px] hover:shadow-md transition-all group"
            >
              <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
              
              <div>
                <div class="flex items-start justify-between gap-2">
                  <h4 class="font-bold text-slate-800 text-base leading-snug group-hover:text-[#005506] transition-colors">
                    {{ item.title }}
                  </h4>
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200 shrink-0">
                    {{ item.status || 'Upcoming' }}
                  </span>
                </div>
                
                <p class="text-xs font-semibold text-[#005506] mt-1">
                  {{ item.subject }}
                </p>
                <p class="text-xs text-slate-500 font-medium mt-2 line-clamp-2">
                  {{ item.description }}
                </p>
              </div>

              <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs mt-3">
                <span class="font-semibold text-rose-600">
                  Due {{ item.dueDate }}
                </span>
                <span class="font-bold text-slate-400">
                  {{ item.points }} pts
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- SECTION 2: GROUP PROJECTS -->
        <div class="space-y-4">
          <h3 class="text-xl sm:text-2xl font-bold text-[#005506] tracking-tight border-b border-[#005506]/20 pb-2">
            Group Projects
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="item in groupProjectsList" 
              :key="item.id"
              class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/80 p-5 pl-7 flex flex-col justify-between min-h-[160px] hover:shadow-md transition-all group"
            >
              <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
              
              <div>
                <div class="flex items-start justify-between gap-2">
                  <h4 class="font-bold text-slate-800 text-base leading-snug group-hover:text-[#005506] transition-colors">
                    {{ item.title }}
                  </h4>
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-[#005506] border border-emerald-200 shrink-0">
                    Group Task
                  </span>
                </div>
                
                <p class="text-xs font-semibold text-[#005506] mt-1">
                  {{ item.subject }}
                </p>
                <p class="text-xs text-slate-500 font-medium mt-2 line-clamp-2">
                  {{ item.description }}
                </p>
              </div>

              <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs mt-3">
                <span class="font-semibold text-amber-600">
                  Due {{ item.dueDate }}
                </span>
                <span class="font-bold text-slate-400">
                  {{ item.points }} pts
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- SECTION 3: COMPLETED -->
        <div class="space-y-4">
          <h3 class="text-xl sm:text-2xl font-bold text-[#005506] tracking-tight border-b border-[#005506]/20 pb-2">
            Completed
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="item in completedList" 
              :key="item.id"
              class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/80 p-5 pl-7 flex flex-col justify-between min-h-[160px] hover:shadow-md transition-all opacity-95 group"
            >
              <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-slate-400 rounded-l-2xl"></div>
              
              <div>
                <div class="flex items-start justify-between gap-2">
                  <h4 class="font-bold text-slate-800 text-base leading-snug group-hover:text-[#005506] transition-colors">
                    {{ item.title }}
                  </h4>
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0">
                    Graded
                  </span>
                </div>
                
                <p class="text-xs font-semibold text-[#005506] mt-1">
                  {{ item.subject }}
                </p>
                <p class="text-xs text-slate-500 font-medium mt-2 line-clamp-2">
                  Submitted on {{ item.submittedAt }}
                </p>
              </div>

              <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs mt-3">
                <span class="font-bold text-[#005506]">
                  Score: {{ item.score }} / {{ item.points }}
                </span>
                <span class="text-slate-400 text-[11px]">
                  100% Turn-in
                </span>
              </div>
            </div>
          </div>
        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

import dashboardBg from '@/../assets/img/dashboardbackground.png'

const props = defineProps({
  upcoming: Array,
  groupProjects: Array,
  completed: Array,
})

// Sample fallback lists if backend data isn't piped yet
const upcomingList = props.upcoming?.length ? props.upcoming : [
  { id: 1, title: 'General Math Midterm Quiz', subject: 'General Mathematics', dueDate: 'Oct 24, 2026', points: 100, description: 'Covers functions, rational expressions, and logarithmic equations.', status: 'Upcoming' },
  { id: 2, title: 'Physics Lab Report #2', subject: 'General Physics 1', dueDate: 'Oct 28, 2026', points: 50, description: 'Vector analysis, kinematics, and laboratory chart attachments.', status: 'Pending' },
  { id: 3, title: 'Persuasive Speech Video', subject: 'Oral Communication', dueDate: 'Nov 02, 2026', points: 100, description: '3-minute video recording on environmental sustainability.', status: 'Upcoming' },
  { id: 4, title: 'Plate Tectonics Reflection', subject: 'Earth Science', dueDate: 'Nov 05, 2026', points: 50, description: 'Written summary paper on geological structures and rock layers.', status: 'Upcoming' },
  { id: 5, title: 'HTML Wireframe Submission', subject: 'Empowerment Tech', dueDate: 'Nov 10, 2026', points: 100, description: 'First prototype draft of the personal portfolio website.', status: 'Upcoming' },
  { id: 6, title: 'Fitness Activity Log', subject: 'Physical Education 1', dueDate: 'Nov 12, 2026', points: 30, description: 'Weekly physical exercise and cardiovascular activity tracker.', status: 'Upcoming' },
]

const groupProjectsList = props.groupProjects?.length ? props.groupProjects : [
  { id: 101, title: 'Noli Me Tangere Group Dramatization', subject: 'Filipino / Rizal', dueDate: 'Nov 18, 2026', points: 100, description: 'Video skit presentation covering Chapters 1 to 10.' },
  { id: 102, title: 'STEM Innovation Project Proposal', subject: 'Research in Daily Life 1', dueDate: 'Nov 25, 2026', points: 150, description: 'Chapter 1 to 3 research methodology draft submission.' },
  { id: 103, title: 'Community Outreach Plan', subject: 'Personal Development', dueDate: 'Dec 01, 2026', points: 100, description: 'Action plan proposal for barangay educational assistance.' },
  { id: 104, title: 'Ecosystem Case Study', subject: 'Earth Science', dueDate: 'Dec 05, 2026', points: 80, description: 'Collaborative analysis on marine biodiversity in Cavite.' },
  { id: 105, title: 'Capstobne App Wireframe', subject: 'Empowerment Tech', dueDate: 'Dec 10, 2026', points: 100, description: 'Interactive prototype design for school management app.' },
  { id: 106, title: 'Physics Experiment Video', subject: 'General Physics 1', dueDate: 'Dec 15, 2026', points: 100, description: 'Recorded experiment showing Newton laws of motion.' },
]

const completedList = props.completed?.length ? props.completed : [
  { id: 201, title: 'Diagnostic Exam', subject: 'General Mathematics', submittedAt: 'Sep 12, 2026', score: 95, points: 100 },
  { id: 202, title: 'Essay on Communication Barriers', subject: 'Oral Communication', submittedAt: 'Sep 18, 2026', score: 48, points: 50 },
  { id: 203, title: 'Vector Addition Worksheet', subject: 'General Physics 1', submittedAt: 'Sep 25, 2026', score: 98, points: 100 },
  { id: 204, title: 'Earth System Quiz', subject: 'Earth Science', submittedAt: 'Oct 02, 2026', score: 45, points: 50 },
  { id: 205, title: 'ICT Safety Reflection', subject: 'Empowerment Tech', submittedAt: 'Oct 08, 2026', score: 50, points: 50 },
  { id: 206, title: 'Warm-up Routine Video', subject: 'Physical Education 1', submittedAt: 'Oct 15, 2026', score: 30, points: 30 },
]
</script>

<style scoped>

/* REVERSE ANIMATED FILL-STROKE TEXT EFFECT FOR 'ASSESSMENTS' */
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
</style>