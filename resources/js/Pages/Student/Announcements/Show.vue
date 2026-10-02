<template>
  <Head :title="`${announcement.title} - Salawag LMS`" />

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

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16 max-w-4xl mx-auto w-full">

        <!-- HERO -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 relative z-10">
            <div class="space-y-1 max-w-2xl">
              <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
                <span class="text-white">NOTICE</span>
                <span class="animated-stroke-text">DETAILS</span>
              </div>
              <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
                Full announcement from the school bulletin.
              </p>
            </div>

            <Link :href="route('student.announcements.feed')"
              class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 self-start">
              <Icon icon="arrow-left" size="xs" />
              Back to Feed
            </Link>
          </div>

          <div class="pt-2 space-y-3 relative z-10">
            <div class="flex flex-wrap items-center gap-2">
              <span v-if="announcement.is_pinned" class="bg-amber-400 text-slate-950 font-black text-[10px] uppercase tracking-wider px-3 py-1 rounded-full flex items-center gap-1 shadow-xs">
                <Icon icon="star" size="xs" />
                Pinned
              </span>
              <span v-if="announcement.priority === 'urgent'" class="bg-rose-500 text-white font-black text-[10px] uppercase tracking-wider px-3 py-1 rounded-full flex items-center gap-1 shadow-xs">
                <Icon icon="alert-triangle" size="xs" />
                Urgent
              </span>
              <span v-else-if="announcement.priority === 'important'" class="bg-amber-400 text-slate-950 font-black text-[10px] uppercase tracking-wider px-3 py-1 rounded-full flex items-center gap-1 shadow-xs">
                Important
              </span>
              <span class="bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10 inline-flex items-center gap-1">
                <Icon :icon="announcement.is_school_wide ? 'megaphone' : 'book-open'" size="xs" />
                {{ announcement.is_school_wide ? 'School-wide' : (announcement.subject || 'Class Notice') }}
              </span>
            </div>
            <h2 class="font-bold text-lg sm:text-2xl text-white tracking-tight leading-snug">
              {{ announcement.title }}
            </h2>
          </div>
        </div>

        <!-- BODY -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-6 sm:p-8 shadow-sm space-y-6">
          <div v-if="announcement.image_url" class="rounded-2xl overflow-hidden border border-slate-200/80 dark:border-[#3F4F43]">
            <img :src="announcement.image_url" :alt="announcement.title" class="w-full h-auto object-cover max-h-96" />
          </div>

          <div class="text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">
            {{ announcement.body }}
          </div>
        </div>

        <!-- META -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-6 shadow-sm">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
                <Icon icon="user" size="md" />
              </div>
              <div class="min-w-0">
                <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 block">Posted By</span>
                <span class="text-sm font-bold text-slate-900 dark:text-white block mt-0.5 truncate">{{ announcement.author || '—' }}</span>
                <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">
                  {{ announcement.is_school_wide ? 'School Administration' : (announcement.subject || 'Class Teacher') }}
                </span>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
                <Icon icon="calendar" size="md" />
              </div>
              <div class="min-w-0">
                <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 block">Published</span>
                <span class="text-sm font-bold text-slate-900 dark:text-white block mt-0.5">{{ formatDate(announcement.published_at) }}</span>
                <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">Official release</span>
              </div>
            </div>

            <div v-if="announcement.expires_at" class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-300 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40">
                <Icon icon="clock" size="md" />
              </div>
              <div class="min-w-0">
                <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 block">Expires</span>
                <span class="text-sm font-bold text-slate-900 dark:text-white block mt-0.5">{{ formatDate(announcement.expires_at) }}</span>
                <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">Auto-hide</span>
              </div>
            </div>
          </div>

          <div v-if="announcement.classroom_id && announcement.subject" class="pt-4 border-t border-slate-200/60 dark:border-[#3F4F43] mt-4">
            <Link :href="route('student.classes.show', announcement.classroom_id)"
              class="inline-flex items-center gap-1.5 bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl transition-all active:scale-95">
              <Icon icon="arrow-right" size="xs" />
              Open {{ announcement.subject }} Class
            </Link>
          </div>
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
  announcement: { type: Object, default: () => ({}) },
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