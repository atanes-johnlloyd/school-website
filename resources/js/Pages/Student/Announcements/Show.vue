<template>

  <Head :title="`${announcement.title} - Salawag LMS`" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop searchPlaceholder="Search announcements, subject, or keyword..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size" />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- ═══════════════ HERO ═══════════════ -->
        <div v-observe
          class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[260px] flex flex-col justify-center p-4 sm:p-8 md:p-10">

          <img
            :src="heroImage || 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1600&auto=format&fit=crop'"
            alt="Notice Details Hero"
            class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

          <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

          <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0"></div>
          <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0"></div>

          <div class="relative z-10 w-full space-y-3 sm:space-y-4">

            <!-- Badges -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
              <div class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                <span>📢</span> BULLETIN
              </div>
              <div class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                {{ announcement.is_school_wide ? 'School-wide Notice' : 'Class Notice' }}
              </div>
            </div>

            <!-- Title -->
            <div class="space-y-1 sm:space-y-1.5 max-w-3xl">
              <div class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                <span>NOTICE</span>
                <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">DETAILS</span>
              </div>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium italic">
                "Full announcement from the school bulletin."
              </p>
            </div>

            <!-- Headline + Quick Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-1">
              <div class="flex items-center gap-3 sm:gap-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl border-2 border-white/30 bg-white/10 backdrop-blur-sm shrink-0 shadow-lg flex items-center justify-center transition-transform hover:scale-105 duration-300">
                  <Icon icon="megaphone" size="lg" class="text-white" />
                </div>

                <div class="space-y-0.5 min-w-0">
                  <h2 class="text-base sm:text-xl md:text-2xl font-black text-white tracking-tight leading-tight line-clamp-2">
                    {{ announcement.title }}
                  </h2>
                  <p class="text-white/90 text-[11px] sm:text-sm font-medium">
                    <span class="font-black text-[#F9C20C]">{{ announcement.author || 'Staff' }}</span>
                    &bull; {{ formatShortDate(announcement.published_at) }}
                  </p>
                </div>
              </div>

              <div class="flex flex-wrap items-center gap-2 shrink-0">
                <Link :href="route('student.announcements.feed')"
                  class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95">
                  <Icon icon="arrow-left" size="xs" />
                  Back to Feed
                </Link>
              </div>
            </div>

          </div>
        </div>

        <!-- ═══════════════ META SNAPSHOT CARDS ═══════════════ -->
        <div v-observe class="anim-fade-slide-up grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5" style="animation-delay: 100ms;">

          <!-- Priority -->
          <div class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/80 dark:border-[#3F4F43] flex flex-col justify-between space-y-2">
            <div class="flex items-start justify-between">
              <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Priority
              </span>
              <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 border" :class="priorityIconClass">
                <Icon :icon="priorityIcon" size="sm" />
              </div>
            </div>
            <div class="text-lg sm:text-xl font-black" :class="priorityValueClass">{{ priorityLabel }}</div>
            <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400">{{ priorityHint }}</p>
          </div>

          <!-- Posted By -->
          <div class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/80 dark:border-[#3F4F43] flex flex-col justify-between space-y-2">
            <div class="flex items-start justify-between">
              <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Posted By
              </span>
              <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
                <Icon icon="user" size="sm" />
              </div>
            </div>
            <div class="text-sm sm:text-base font-black text-slate-900 dark:text-white truncate">
              {{ announcement.author || '—' }}
            </div>
            <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 truncate">
              {{ announcement.is_school_wide ? 'School Administration' : (announcement.subject || 'Class Teacher') }}
            </p>
          </div>

          <!-- Published -->
          <div class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/80 dark:border-[#3F4F43] flex flex-col justify-between space-y-2">
            <div class="flex items-start justify-between">
              <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Published
              </span>
              <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
                <Icon icon="calendar" size="sm" />
              </div>
            </div>
            <div class="text-sm sm:text-base font-black text-slate-900 dark:text-white">
              {{ formatShortDate(announcement.published_at) }}
            </div>
            <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400">
              {{ formatShortTime(announcement.published_at) || '—' }}
            </p>
          </div>

          <!-- Expires / Status -->
          <div class="bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/80 dark:border-[#3F4F43] flex flex-col justify-between space-y-2">
            <div class="flex items-start justify-between">
              <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                {{ announcement.expires_at ? 'Expires' : 'Status' }}
              </span>
              <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 border" :class="expiresIconClass">
                <Icon :icon="expiresIcon" size="sm" />
              </div>
            </div>
            <div class="text-sm sm:text-base font-black text-slate-900 dark:text-white">
              {{ announcement.expires_at ? formatShortDate(announcement.expires_at) : 'Active' }}
            </div>
            <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400">
              {{ announcement.expires_at ? 'Auto-hide date' : 'No expiry set' }}
            </p>
          </div>

        </div>

        <!-- ═══════════════ BODY ═══════════════ -->
        <div v-observe
          class="anim-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6"
          style="animation-delay: 200ms;">

          <!-- Meta badges row -->
          <div class="flex items-center gap-2 flex-wrap pb-4 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <span v-if="announcement.is_pinned"
              class="bg-amber-400 text-slate-950 font-black text-[10px] uppercase tracking-wider px-3 py-1 rounded-full flex items-center gap-1 shadow-xs">
              <Icon icon="star" size="xs" />
              Pinned
            </span>
            <span v-if="announcement.priority === 'urgent'"
              class="bg-rose-500 text-white font-black text-[10px] uppercase tracking-wider px-3 py-1 rounded-full flex items-center gap-1 shadow-xs">
              <Icon icon="alert-triangle" size="xs" />
              Urgent
            </span>
            <span v-else-if="announcement.priority === 'important'"
              class="bg-amber-400 text-slate-950 font-black text-[10px] uppercase tracking-wider px-3 py-1 rounded-full">
              Important
            </span>
            <span
              class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full border inline-flex items-center gap-1"
              :class="announcement.is_school_wide
                ? 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40'
                : 'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40'">
              <Icon :icon="announcement.is_school_wide ? 'megaphone' : 'book-open'" size="xs" />
              {{ announcement.is_school_wide ? 'School-wide' : (announcement.subject || 'Class Notice') }}
            </span>
          </div>

          <!-- Optional cover image -->
          <div v-if="announcement.image_url" class="rounded-2xl overflow-hidden border border-slate-200/80 dark:border-[#3F4F43]">
            <img :src="announcement.image_url" :alt="announcement.title"
              class="w-full h-auto object-cover max-h-[480px]" />
          </div>

          <!-- Body copy -->
          <div class="text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap font-medium">
            {{ announcement.body }}
          </div>
        </div>

        <!-- ═══════════════ CLASS CTA ═══════════════ -->
        <div v-if="announcement.classroom_id && announcement.subject"
          v-observe
          class="anim-fade-slide-up bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/80 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-4"
          style="animation-delay: 300ms;">

          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="book-open" size="md" />
            </div>
            <div class="min-w-0">
              <p class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Posted for</p>
              <p class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white truncate">
                {{ announcement.subject }}
              </p>
            </div>
          </div>

          <Link :href="route('student.classes.show', announcement.classroom_id)"
            class="inline-flex items-center justify-center gap-2 bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] dark:hover:bg-emerald-300 text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl shadow-sm transition-all active:scale-95 shrink-0">
            <Icon icon="arrow-right" size="xs" />
            Open Class
          </Link>
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
  announcement: { type: Object, default: () => ({}) },
})

const isSidebarOpen = ref(false)
const fontSizeMode  = ref('base')

/* ─── Priority presentation ─────────────────────────────── */
const priorityLabel = computed(() => ({
  normal:    'Normal',
  important: 'Important',
  urgent:    'Urgent',
}[props.announcement.priority] || 'Normal'))

const priorityIcon = computed(() => ({
  normal:    'info',
  important: 'alert-circle',
  urgent:    'alert-triangle',
}[props.announcement.priority] || 'info'))

const priorityIconClass = computed(() => ({
  normal:    'bg-slate-50 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]',
  important: 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-900/40',
  urgent:    'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-900/40',
}[props.announcement.priority] || 'bg-slate-50 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]'))

const priorityValueClass = computed(() => ({
  normal:    'text-slate-900 dark:text-white',
  important: 'text-amber-700 dark:text-amber-400',
  urgent:    'text-rose-600 dark:text-rose-400',
}[props.announcement.priority] || 'text-slate-900 dark:text-white'))

const priorityHint = computed(() => ({
  normal:    'Standard bulletin post',
  important: 'Elevated attention required',
  urgent:    'All users notified by email',
}[props.announcement.priority] || 'Standard bulletin post'))

/* ─── Expires presentation ─────────────────────────────── */
const expiresIcon = computed(() => props.announcement.expires_at ? 'clock' : 'check-circle')

const expiresIconClass = computed(() =>
  props.announcement.expires_at
    ? 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-900/40'
    : 'bg-emerald-50 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40')

/* ─── Date helpers ─────────────────────────────────────── */
function formatShortDate(value) {
  if (!value) return '—'
  try {
    return new Date(value).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
  } catch { return '—' }
}

function formatShortTime(value) {
  if (!value) return ''
  try {
    return new Date(value).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })
  } catch { return '' }
}

/* ─── Animations ───────────────────────────────────────── */
const vObserve = {
  mounted(el) {
    el.classList.add('not-visible')
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) el.classList.add('is-animated')
      else el.classList.remove('is-animated')
    }, { threshold: 0.1 })
    observer.observe(el)
  },
}
</script>

<style scoped>
@keyframes pulse-opacity { 0%, 100% { opacity: 0.88; } 50% { opacity: 0.65; } }
.animate-overlay { animation: pulse-opacity 6s infinite ease-in-out; }

@keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
</style>