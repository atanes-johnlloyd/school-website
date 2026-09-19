<template>
  <Head :title="`Materials - ${classroom.subject} - Salawag LMS`" />

  <div class="min-h-screen flex bg-[#e8f5e9] font-['Inter'] relative">
    <div class="sticky top-0 h-screen z-30 shrink-0">
      <Sidebar />
    </div>

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between">
      <div class="relative z-10 p-6 md:p-10 space-y-8 flex-1 pb-24">
        
        <!-- HEADER -->
        <div class="space-y-1">
          <h1 class="font-['Anton'] text-2xl sm:text-3xl md:text-4xl tracking-wide uppercase text-[#005506]">
            Learning Materials
          </h1>
          <p class="text-xs sm:text-sm font-semibold text-slate-600">
            Class: <span class="text-slate-800 font-bold">{{ classroom.subject }} - {{ classroom.section }}</span>
          </p>
        </div>

        <!-- TABS -->
        <div class="relative z-10 flex border-b border-slate-200/80 gap-2 sm:gap-6 overflow-x-auto">
          <Link :href="route('student.classes.show', classroom.id)" class="pb-3 text-xs sm:text-sm font-semibold text-slate-500 hover:text-[#005506]">Overview</Link>
          <Link :href="route('student.classes.assignments.index', classroom.id)" class="pb-3 text-xs sm:text-sm font-semibold text-slate-500 hover:text-[#005506]">Assignments</Link>
          <Link :href="route('student.classes.materials.index', classroom.id)" class="pb-3 text-xs sm:text-sm font-bold border-b-2 border-[#005506] text-[#005506]">Materials</Link>
        </div>

        <!-- GRID DISPLAY -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          <div v-if="!materials.length" class="col-span-full bg-white/90 rounded-2xl p-12 text-center text-slate-500 border border-slate-200/60 shadow-sm">
            No learning materials published by your teacher yet.
          </div>

          <div 
            v-for="material in materials" 
            :key="material.id"
            class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 border border-slate-200/60 shadow-sm flex flex-col justify-between space-y-4 hover:shadow-md transition-shadow"
          >
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-[10px] uppercase font-extrabold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800">
                  {{ material.type === 'link' ? 'Web Link' : 'Document' }}
                </span>
                <span class="text-[11px] font-semibold text-slate-400">
                  {{ new Date(material.created_at).toLocaleDateString() }}
                </span>
              </div>

              <h2 class="font-bold text-slate-800 text-base leading-snug">{{ material.title }}</h2>
              <p class="text-xs text-slate-600 line-clamp-3">{{ material.description || 'No additional instructions.' }}</p>
            </div>

            <a 
              :href="material.file_url || material.link_url" 
              target="_blank"
              class="w-full text-center bg-[#005506] hover:bg-[#003d04] text-white text-xs font-bold py-2.5 rounded-xl shadow-xs transition-all block"
            >
              {{ material.type === 'link' ? 'Open Resource Link ↗' : 'Download File ⬇' }}
            </a>
          </div>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

defineProps({
  classroom: Object,
  materials: Array,
})
</script>