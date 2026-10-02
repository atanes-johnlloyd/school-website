<template>
  <Head :title="`${lesson.title} - Salawag LMS`" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size"
      />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16 max-w-5xl mx-auto w-full">

        <!-- HERO -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 relative z-10">
            <div class="space-y-1 max-w-2xl">
              <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
                <span class="text-white">LESSON</span>
                <span class="animated-stroke-text">CONTENT</span>
              </div>
              <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
                Read the full lesson material.
              </p>
            </div>

            <Link :href="route('student.classes.lessons.index', classroom.id)"
              class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 self-start">
              <Icon icon="arrow-left" size="xs" />
              All Lessons
            </Link>
          </div>

          <div class="pt-2 space-y-2 relative z-10">
            <div class="flex flex-wrap items-center gap-2">
              <span class="bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                {{ classroom.subject || 'Subject' }}
              </span>
              <span v-if="classroom.section" class="bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                {{ classroom.section }}
              </span>
            </div>
            <h2 class="font-bold text-xl sm:text-2xl text-white tracking-tight leading-snug">
              {{ lesson.title }}
            </h2>
            <p class="text-xs text-emerald-100/70 font-medium">
              Published {{ formatDate(lesson.created_at) }} • {{ classroom.teacher || '—' }}
            </p>
          </div>
        </div>

        <!-- BODY -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-6 sm:p-8 shadow-sm space-y-6">

          <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
            <div>
              <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-none">
                Lesson Body
              </h3>
              <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                Full text from your instructor
              </p>
            </div>
          </div>

          <div v-if="lesson.body" class="prose prose-sm sm:prose-base max-w-none text-slate-700 dark:text-slate-300 whitespace-pre-wrap leading-relaxed">
            {{ lesson.body }}
          </div>

          <div v-else class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-8 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
            <div class="flex justify-center text-slate-400">
              <Icon icon="document-text" size="lg" />
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400">No body content for this lesson.</p>
          </div>
        </div>

        <!-- FOOTER ACTIONS -->
        <div class="animate-fade-slide-up flex flex-col sm:flex-row items-center justify-between gap-3 bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-3xl p-4 border border-slate-200/80 dark:border-[#3F4F43]">
          <Link :href="route('student.classes.lessons.index', classroom.id)"
            class="inline-flex items-center gap-1.5 text-slate-600 dark:text-slate-300 hover:text-[#004d08] dark:hover:text-[#86EFAC] text-xs font-bold transition-colors">
            <Icon icon="arrow-left" size="xs" />
            Back to all lessons
          </Link>

          <Link :href="route('student.classes.show', classroom.id)"
            class="inline-flex items-center gap-1.5 bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl transition-all active:scale-95">
            Open Class
            <Icon icon="arrow-right" size="xs" />
          </Link>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  classroom: { type: Object, default: () => ({}) },
  lesson:    { type: Object, default: () => ({}) },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')

function formatDate(value) {
  if (!value) return '—'
  try {
    return new Date(value).toLocaleString('en-US', {
      month: 'long', day: 'numeric', year: 'numeric',
      hour: 'numeric', minute: '2-digit', hour12: true,
    })
  } catch { return value }
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