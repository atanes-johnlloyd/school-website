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
        'fixed md:sticky top-0 left-0 h-screen w-72 flex flex-col bg-[#F9F7F1] dark:bg-[#232D26] border-r border-gray-200 dark:border-[#3F4F43] overflow-hidden transition-all duration-300 z-50 shrink-0 font-[\'Inter\'] shadow-xl',
        isOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full md:translate-x-0'
      ]"
    >
      <!-- Background Image Layer -->
      <div
        class="absolute inset-0 z-0 pointer-events-none bg-cover bg-center bg-no-repeat opacity-60 dark:opacity-[0.15] transition-opacity duration-300"
        :style="{ backgroundImage: `url(${sidebarBg})` }"
      ></div>

      <!-- 1. PINNED TOP HEADER SECTION (NON-SCROLLABLE) -->
      <div class="relative z-10 p-5 pb-3 shrink-0 border-b border-[#006907]/10 dark:border-[#3F4F43] bg-[#F9F7F1]/90 dark:bg-[#232D26]/90 backdrop-blur-xs">
        
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
        <div class="flex flex-col items-center mb-4 mt-1">
          <!-- LOGO WITH WATER DROPLET RIPPLE ANIMATION -->
          <div class="relative flex items-center justify-center mb-3">
            <div class="ripple-wave absolute inset-0 rounded-full border border-[#006907]/40 dark:border-[#86EFAC]/50"></div>
            <div class="ripple-wave absolute inset-0 rounded-full border border-[#006907]/30 dark:border-[#86EFAC]/40" style="animation-delay: 1s;"></div>
            <div class="absolute inset-0 rounded-full bg-[#006907]/10 dark:bg-[#86EFAC]/15 blur-sm animate-pulse"></div>

            <div
              class="relative w-20 h-20 rounded-full bg-white dark:bg-[#2D3A31] border-2 border-[#006907] dark:border-[#86EFAC] flex items-center justify-center p-1 shadow-md transition-colors duration-300 z-10"
            >
              <img :src="schoolLogo" alt="Salawag SHS Logo" class="w-full h-full object-contain rounded-full" />
            </div>
          </div>

          <h1
            class="text-center text-[#006907] dark:text-[#86EFAC] font-extrabold text-xs leading-tight tracking-wide transition-colors duration-300 uppercase"
          >
            SALAWAG<br>SENIOR HIGH SCHOOL
          </h1>
        </div>

        <!-- Workspace & School Year Header -->
        <div class="flex items-center justify-between px-1 pt-1">
          <span
            class="text-[10px] font-extrabold text-[#006907] dark:text-slate-300 tracking-widest uppercase transition-colors"
          >Student Workspace</span>
          <span
            class="text-[10px] font-black text-[#006907] dark:text-[#232D26] bg-[#F9C20C] px-2.5 py-0.5 rounded-full shadow-xs"
          >S.Y. 25-26</span>
        </div>
      </div>

      <!-- 2. SCROLLABLE MIDDLE NAVIGATION AREA -->
      <div class="relative z-10 flex-1 overflow-y-auto px-4 py-4 no-scrollbar space-y-1.5 text-xs font-semibold">
        <nav class="flex flex-col space-y-1.5">
          <!-- Dashboard -->
          <Link 
            :href="route('student.dashboard')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('student.dashboard')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z" />
            </svg>
            <span class="text-sm tracking-wide">Dashboard</span>
          </Link>

          <!-- My Subjects -->
          <Link 
            :href="route('student.classes.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('student.classes.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
            <span class="text-sm tracking-wide">My Subjects</span>
          </Link>

          <!-- Assessments -->
          <Link 
            :href="route('student.assessments.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('student.assessments.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span class="text-sm tracking-wide">Assessments</span>
          </Link>

          <!-- Quiz Hub -->
          <Link 
            :href="route('student.quizhub.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('student.quizhub.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm tracking-wide">Quiz Hub</span>
          </Link>

          <!-- Grades -->
          <Link 
            :href="route('student.grades.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('student.grades.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
            <span class="text-sm tracking-wide">Grades</span>
          </Link>

          <!-- Schedule -->
          <Link 
            :href="route('student.schedule.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('student.schedule.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="text-sm tracking-wide">Schedule</span>
          </Link>

          <!-- Attendance -->
          <Link 
            :href="route('student.attendance.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('student.attendance.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm tracking-wide">Attendance</span>
          </Link>

          <!-- Announcements -->
          <Link 
            :href="route('student.announcements.feed')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('student.announcements.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
            </svg>
            <span class="text-sm tracking-wide">Announcements</span>
          </Link>

          <!-- Student Records -->
          <Link 
            :href="route('student.studentrecords.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('student.studentrecords.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="text-sm tracking-wide">Student Records</span>
          </Link>
        </nav>
      </div>

      <!-- 3. PINNED BOTTOM FOOTER SECTION (NON-SCROLLABLE) -->
      <div class="relative z-10 p-4 pt-2 shrink-0 bg-[#F9F7F1]/95 dark:bg-[#232D26]/95 backdrop-blur-xs border-t border-[#006907]/10 dark:border-[#3F4F43]">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 shadow-sm border border-[#006907]/10 dark:border-[#3F4F43] transition-colors duration-300">
          <Link 
            href="/"
            class="flex items-center gap-2.5 text-xs font-bold text-[#006907] dark:text-[#86EFAC] hover:text-green-800 dark:hover:text-white transition-colors mb-3 group"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Public Website</span>
          </Link>
          <div class="flex items-center justify-between text-xs font-semibold text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-[#3F4F43] pt-2.5 transition-colors">
            <span>School ID</span>
            <span class="text-gray-900 dark:text-white font-black tracking-wide">342512</span>
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