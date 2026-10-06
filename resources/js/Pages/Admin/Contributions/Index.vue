<template>
  <Head title="Contributions - Admin" />

  <AdminLayout>
    <div class="space-y-6">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] flex flex-col justify-center p-6 sm:p-8 md:p-10">
        <img :src="heroImage" alt="Contributions"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 max-w-3xl space-y-4">
          <div class="flex flex-wrap items-center gap-2.5">
            <div class="inline-flex items-center gap-2 bg-[#F9C20C] text-[#2C3E2D] font-black text-xs px-4 py-1.5 rounded-full shadow-sm tracking-wide">
              <Icon icon="credit-card" size="xs" />
              ADMIN — CONTRIBUTIONS
            </div>
            <div class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-xs font-bold px-4 py-1.5 rounded-full shadow-sm">
              <Icon icon="clipboard-list" size="xs" />
              {{ stats.total }} {{ stats.total === 1 ? 'Campaign' : 'Campaigns' }}
            </div>
            <div v-if="stats.active > 0"
              class="inline-flex items-center gap-2 bg-emerald-500/90 text-white border border-emerald-300/30 text-xs font-black px-4 py-1.5 rounded-full shadow-sm">
              <Icon icon="check-circle" size="xs" />
              {{ stats.active }} Active
            </div>
          </div>

          <div class="space-y-2">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-white tracking-tight leading-tight">
              All Class Contributions
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium max-w-2xl">
              School-wide oversight of every contribution created by teachers. Verify guardians, confirm cash, override when needed.
            </p>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <Link :href="route('admin.contributions.create')"
              class="inline-flex items-center gap-1.5 bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] px-3.5 py-2 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95">
              <Icon icon="plus" size="xs" />
              New Contribution
            </Link>
          </div>
        </div>
      </div>

      <!-- STATS -->
      <div v-observe class="anim-slide-up grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5" style="animation-delay: 100ms;">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Campaigns</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="credit-card" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ stats.total }}</div>
          <p class="text-[11px] font-medium text-slate-500">{{ stats.active }} active</p>
        </div>

        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Students Paid</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="check-circle" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-[#005506] dark:text-[#86EFAC]">{{ stats.students_paid }}</div>
          <p class="text-[11px] font-medium text-slate-500">of {{ stats.students_total }} assigned</p>
        </div>

        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Drafts</span>
            <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0 border border-slate-200 dark:border-[#3F4F43]">
              <Icon icon="edit" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-slate-700 dark:text-slate-200">{{ stats.drafts }}</div>
          <p class="text-[11px] font-medium text-slate-500">Not yet published</p>
        </div>

        <div :class="stats.collected > 0
          ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-200/80 dark:border-emerald-900/40'
          : 'bg-white dark:bg-[#2D3A31] border-slate-200/60 dark:border-[#3F4F43]'"
          class="rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-emerald-800 dark:text-emerald-300">Collected</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shrink-0 border border-emerald-200 dark:border-emerald-900/50">
              <Icon icon="chart-bar" size="sm" />
            </div>
          </div>
          <div class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 truncate">₱{{ formatShort(stats.collected) }}</div>
          <p class="text-[11px] font-medium text-emerald-700 dark:text-emerald-400">Total verified</p>
        </div>
      </div>

      <!-- FILTER + SEARCH -->
      <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-3.5 sm:p-4 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3" style="animation-delay: 150ms;">
        <div class="flex flex-col lg:flex-row lg:items-center gap-3">
          <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 lg:pb-0">
            <button v-for="f in statusFilters" :key="f.id" @click="statusFilter = f.id"
              :class="[
                'text-[11px] font-black px-3.5 py-1.5 sm:py-2 rounded-xl shadow-sm transition-all shrink-0 active:scale-95',
                statusFilter === f.id
                  ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                  : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43]'
              ]">
              {{ f.label }} ({{ f.count }})
            </button>
          </div>

          <div class="relative flex-1 min-w-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
              <Icon icon="search" size="sm" />
            </span>
            <input v-model="search" type="text" placeholder="Search title, teacher, section..."
              class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-xs font-semibold text-slate-700 dark:text-slate-200" />
          </div>
        </div>
      </div>

      <!-- GRID -->
      <div v-if="filteredContributions.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        <div v-for="(c, idx) in filteredContributions" :key="c.id"
          v-observe
          :style="{ animationDelay: `${idx * 40}ms` }"
          class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 hover:shadow-md hover:-translate-y-0.5 transition-all">

          <div class="space-y-3">
            <div class="flex items-center justify-between gap-2 flex-wrap">
              <span class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-extrabold px-2.5 py-1 rounded-md border border-emerald-200/60 dark:border-emerald-900/40 inline-flex items-center gap-1 truncate max-w-[70%]">
                <Icon :icon="c.scope === 'section' ? 'users' : 'book-open'" size="xs" />
                {{ c.display_name }}
              </span>
              <span :class="c.is_published
                ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40'
                : 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]'"
                class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full border shrink-0">
                {{ c.is_published ? 'Live' : 'Draft' }}
              </span>
            </div>

            <div class="space-y-1">
              <h4 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white leading-snug line-clamp-2">
                {{ c.title }}
              </h4>
              <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">
                {{ c.purpose || 'Class activity' }} • by {{ c.creator || 'Teacher' }}
              </p>
            </div>

            <div class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2 text-xs">
              <div class="flex items-center justify-between">
                <span class="text-slate-500 dark:text-slate-400 font-semibold">Amount</span>
                <span class="font-black text-slate-900 dark:text-white">
                  {{ c.amount_type === 'fixed' ? '₱' + formatMoney(c.amount) : '₱' + formatMoney(c.min_amount) + '+' }}
                </span>
              </div>
              <div v-if="c.deadline_at" class="flex items-center justify-between">
                <span class="text-slate-500 dark:text-slate-400 font-semibold">Deadline</span>
                <span :class="isPast(c.deadline_at) ? 'text-rose-600 dark:text-rose-400 font-bold' : 'text-slate-800 dark:text-slate-200 font-bold'">
                  {{ formatShortDate(c.deadline_at) }}
                </span>
              </div>
            </div>

            <div class="space-y-1.5">
              <div class="flex items-center justify-between text-[11px] font-bold">
                <span class="text-slate-500 dark:text-slate-400">{{ c.paid_count }} of {{ c.total_count }} paid</span>
                <span class="text-[#005506] dark:text-[#86EFAC]">{{ paidPercent(c) }}%</span>
              </div>
              <div class="w-full h-1.5 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-[#005506] to-emerald-500 dark:from-[#86EFAC] dark:to-emerald-400 transition-all duration-500"
                  :style="{ width: paidPercent(c) + '%' }"></div>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-2 pt-3 border-t border-slate-100 dark:border-[#3F4F43]">
            <Link :href="route('admin.contributions.show', c.id)"
              class="flex-1 bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] font-bold py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
              <Icon icon="eye" size="xs" />
              View
            </Link>
            <Link :href="route('admin.contributions.show', c.id)"
              class="flex-1 bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-[11px] font-black py-2 rounded-xl shadow-sm flex items-center justify-center gap-1.5 transition-all active:scale-95">
              <Icon icon="chart-bar" size="xs" />
              Manage
            </Link>
          </div>
        </div>
      </div>

      <div v-else class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-12 shadow-sm border border-dashed border-slate-300 dark:border-[#3F4F43] text-center space-y-3">
        <Icon icon="credit-card" size="xl" class="text-slate-400 mx-auto" />
        <p class="text-sm font-bold text-slate-800 dark:text-white">
          {{ contributions.length === 0 ? 'No contributions yet' : 'No matches' }}
        </p>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          {{ contributions.length === 0 ? 'No teacher has created a contribution yet.' : 'Try clearing the filters.' }}
        </p>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/assignment.png'

const props = defineProps({
  contributions: { type: Array,  default: () => [] },
  stats:         { type: Object, default: () => ({ total: 0, active: 0, drafts: 0, collected: 0, students_paid: 0, students_total: 0 }) },
})

const statusFilter = ref('all')
const search = ref('')

const statusFilters = computed(() => [
  { id: 'all',       label: 'All',       count: props.contributions.length },
  { id: 'published', label: 'Published', count: props.contributions.filter(c => c.is_published).length },
  { id: 'draft',     label: 'Drafts',    count: props.contributions.filter(c => !c.is_published).length },
].filter(f => f.id === 'all' || f.count > 0))

const filteredContributions = computed(() => {
  const q = search.value.trim().toLowerCase()
  return props.contributions.filter(c => {
    if (statusFilter.value === 'published' && !c.is_published) return false
    if (statusFilter.value === 'draft' && c.is_published) return false
    if (q && !(
      (c.title || '').toLowerCase().includes(q) ||
      (c.purpose || '').toLowerCase().includes(q) ||
      (c.creator || '').toLowerCase().includes(q) ||
      (c.display_name || '').toLowerCase().includes(q)
    )) return false
    return true
  })
})

function paidPercent(c) { return c.total_count ? Math.round((c.paid_count / c.total_count) * 100) : 0 }
function formatMoney(v) { return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }
function formatShort(v) {
  const n = Number(v || 0)
  if (n >= 1_000_000) return (n / 1_000_000).toFixed(1) + 'M'
  if (n >= 1_000) return (n / 1_000).toFixed(1) + 'K'
  return n.toFixed(0)
}
function formatShortDate(v) {
  if (!v) return '—'
  try { return new Date(v).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }
  catch { return '—' }
}
function isPast(v) { return v && new Date(v) < new Date() }

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
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideUpFade { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up { animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>