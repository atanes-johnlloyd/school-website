<template>
  <div class="min-h-screen flex ...">
    <AdminSidebar />
    <main class="flex-1 flex flex-col ...">
      <AdminNavbar />
      <div class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto space-y-6">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import { watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AdminSidebar from '@/Components/AdminSidebar.vue'
import AdminNavbar from '@/Components/AdminNavBar.vue'
import { useFlash } from '@/Composables/useFlash'

const page  = usePage()
const flash = useFlash()

// Route Inertia session flash into the useFlash store
watch(
  () => page.props.flash,
  (f) => {
    if (! f) return
    if (f.success) flash.success(f.success)
    if (f.error)   flash.error(f.error)
    if (f.info)    flash.info(f.info)
    if (f.warning) flash.error(f.warning)   // no warning style in useFlash, use error
  },
  { deep: true }
)
</script>