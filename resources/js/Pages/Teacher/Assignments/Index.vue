<template>
  <Head :title="`Assignments - ${classroom.subject} - Salawag LMS`" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-24">
        
        <!-- HEADER ROW -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-1 max-w-2xl">
            <div class="flex items-center gap-2 font-['Anton'] text-2xl sm:text-3xl md:text-4xl tracking-wide uppercase leading-tight">
              <span class="text-[#005506]">Assignments</span>
            </div>
            
            <p class="text-xs sm:text-sm font-semibold text-slate-600">
              Subject: <span class="text-slate-800 font-bold">{{ classroom.subject }}</span>
              <span class="text-slate-300 mx-2">|</span> 
              Section: <span class="text-slate-800 font-bold">{{ classroom.section }}</span>
            </p>

            <!-- Accent Star Line -->
            <div class="flex items-center gap-2 pt-1 max-w-sm">
              <div class="h-[2px] w-full bg-[#005506]"></div>
              <span class="text-[#005506] text-xs">★</span>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center gap-3 shrink-0">
            <Link 
              :href="route('teacher.classes.show', classroom.id)"
              class="inline-flex items-center gap-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition-all duration-200"
            >
              ← Back to Class
            </Link>
            <Link 
              :href="route('teacher.classes.assignments.create', classroom.id)"
              class="inline-flex items-center gap-2 bg-[#005506] hover:bg-[#003d04] text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-sm hover:shadow transition-all duration-200 active:scale-95"
            >
              + Create Assignment
            </Link>
          </div>
        </div>

        <div class="relative z-10 flex border-b border-slate-200/80 gap-2 sm:gap-6">
            <Link
              :href="route('teacher.classes.show', classroom.id)"
              class="pb-3 text-xs sm:text-sm font-semibold text-slate-500 hover:text-[#005506] border-b-2 border-transparent transition-colors"
            >
              Students
            </Link>

            <button class="pb-3 text-xs sm:text-sm font-bold border-b-2 border-[#005506] text-[#005506] transition-colors">
              Assignments
            </button>

            <button disabled class="pb-3 text-xs sm:text-sm font-semibold text-slate-300 cursor-not-allowed">
              Lessons (soon)
            </button>

            <button disabled class="pb-3 text-xs sm:text-sm font-semibold text-slate-300 cursor-not-allowed">
              Grades (soon)
            </button>
          </div>

        <!-- MAIN SECTION CANVAS -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-6">
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg})` }"
          ></div>

          <!-- EMPTY STATE -->
          <div 
            v-if="!assignments.length" 
            class="relative z-10 bg-white/90 backdrop-blur-sm rounded-2xl p-12 text-center text-slate-500 border border-slate-200/60 shadow-sm space-y-2"
          >
            <p class="font-bold text-slate-700 text-base sm:text-lg">No assignments created yet</p>
            <p class="text-xs sm:text-sm text-slate-500">Get started by creating your first assignment for this class section.</p>
          </div>

          <!-- ASSIGNMENTS DATA TABLE -->
          <div v-else class="relative z-10 bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-emerald-900/5 text-slate-600 uppercase text-[11px] font-extrabold tracking-wider border-b border-slate-200/60">
                    <th class="px-6 py-3.5">Assignment Title</th>
                    <th class="px-6 py-3.5">Due Date</th>
                    <th class="px-6 py-3.5">Max Points</th>
                    <th class="px-6 py-3.5">Submissions</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                  <tr 
                    v-for="a in assignments" 
                    :key="a.id" 
                    class="hover:bg-emerald-50/40 transition-colors"
                  >
                    <td class="px-6 py-4 font-bold text-slate-800">
                      <div class="flex items-center gap-2">
                        <span>{{ a.title }}</span>
                        <span v-if="a.has_file" class="text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded font-mono" title="Has attached file">📎 File</span>
                      </div>
                    </td>

                    <td class="px-6 py-4 font-semibold" :class="isOverdue(a.due_at) ? 'text-amber-600' : 'text-slate-600'">
                      {{ a.due_at ? new Date(a.due_at).toLocaleString() : 'No due date' }}
                    </td>

                    <td class="px-6 py-4 font-medium text-slate-600">
                      {{ a.points }} pts
                    </td>

                    <td class="px-6 py-4 font-bold text-slate-700">
                      {{ a.submissions_count }}
                    </td>

                    <td class="px-6 py-4">
                      <span 
                        class="text-xs font-bold px-2.5 py-1 rounded-full border"
                        :class="a.is_published 
                          ? 'bg-emerald-100 text-emerald-800 border-emerald-200' 
                          : 'bg-slate-100 text-slate-600 border-slate-200'"
                      >
                        {{ a.is_published ? 'Published' : 'Draft' }}
                      </span>
                    </td>

                    <td class="px-6 py-4 text-right">
                      <Link 
                        :href="route('teacher.assignments.show', a.id)"
                        class="bg-[#005506] hover:bg-[#003d04] text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm inline-block transition-transform active:scale-95"
                      >
                        View & Grade →
                      </Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import dashboardBg from '@/../assets/img/dashboardbackground.png'

defineProps({
  classroom: Object,
  assignments: Array,
})

function isOverdue(due) {
  return due && new Date(due) < new Date()
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');
</style>