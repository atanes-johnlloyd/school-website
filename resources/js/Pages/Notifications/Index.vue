<template>
  <Head title="Notifications - Salawag LMS" />

  <component :is="layout">
    <div class="space-y-4 sm:space-y-6 pb-10 font-['Inter'] min-w-0">

      <!-- HERO -->
      <div class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[160px] sm:min-h-[200px] flex flex-col justify-center p-5 sm:p-8 md:p-10">
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1600&auto=format&fit=crop'"
          alt="Notifications Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0"></div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full space-y-3 sm:space-y-4">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <div class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
              <span>🔔</span> INBOX
            </div>
            <div v-if="unreadCount > 0" class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              {{ unreadCount }} unread
            </div>
          </div>

          <div class="space-y-1 sm:space-y-1.5 max-w-3xl">
            <div class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
              <span>YOUR</span>
              <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">NOTIFICATIONS</span>
            </div>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium italic">
              "Grades, deadlines, announcements, and updates — all in one place."
            </p>
          </div>
        </div>
      </div>

      <!-- MAIN CARD -->
      <div class="bento-card rounded-2xl sm:rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 shadow-sm space-y-4 sm:space-y-5">

        <!-- HEADER ROW -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
          <div class="flex items-center gap-3">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
            <div>
              <h3 class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight leading-none">Inbox</h3>
              <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                {{ items.total }} {{ items.total === 1 ? 'notification' : 'notifications' }}
              </p>
            </div>
          </div>

          <div class="flex items-center gap-2 self-start sm:self-auto">
            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
              <button
                @click="activeFilter = 'all'"
                :class="[
                  'text-[10px] sm:text-[11px] font-black px-2.5 sm:px-3 py-1.5 rounded-xl shadow-sm transition-all shrink-0',
                  activeFilter === 'all'
                    ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                    : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43]'
                ]">
                All ({{ items.total }})
              </button>
              <button
                @click="activeFilter = 'unread'"
                :class="[
                  'text-[10px] sm:text-[11px] font-black px-2.5 sm:px-3 py-1.5 rounded-xl shadow-sm transition-all shrink-0',
                  activeFilter === 'unread'
                    ? 'bg-amber-500 text-white dark:bg-amber-400 dark:text-slate-900'
                    : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43]'
                ]">
                Unread ({{ unreadCount }})
              </button>
            </div>

            <button
              v-if="unreadCount > 0"
              @click="markAllRead"
              class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] hover:bg-[#004105] text-[10px] sm:text-[11px] font-black px-3 py-1.5 rounded-xl shadow-sm transition-all shrink-0">
              Mark all read
            </button>
          </div>
        </div>

        <!-- LIST -->
        <ul v-if="filteredItems.length" class="divide-y divide-slate-100 dark:divide-[#3F4F43] -mx-1">
          <li v-for="n in filteredItems" :key="n.id">
            <button
              @click="openNotification(n)"
              class="w-full text-left flex items-start gap-3 px-3 py-3.5 rounded-xl transition-colors min-w-0"
              :class="[
                !n.read_at
                  ? 'bg-[#006907]/[0.04] dark:bg-[#86EFAC]/[0.06] hover:bg-[#006907]/[0.08] dark:hover:bg-[#86EFAC]/[0.1]'
                  : 'hover:bg-slate-50 dark:hover:bg-white/5'
              ]">

              <!-- Kind dot -->
              <span class="mt-2 w-2.5 h-2.5 rounded-full shrink-0" :class="kindDot(n.kind)"></span>

              <span class="min-w-0 flex-1 space-y-1">
                <span class="flex items-start justify-between gap-3">
                  <span
                    class="text-sm text-slate-900 dark:text-white leading-snug"
                    :class="!n.read_at ? 'font-black' : 'font-bold'">
                    {{ n.title }}
                  </span>
                  <span class="text-[10px] sm:text-[11px] font-semibold text-slate-400 dark:text-slate-500 shrink-0 whitespace-nowrap">
                    {{ n.created_at }}
                  </span>
                </span>
                <span class="block text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed line-clamp-2">
                  {{ n.body }}
                </span>
                <span v-if="n.url" class="inline-flex items-center gap-1 text-[10px] font-black text-[#005506] dark:text-[#86EFAC] uppercase tracking-wide pt-0.5">
                  <span>Open</span>
                  <Icon icon="arrow-right" size="xs" />
                </span>
              </span>

              <span v-if="!n.read_at" class="mt-2 w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
            </button>
          </li>
        </ul>

        <!-- EMPTY STATE -->
        <div v-else class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-8 sm:p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
          <div class="flex justify-center text-emerald-500">
            <Icon icon="bell" size="xl" />
          </div>
          <p class="text-sm font-bold text-slate-800 dark:text-white">
            {{ activeFilter === 'unread' ? 'No unread notifications' : 'Nothing here yet' }}
          </p>
          <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ activeFilter === 'unread'
              ? 'You\'re all caught up.'
              : 'Notifications from your teachers and the school will show up here.' }}
          </p>
        </div>

        <!-- PAGINATION -->
        <div v-if="items.links?.length > 3" class="flex flex-wrap gap-1.5 justify-center pt-2">
          <Link
            v-for="l in items.links"
            :key="l.label"
            :href="l.url || '#'"
            v-html="l.label"
            class="px-3 py-1.5 rounded-lg text-xs font-black border transition-colors"
            :class="l.active
              ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] border-[#005506] dark:border-[#86EFAC]'
              : (l.url
                  ? 'bg-white dark:bg-[#232D26] text-slate-700 dark:text-slate-200 border-slate-200 dark:border-[#3F4F43] hover:bg-slate-50 dark:hover:bg-[#3F4F43]'
                  : 'opacity-40 cursor-not-allowed bg-white dark:bg-[#232D26] text-slate-400 border-slate-200 dark:border-[#3F4F43]')"
            preserve-scroll
            preserve-state
          />
        </div>

      </div>
    </div>
  </component>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../assets/img/local/studentdashboard.png'
import { defineAsyncComponent } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const roles = computed(() => page.props.auth?.roles || [])

const layout = computed(() => {
  if (roles.value.includes('admin'))   return defineAsyncComponent(() => import('@/Layouts/AdminLayout.vue'))
  if (roles.value.includes('teacher')) return defineAsyncComponent(() => import('@/Layouts/TeacherLayout.vue'))
  return defineAsyncComponent(() => import('@/Layouts/StudentLayout.vue'))
})

const props = defineProps({
  items: { type: Object, required: true },
})

const activeFilter = ref('all')

const filteredItems = computed(() => {
  const list = props.items?.data ?? []
  if (activeFilter.value === 'unread') return list.filter(n => !n.read_at)
  return list
})

const unreadCount = computed(() =>
  (props.items?.data ?? []).filter(n => !n.read_at).length
)

const kindDot = (kind) => ({
  info:    'bg-blue-500',
  success: 'bg-emerald-500',
  warning: 'bg-amber-500',
  danger:  'bg-red-500',
}[kind] || 'bg-gray-400')

const openNotification = (n) => {
  const go = () => { if (n.url) router.visit(n.url) }
  if (n.read_at) return go()

  router.post(route('notifications.mark-read', n.id), {}, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: go,
  })
}

const markAllRead = () => {
  router.post(route('notifications.mark-all-read'), {}, {
    preserveScroll: true,
    preserveState: true,
  })
}
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>