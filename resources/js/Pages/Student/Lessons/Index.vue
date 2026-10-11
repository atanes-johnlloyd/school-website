<template>
  <Head :title="`Lessons — ${classroom.subject || 'Subject'} - Salawag LMS`" />

  <StudentLayout>
    <div class="space-y-6 pb-10 font-['Inter'] min-w-0">

      <!-- HERO -->
      <div v-observe
        class="animate-fade-in-down relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[260px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <img
          src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1600&auto=format&fit=crop"
          alt="Lessons Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0"></div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full space-y-4 sm:space-y-5">

          <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
              <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                <div class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                  <Icon icon="book-open" size="xs" />
                  LEARNING MATERIALS
                </div>

                <div class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                  <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                  {{ lessons.length }} {{ lessons.length === 1 ? 'lesson' : 'lessons' }} published
                </div>
              </div>

              <div class="space-y-1">
                <h1 class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                  <span>LEARNING</span>
                  <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">MATERIALS</span>
                </h1>
                <p class="text-white/90 text-xs sm:text-sm leading-relaxed font-medium italic">
                  "Course content organized for your pace."
                </p>
              </div>
            </div>

            <Link :href="route('student.classes.show', classroom.id)"
              class="inline-flex items-center justify-center gap-2 bg-black/30 hover:bg-black/40 backdrop-blur-md text-white border border-white/20 font-bold px-4 py-2.5 rounded-xl transition-all active:scale-95 text-xs sm:text-sm cursor-pointer shadow-sm shrink-0 self-start">
              <Icon icon="arrow-left" size="xs" />
              <span>Back to Class</span>
            </Link>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-1">
            <div class="lg:col-span-7 flex items-center gap-3 sm:gap-4">
              <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl border-2 border-white/30 bg-white/10 backdrop-blur-sm overflow-hidden shrink-0 shadow-lg flex items-center justify-center transition-transform hover:scale-105 duration-300">
                <Icon icon="book-open" size="lg" class="text-white" />
              </div>

              <div class="space-y-0.5 min-w-0">
                <div class="inline-block bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-[11px] font-black px-3 py-0.5 rounded-full shadow-xs uppercase tracking-wide">
                  {{ classroom.section || '—' }}
                </div>
                <h2 class="text-base sm:text-xl font-black text-white tracking-tight leading-snug truncate">
                  {{ classroom.subject || 'Untitled Subject' }}
                </h2>
                <p class="text-white/90 text-xs font-medium truncate">
                  Instructor: <span class="font-bold">{{ classroom.teacher || '—' }}</span>
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-2 gap-2.5 sm:gap-3 w-full">
              <div class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl p-3 sm:p-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between shadow-sm min-h-[75px]">
                <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">Total Lessons</span>
                <div class="text-lg sm:text-2xl font-black text-[#F9C20C] leading-tight my-0.5">{{ lessons.length }}</div>
                <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium truncate">Published</span>
              </div>

              <div class="bg-black/30 dark:bg-black/50 backdrop-blur-md border border-white/20 rounded-2xl p-3 sm:p-3.5 hover:bg-black/40 transition-all duration-300 flex flex-col justify-between shadow-sm min-h-[75px]">
                <span class="text-[9px] sm:text-[10px] font-black text-emerald-100/80 uppercase tracking-wider block">Latest</span>
                <div class="text-xs sm:text-sm font-black text-white leading-tight my-0.5 line-clamp-2">
                  {{ lessons[0]?.title || '—' }}
                </div>
                <span class="text-[9px] sm:text-[10px] text-emerald-100/70 block font-medium truncate">Most recent</span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- WORKSPACE -->
      <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-200/60 dark:border-[#3F4F43]">
          <div class="flex items-center gap-3">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
            <div>
              <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight leading-none">
                Lesson Library
              </h3>
              <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                Click any lesson to read the full content
              </p>
            </div>
          </div>

          <div class="relative w-full sm:w-72">
            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 dark:text-slate-500">
              <Icon icon="search" size="sm" />
            </span>
            <input v-model="searchQuery" type="text" placeholder="Search lessons..."
              class="w-full bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl pl-8 pr-8 py-2 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC]" />
            <button v-if="searchQuery" @click="searchQuery = ''"
              class="absolute inset-y-0 right-2.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
              <Icon icon="x" size="xs" />
            </button>
          </div>
        </div>

        <div v-if="filteredLessons.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          <Link v-for="(l, idx) in filteredLessons" :key="l.id"
            :href="route('student.lessons.show', l.id)"
            class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl p-5 border border-slate-200/80 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 hover:-translate-y-1 hover:shadow-md hover:border-[#004d08] dark:hover:border-[#86EFAC] transition-all group">

            <div class="space-y-3">
              <div class="flex items-center justify-between gap-2">
                <span class="w-9 h-9 rounded-2xl bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] font-black text-xs flex items-center justify-center shrink-0 shadow-sm">
                  {{ String(idx + 1).padStart(2, '0') }}
                </span>
                <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500">
                  {{ formatShort(l.created_at) }}
                </span>
              </div>

              <h4 class="font-extrabold text-slate-900 dark:text-white text-base leading-snug group-hover:text-[#004d08] dark:group-hover:text-[#86EFAC] transition-colors line-clamp-2">
                {{ l.title }}
              </h4>

              <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-3 font-medium">
                {{ l.body_preview || 'No preview available.' }}
              </p>
            </div>

            <div class="pt-3 border-t border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between text-[11px] font-bold text-slate-500 dark:text-slate-400">
              <span class="inline-flex items-center gap-1">
                <Icon icon="document-text" size="xs" />
                Lesson
              </span>
              <span class="text-[#004d08] dark:text-[#86EFAC] inline-flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                Read
                <Icon icon="arrow-right" size="xs" />
              </span>
            </div>
          </Link>
        </div>

        <div v-else-if="lessons.length"
          class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
          <div class="flex justify-center text-slate-400">
            <Icon icon="search" size="xl" />
          </div>
          <p class="text-sm font-bold text-slate-800 dark:text-white">No lessons match your search</p>
          <button @click="searchQuery = ''"
            class="bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold px-4 py-2 rounded-xl hover:bg-[#003805] transition-colors cursor-pointer">
            Clear Search
          </button>
        </div>

        <div v-else
          class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
          <div class="flex justify-center text-slate-400">
            <Icon icon="book-open" size="xl" />
          </div>
          <p class="text-sm font-bold text-slate-800 dark:text-white">No lessons published yet</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">
            Your teacher hasn't posted any learning materials for this class.
          </p>
        </div>

      </div>
    </div>
  </StudentLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import StudentLayout from '@/Layouts/StudentLayout.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  classroom: { type: Object, default: () => ({}) },
  lessons:   { type: Array,  default: () => [] },
})

const searchQuery = ref('')

const filteredLessons = computed(() => {
  const q = searchQuery.value.trim().toLowerCase()
  if (!q) return props.lessons
  return props.lessons.filter(l =>
    (l.title || '').toLowerCase().includes(q) ||
    (l.body_preview || '').toLowerCase().includes(q)
  )
})

function formatShort(value) {
  if (!value) return '—'
  try {
    return new Date(value).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
  } catch { return '—' }
}
</script>

<style scoped>
@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-20px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes fadeSlideUp {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: translateY(0); }
}

.animate-fade-in-down  { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
</style>