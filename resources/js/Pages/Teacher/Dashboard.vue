<template>

  <Head title="Teacher Dashboard - Salawag LMS" />

  <div class="min-h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300">

    <!-- Sticky Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-40 shrink-0 shadow-lg">
      <Sidebart />
    </div>

    <!-- Main Workspace Canvas -->
    <main :class="['flex-1 relative overflow-y-auto min-h-screen flex flex-col', `text-scale-${fontSizeMode}`]">

      <!-- TOP NAVIGATION BAR -->
      <header
        class="flex flex-col-reverse lg:flex-row lg:items-center justify-between px-6 md:px-10 py-5 bg-[#F9F7F1] dark:bg-[#232D26] sticky top-0 z-30 gap-4 lg:gap-8 transition-colors duration-300">

        <!-- Search Bar -->
        <div class="relative w-full max-w-2xl">
          <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 dark:text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </span>
          <input type="text" placeholder="Search learners (LRN), strand records, advisories.."
            class="w-full pl-12 pr-4 py-3 rounded-full bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#006907] text-sm text-gray-700 dark:text-slate-200 shadow-sm transition-colors" />
        </div>

        <!-- Right Controls & Profile -->
        <div class="flex items-center justify-end gap-5 md:gap-6 shrink-0 relative">

          <!-- Font Size Toggle -->
          <div
            class="hidden md:flex items-center bg-[#EAE7DF] dark:bg-[#3F4F43] rounded-full p-1 gap-1 transition-colors">
            <button @click="changeFontSize('sm')"
              :class="fontSizeMode === 'sm' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'"
              class="px-3 py-1 rounded-full text-xs font-bold transition-colors">A-</button>
            <button @click="changeFontSize('base')"
              :class="fontSizeMode === 'base' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'"
              class="px-3 py-1 rounded-full text-xs font-black transition-colors">A</button>
            <button @click="changeFontSize('lg')"
              :class="fontSizeMode === 'lg' ? 'bg-white dark:bg-[#2D3A31] text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'"
              class="px-3 py-1 rounded-full text-xs font-bold transition-colors">A+</button>
          </div>

          <!-- Theme Toggle -->
          <button @click="toggleDarkMode"
            class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
            <svg v-if="!isDark" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 20V4C16.4183 4 20 7.58172 20 12C20 16.4183 16.4183 20 12 20Z" />
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
              </path>
            </svg>
          </button>

          <!-- Notifications Bell & Dropdown -->
          <div class="relative">
            <button @click="toggleNotifications"
              class="relative text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
              </svg>
              <span
                class="absolute top-0 right-0.5 block w-2 h-2 rounded-full bg-red-600 ring-2 ring-[#F9F7F1] dark:ring-[#232D26]"></span>
            </button>

            <div v-if="showNotifications"
              class="absolute right-0 mt-3 w-72 bg-white dark:bg-[#2D3A31] rounded-xl shadow-xl border border-gray-100 dark:border-[#3F4F43] overflow-hidden z-50">
              <div class="p-3 border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50 dark:bg-[#232D26]">
                <h4 class="text-sm font-bold text-gray-800 dark:text-white">Notifications</h4>
              </div>
              <div class="p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">No new notifications.</p>
              </div>
            </div>
          </div>

          <!-- Profile & Dropdown -->
          <div class="relative flex items-center gap-3 pl-2 sm:pl-4 border-l border-gray-300 dark:border-[#3F4F43]">
            <button @click="toggleProfile" class="flex items-center gap-3 focus:outline-none">
              <img src="https://ui-avatars.com/api/?name=Maria+Santos&background=random" alt="Maria Santos"
                class="w-10 h-10 md:w-11 md:h-11 rounded-full border-2 border-[#F9C20C] object-cover cursor-pointer" />
              <div class="hidden sm:block text-left">
                <p class="text-sm font-bold text-gray-900 dark:text-slate-100 leading-none">Maria Santos, LPT</p>
                <p class="text-[11px] font-bold text-[#006907] dark:text-[#86EFAC] mt-1 tracking-wide">SHS Faculty •
                  STEM</p>
              </div>
            </button>

            <div v-if="showProfile"
              class="absolute right-0 top-12 mt-2 w-48 bg-white dark:bg-[#2D3A31] rounded-xl shadow-xl border border-gray-100 dark:border-[#3F4F43] overflow-hidden z-50">
              <ul class="py-1">
                <li>
                  <a href="#"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-[#3F4F43]">My
                    Profile</a>
                </li>
                <li>
                  <a href="#"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-[#3F4F43]">Account
                    Settings</a>
                </li>
                <li class="border-t border-gray-100 dark:border-[#3F4F43]">
                  <button @click="handleLogout"
                    class="w-full text-left block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-[#3F4F43]">
                    Sign Out
                  </button>
                </li>
              </ul>
            </div>
          </div>

        </div>
      </header>

      <!-- Overlay to close dropdowns -->
      <div v-if="showNotifications || showProfile" @click="closeDropdowns" class="fixed inset-0 z-20"></div>

      <!-- DASHBOARD CONTENT -->
      <div class="relative z-10 px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2">

        <!-- HERO BANNER -->
        <div
          class="relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[240px] flex flex-col justify-center">
          <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2000&auto=format&fit=crop"
            alt="School Dashboard Background" class="absolute inset-0 w-full h-full object-cover z-0" />
          <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

          <div class="relative z-10 p-8 md:p-10 flex flex-col justify-center">
            <div class="flex flex-wrap items-center gap-3 mb-5">
              <span
                class="bg-[#F9C20C] text-[#2C3E2D] text-[11px] md:text-xs font-black uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-sm">
                DEPED REGION IV-A • DASMARIÑAS
              </span>
              <span
                class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[11px] md:text-xs font-semibold px-3.5 py-1.5 rounded-full shadow-sm">
                SY 2024-2025 • Quarter 3
              </span>
            </div>

            <h2 class="text-3xl md:text-5xl font-bold text-white mb-3 tracking-tight">
              Mabuhay, Engr. Benjamin Bautista!
            </h2>

            <p class="text-white/90 text-sm md:text-base max-w-3xl leading-relaxed font-medium">
              Master Teacher II • SHS STEM Department Coordinator. Your active learning laboratory is prepped, and
              advisory records for Grade 12 STEM-Rizal are live.
            </p>
          </div>
        </div>

        <!-- STATS GRID (3 CARDS) -->
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-5">

          <!-- 1. AWAITING EVALUATION -->
          <div
            class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between">
            <div class="space-y-1">
              <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Awaiting
                Evaluation</span>
              <div class="flex items-baseline gap-2 mt-1">
                <span class="text-3xl sm:text-4xl font-black text-slate-800 dark:text-white">42</span>
                <span class="text-xs font-bold text-orange-600 dark:text-orange-400">PS-4 & Proposals</span>
              </div>
              <p class="text-xs text-slate-500 dark:text-slate-400 pt-1">Submissions across 3 sections</p>
            </div>
            <div
              class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 shadow-sm border border-amber-100 dark:border-amber-900/30">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                </path>
              </svg>
            </div>
          </div>

          <!-- 2. ADVISORY & CONDUCT REPORTS -->
          <div
            class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between">
            <div class="space-y-1">
              <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Advisory &
                Conduct</span>
              <div class="flex items-baseline gap-2 mt-1">
                <span class="text-3xl sm:text-4xl font-black text-slate-800 dark:text-white">3</span>
                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">Behavior logs</span>
              </div>
              <p class="text-xs text-slate-500 dark:text-slate-400 pt-1">Pending student record updates</p>
            </div>
            <div
              class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 shadow-sm border border-indigo-100 dark:border-indigo-900/30">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                </path>
              </svg>
            </div>
          </div>

          <!-- 3. EXAM & QUIZ BANK -->
          <div
            class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between">
            <div class="space-y-1">
              <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Exam &
                Quiz Bank</span>
              <div class="flex items-baseline gap-2 mt-1">
                <span class="text-3xl sm:text-4xl font-black text-slate-800 dark:text-white">8</span>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Active test papers</span>
              </div>
              <p class="text-xs text-slate-500 dark:text-slate-400 pt-1">Stem-Rizal & General Physics</p>
            </div>
            <div
              class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 shadow-sm border border-emerald-100 dark:border-emerald-900/30">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                </path>
              </svg>
            </div>
          </div>

        </div>

        <!-- TODAY'S TEACHING SCHEDULE SECTION -->
        <div
          class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 sm:p-7 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-6">

          <!-- Header Row -->
          <div class="flex items-center justify-between border-b border-slate-100 dark:border-[#3F4F43] pb-4">
            <h3 class="font-extrabold text-slate-800 dark:text-white text-base sm:text-lg flex items-center gap-2.5">
              <span class="text-[#005506] dark:text-[#86EFAC]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </span>
              Today's Teaching Schedule
            </h3>
            <span
              class="text-xs font-bold bg-[#EAE7DF] dark:bg-[#3F4F43] text-slate-700 dark:text-slate-200 px-3 py-1.5 rounded-full">
              Thursday, Week 8
            </span>
          </div>

          <!-- Schedule Items List -->
          <div class="space-y-4">

            <!-- Item 1: In Session -->
            <div
              class="p-4 sm:p-5 rounded-2xl bg-[#EAF3EC] dark:bg-[#232D26] border border-emerald-200/80 dark:border-emerald-900/40 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 transition-all">
              <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div
                  class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] font-extrabold text-xs px-3.5 py-2.5 rounded-xl flex items-center gap-1.5 shadow-sm shrink-0">
                  <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
                  IN SESSION
                </div>
                <div>
                  <h4 class="font-black text-slate-900 dark:text-white text-sm sm:text-base">
                    General Physics 2 <span
                      class="font-normal text-slate-600 dark:text-slate-300">(Electromagnetism)</span>
                  </h4>
                  <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mt-0.5">
                    07:30 – 09:00 AM • STEM 12–Rizal • Physics Lab 204
                  </p>
                </div>
              </div>
              <button
                class="bg-[#004d05] dark:bg-[#86EFAC] hover:bg-[#003d04] dark:hover:bg-[#4ade80] text-white dark:text-[#232D26] text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 shrink-0 self-end sm:self-center transition-transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                  </path>
                </svg>
                Take Attendance
              </button>
            </div>

            <!-- Item 2: Upcoming Class -->
            <div
              class="p-4 sm:p-5 rounded-2xl bg-[#F6F2EA] dark:bg-[#232D26]/60 border border-slate-200/60 dark:border-[#3F4F43] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
              <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div
                  class="bg-[#EAE7DF] dark:bg-[#3F4F43] text-slate-800 dark:text-slate-200 font-extrabold text-xs px-3.5 py-2.5 rounded-xl text-center shrink-0">
                  10:00 – 11:30<br>AM
                </div>
                <div>
                  <h4 class="font-black text-slate-900 dark:text-white text-sm sm:text-base">
                    Robotics & Microcontrollers
                  </h4>
                  <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mt-0.5">
                    TechPro 11–Turing • STEM Innovation Hub
                  </p>
                </div>
              </div>
              <div
                class="bg-white dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] px-3.5 py-2 rounded-xl text-right shrink-0 self-end sm:self-center">
                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block">Starts in</span>
                <span class="text-xs font-black text-slate-800 dark:text-white">1h 20m</span>
              </div>
            </div>

            <!-- Item 3: Consultation Window -->
            <div
              class="p-4 sm:p-5 rounded-2xl bg-[#FDF8EC] dark:bg-[#232D26]/60 border border-amber-200/60 dark:border-[#3F4F43] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
              <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div
                  class="bg-[#FEF3C7] dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 font-extrabold text-xs px-3.5 py-2.5 rounded-xl text-center shrink-0">
                  03:00 –<br>04:30 PM
                </div>
                <div>
                  <h4 class="font-black text-slate-900 dark:text-white text-sm sm:text-base">
                    Parent–Learner Consultation Window
                  </h4>
                  <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mt-0.5">
                    Faculty Room A & DepEd Google Meet Hall
                  </p>
                </div>
              </div>
              <div
                class="bg-[#EAF3EC] dark:bg-[#3F4F43] border border-emerald-200/60 dark:border-emerald-900/40 px-3.5 py-2 rounded-xl text-right shrink-0 self-end sm:self-center">
                <span class="text-xs font-black text-[#005506] dark:text-[#86EFAC] block">2 Appointments Booked</span>
              </div>
            </div>

          </div>

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import Sidebart from '@/Components/Sidebart.vue'

defineProps({
  stats: Object,
  activeTerm: String,
  today: String,
  needs_grading: Array,
  today_schedule: Array,
})

const handleLogout = () => {
  router.post(route('logout'), {}, {
    onSuccess: () => {
      router.visit('/') // Directs back to Student/Home.vue (route '/')
    }
  })
}

const showNotifications = ref(false)
const showProfile = ref(false)
const toggleNotifications = () => { showNotifications.value = !showNotifications.value; showProfile.value = false; }
const toggleProfile = () => { showProfile.value = !showProfile.value; showNotifications.value = false; }
const closeDropdowns = () => { showNotifications.value = false; showProfile.value = false; }

// Dark Mode Toggle Logic
const isDark = ref(false)
const toggleDarkMode = () => {
  isDark.value = !isDark.value
  if (isDark.value) {
    document.documentElement.classList.add('dark')
    localStorage.setItem('theme', 'dark')
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.setItem('theme', 'light')
  }
}

// Font Scaling Logic
const fontSizeMode = ref('base')
const changeFontSize = (size) => { fontSizeMode.value = size }

onMounted(() => {
  if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    isDark.value = true
    document.documentElement.classList.add('dark')
  }
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800;900&display=swap');

/* Breathing opacity animation for the green background layer */
@keyframes pulse-opacity {

  0%,
  100% {
    opacity: 0.88;
  }

  50% {
    opacity: 0.65;
  }
}

.animate-overlay {
  animation: pulse-opacity 6s infinite ease-in-out;
}

/* TEXT SCALING OVERRIDES */
.text-scale-sm :deep(.text-xs) {
  font-size: 0.65rem !important;
  line-height: 0.85rem !important;
}

.text-scale-sm :deep(.text-sm) {
  font-size: 0.75rem !important;
  line-height: 1rem !important;
}

.text-scale-sm :deep(.text-base) {
  font-size: 0.875rem !important;
  line-height: 1.25rem !important;
}

.text-scale-sm :deep(.text-lg) {
  font-size: 1rem !important;
  line-height: 1.5rem !important;
}

.text-scale-sm :deep(.text-xl) {
  font-size: 1.125rem !important;
  line-height: 1.75rem !important;
}

.text-scale-sm :deep(.text-3xl) {
  font-size: 1.5rem !important;
  line-height: 2rem !important;
}

.text-scale-sm :deep(.sm\:text-4xl) {
  font-size: 1.875rem !important;
  line-height: 2.25rem !important;
}

.text-scale-sm :deep(.md\:text-5xl) {
  font-size: 2.25rem !important;
  line-height: 1 !important;
}

.text-scale-lg :deep(.text-xs) {
  font-size: 0.875rem !important;
  line-height: 1.25rem !important;
}

.text-scale-lg :deep(.text-sm) {
  font-size: 1rem !important;
  line-height: 1.5rem !important;
}

.text-scale-lg :deep(.text-base) {
  font-size: 1.125rem !important;
  line-height: 1.75rem !important;
}

.text-scale-lg :deep(.text-lg) {
  font-size: 1.25rem !important;
  line-height: 1.75rem !important;
}

.text-scale-lg :deep(.text-xl) {
  font-size: 1.5rem !important;
  line-height: 2rem !important;
}

.text-scale-lg :deep(.text-3xl) {
  font-size: 2.25rem !important;
  line-height: 2.5rem !important;
}

.text-scale-lg :deep(.sm\:text-4xl) {
  font-size: 2.75rem !important;
  line-height: 1 !important;
}

.text-scale-lg :deep(.md\:text-5xl) {
  font-size: 3.5rem !important;
  line-height: 1 !important;
}
</style>