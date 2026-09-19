<template>
  <Head title="My Classes - Salawag LMS" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    
    <!-- Sticky Desktop Sidebar Navigation -->
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <!-- Main Workspace Canvas -->
    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-24">
        
        <!-- HEADER ROW -->
        <div class="space-y-1">
          <div class="flex items-center gap-2 font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase">
              <span class="text-[#005506]">STUDENT</span>
              <span class="text-transparent" style="-webkit-text-stroke: 1.5px #005506;">DASHBOARD</span>
          </div>
          
          <p class="text-xs sm:text-sm italic font-medium text-slate-600">
            "Organize your subjects, track assignments, and excel in every course."
            <span v-if="activeTerm" class="not-italic font-bold text-[#005506] ml-1">— {{ activeTerm }}</span>
          </p>

          <!-- Decorative Star Divider Line -->
          <div class="flex items-center gap-2 pt-1 max-w-md">
            <div class="h-[2px] w-full bg-[#005506]"></div>
            <span class="text-[#005506] text-xs">★</span>
          </div>
        </div>

        <!-- MAIN CLASSES SECTION CONTAINER -->
        <div class="relative rounded-3xl p-6 sm:p-8 overflow-hidden space-y-6">
          <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none z-0 opacity-15 rounded-3xl"
            :style="{ backgroundImage: `url(${dashboardBg1})` }"
          ></div>

          <!-- EMPTY STATE -->
          <div 
            v-if="!classes?.data?.length" 
            class="relative z-10 bg-white/80 backdrop-blur-sm rounded-2xl p-10 text-center border border-slate-200/60 shadow-sm text-slate-500 text-sm font-medium"
          >
            You are not enrolled in any classes yet.
          </div>

          <!-- GRID LIST -->
          <div 
            v-else 
            class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
          >
            <Link
              v-for="(klass, index) in classes.data"
              :key="klass.id"
              :href="route('student.classes.show', klass.id)"
              class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-sm hover:shadow-md border border-slate-200/60 flex flex-col justify-between h-60 transition-all duration-200 hover:-translate-y-1 cursor-pointer group"
            >
              <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 group-hover:bg-[#005506] text-[#005506] group-hover:text-white font-bold text-sm flex items-center justify-center transition-colors">
                  {{ String(index + 1).padStart(2, '0') }}
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-emerald-100/70 text-[#005506]">
                  {{ klass.subject_code }}
                </span>
              </div>

              <div>
                <h3 class="font-bold text-slate-800 text-lg leading-snug group-hover:text-[#005506] transition-colors line-clamp-2">
                  {{ klass.subject }}
                </h3>
                <div class="mt-4 text-xs text-slate-500 space-y-1 border-t border-slate-100 pt-3">
                  <p><span class="text-slate-400 font-medium">Section:</span> {{ klass.section }}</p>
                  <p><span class="text-slate-400 font-medium">Teacher:</span> {{ klass.teacher }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- PAGINATION -->
          <div v-if="classes?.last_page > 1" class="relative z-10 pt-4 flex gap-2 justify-center">
            <Link
              v-for="link in classes.links"
              :key="link.label"
              :href="link.url || '#'"
              class="px-3.5 py-1.5 rounded-xl text-xs font-semibold border transition-all duration-150"
              :class="link.active
                ? 'bg-[#005506] text-white border-[#005506] shadow-sm'
                : 'bg-white/80 text-slate-700 border-slate-200 hover:bg-white hover:border-slate-300'"
              v-html="link.label"
            />
          </div>
        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import dashboardBg1 from '@/../assets/img/dashboardbackground.png'

defineProps({
  classes: Object,
  activeTerm: String,
  filters: Object,
  filterOptions: Object,
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap');
</style>