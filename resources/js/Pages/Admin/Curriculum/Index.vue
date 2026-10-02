<template>
  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10 font-['Inter']">

      <!-- Hero Banner -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <!-- Background Image Layer (Curriculum / Academic Books Theme) -->
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=1600&auto=format&fit=crop'"
          alt="Curriculum Setup Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <!-- Animated Dark Green Overlay -->
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <!-- Ambient Light Glow Highlights -->
        <div
          class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0">
        </div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0">
        </div>

        <!-- Content Container -->
        <div class="relative z-10 w-full max-w-4xl space-y-2.5 sm:space-y-3.5">
          <!-- Top Capsule Badges -->
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <span
              class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
              <span>📖</span> ACADEMICS
            </span>

            <span
              class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              Curriculum
            </span>
          </div>

          <!-- Title & Subtitle -->
          <div class="space-y-1 sm:space-y-1.5">
            <h1
              class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
              <span>CURRICULUM</span>
              <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">SETUP</span>
            </h1>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              Manage tracks, strands, and subjects that form the academic structure.
            </p>
          </div>
        </div>
      </div>

      <!-- Tab container -->
      <div
        class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden">

        <!-- Tab headers -->
        <div class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 px-2 sm:px-4">
          <nav class="flex gap-1">
            <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key" :class="[
              'px-4 py-3 text-xs font-medium uppercase tracking-wider transition-colors border-b-2 -mb-px cursor-pointer',
              activeTab === tab.key
                ? 'text-[#004d08] dark:text-[#86EFAC] border-[#004d08] dark:border-[#86EFAC]'
                : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600',
            ]">
              {{ tab.label }}
              <span v-if="tab.count !== undefined" class="ml-1.5 text-[10px] font-normal opacity-60">({{ tab.count
                }})</span>
            </button>
          </nav>
        </div>

        <!-- Tab body -->
        <div>
          <TracksTab v-if="activeTab === 'tracks'" :tracks="tracks" @changed="refresh" />

          <StrandsTab v-else-if="activeTab === 'strands'" :strands="strands" :tracks="tracks" @changed="refresh" />

          <SubjectsTab v-else-if="activeTab === 'subjects'" :subjects="subjects" :strands="strands"
            :prerequisites="prerequisites" @changed="refresh" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import TracksTab from './TracksTab.vue'
import StrandsTab from './StrandsTab.vue'
import SubjectsTab from './SubjectsTab.vue'

const props = defineProps({
  tracks: { type: Array, default: () => [] },
  strands: { type: Array, default: () => [] },
  subjects: { type: Array, default: () => [] },
  prerequisites: { type: Array, default: () => [] },
})

const activeTab = ref('tracks')

const tabs = computed(() => [
  { key: 'tracks', label: 'Tracks', count: props.tracks.length },
  { key: 'strands', label: 'Strands', count: props.strands.length },
  { key: 'subjects', label: 'Subjects', count: props.subjects.length },
])

const refresh = () => router.reload({ only: ['tracks', 'strands', 'subjects', 'prerequisites'] })
</script>