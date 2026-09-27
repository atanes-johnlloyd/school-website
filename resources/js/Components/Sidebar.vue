<template>
  <aside
    class="relative w-64 md:w-72 h-screen flex flex-col justify-between p-5 overflow-hidden font-['Inter'] select-none shrink-0 border-r border-emerald-900/10 shadow-sm"
    style="background-color: #E8FFE8;"
  >
    <!-- Watermark Background Image (5% Opacity) -->
    <div 
      class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat pointer-events-none opacity-[0.05]"
      :style="{ backgroundImage: 'url(' + bgImage + ')' }"
    ></div>

    <!-- Z-Index Wrapper for Content Above Background -->
    <div class="relative z-10 flex flex-col h-full justify-between">
      
      <!-- TOP SECTION: Logo, Headers & Navigation Links -->
      <div class="flex flex-col space-y-6 overflow-y-auto no-scrollbar">

        <!-- 1. School Logo & Name -->
        <div class="flex flex-col items-center text-center pt-2">
          <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full border-2 border-[#005506]/30 p-1 flex items-center justify-center bg-white/40 shadow-sm mb-2 overflow-hidden">
            <img :src="logoImg" alt="Salawag SHS Logo" class="w-full h-full object-contain scale-[2]" />
          </div>
          <h1 class="text-[#004d08] font-black text-xs sm:text-sm tracking-wide uppercase leading-tight">
            SALAWAG<br />SENIOR HIGH SCHOOL
          </h1>
        </div>

        <!-- 2. Academic Workspace & S.Y. Label Row -->
        <div class="flex items-center justify-between text-[11px] font-bold tracking-tight text-[#004d08]/80 px-1 pt-1 border-t border-[#005506]/15">
          <span class="uppercase leading-tight">ACADEMIC<br />WORKSPACE</span>
          <span class="text-right">S.Y. 2024–2025</span>
        </div>

        <!-- 3. Navigation Links List -->
        <nav class="space-y-1.5 pt-1">

          <!-- Dashboard -->
          <Link
            :href="route('student.dashboard')"
            :class="
              $page.component === 'Dashboard' || $page.component === 'Student/Dashboard' || isRouteActive('student.dashboard')
                ? 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all bg-[#004d08] text-white shadow-md'
                : 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all text-[#004d08] hover:bg-[#005506]/10'
            "
          >
            <!-- Layout / Dashboard Icon -->
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <span>Dashboard</span>
          </Link>

          <!-- Subjects -->
          <Link
            :href="route('student.classes.index')"
            :class="
              $page.component.startsWith('Student/Classes') || isRouteActive('student.classes.*')
                ? 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all bg-[#004d08] text-white shadow-md'
                : 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all text-[#004d08] hover:bg-[#005506]/10'
            "
          >
            <!-- Book / Class Icon -->
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
            <span>Subjects</span>
          </Link>

          <Link
            :href="route('student.studentrecords.index')"
            :class="
              $page.component.startsWith('Student/StudentRecords') || isRouteActive('student.studentrecords.*')
                ? 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all bg-[#004d08] text-white shadow-md'
                : 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all text-[#004d08] hover:bg-[#005506]/10'
            "
          >
            <!-- Academic / Student Records Folder Icon -->
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Student Records</span>
          </Link>

          <!-- Grades -->
          <Link
            :href="route('student.grades.index')"
            :class="
              $page.component.startsWith('Student/Grades') || isRouteActive('student.grades.*')
                ? 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all bg-[#004d08] text-white shadow-md'
                : 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all text-[#004d08] hover:bg-[#005506]/10'
            "
          >
            <!-- Star Icon -->
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
            <span>Grades</span>
          </Link>

          <!-- Schedule -->
          <Link
            :href="route('student.schedule.index')"
            :class="
              $page.component.startsWith('Student/Schedule') || isRouteActive('student.schedule.*')
                ? 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all bg-[#004d08] text-white shadow-md'
                : 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all text-[#004d08] hover:bg-[#005506]/10'
            "
          >
            <!-- Calendar Icon -->
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Schedule</span>
          </Link>

          <!-- Assessments -->
          <Link
            :href="route('student.assessments.index')"
            :class="
              $page.component.startsWith('Student/Assessments') || isRouteActive('student.assessments.*')
                ? 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all bg-[#004d08] text-white shadow-md'
                : 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all text-[#004d08] hover:bg-[#005506]/10'
            "
          >
            <!-- Clipboard List Icon -->
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span>Assessments</span>
          </Link>

          <!-- Quiz Hub -->
          <Link
            :href="route('student.quizhub.index')"
            :class="
              $page.component.startsWith('Student/QuizHub') || isRouteActive('student.quizhub.*')
                ? 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all bg-[#004d08] text-white shadow-md'
                : 'flex items-center gap-3.5 px-4 py-3 rounded-2xl font-semibold text-sm transition-all text-[#004d08] hover:bg-[#005506]/10'
            "
          >
            <!-- Question Box / Quiz Icon -->
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Quiz Hub</span>
          </Link>

        </nav>

      </div>

      <!-- BOTTOM SECTION: Back to Public Website & School ID Box -->
      <div class="pt-4">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#005506]/10 space-y-2">
          
          <Link
            href="/"
            class="flex items-center gap-2 text-[#004d08] font-bold text-xs hover:underline transition-all"
          >
            <!-- Left Arrow -->
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Back to Public Website</span>
          </Link>

          <div class="flex items-center justify-between text-[11px] font-semibold text-slate-500 pt-1">
            <span>School ID</span>
            <span class="font-bold text-slate-800">342512</span>
          </div>

        </div>
      </div>

    </div>
  </aside>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

import bgImage from '@/../assets/img/dashboardbackground.png'
import logoImg from '@/../assets/img/logo_trans_big.png'

const isRouteActive = (routeName) => {
  try {
    return typeof route === 'function' && route().current(routeName)
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
</style>