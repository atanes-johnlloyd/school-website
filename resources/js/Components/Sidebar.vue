<template>
  <div>
    <!-- Mobile Backdrop Overlay -->
    <div 
      v-if="isOpen" 
      @click="$emit('close-sidebar')" 
      class="fixed inset-0 bg-black/60 backdrop-blur-xs z-40 md:hidden transition-opacity duration-300"
    ></div>

    <!-- Sidebar Container (Responsive Slide-over Drawer for Mobile & Sticky Column for Desktop) -->
    <aside
      :class="[
        'fixed md:sticky top-0 left-0 h-screen w-72 flex flex-col bg-[#F9F7F1] dark:bg-[#232D26] border-r border-gray-200 dark:border-[#3F4F43] overflow-hidden transition-all duration-300 z-50',
        isOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full md:translate-x-0'
      ]"
    >
      <!-- Background Image Layer -->
      <div
        class="absolute inset-0 z-0 pointer-events-none bg-cover bg-center bg-no-repeat opacity-60 dark:opacity-[0.15] transition-opacity duration-300"
        :style="{ backgroundImage: `url(${sidebarBg})` }"
      ></div>

      <!-- Sidebar Content Area -->
      <div class="relative z-10 flex flex-col h-full overflow-y-auto px-5 py-6 no-scrollbar">

        <!-- Mobile Drawer Close Button -->
        <button 
          @click="$emit('close-sidebar')" 
          class="md:hidden absolute top-4 right-4 p-2 rounded-xl bg-white/80 dark:bg-[#2D3A31]/80 text-gray-700 dark:text-slate-200 border border-gray-200 dark:border-[#3F4F43] transition-colors"
          aria-label="Close Navigation"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <!-- Logo & School Name Section -->
        <div class="flex flex-col items-center mb-8 mt-2">
          
          <!-- LOGO WITH WATER DROPLET RIPPLE ANIMATION -->
          <div class="relative flex items-center justify-center mb-3">
            <!-- Ripple Wave 1 -->
            <div class="ripple-wave absolute inset-0 rounded-full border border-[#006907]/40 dark:border-[#86EFAC]/50"></div>
            
            <!-- Ripple Wave 2 (Staggered Delay) -->
            <div class="ripple-wave absolute inset-0 rounded-full border border-[#006907]/30 dark:border-[#86EFAC]/40" style="animation-delay: 1s;"></div>

            <!-- Center Ambient Glow -->
            <div class="absolute inset-0 rounded-full bg-[#006907]/10 dark:bg-[#86EFAC]/15 blur-sm animate-pulse"></div>

            <!-- Logo Container -->
            <div
              class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-white dark:bg-[#2D3A31] border-2 border-[#006907] dark:border-[#86EFAC] flex items-center justify-center p-1 shadow-md transition-colors duration-300 z-10"
            >
              <img :src="schoolLogo" alt="Salawag SHS Logo" class="w-full h-full object-contain rounded-full" />
            </div>
          </div>

          <h1
            class="text-center text-[#006907] dark:text-[#86EFAC] font-extrabold text-sm sm:text-base leading-tight tracking-wide transition-colors duration-300"
          >
            SALAWAG<br>SENIOR HIGH SCHOOL
          </h1>
        </div>

        <!-- Workspace & School Year Header -->
        <div class="flex items-center justify-between mb-4 px-1">
          <span
            class="text-xs font-bold text-[#006907] dark:text-slate-300 tracking-wider uppercase transition-colors"
          >Student Workspace</span>
          <span
            class="text-[10px] font-bold text-[#006907] dark:text-[#232D26] bg-[#F9C20C] px-2.5 py-0.5 rounded-full shadow-sm"
          >S.Y. 25-26</span>
        </div>

        <!-- Navigation Menu Links -->
        <nav class="flex flex-col space-y-1.5 flex-1">
          <Link 
            v-for="item in menuItems" 
            :key="item.name" 
            :href="item.route ? route(item.route) : '#'" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 ease-in-out',
              isRouteActive(item.route)
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <!-- Icon -->
            <div class="relative flex-shrink-0 w-5 h-5 flex items-center justify-center">
              <component 
                :is="item.icon" 
                class="w-5 h-5"
                :class="isRouteActive(item.route) ? 'text-current' : (item.iconColor || 'text-[#006907] dark:text-slate-400')" 
              />
              <!-- Notification Indicator Dot -->
              <span 
                v-if="item.hasNotification && !isRouteActive(item.route)"
                class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-[#F9C20C] rounded-full"
              ></span>
            </div>
            <!-- Label -->
            <span class="text-sm tracking-wide">{{ item.name }}</span>
          </Link>
        </nav>

        <!-- Footer Card -->
        <div
          class="mt-8 bg-white dark:bg-[#2D3A31] rounded-2xl p-4 shadow-sm border border-[#006907]/10 dark:border-[#3F4F43] relative z-10 transition-colors duration-300"
        >
          <Link 
            href="/"
            class="flex items-center gap-2 text-xs sm:text-sm font-bold text-[#006907] dark:text-[#86EFAC] hover:text-green-800 dark:hover:text-white transition-colors mb-4"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Public Website
          </Link>
          <div
            class="flex items-center justify-between text-xs font-semibold text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-[#3F4F43] pt-3 transition-colors"
          >
            <span>School ID</span>
            <span class="text-gray-900 dark:text-white font-bold">342512</span>
          </div>
        </div>

      </div>
    </aside>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

import sidebarBg from '@/../assets/img/newsidebarbg.png'
import schoolLogo from '@/../assets/img/logo_trans.png'

defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
})

defineEmits(['close-sidebar'])

const isRouteActive = (routeName) => {
  if (!routeName) return false
  try {
    return route().current(routeName) || route().current(routeName + '.*')
  } catch (e) {
    return false
  }
}

const IconDashboard = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/></svg>` }
const IconSubjects = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>` }
const IconRecords = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>` }
const IconGrades = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>` }
const IconSchedule = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>` }
const IconAssessments = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>` }
const IconQuiz = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>` }

const IconAnnouncements = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>` }
const IconAttendance = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>` }
const IconLessons = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>` }
const IconMessages = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>` }
const IconProfile = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>` }

const menuItems = [
  { name: 'Dashboard',       icon: IconDashboard,     route: 'student.dashboard' },
  { name: 'My Subjects',     icon: IconSubjects,      route: 'student.classes.index' },
  { name: 'Assessments',     icon: IconAssessments,   route: 'student.assessments.index' },
  { name: 'Quiz Hub',        icon: IconQuiz,          route: 'student.quizhub.index' },
  { name: 'Grades',          icon: IconGrades,        route: 'student.grades.index' },
  { name: 'Schedule',        icon: IconSchedule,      route: 'student.schedule.index' },
  { name: 'Attendance',      icon: IconAttendance,    route: 'student.attendance.index' },
  { name: 'Announcements',   icon: IconAnnouncements, route: 'student.announcements.feed' },
  { name: 'Student Records', icon: IconRecords,       route: 'student.studentrecords.index' },
]

</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

@keyframes waterDripRipple {
  0% {
    transform: scale(0.95);
    opacity: 0.8;
  }
  50% {
    opacity: 0.4;
  }
  100% {
    transform: scale(1.35);
    opacity: 0;
  }
}

.ripple-wave {
  animation: waterDripRipple 2.5s cubic-bezier(0.25, 0.8, 0.25, 1) infinite;
}
</style>