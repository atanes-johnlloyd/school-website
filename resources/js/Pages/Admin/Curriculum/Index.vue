<template>
  <AdminLayout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10 font-['Inter']">

      <!-- Compact header -->
      <div class="relative overflow-hidden bg-[#004d08] text-white rounded-2xl p-6 md:p-8 shadow-lg border border-emerald-900/40">
        <div class="relative z-10">
          <div class="flex items-center gap-2 mb-2">
            <span class="bg-amber-400/20 text-amber-300 text-xs font-medium px-2.5 py-0.5 rounded-md border border-amber-400/30">
              ACADEMICS
            </span>
            <span class="text-emerald-200 text-xs font-normal">&bull; Curriculum</span>
          </div>
          <h1 class="font-['Anton'] text-3xl md:text-4xl tracking-wide uppercase">
            Curriculum <span class="text-amber-400">Setup</span>
          </h1>
          <p class="text-emerald-100/80 text-sm mt-1 max-w-xl font-normal">
            Manage tracks, strands, and subjects that form the academic structure.
          </p>
        </div>

        <div class="absolute -right-6 -bottom-8 opacity-10 text-9xl font-['Anton'] pointer-events-none select-none text-white">
          LEARN
        </div>
      </div>

      <!-- Tab container -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden">

        <!-- Tab headers -->
        <div class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 px-2 sm:px-4">
          <nav class="flex gap-1">
            <button
              v-for="tab in tabs"
              :key="tab.key"
              @click="activeTab = tab.key"
              :class="[
                'px-4 py-3 text-xs font-medium uppercase tracking-wider transition-colors border-b-2 -mb-px cursor-pointer',
                activeTab === tab.key
                  ? 'text-[#004d08] dark:text-[#86EFAC] border-[#004d08] dark:border-[#86EFAC]'
                  : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600',
              ]"
            >
              {{ tab.label }}
              <span v-if="tab.count !== undefined" class="ml-1.5 text-[10px] font-normal opacity-60">({{ tab.count }})</span>
            </button>
          </nav>
        </div>

        <!-- Tab body -->
        <div>
          <TracksTab
            v-if="activeTab === 'tracks'"
            :tracks="tracks"
            @changed="refresh" />

          <StrandsTab
            v-else-if="activeTab === 'strands'"
            :strands="strands"
            :tracks="tracks"
            @changed="refresh" />

          <SubjectsTab
            v-else-if="activeTab === 'subjects'"
            :subjects="subjects"
            :strands="strands"
            :prerequisites="prerequisites"
            @changed="refresh" />
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
  tracks:        { type: Array, default: () => [] },
  strands:       { type: Array, default: () => [] },
  subjects:      { type: Array, default: () => [] },
  prerequisites: { type: Array, default: () => [] },
})

const activeTab = ref('tracks')

const tabs = computed(() => [
  { key: 'tracks',   label: 'Tracks',   count: props.tracks.length },
  { key: 'strands',  label: 'Strands',  count: props.strands.length },
  { key: 'subjects', label: 'Subjects', count: props.subjects.length },
])

const refresh = () => router.reload({ only: ['tracks', 'strands', 'subjects', 'prerequisites'] })
</script>