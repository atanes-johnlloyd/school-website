<template>
  <Head title="Teacher Dashboard - Salawag LMS" />

  <div class="min-h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300">

    <!-- Sticky Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-40 shrink-0 shadow-lg">
      <Sidebart />
    </div>

    <!-- Main Workspace Canvas -->
    <main :class="['flex-1 relative overflow-y-auto min-h-screen flex flex-col', `text-scale-${fontSizeMode}`]">

      <!-- Top Navigation Component -->
      <Navbartop 
        searchPlaceholder="Search learners (LRN), strand records, advisories.." 
        @font-size-changed="(size) => fontSizeMode = size" 
      />

      <!-- DASHBOARD CONTENT -->
      <div class="relative z-10 px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2">

        <!-- HERO BANNER -->
        <div
          class="relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[240px] flex flex-col justify-center anim-fade-down">
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
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between" style="animation-delay: 100ms;">
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
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between" style="animation-delay: 200ms;">
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
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between" style="animation-delay: 300ms;">
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
          class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl p-6 sm:p-7 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-6" style="animation-delay: 400ms;">

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
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import Sidebart from '@/Components/Sidebart.vue'
import Navbartop from '@/Components/navbartop.vue'

defineProps({
  stats: Object,
  activeTerm: String,
  today: String,
  needs_grading: Array,
  today_schedule: Array,
})

const fontSizeMode = ref('base')
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

/* Page load and refresh entrance animations */
@keyframes slideUpFade {
  from {
    opacity: 0;
    transform: translateY(25px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.anim-fade-down {
  animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.anim-slide-up {
  opacity: 0;
  animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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