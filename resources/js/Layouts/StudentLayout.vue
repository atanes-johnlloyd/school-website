<template>
  <div class="min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-['Inter'] transition-colors duration-300">
    <!-- Sidebar is single-root, so these classes land on its outer <div>,
         which then becomes the flex child and the sticky element. -->
    <Sidebar
      class="print:hidden sticky top-0 h-screen shrink-0"
      :is-open="sidebarOpen"
      @close-sidebar="sidebarOpen = false"
    />
    <main class="flex-1 flex flex-col min-w-0">
      <!-- navbartop handles its own print:hidden internally (multi-root) -->
      <navbartop
        search-placeholder="Search subjects, assessments, grades..."
        @open-sidebar="sidebarOpen = true"
        @font-size-changed="onFontSizeChanged"
      />
      <div class="flex-1 p-6 md:p-8 w-full mx-auto space-y-6 print:p-0 print:m-0 print:max-w-none print:block">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import { useFlash } from '@/Composables/useFlash'

const page  = usePage()
const flash = useFlash()
const sidebarOpen = ref(false)

watch(
  () => page.props.flash,
  (f) => {
    if (! f) return
    if (f.success) flash.success(f.success)
    if (f.error)   flash.error(f.error)
    if (f.info)    flash.info(f.info)
    if (f.warning) flash.error(f.warning)
  },
  { deep: true }
)

const onFontSizeChanged = () => {}
</script>