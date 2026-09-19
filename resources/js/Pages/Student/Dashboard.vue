<template>
  <Head title="Student Dashboard - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <!-- Main Dashboard Content Container -->
      <div class="relative z-10 p-6 md:p-10 space-y-10 flex-1 pb-32 lg:pb-48">
        
        <!-- HEADER ROW WITH RELATIVE POSITIONING -->
        <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-6 overflow-x-clip py-2 px-1 min-h-[100px]">
          
          <!-- Left Header Title & Subtext -->
          <div class="space-y-1">
            <div class="flex items-center gap-2 font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase">
              <span class="text-[#005506]">STUDENT</span>
              <span class="text-transparent" style="-webkit-text-stroke: 1.5px #005506;">DASHBOARD</span>
            </div>
            
            <p class="text-xs sm:text-sm italic font-medium text-slate-600">
              "Your journey to knowledge starts with one click."
            </p>

            <!-- Decorative Star Divider Line -->
            <div class="flex items-center gap-2 pt-1 max-w-md">
              <div class="h-[2px] w-full bg-[#005506]"></div>
              <span class="text-[#005506] text-xs">★</span>
            </div>
          </div>

          <!-- Floating Student Profile Banner -->
          <div class="absolute right-0 top-0 z-20 flex items-center overflow-hidden py-2 pl-4 pointer-events-none animate-banner-auto-hide">
            <div class="bg-[#eab308] text-slate-900 px-8 sm:px-10 py-3.5 rounded-l-full shadow-lg flex flex-col justify-center text-right pr-14 -mr-10 pointer-events-auto animate-pill-slide-left">
              <h2 class="font-extrabold text-base sm:text-lg tracking-tight leading-tight whitespace-nowrap">
                Good day, {{ auth?.user?.name || 'Student' }}!
              </h2>
              <p class="text-xs font-bold text-slate-800 tracking-wide italic mt-0.5">
                {{ activeTerm || 'Current Term' }}
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
                {{ auth?.user?.name ? auth.user.name.charAt(0) : 'S' }}
              </div>
            </div>
          </div>

        </div>

        <!-- SECTION 1: SUBJECTS -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-1">
          
          <!-- Background Pattern Image ONLY behind the Subject Section -->
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg1})` }"
          ></div>

          <!-- Section Title & Custom Carousel Controls -->
          <div class="relative z-10 flex items-center justify-between">
            <h3 class="text-xl sm:text-2xl font-bold text-[#005506] tracking-tight">
              Subjects
            </h3>

            <!-- Scroll Navigation Buttons -->
            <div v-if="classes?.data?.length" class="flex items-center gap-2">
              <button 
                @click="scrollSubjects('left')"
                type="button"
                aria-label="Scroll left"
                class="w-9 h-9 rounded-full bg-white/90 hover:bg-[#005506] text-[#005506] hover:text-white shadow-sm border border-slate-200/80 flex items-center justify-center transition-all duration-200 active:scale-95 cursor-pointer backdrop-blur-sm"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
              </button>

              <button 
                @click="scrollSubjects('right')"
                type="button"
                aria-label="Scroll right"
                class="w-9 h-9 rounded-full bg-white/90 hover:bg-[#005506] text-[#005506] hover:text-white shadow-sm border border-slate-200/80 flex items-center justify-center transition-all duration-200 active:scale-95 cursor-pointer backdrop-blur-sm"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>

          <!-- EMPTY STATE -->
          <div 
            v-if="!classes?.data?.length" 
            class="relative z-10 bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200/60 shadow-sm text-slate-500 text-sm font-medium"
          >
            You are not enrolled in any classes yet.
          </div>

          <!-- LIST (Populated directly from classes.data) -->
          <div 
            v-else
            ref="subjectsContainer" 
            @wheel.prevent="handleWheelScroll"
            class="relative z-10 flex gap-5 overflow-x-auto no-scrollbar py-2 px-1"
          >
            <Link
              v-for="(klass, index) in classes.data"
              :key="klass.id"
              :href="route('student.classes.show', klass.id)"
              class="w-64 sm:w-72 md:w-80 shrink-0 bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-sm hover:shadow-md border border-slate-200/60 h-64 flex flex-col justify-between transition-all duration-200 hover:-translate-y-1 cursor-pointer group"
            >
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 group-hover:bg-[#005506] text-[#005506] group-hover:text-white font-bold text-sm flex items-center justify-center transition-colors">
                  {{ String(index + 1).padStart(2, '0') }}
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-emerald-100/70 text-[#005506]">
                  {{ klass.subject_code }}
                </span>
              </div>

              <div>
                <h4 class="font-bold text-slate-800 text-base leading-snug group-hover:text-[#005506] transition-colors line-clamp-2">
                  {{ klass.subject }}
                </h4>
                <div class="mt-3 text-xs text-slate-500 space-y-1 border-t border-slate-100 pt-3">
                  <p><span class="text-slate-400 font-medium">Section:</span> {{ klass.section }}</p>
                  <p><span class="text-slate-400 font-medium">Teacher:</span> {{ klass.teacher }}</p>
                </div>
              </div>
            </Link>
          </div>

        </div>

        <!-- SECTION 2: ANNOUNCEMENTS -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-6">
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg2})` }"
          ></div>

          <h3 class="relative z-10 text-xl sm:text-2xl font-bold text-[#005506] tracking-tight">
            Announcements
          </h3>

          <!-- DYNAMIC ANNOUNCEMENTS LIST -->
          <div class="relative z-10 space-y-4">
            <div 
              v-if="!recentAnnouncements?.length" 
              class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 text-center border border-slate-200/60 text-slate-500 text-sm font-medium"
            >
              No recent announcements.
            </div>

            <div 
              v-else
              v-for="item in recentAnnouncements" 
              :key="item.id"
              class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 p-5 pl-7 overflow-hidden transition-all duration-200 hover:shadow-md"
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

    </main>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
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

const subjectsContainer = ref(null)

// Smooth horizontal momentum scroll engine
let targetScrollLeft = 0
let animationFrameId = null

const updateSmoothScroll = () => {
  if (!subjectsContainer.value) return

  const el = subjectsContainer.value
  const maxScroll = el.scrollWidth - el.clientWidth

  targetScrollLeft = Math.max(0, Math.min(targetScrollLeft, maxScroll))

  const current = el.scrollLeft
  const diff = targetScrollLeft - current

  if (Math.abs(diff) > 0.5) {
    el.scrollLeft += diff * 0.08
    animationFrameId = requestAnimationFrame(updateSmoothScroll)
  } else {
    el.scrollLeft = targetScrollLeft
    animationFrameId = null
  }
}

const handleWheelScroll = (event) => {
  if (!subjectsContainer.value) return

  const el = subjectsContainer.value

  if (!animationFrameId) {
    targetScrollLeft = el.scrollLeft
  }

  const scrollSpeed = 1.6
  targetScrollLeft += event.deltaY * scrollSpeed

  if (!animationFrameId) {
    animationFrameId = requestAnimationFrame(updateSmoothScroll)
  }
}

const scrollSubjects = (direction) => {
  if (!subjectsContainer.value) return
  const el = subjectsContainer.value

  if (!animationFrameId) {
    targetScrollLeft = el.scrollLeft
  }

  const step = 320
  targetScrollLeft += direction === 'left' ? -step : step

  if (!animationFrameId) {
    animationFrameId = requestAnimationFrame(updateSmoothScroll)
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');

.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

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