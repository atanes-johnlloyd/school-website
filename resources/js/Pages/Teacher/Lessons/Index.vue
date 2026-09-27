<template>

  <Head title="DepEd LR Resources & Lesson Log Hub - Salawag LMS" />

  <div class="min-h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300">

    <!-- Sticky Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-40 shrink-0 shadow-lg">
      <Sidebart />
    </div>

    <!-- Main Workspace Canvas -->
    <main :class="['flex-1 relative overflow-y-auto min-h-screen flex flex-col', `text-scale-${fontSizeMode}`]">

      <!-- Reusable Top Navigation Component -->
      <navbartop searchPlaceholder="Search lessons, DLL logs, PhET simulations, module decks..."
        @font-size-changed="(size) => fontSizeMode = size" />

      <!-- MAIN PAGE CONTENT -->
      <div class="relative z-10 px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2">

        <!-- CLEANED LESSONS HERO BANNER -->
        <div v-observe
          class="anim-fade-down relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[240px] flex flex-col justify-center p-6 sm:p-8 md:p-10">
          
          <!-- Background Image properly aligned -->
          <img :src="heroImage"
            alt="Lessons Background" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <!-- Animated Green Overlay -->
          <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

          <!-- Content Container -->
          <div class="relative z-10 max-w-3xl space-y-3">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
              Instructional Resources & Daily Lesson Log Hub
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              Curated DepEd-aligned DLL/DLP repositories, Interactive PhET STEM simulations, and verified open learning packages for General Physics 2 & Senior High Faculty.
            </p>
          </div>

        </div>

        <!-- 4 METRICS OVERVIEW CARDS (MOVED OUTSIDE HERO BANNER) -->
        <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

          <!-- 1. UPLOADED MODULES -->
          <div v-observe style="animation-delay: 100ms;"
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="flex items-start justify-between">
                <span
                  class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">
                  Uploaded Modules
                </span>
                <div
                  class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/30">
                  📒
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-slate-900 dark:text-white">48</span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Modules & Decks</span>
              </div>
            </div>

            <div
              class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center gap-2 text-xs font-bold text-[#005506] dark:text-[#86EFAC]">
              <span>📚</span>
              <span>Available across 6 subjects</span>
            </div>
          </div>

          <!-- 2. DEPED DO 42 AUDIT -->
          <div v-observe style="animation-delay: 200ms;"
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="flex items-start justify-between">
                <span
                  class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">
                  DepEd DO 42
                </span>
                <div
                  class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                  ✔
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-[#005506] dark:text-[#86EFAC]">100%</span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Q3 DLL Audit</span>
              </div>
            </div>

            <div
              class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center gap-2 text-xs font-bold text-[#005506] dark:text-[#86EFAC]">
              <span>🛡️</span>
              <span>Fully DepEd Compliant</span>
            </div>
          </div>

          <!-- 3. PHET SIM LABS -->
          <div v-observe style="animation-delay: 300ms;"
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="flex items-start justify-between">
                <span
                  class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">
                  PhET Interactive
                </span>
                <div
                  class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/30">
                  ⚙️
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-amber-800 dark:text-amber-400">12</span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Active Sim Labs</span>
              </div>
            </div>

            <div
              class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-400">
              <span class="shrink-0">⚛️</span>
              <span class="truncate">STEM & Physics Embeds</span>
            </div>
          </div>

          <!-- 4. STUDENT DOWNLOADS -->
          <div v-observe style="animation-delay: 400ms;"
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <div class="flex items-start justify-between">
                <span
                  class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[120px]">
                  Student Downloads
                </span>
                <div
                  class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                  📈
                </div>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-slate-900 dark:text-white">842</span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Weekly Accesses</span>
              </div>
            </div>

            <div
              class="pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex items-center gap-2 text-xs font-bold text-[#005506] dark:text-[#86EFAC]">
              <span>📥</span>
              <span>High Engagement Rate</span>
            </div>
          </div>

        </div>

        <!-- RESOURCE MATERIALS TABULATOR / LIST -->
        <div v-observe style="animation-delay: 500ms;"
          class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-3xl p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">
          
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-[#3F4F43] pb-4">
            <div>
              <h3 class="text-lg font-black text-slate-900 dark:text-white">Instructional Modules & Materials</h3>
              <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Manage uploaded lesson logs, presentation slides, and lab guides.</p>
            </div>

            <div class="flex items-center gap-2">
              <input type="text" placeholder="Filter materials..." class="bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] rounded-xl text-xs px-3.5 py-2 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#006907]" />
            </div>
          </div>

          <!-- LIST TABLE STRUCTURE -->
          <div class="space-y-3">
            <div v-for="item in resourceList" :key="item.id"
              class="bg-[#F9F7F1] dark:bg-[#232D26] p-4 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-[#006907] transition-all">
              
              <div class="space-y-1">
                <div class="flex items-center gap-2">
                  <span class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-0.5 rounded-md border border-emerald-200">
                    {{ item.subject }}
                  </span>
                  <span class="text-xs font-bold text-slate-400">• {{ item.type }}</span>
                </div>
                <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ item.title }}</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ item.description }}</p>
              </div>

              <div class="flex items-center gap-3 shrink-0">
                <button class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] px-4 py-2 rounded-xl text-xs font-black hover:bg-[#004105] transition-all">
                  Open Material ➔
                </button>
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
import heroImage from '../../../../assets/img/local/lessons-hero.jpg'
import { Head } from '@inertiajs/vue3'
import Sidebart from '@/Components/Sidebart.vue'
import navbartop from '@/Components/navbartop.vue'

// Font Scaling State Managed Locally via navbartop event
const fontSizeMode = ref('base')

// Mock Resource List Data
const resourceList = ref([
  {
    id: 1,
    subject: 'STEM • Gen Physics 2',
    type: 'Daily Lesson Log (DLL)',
    title: 'Module 4: Electromagnetic Induction & Faraday\'s Law',
    description: 'Complete DepEd DO 42 compliant lesson log with interactive PhET simulation embeds.'
  },
  {
    id: 2,
    subject: 'STEM • Gen Physics 2',
    type: 'Presentation Deck',
    title: 'DC Circuits & Kirchhoff\'s Rules Slide Set',
    description: 'High-contrast slides covering multi-loop network derivation and resistor tolerance calculation.'
  },
  {
    id: 3,
    subject: 'TVL-ICT • Robotics',
    type: 'Lab Assembly Guide',
    title: 'Arduino Sensor Array Breadboard Schematic',
    description: 'Hardware wiring diagrams and code template for ultrasonic rangefinder integration.'
  }
])

// Custom Directive for Smooth Entrance Animations
const vObserve = {
  mounted(el) {
    el.classList.add('not-visible')
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) {
        el.classList.add('is-animated')
      } else {
        el.classList.remove('is-animated')
      }
    }, { threshold: 0.1 })
    observer.observe(el)
  }
}
</script>

<style scoped>

/* Breathing opacity animation for the green background layer */
@keyframes pulse-opacity {
  0%, 100% { opacity: 0.88; }
  50% { opacity: 0.65; }
}

.animate-overlay {
  animation: pulse-opacity 6s infinite ease-in-out;
}

/* Replayable Entrance Animations */
@keyframes slideUpFade {
  from {
    opacity: 0;
    transform: translateY(24px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-16px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.not-visible {
  opacity: 0;
}

.is-animated.anim-fade-down {
  animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.is-animated.anim-slide-up {
  animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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
</style>