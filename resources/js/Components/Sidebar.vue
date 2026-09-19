<template>
  <aside class="relative w-64 md:w-72 h-screen flex flex-col justify-between p-6 overflow-hidden font-['Inter'] shadow-2xl select-none shrink-0">
    
    <!-- Background Image Layer -->
    <img 
      :src="sidebarBg" 
      alt="Sidebar Background" 
      class="absolute inset-0 w-full h-full object-cover z-0 pointer-events-none"
    />

    <!-- Fixed Emerald Green Overlay (#005506 with 71% Opacity) -->
    <div 
      class="absolute inset-0 z-10 pointer-events-none" 
      style="background-color: rgba(0, 85, 6, 0.71);"
    ></div>

    <!-- TOP SECTION: Logo, School Name & Main Navigation -->
    <div class="relative z-20 flex flex-col space-y-8 overflow-y-auto pb-24 no-scrollbar">
      
      <!-- School Logo & Header -->
      <div class="flex flex-col items-center text-center pt-2">
        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-white/30 p-1 flex items-center justify-center bg-white/10 shadow-md mb-3 overflow-hidden">
          <img 
            :src="logoImg" 
            alt="Salawag SHS Logo" 
            class="w-full h-full object-contain scale-[1.8]"
          />
        </div>
        <h1 class="text-white font-bold text-xs sm:text-sm tracking-wider uppercase leading-snug drop-shadow-sm">
          SALAWAG<br />SENIOR HIGH SCHOOL
        </h1>
      </div>

      <!-- Main Navigation Links -->
      <nav class="space-y-2">
        
        <!-- Dashboard (Active State) -->
        <Link 
          :href="route('dashboard')"
          class="flex items-center gap-3.5 px-4 py-3 rounded-2xl bg-white/20 text-white font-semibold text-sm shadow-lg transition-all border border-white/20"
        >
          <svg class="w-5 h-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
          </svg>
          <span>Dashboard</span>
        </Link>

        <!-- Class -->
        <Link 
          :href="route('student.classes.index')"
          class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-white/90 hover:bg-white/10 hover:text-white font-medium text-sm transition-all"
        >
          <svg class="w-5 h-5 shrink-0 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Class</span>
        </Link>

        <!-- Assessments -->
        <a 
          href="#"
          class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-white/90 hover:bg-white/10 hover:text-white font-medium text-sm transition-all"
        >
          <svg class="w-5 h-5 shrink-0 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <span>Assessments</span>
        </a>

        <!-- Grades -->
        <a 
          href="#"
          class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-white/90 hover:bg-white/10 hover:text-white font-medium text-sm transition-all"
        >
          <span class="w-5 h-5 shrink-0 font-extrabold text-sm flex items-center justify-center">A+</span>
          <span>Grades</span>
        </a>

        <!-- Calendar -->
        <a 
          href="#"
          class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-white/90 hover:bg-white/10 hover:text-white font-medium text-sm transition-all"
        >
          <svg class="w-5 h-5 shrink-0 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span>Calendar</span>
        </a>

        <!-- Attendance -->
        <a 
          href="#"
          class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-white/90 hover:bg-white/10 hover:text-white font-medium text-sm transition-all"
        >
          <svg class="w-5 h-5 shrink-0 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
          <span>Attendance</span>
        </a>

      </nav>

    </div>

    <!-- FIXED BOTTOM SECTION: Always pinned with constant padding bottom -->
    <div class="absolute bottom-6 left-6 right-6 z-30">
      
      <!-- Backdrop listener to close menu on outside click -->
      <div 
        v-if="isMenuOpen" 
        @click="isMenuOpen = false" 
        class="fixed inset-0 z-10"
      ></div>

      <!-- Upward Dropping Options Menu Container -->
      <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 translate-y-2 scale-95"
        enter-to-class="opacity-100 translate-y-0 scale-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 translate-y-0 scale-100"
        leave-to-class="opacity-0 translate-y-2 scale-95"
      >
        <div 
          v-if="isMenuOpen"
          class="absolute bottom-full left-0 right-0 mb-3 z-20 bg-white/15 backdrop-blur-xl rounded-2xl p-2 border border-white/20 shadow-2xl space-y-1"
        >
          <!-- Notifications -->
          <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-white text-xs font-semibold hover:bg-white/15 rounded-xl transition-all">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span>Notifications</span>
          </a>

          <!-- Settings -->
          <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-white text-xs font-semibold hover:bg-white/15 rounded-xl transition-all">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Settings</span>
          </a>

          <!-- Profile Link -->
          <Link 
            :href="route('profile.edit')" 
            class="flex items-center gap-3 px-3 py-2.5 text-white text-xs font-semibold hover:bg-white/15 rounded-xl transition-all"
          >
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Profile</span>
          </Link>

          <div class="h-[1px] bg-white/15 my-1"></div>

          <!-- Log Out Button -->
          <Link 
            :href="route('logout')" 
            method="post" 
            as="button"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-[#8B261D]/80 hover:bg-[#8B261D] text-white font-bold text-xs tracking-wider uppercase transition-all"
          >
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            <span>Log Out</span>
          </Link>
        </div>
      </Transition>

      <!-- Main Profile Trigger Button -->
      <button 
        @click="isMenuOpen = !isMenuOpen"
        type="button"
        class="relative z-20 w-full flex items-center justify-between px-3.5 py-3 rounded-2xl bg-white/15 hover:bg-white/20 text-white border border-white/20 shadow-md transition-all active:scale-[0.99]"
      >
        <div class="flex items-center gap-3 overflow-hidden">
          <!-- First Name Initial Badge Placeholder -->
          <div class="w-8 h-8 rounded-full bg-white/20 border border-white/30 flex items-center justify-center font-bold text-xs uppercase text-white shrink-0 shadow-sm">
            {{ userInitial }}
          </div>
          <!-- Full Name -->
          <span class="font-semibold text-xs truncate text-left text-white/90">
            {{ userName }}
          </span>
        </div>

        <!-- Up Arrow Indicator -->
        <svg 
          class="w-4 h-4 text-white/70 transition-transform duration-200 shrink-0"
          :class="{ 'rotate-180': isMenuOpen }"
          fill="none" 
          stroke="currentColor" 
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
      </button>

    </div>

  </aside>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

import sidebarBg from '@/../assets/img/sidebar.png'
import logoImg from '@/../assets/img/logo_trans_big.png'

const page = usePage()

// Reactive state for the upward menu toggle
const isMenuOpen = ref(false)

// Access authenticated user name from global Inertia props
const userName = computed(() => {
  return page.props.auth?.user?.name || 'Student'
})

// Extract first letter for the badge initial placeholder
const userInitial = computed(() => {
  return userName.value.charAt(0).toUpperCase()
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

/* Hide scrollbar for Chrome, Safari and Opera */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
/* Hide scrollbar for IE, Edge and Firefox */
.no-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
</style>