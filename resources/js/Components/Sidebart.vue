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
          >Academic Workspace</span>
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
            :href="route('teacher.dashboard')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('teacher.dashboard')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z" />
            </svg>
            <span class="text-sm tracking-wide">Dashboard</span>
          </Link>

          <!-- My Classes -->
          <Link 
            :href="route('teacher.classes.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('teacher.classes.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-sm tracking-wide">My Classes</span>
          </Link>

          <!-- Gradebook -->
          <Link 
            :href="route('teacher.gradebook.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('teacher.gradebook.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <div class="relative shrink-0">
              <svg class="w-5 h-5 text-[#F9C20C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
              </svg>
              <span v-if="!isRouteActive('teacher.gradebook.*')" class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-[#F9C20C] rounded-full"></span>
            </div>
            <span class="text-sm tracking-wide">Gradebook</span>
          </Link>

          <!-- Daily Attendance -->
          <Link 
            :href="route('teacher.attendance.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('teacher.attendance.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z M18 12l2 2 4-4" />
            </svg>
            <span class="text-sm tracking-wide">Daily Attendance</span>
          </Link>

          <!-- Assignments & Tasks -->
          <Link 
            :href="route('teacher.tasks.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('teacher.tasks.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span class="text-sm tracking-wide">Assignments &amp; Tasks</span>
          </Link>

          <!-- DepEd LR Resources -->
          <Link 
            :href="route('teacher.resources.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('teacher.resources.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span class="text-sm tracking-wide">DepEd LR Resources</span>
          </Link>

          <Link
              :href="route('teacher.announcements.index')"
              @click="$emit('close-sidebar')"
              :class="[
                'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
                isRouteActive('teacher.announcements.*')
                  ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                  : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
              ]"
            >
              <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
              </svg>
              <span class="text-sm tracking-wide">Announcements</span>
            </Link>

          <!-- Quiz Hub & Exam Bank -->
          <Link 
            :href="route('teacher.quizzes.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('teacher.quizzes.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm tracking-wide">Quiz Hub &amp; Exam Bank</span>
          </Link>

          <!-- Contributions -->
          <Link 
            :href="route('teacher.contributions.index')" 
            @click="$emit('close-sidebar')"
            :class="[
              'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold transition-all duration-200 ease-in-out',
              isRouteActive('teacher.contributions.*')
                ? 'bg-[#006907] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-md font-extrabold'
                : 'text-[#2C3E2D] dark:text-slate-300 hover:bg-[#006907]/10 dark:hover:bg-[#3F4F43] hover:text-[#006907] dark:hover:text-white'
            ]"
          >
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            <span class="text-sm tracking-wide">Contributions</span>
          </Link>
        </nav>
      </div>

      <!-- 3. PINNED BOTTOM FOOTER SECTION (NON-SCROLLABLE) -->
      <div class="relative z-10 p-4 pt-2 shrink-0 bg-[#F9F7F1]/95 dark:bg-[#232D26]/95 backdrop-blur-xs border-t border-[#006907]/10 dark:border-[#3F4F43]">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 shadow-sm border border-[#006907]/10 dark:border-[#3F4F43] transition-colors duration-300">
          <Link 
            :href="route('home')"
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