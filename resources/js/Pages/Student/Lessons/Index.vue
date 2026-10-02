<template>
  <Head :title="`Lessons — ${classroom.subject || 'Subject'} - Salawag LMS`" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search lesson titles or topics..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size"
      />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- HERO -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 relative z-10">
            <div class="space-y-1 max-w-2xl">
              <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
                <span class="text-white">LEARNING</span>
                <span class="animated-stroke-text">MATERIALS</span>
              </div>
              <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
                Course content organized for your pace.
              </p>
            </div>

            <Link :href="route('student.classes.show', classroom.id)"
              class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 self-start">
              <Icon icon="arrow-left" size="xs" />
              Back to Class
            </Link>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2 relative z-10">
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 flex items-center justify-center shrink-0">
                <Icon icon="book-open" size="xl" class="text-white" />
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                  {{ classroom.section || '—' }}
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  {{ classroom.subject || 'Untitled Subject' }}
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  Instructor: {{ classroom.teacher || '—' }}
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-2 gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80">Total Lessons</span>
                <div class="text-xl font-extrabold text-amber-300 my-0.5">{{ lessons.length }}</div>
                <span class="text-[10px] text-emerald-100/70">Published</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between">
                <span class="text-[11px] font-medium text-emerald-100/80">Latest</span>
                <div class="text-xs font-extrabold text-white my-0.5 truncate">
                  {{ lessons[0]?.title || '—' }}
                </div>
                <span class="text-[10px] text-emerald-100/70">Most recent</span>
              </div>
            </div>
          </div>
        </div>

        <!-- WORKSPACE -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

          <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="flex items-center gap-3">
              <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
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
              class="bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold px-4 py-2 rounded-xl hover:bg-[#003805] transition-colors">
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
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  classroom: { type: Object, default: () => ({}) },
  lessons:   { type: Array,  default: () => [] },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')
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
.animated-stroke-text { color: transparent; -webkit-text-stroke: 1.5px #ffffff; }
@keyframes fadeInDown  { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px);  } to { opacity: 1; transform: translateY(0); } }
@keyframes sheenMove   { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
@keyframes spinSlow    { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.animate-fade-in-down  { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
.animate-sheen         { animation: sheenMove 4s ease-in-out infinite; }
.animate-spin-slow     { display: inline-block; animation: spinSlow 12s linear infinite; }
</style>