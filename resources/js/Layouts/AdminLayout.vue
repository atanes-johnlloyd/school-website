<template>
  <div class="min-h-screen flex bg-[#F9F7F1] dark:bg-[#232D26] font-['Inter'] text-slate-900 dark:text-slate-100">
    <!-- Admin Sidebar -->
    <AdminSidebar />

    <!-- Main Admin Workspace Canvas -->
    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto h-screen">
      <!-- Top Admin Navbar -->
      <AdminNavbar />

      <!-- Dynamic Page Content Slot -->
      <div class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto space-y-6">
        <slot />
      </div>
    </main>
    <Toast />
  </div>
</template>

<script setup>
import AdminSidebar from '@/Components/AdminSidebar.vue'
import AdminNavbar from '@/Components/AdminNavBar.vue'
import Toast from '@/Components/Toast.vue'
</script>