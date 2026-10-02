<template>
  <Head title="Announcements - Salawag LMS" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search announcements, subject, or keyword..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size"
      />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- HERO -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="space-y-1 relative z-10">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
              <span class="text-white">CAMPUS</span>
              <span class="animated-stroke-text">ANNOUNCEMENTS</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              "Stay updated with school-wide bulletins and class notices."
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs animate-spin-slow">★</span>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2 relative z-10">
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 flex items-center justify-center shrink-0">
                <Icon icon="megaphone" size="xl" class="text-white" />
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10">
                  {{ active_term || 'Active Term' }}
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  School & Class Bulletin
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  {{ counts.all }} {{ counts.all === 1 ? 'notice' : 'notices' }} published
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-3 gap-2 sm:gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">School-wide</span>
                <div class="text-xl font-extrabold text-amber-300 my-0.5">{{ counts.school_wide }}</div>
                <span class="text-[9px] text-emerald-100/70">Campus</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">Class</span>
                <div class="text-xl font-extrabold text-white my-0.5">{{ counts.class }}</div>
                <span class="text-[9px] text-emerald-100/70">Per subject</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-3 flex flex-col justify-between">
                <span class="text-[10px] font-medium text-emerald-100/80">Urgent</span>
                <div class="text-xl font-extrabold text-rose-300 my-0.5">{{ counts.urgent }}</div>
                <span class="text-[9px] text-emerald-100/70">Priority</span>
              </div>
            </div>
          </div>
        </div>

        <!-- WORKSPACE -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

          <!-- FILTER + SEARCH -->
          <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="flex items-center gap-1.5 bg-[#f5f7f2] dark:bg-[#232D26] p-1.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] overflow-x-auto w-full sm:w-auto">
              <button v-for="tab in filterTabs" :key="tab.id" @click="activeTab = tab.id"
                :class="[
                  'px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all shrink-0 flex items-center gap-1.5 active:scale-95',
                  activeTab === tab.id
                    ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-xs'
                    : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'
                ]">
                <span>{{ tab.label }}</span>
                <span :class="[
                  'text-[10px] px-1.5 py-0.2 rounded-full font-black',
                  activeTab === tab.id ? 'bg-white/20 text-white dark:text-[#232D26]' : 'bg-slate-200 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300'
                ]">{{ tab.count }}</span>
              </button>
            </div>

            <div class="relative w-full sm:w-72">
              <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 dark:text-slate-500">
                <Icon icon="search" size="sm" />
              </span>
              <input v-model="searchQuery" type="text" placeholder="Search announcements..."
                class="w-full bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl pl-8 pr-8 py-2 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC]" />
              <button v-if="searchQuery" @click="searchQuery = ''"
                class="absolute inset-y-0 right-2.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <Icon icon="x" size="xs" />
              </button>
            </div>
          </div>

          <!-- PINNED HERO -->
          <div v-if="pinnedAnnouncement"
            @click="goTo(pinnedAnnouncement)"
            class="bg-gradient-to-br from-[#004d08] to-[#003304] dark:from-[#152B1C] dark:to-[#0F2114] text-white rounded-3xl p-6 sm:p-8 shadow-md relative overflow-hidden group cursor-pointer">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 space-y-4">
              <div class="flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2 flex-wrap">
                  <span class="bg-amber-400 text-slate-950 font-black text-[10px] uppercase tracking-wider px-3 py-1 rounded-full flex items-center gap-1 shadow-xs">
                    <Icon icon="star" size="xs" />
                    Pinned
                  </span>
                  <span v-if="pinnedAnnouncement.priority === 'urgent'" class="bg-rose-500 text-white font-black text-[10px] uppercase tracking-wider px-3 py-1 rounded-full flex items-center gap-1">
                    <Icon icon="alert-triangle" size="xs" />
                    Urgent
                  </span>
                </div>
                <span class="text-xs text-emerald-200/80 font-medium">{{ pinnedAnnouncement.published_human }}</span>
              </div>

              <div class="space-y-2 max-w-3xl">
                <h3 class="font-black text-xl sm:text-2xl text-white group-hover:text-emerald-300 transition-colors">
                  {{ pinnedAnnouncement.title }}
                </h3>
                <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed line-clamp-3">
                  {{ pinnedAnnouncement.body_preview }}
                </p>
              </div>

              <div class="pt-4 border-t border-white/10 flex items-center justify-between flex-wrap gap-3 text-xs text-emerald-200">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-white border border-white/30">
                    {{ initialOf(pinnedAnnouncement.author) }}
                  </div>
                  <div>
                    <span class="font-extrabold block text-white">{{ pinnedAnnouncement.author || 'Staff' }}</span>
                    <span class="text-[10px] text-emerald-300">
                      {{ pinnedAnnouncement.is_school_wide ? 'School-wide' : (pinnedAnnouncement.subject || 'Class') }}
                    </span>
                  </div>
                </div>
                <span class="font-bold text-white group-hover:translate-x-1 transition-transform flex items-center gap-1">
                  Read Full Notice
                  <Icon icon="arrow-right" size="xs" />
                </span>
              </div>
            </div>
          </div>

          <!-- ANNOUNCEMENT GRID -->
          <div v-if="regularAnnouncements.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="item in regularAnnouncements" :key="item.id"
              @click="goTo(item)"
              class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl p-6 shadow-xs border border-slate-200/80 dark:border-[#3F4F43] min-h-[220px] flex flex-col justify-between hover:border-[#004d08] dark:hover:border-[#86EFAC] hover:shadow-md transition-all cursor-pointer group relative">

              <div class="space-y-3">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                  <div class="flex items-center gap-1.5 flex-wrap">
                    <span :class="scopeBadgeClass(item)"
                      class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full border inline-flex items-center gap-1">
                      <Icon :icon="item.is_school_wide ? 'megaphone' : 'book-open'" size="xs" />
                      {{ item.is_school_wide ? 'School-wide' : (item.subject || 'Class') }}
                    </span>
                    <span v-if="item.priority === 'urgent'" class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full border bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-900/40">
                      Urgent
                    </span>
                    <span v-else-if="item.priority === 'important'" class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full border bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40">
                      Important
                    </span>
                  </div>
                  <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500">{{ item.published_human }}</span>
                </div>

                <h4 class="font-extrabold text-slate-900 dark:text-white text-base leading-snug group-hover:text-[#004d08] dark:group-hover:text-[#86EFAC] transition-colors line-clamp-2">
                  {{ item.title }}
                </h4>

                <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed font-medium">
                  {{ item.body_preview }}
                </p>
              </div>

              <div class="pt-4 border-t border-slate-200/60 dark:border-[#3F4F43] flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 font-semibold">
                <div class="flex items-center gap-2 min-w-0">
                  <div class="w-6 h-6 rounded-full bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-[10px] flex items-center justify-center font-bold shrink-0">
                    {{ initialOf(item.author) }}
                  </div>
                  <span class="text-slate-700 dark:text-slate-200 font-bold truncate">{{ item.author || 'Staff' }}</span>
                </div>
                <span class="text-[#004d08] dark:text-[#86EFAC] font-bold inline-flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                  Read
                  <Icon icon="arrow-right" size="xs" />
                </span>
              </div>
            </div>
          </div>

          <div v-else-if="!pinnedAnnouncement"
            class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
            <div class="flex justify-center text-slate-400">
              <Icon icon="megaphone" size="xl" />
            </div>
            <p class="text-sm font-bold text-slate-800 dark:text-white">No announcements yet</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              {{ searchQuery ? 'Try a different search term.' : 'Your school and class bulletins will appear here.' }}
            </p>
          </div>

        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  announcements: { type: Array,  default: () => [] },
  counts:        { type: Object, default: () => ({ all: 0, school_wide: 0, class: 0, pinned: 0, urgent: 0 }) },
  active_term:   { type: String, default: null },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')
const activeTab = ref('all')
const searchQuery = ref('')

const filterTabs = computed(() => [
  { id: 'all',         label: 'All',         count: props.counts.all },
  { id: 'school_wide', label: 'School-wide', count: props.counts.school_wide },
  { id: 'class',       label: 'Class',       count: props.counts.class },
  { id: 'pinned',      label: 'Pinned',      count: props.counts.pinned },
])

const filteredAnnouncements = computed(() => {
  const q = searchQuery.value.trim().toLowerCase()
  return props.announcements.filter(a => {
    const matchesTab =
      activeTab.value === 'all' ||
      (activeTab.value === 'school_wide' && a.is_school_wide) ||
      (activeTab.value === 'class' && !a.is_school_wide) ||
      (activeTab.value === 'pinned' && a.is_pinned)

    const matchesSearch = !q ||
      (a.title || '').toLowerCase().includes(q) ||
      (a.body || '').toLowerCase().includes(q) ||
      (a.subject || '').toLowerCase().includes(q) ||
      (a.author || '').toLowerCase().includes(q)

    return matchesTab && matchesSearch
  })
})

const pinnedAnnouncement = computed(() =>
  filteredAnnouncements.value.find(a => a.is_pinned) ?? null
)

const regularAnnouncements = computed(() =>
  filteredAnnouncements.value.filter(a => !a.is_pinned)
)

function goTo(item) {
  router.visit(item.show_url)
}

function initialOf(name) {
  if (typeof name !== 'string' || name.length === 0) return '?'
  return name.charAt(0).toUpperCase()
}

function scopeBadgeClass(item) {
  if (item.is_school_wide) {
    return 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40'
  }
  return 'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40'
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