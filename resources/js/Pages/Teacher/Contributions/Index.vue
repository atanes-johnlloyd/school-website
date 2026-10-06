<template>
  <Head title="Class Contributions - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search contributions, classes, purpose..">

    <div class="relative z-10 px-4 sm:px-6 md:px-10 pb-24 space-y-6 flex-1 mt-4">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] flex flex-col justify-center p-6 sm:p-8 md:p-10">
        <img :src="heroImage" alt="Contributions Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 max-w-3xl space-y-4">
          <div class="space-y-2">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-white tracking-tight leading-tight">
              Class Contributions
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium max-w-2xl">
              Manage class funds, field trips, and activity fees. Guardians approve, students pay securely through PayMongo.
            </p>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <Link :href="route('teacher.contributions.create')"
              class="inline-flex items-center gap-1.5 bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] px-3.5 py-2 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95">
              <Icon icon="plus" size="xs" />
              New Contribution
            </Link>
          </div>
        </div>
      </div>

      <!-- FILTER BAR -->
      <div v-observe
        class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-3.5 sm:p-4 shadow-sm border border-slate-200/60 dark:border-[#3F4F43]"
        style="animation-delay: 100ms;">
        <div class="flex flex-col lg:flex-row lg:items-center gap-3">

          <!-- Class picker -->
          <div class="relative w-full lg:w-64 shrink-0">
            <select v-model="classFilter"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-100 text-xs font-extrabold px-3.5 py-2.5 pr-16 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer truncate">
              <option value="all">All Classes ({{ contributions.length }})</option>
              <option v-for="c in classrooms" :key="c.id" :value="c.id">
                {{ c.subject }} — {{ c.section }}
              </option>
            </select>

            <button v-if="classFilter !== 'all'" type="button" @click="clearClassFilter"
              class="absolute right-8 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-slate-200 dark:bg-[#3F4F43] hover:bg-slate-300 dark:hover:bg-[#4a5c50] text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors"
              title="Clear class filter">
              <Icon icon="x" size="xs" />
            </button>

            <Icon icon="chevron-down" size="xs" class="absolute right-3 top-3.5 text-slate-400 pointer-events-none" />
          </div>

          <!-- Status pills -->
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

          <!-- Search -->
          <div class="relative flex-1 min-w-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
              <Icon icon="search" size="sm" />
            </span>
            <input v-model="search" type="text" placeholder="Search contribution title or purpose..."
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
            <!-- Class + Status badges -->
            <div class="flex items-center justify-between gap-2 flex-wrap">
              <span class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-extrabold px-2.5 py-1 rounded-md border border-emerald-200/60 dark:border-emerald-900/40 inline-flex items-center gap-1 truncate max-w-[70%]">
                <Icon icon="academic-cap" size="xs" />
                {{ c.classroom.subject || 'Class' }}
              </span>
              <span :class="c.is_published
                ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40'
                : 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]'"
                class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full border shrink-0">
                {{ c.is_published ? 'Published' : 'Draft' }}
              </span>
            </div>

            <!-- Title + section -->
            <div class="space-y-1">
              <h4 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white leading-snug line-clamp-2">
                {{ c.title }}
              </h4>
              <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">
                {{ c.classroom.section }} • {{ c.purpose || 'Class activity' }}
              </p>
            </div>

            <!-- Amount + deadline -->
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

            <!-- Progress bar -->
            <div class="space-y-1.5">
              <div class="flex items-center justify-between text-[11px] font-bold">
                <span class="text-slate-500 dark:text-slate-400">
                  {{ c.paid_count }} of {{ c.total_count }} paid
                </span>
                <span class="text-[#005506] dark:text-[#86EFAC]">{{ paidPercent(c) }}%</span>
              </div>
              <div class="w-full h-1.5 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-[#005506] to-emerald-500 dark:from-[#86EFAC] dark:to-emerald-400 transition-all duration-500"
                  :style="{ width: paidPercent(c) + '%' }"></div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center gap-2 pt-3 border-t border-slate-100 dark:border-[#3F4F43]">
            <Link :href="route('teacher.contributions.show', c.id)"
              class="flex-1 bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] font-bold py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
              <Icon icon="eye" size="xs" />
              View
            </Link>
            <Link :href="route('teacher.contributions.show', c.id)"
              class="flex-1 bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-[11px] font-black py-2 rounded-xl shadow-sm flex items-center justify-center gap-1.5 transition-all active:scale-95">
              <Icon icon="chart-bar" size="xs" />
              Manage
            </Link>
          </div>
        </div>
      </div>

      <!-- EMPTY -->
      <div v-else
        class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-12 shadow-sm border border-dashed border-slate-300 dark:border-[#3F4F43] text-center space-y-3">
        <Icon icon="credit-card" size="xl" class="text-slate-400 mx-auto" />
        <p class="text-sm font-bold text-slate-800 dark:text-white">
          {{ contributions.length === 0 ? 'No contributions yet' : 'No contributions match your filters' }}
        </p>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          {{ contributions.length === 0 ? 'Create your first class fund to get started.' : 'Try clearing the filters.' }}
        </p>
        <Link v-if="contributions.length === 0" :href="route('teacher.contributions.create')"
          class="inline-flex bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl hover:bg-[#004105] transition-colors items-center gap-2">
          <Icon icon="plus" size="xs" />
          New Contribution
        </Link>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/assignment.png'

const props = defineProps({
  contributions: { type: Array,  default: () => [] },
  classrooms:    { type: Array,  default: () => [] },
  stats:         { type: Object, default: () => ({}) },   // kept for controller parity, unused in UI
})

const classFilter  = ref('all')
const statusFilter = ref('all')
const search       = ref('')

const classScoped = computed(() => {
  if (classFilter.value === 'all') return props.contributions
  return props.contributions.filter(c => c.classroom?.id === classFilter.value)
})

const statusFilters = computed(() => [
  { id: 'all',       label: 'All',       count: classScoped.value.length },
  { id: 'published', label: 'Published', count: classScoped.value.filter(c => c.is_published).length },
  { id: 'draft',     label: 'Drafts',    count: classScoped.value.filter(c => !c.is_published).length },
].filter(f => f.id === 'all' || f.count > 0))

const filteredContributions = computed(() => {
  const q = search.value.trim().toLowerCase()
  return props.contributions.filter(c => {
    if (classFilter.value !== 'all' && c.classroom?.id !== classFilter.value) return false
    if (statusFilter.value === 'published' && !c.is_published) return false
    if (statusFilter.value === 'draft' && c.is_published) return false
    if (q && !(
      (c.title || '').toLowerCase().includes(q) ||
      (c.purpose || '').toLowerCase().includes(q) ||
      (c.classroom?.subject || '').toLowerCase().includes(q) ||
      (c.classroom?.section || '').toLowerCase().includes(q)
    )) return false
    return true
  })
})

function clearClassFilter() {
  classFilter.value = 'all'
}

function paidPercent(c) {
  if (!c.total_count) return 0
  return Math.round((c.paid_count / c.total_count) * 100)
}

function formatMoney(v) {
  return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

function formatShortDate(v) {
  if (!v) return '—'
  try {
    return new Date(v).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
  } catch { return '—' }
}

function isPast(v) {
  return v && new Date(v) < new Date()
}

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

@keyframes slideUpFade { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }

.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up { animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>