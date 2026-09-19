<template>
  <Head :title="`${classroom.subject} - Salawag LMS`" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-[#Inter] relative">
    <!-- Sticky Main Sidebar -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      <div class="relative z-10 p-6 md:p-10 space-y-6 flex-1 pb-24">
        
        <!-- CLASS HEADER -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-1">
            <h1 class="font-['Anton'] text-2xl sm:text-3xl md:text-4xl tracking-wide uppercase text-[#005506]">
              {{ classroom.subject }}
            </h1>
            <p class="text-xs sm:text-sm font-semibold text-slate-600">
              Section: <span class="text-slate-800 font-bold">{{ classroom.section }}</span> 
              <span v-if="classroom.subject_code"> | {{ classroom.subject_code }}</span>
            </p>
          </div>

          <Link 
            :href="route('teacher.classes.index')" 
            class="inline-flex items-center gap-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-4 py-2 rounded-xl text-xs font-bold shadow-xs transition-all w-fit"
          >
            ← All Classes
          </Link>
        </div>

        <!-- PERSISTENT TAB NAVIGATION BAR -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-2 border border-slate-200/60 shadow-xs flex items-center gap-2 overflow-x-auto">
          <Link
            :href="route('teacher.classes.show', classroom.id)"
            class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0"
            :class="isCurrentRoute('teacher.classes.show') ? 'bg-[#005506] text-white shadow-xs' : 'text-slate-600 hover:bg-emerald-50 hover:text-[#005506]'"
          >
            Stream / Wall
          </Link>

          <Link
            :href="route('teacher.classes.assignments.index', classroom.id)"
            class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0"
            :class="isCurrentRoute('teacher.classes.assignments.*') || isCurrentRoute('teacher.assignments.*') ? 'bg-[#005506] text-white shadow-xs' : 'text-slate-600 hover:bg-emerald-50 hover:text-[#005506]'"
          >
            Assignments
          </Link>

          <Link
            v-if="route().has('teacher.classes.students')"
            :href="route('teacher.classes.students', classroom.id)"
            class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0"
            :class="isCurrentRoute('teacher.classes.students') ? 'bg-[#005506] text-white shadow-xs' : 'text-slate-600 hover:bg-emerald-50 hover:text-[#005506]'"
          >
            Students
          </Link>
        </div>

        <!-- PAGE CONTENT INJECTED HERE -->
        <slot />

      </div>
    </main>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

const props = defineProps({
  classroom: Object,
})

function isCurrentRoute(pattern) {
  return route().current(pattern)
}
</script>