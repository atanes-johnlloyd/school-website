<template>
  <div class="h-screen w-full flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] relative transition-colors duration-300 overflow-hidden">
    
    <!-- Shared Mobile-Responsive Sidebar -->
    <Sidebart 
      :isOpen="isSidebarOpen" 
      @close-sidebar="isSidebarOpen = false" 
    />

    <!-- Main Workspace Canvas -->
    <main :class="['flex-1 min-w-0 relative overflow-y-auto h-screen flex flex-col', `text-scale-${fontSizeMode}`]">
      
      <!-- Shared Top Navigation Component with Hamburger Trigger -->
      <navbartop 
        :searchPlaceholder="searchPlaceholder"
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size" 
      />

      <!-- Page Content Slot -->
      <slot />

    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Sidebart from '@/Components/Sidebart.vue'
import navbartop from '@/Components/navbartop.vue'

defineProps({
  searchPlaceholder: {
    type: String,
    default: 'Search...'
  }
})

// Centralized layout state for font scaling and mobile drawer open/close
const fontSizeMode = ref('base')
const isSidebarOpen = ref(false)
</script>