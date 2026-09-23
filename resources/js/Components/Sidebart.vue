<template>
  <!-- Sidebar Container -->
  <aside 
    class="relative w-72 h-screen flex flex-col bg-[#F9F7F1] dark:bg-[#232D26] border-r border-gray-200 dark:border-[#3F4F43] overflow-hidden transition-colors duration-300"
  >
    <!-- Background Image Layer -->
    <div 
      class="absolute inset-0 z-0 pointer-events-none bg-cover bg-center bg-no-repeat opacity-60 dark:opacity-[0.15] transition-opacity duration-300"
      :style="{ backgroundImage: `url(${sidebarBg})` }"
    ></div>

    <!-- Sidebar Content -->
    <div class="relative z-10 flex flex-col h-full overflow-y-auto px-5 py-6 no-scrollbar">
      
      <!-- Logo & School Name Section -->
      <div class="flex flex-col items-center mb-8 mt-2">
        <div class="w-24 h-24 rounded-full bg-white dark:bg-[#2D3A31] border-2 border-[#006907] dark:border-[#86EFAC] flex items-center justify-center p-1 shadow-sm mb-3 transition-colors duration-300">
          <img :src="schoolLogo" alt="Salawag SHS Logo" class="w-full h-full object-contain rounded-full" />
        </div>
        <h1 class="text-center text-[#006907] dark:text-[#86EFAC] font-extrabold text-base leading-tight tracking-wide transition-colors duration-300">
          SALAWAG<br>SENIOR HIGH SCHOOL
        </h1>
      </div>

      <!-- Workspace & School Year Header -->
      <div class="flex items-center justify-between mb-4 px-1">
        <span class="text-xs font-bold text-[#006907] dark:text-slate-300 tracking-wider uppercase transition-colors">Academic Workspace</span>
        <span class="text-[10px] font-bold text-[#006907] dark:text-[#232D26] bg-[#F9C20C] px-2.5 py-0.5 rounded-full shadow-sm">S.Y. 25-26</span>
      </div>

      <!-- Navigation Menu -->
      <nav class="flex flex-col space-y-1.5 flex-1">
        <Link 
          v-for="item in menuItems" 
          :key="item.name"
          :href="item.route ? route(item.route) : '#'"
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
            <!-- Yellow notification dot (e.g., for Gradebook) -->
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
      <div class="mt-8 bg-white dark:bg-[#2D3A31] rounded-2xl p-4 shadow-sm border border-[#006907]/10 dark:border-[#3F4F43] relative z-10 transition-colors duration-300">
        <Link :href="route('dashboard')" class="flex items-center gap-2 text-sm font-bold text-[#006907] dark:text-[#86EFAC] hover:text-green-800 dark:hover:text-white transition-colors mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back to Public Website
        </Link>
        <div class="flex items-center justify-between text-xs font-semibold text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-[#3F4F43] pt-3 transition-colors">
          <span>School ID</span>
          <span class="text-gray-900 dark:text-white font-bold">342512</span>
        </div>
      </div>

    </div>
  </aside>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

import sidebarBg from '@/../assets/img/newsidebarbg.png'
import schoolLogo from '@/../assets/img/logo_trans.png'

// Helper function to check if the current window route matches the item route
const isRouteActive = (routeName) => {
  if (!routeName) return false
  try {
    return route().current(routeName) || route().current(routeName + '.*')
  } catch (e) {
    return false
  }
}

const IconDashboard = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/></svg>` }
const IconMyClasses = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>` }
const IconGradebook = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>` }
const IconAttendance = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z M18 12l2 2 4-4"/></svg>` }
const IconAssignments = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>` }
const IconDepEd = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>` }
const IconQuiz = { template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>` }

// Menu items mapped to their corresponding Laravel route names
const menuItems = [
  { name: 'Dashboard', icon: IconDashboard, route: 'teacher.dashboard' },
  { name: 'My Classes', icon: IconMyClasses, route: 'teacher.classes.index' },
  { name: 'Gradebook', icon: IconGradebook, route: null, iconColor: 'text-[#F9C20C]', hasNotification: true },
  { name: 'Daily Attendance', icon: IconAttendance, route: null },
  { name: 'Assignments & Tasks', icon: IconAssignments, route: null },
  { name: 'DepEd LR Resources', icon: IconDepEd, route: null },
  { name: 'Quiz Hub & Exam Bank', icon: IconQuiz, route: null },
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
</style>