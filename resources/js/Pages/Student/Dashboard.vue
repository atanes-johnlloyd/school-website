<template>
  <Head title="Student Dashboard - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <!-- Main Content Container -->
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-16">
        
        <!-- HEADER ROW WITH TITLE & FLOATING PROFILE BANNER -->
        <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-6 overflow-x-clip py-2 px-1 min-h-[100px]">
          
          <!-- Left Header Title & Subtext -->
          <div class="space-y-1">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none">
              <!-- Solid Emerald Fill for STUDENT -->
              <span class="text-[#005506]">STUDENT</span>
              <!-- Reverse Fill-Stroke Animation for DASHBOARD -->
              <span class="animated-reverse-stroke-text">DASHBOARD</span>
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

          <!-- Floating Student Profile Banner -->
          <div class="absolute right-0 top-0 z-20 flex items-center overflow-hidden py-2 pl-4 pointer-events-none animate-banner-auto-hide">
            <div class="bg-[#eab308] text-slate-900 px-8 sm:px-10 py-3.5 rounded-l-full shadow-lg flex flex-col justify-center text-right pr-14 -mr-10 pointer-events-auto animate-pill-slide-left">
              <h2 class="font-extrabold text-base sm:text-lg tracking-tight leading-tight whitespace-nowrap">
                Good day, {{ auth?.user?.name || 'kitKIT!' }}
              </h2>
              <p class="text-xs font-bold text-slate-800 tracking-wide italic mt-0.5">
                {{ activeTerm || 'STEM 11-2' }}
              </p>
            </div>

            <div class="relative z-10 w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-[#eab308] bg-slate-200 overflow-hidden shadow-xl shrink-0 pointer-events-auto animate-avatar-pop">
              <img 
                v-if="auth?.user?.avatar" 
                :src="auth.user.avatar" 
                alt="Student Avatar" 
                class="w-full h-full object-cover" 
              />
              <div v-else class="w-full h-full bg-[#005506] text-white font-bold flex items-center justify-center text-2xl uppercase">
                {{ auth?.user?.name ? auth.user.name.charAt(0) : 'K' }}
              </div>
            </div>
          </div>

        </div>

        <!-- SECTION 1: ANNOUNCEMENTS (TOP FULL-WIDTH FRAME) -->
        <div class="space-y-3">
          <h3 class="text-xl sm:text-2xl font-bold text-[#005506] tracking-tight">
            Announcements
          </h3>

          <!-- Full-Width Card Container Wrapper -->
          <div class="relative rounded-3xl border border-[#005506]/20 bg-white/40 p-6 sm:p-8 overflow-hidden shadow-sm">
            
            <!-- Isolated Pattern Background 2 (15% Opacity) -->
            <div 
              class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
              :style="{ backgroundImage: `url(${dashboardBg2})` }"
            ></div>

            <!-- Scrollable Announcements List -->
            <div class="relative z-10 max-h-80 overflow-y-auto pr-2 space-y-4 custom-scrollbar">
              
              <div 
                v-if="!recentAnnouncements?.length" 
                class="space-y-4"
              >
                <!-- Announcement Item 1 -->
                <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 transition-all hover:shadow-md">
                  <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <h4 class="font-bold text-slate-800 text-base">
                      Midterm Examination Schedule Released
                    </h4>
                    <span class="text-xs font-semibold text-slate-400">Oct 24, 2026</span>
                  </div>
                  <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                    Please review your subject schedules in the calendar tab to prepare for upcoming exam dates.
                  </p>
                </div>

                <!-- Announcement Item 2 -->
                <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 transition-all hover:shadow-md">
                  <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <h4 class="font-bold text-slate-800 text-base">
                      Campus Science Fair Registration Open
                    </h4>
                    <span class="text-xs font-semibold text-slate-400">Oct 18, 2026</span>
                  </div>
                  <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                    STEM students are encouraged to submit project proposals to their respective physics advisors.
                  </p>
                </div>
              </div>

              <!-- Dynamic Items from Props -->
              <div 
                v-else
                v-for="item in recentAnnouncements" 
                :key="item.id"
                class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 transition-all hover:shadow-md"
              >
                <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                  <h4 class="font-bold text-slate-800 text-base">
                    {{ item.title }}
                  </h4>
                  <span class="text-xs font-semibold text-slate-400">
                    {{ item.published_human || item.published_at }}
                  </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                  {{ item.body_preview }}
                </p>
                <span v-if="item.subject" class="inline-block mt-2 text-[11px] font-bold text-[#005506] bg-emerald-50 px-2.5 py-0.5 rounded-full">
                  {{ item.subject }}
                </span>
              </div>

            </div>

          </div>
        </div>

        <!-- SECTION 2: ASSESSMENTS (BOTTOM 2-COLUMN GRID FRAME) -->
        <div class="space-y-3 pt-2">
          <h3 class="text-xl sm:text-2xl font-bold text-[#005506] tracking-tight">
            Assessments
          </h3>

          <!-- Full-Width Card Container Wrapper -->
          <div class="relative rounded-3xl border border-[#005506]/20 bg-white/40 p-6 sm:p-8 overflow-hidden shadow-sm">
            
            <!-- Isolated Pattern Background 1 (15% Opacity) -->
            <div 
              class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
              :style="{ backgroundImage: `url(${dashboardBg1})` }"
            ></div>

            <!-- Scrollable 2-Column Grid for Assessments -->
            <div class="relative z-10 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Assessment Card 1 -->
                <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 transition-all hover:shadow-md">
                  <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <h4 class="font-bold text-slate-800 text-base">
                      General Mathematics - Midterm Quiz
                    </h4>
                    <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-200">
                      Due Oct 24
                    </span>
                  </div>
                  <p class="text-xs text-slate-500 font-medium mt-1">
                    Functions, Rational Expressions, and Logarithmic Equations.
                  </p>
                </div>

                <!-- Assessment Card 2 -->
                <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 transition-all hover:shadow-md">
                  <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <h4 class="font-bold text-slate-800 text-base">
                      General Physics 1 - Lab Report
                    </h4>
                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">
                      Due Oct 28
                    </span>
                  </div>
                  <p class="text-xs text-slate-500 font-medium mt-1">
                    Vector analysis and kinematic charts PDF submission.
                  </p>
                </div>

                <!-- Assessment Card 3 -->
                <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 transition-all hover:shadow-md">
                  <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <h4 class="font-bold text-slate-800 text-base">
                      Oral Communication - Speech Video
                    </h4>
                    <span class="text-xs font-semibold text-slate-400">
                      Due Nov 02
                    </span>
                  </div>
                  <p class="text-xs text-slate-500 font-medium mt-1">
                    3-minute persuasive speech recording.
                  </p>
                </div>

                <!-- Assessment Card 4 -->
                <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 transition-all hover:shadow-md">
                  <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <h4 class="font-bold text-slate-800 text-base">
                      Earth Science - Reflection Paper
                    </h4>
                    <span class="text-xs font-semibold text-slate-400">
                      Due Nov 05
                    </span>
                  </div>
                  <p class="text-xs text-slate-500 font-medium mt-1">
                    Summary paper on plate tectonics and rock formations.
                  </p>
                </div>

                <!-- Assessment Card 5 -->
                <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 transition-all hover:shadow-md">
                  <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <h4 class="font-bold text-slate-800 text-base">
                      Empowerment Tech - Website Draft
                    </h4>
                    <span class="text-xs font-semibold text-slate-400">
                      Due Nov 10
                    </span>
                  </div>
                  <p class="text-xs text-slate-500 font-medium mt-1">
                    Initial wireframe and layout submission.
                  </p>
                </div>

                <!-- Assessment Card 6 -->
                <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 transition-all hover:shadow-md">
                  <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-[#005506] rounded-l-2xl"></div>
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <h4 class="font-bold text-slate-800 text-base">
                      PE 1 - Fitness Routine Log
                    </h4>
                    <span class="text-xs font-semibold text-slate-400">
                      Due Nov 12
                    </span>
                  </div>
                  <p class="text-xs text-slate-500 font-medium mt-1">
                    Weekly activity tracking and exercise logbook.
                  </p>
                </div>

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

import dashboardBg1 from '@/../assets/img/dashboardbackground.png'
import dashboardBg2 from '@/../assets/img/dashboardbackground2.png'

defineProps({
  auth: Object,
  classes: Object,
  stats: Object,
  upcomingDeadlines: Array,
  recentAnnouncements: Array,
  activeTerm: String,
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');

/* --- REVERSE ANIMATED FILL-STROKE TEXT EFFECT --- */
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

/* Custom Emerald Scrollbars */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(0, 85, 6, 0.05);
  border-radius: 8px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(0, 85, 6, 0.25);
  border-radius: 8px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(0, 85, 6, 0.5);
}

/* Banner Animations */
@keyframes avatarPop {
  0% {
    opacity: 0;
    transform: scale(0.2);
  }
  70% {
    transform: scale(1.1);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes pillSlideLeft {
  0% {
    opacity: 0;
    transform: translateX(120%);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes bannerAutoHide {
  0%, 80% {
    opacity: 1;
    transform: scale(1);
  }
  100% {
    opacity: 0;
    transform: scale(0.95);
    display: none;
    pointer-events: none;
  }
}

.animate-avatar-pop {
  animation: avatarPop 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}

.animate-pill-slide-left {
  animation: pillSlideLeft 0.85s cubic-bezier(0.22, 1, 0.36, 1) 0.3s both;
}

.animate-banner-auto-hide {
  animation: bannerAutoHide 0.8s cubic-bezier(0.4, 0, 0.2, 1) 5.8s forwards;
}
</style>