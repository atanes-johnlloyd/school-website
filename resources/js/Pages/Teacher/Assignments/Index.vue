<template>

  <Head title="Assignment & Classwork Evaluation Manager - Salawag LMS" />

  <div class="min-h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300">

    <!-- Sticky Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-40 shrink-0 shadow-lg">
      <Sidebart />
    </div>

    <!-- Main Workspace Canvas -->
    <main :class="['flex-1 relative overflow-y-auto min-h-screen flex flex-col', `text-scale-${fontSizeMode}`]">

      <!-- Reusable Top Navigation Component -->
      <navbartop searchPlaceholder="Search assignments, rubric-grade tasks, student turn-ins.."
        @font-size-changed="(size) => fontSizeMode = size" />

      <!-- MAIN PAGE CONTENT -->
      <div class="relative z-10 px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2">

        <!-- ASSIGNMENTS HERO BANNER WITH ANIMATED OVERLAY -->
        <div
          class="relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[260px] flex flex-col justify-center">
          <!-- Background Image properly aligned and fitted -->
          <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2000&auto=format&fit=crop"
            alt="Assignments Background" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <!-- Animated Green Overlay -->
          <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

          <!-- Content Container -->
          <div class="relative z-10 p-6 sm:p-8 md:p-10 max-w-3xl space-y-4">

            <!-- Quarter Badge -->
            <div
              class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md text-white border border-white/20 text-xs font-semibold px-4 py-1.5 rounded-full shadow-sm">
              <span>📅</span> SY 2025–2026 • Quarter 2 Evaluation
            </div>

            <!-- Main Title & Description -->
            <div class="space-y-3">
              <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                Assignments & Classwork Evaluation Manager
              </h2>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Review artifacts, rubric-grade DepEd performance tasks, track turn-ins across Senior High STEM & TVL
                sections, and grant gamified Salawag Spark bonuses.
              </p>
            </div>

          </div>
        </div>

        <!-- 4-CARD ASSIGNMENT METRICS GRID -->
        <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

          <!-- 1. TOTAL ACTIVE TASKS -->
          <div
            class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="flex items-start justify-between">
                <span
                  class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">Total
                  Active Tasks</span>
                <div
                  class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                  </svg>
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-slate-900 dark:text-white">6</span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Published Tasks</span>
              </div>
            </div>

            <div
              class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center gap-2 text-xs font-bold text-[#005506] dark:text-[#86EFAC]">
              <span>📈</span>
              <span>2 Sections completing today</span>
            </div>
          </div>

          <!-- 2. AWAITING EVALUATION -->
          <div
            class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="flex items-start justify-between">
                <span
                  class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">Awaiting
                  Evaluation</span>
                <div
                  class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/30">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                  </svg>
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-orange-600 dark:text-orange-400">42</span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Submissions</span>
              </div>
            </div>

            <div
              class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-400">
              <span class="shrink-0">👥</span>
              <span class="truncate">STEM Rizal, Archimedes, TVL-Turing</span>
            </div>
          </div>

          <!-- 3. GRADED THIS WEEK -->
          <div
            class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="flex items-start justify-between">
                <span
                  class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">Graded
                  This Week</span>
                <div
                  class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-[#005506] dark:text-[#86EFAC]">89</span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Evaluated</span>
              </div>
            </div>

            <div
              class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center gap-2 text-xs font-bold text-[#005506] dark:text-[#86EFAC]">
              <span>⏱️</span>
              <span>Turnaround: 1.4d (Target ≤ 2.0d)</span>
            </div>
          </div>

          <!-- 4. MISSING / OVERDUE -->
          <div
            class="bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="flex items-start justify-between">
                <span
                  class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">Missing
                  / Overdue</span>
                <div
                  class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/30">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                  </svg>
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-rose-600 dark:text-rose-400">3</span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Learners</span>
              </div>
            </div>

            <div
              class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-xs font-bold">
              <span class="text-rose-600 dark:text-rose-400 flex items-center gap-1">
                <span>✉️</span> Dispatched
              </span>
              <a href="#" class="text-[#005506] dark:text-[#86EFAC] hover:underline">
                View 3 LRNs
              </a>
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
import navbartop from '@/Components/navbartop.vue'

// Font Scaling State Managed Locally via navbartop event
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