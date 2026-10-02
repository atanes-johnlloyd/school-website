<template>
  <AdminLayout>
    <div class="space-y-6 pb-10 font-['Inter']">

      <!-- HEADER BANNER -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <!-- Background Image Layer -->
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1600&auto=format&fit=crop'"
          alt="Admin Dashboard Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <!-- Animated Green Overlay (Blend mode matched to system dark green) -->
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <!-- Ambient Light Glow Highlights -->
        <div
          class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0">
        </div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0">
        </div>

        <!-- Content Container -->
        <div class="relative z-10 w-full max-w-4xl space-y-2.5 sm:space-y-3.5">
          <!-- Top Badges -->
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <span
              class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
              <span>🏛️</span> OVERVIEW
            </span>

            <span v-if="activeYear"
              class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              S.Y. {{ activeYear }}<span v-if="activeTerm"> • {{ activeTerm }}</span>
            </span>
          </div>

          <!-- Title & Quote Section -->
          <div class="space-y-1 sm:space-y-1.5">
            <h1
              class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
              <span>ADMIN</span>
              <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">DASHBOARD</span>
            </h1>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              Operational snapshot of the school's enrollment pipeline and daily action items.
            </p>
          </div>
        </div>
      </div>

      <!-- KPI Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div
          class="p-5 rounded-2xl border bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] flex items-center justify-between">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Pending Review</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.applicants_pending || 0 }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">Awaiting action</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>

        <div
          class="p-5 rounded-2xl border bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] flex items-center justify-between">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-sky-500 tracking-wider">Approved</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.applicants_approved || 0 }}</p>
            <span class="text-[10px] text-sky-600 dark:text-sky-400 font-normal">Awaiting exam</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-[#1C261E] border border-sky-100 dark:border-[#3F4F43] flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>

        <div
          class="p-5 rounded-2xl border bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] flex items-center justify-between">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Active Students</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.students_active || 0 }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Currently enrolled</span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
          </div>
        </div>

        <div
          class="p-5 rounded-2xl border bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43] flex items-center justify-between">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-violet-500 tracking-wider">Occupancy</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.occupancy_rate || 0 }}%</p>
            <span class="text-[10px] text-violet-600 dark:text-violet-400 font-normal">
              {{ stats.total_enrolled }}/{{ stats.total_capacity }} seats
            </span>
          </div>
          <div
            class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-[#1C261E] border border-violet-100 dark:border-[#3F4F43] flex items-center justify-center text-violet-600 dark:text-violet-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Action items -->
      <div v-if="actionItems.length"
        class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 dark:border-[#3F4F43] bg-amber-50/50 dark:bg-amber-950/20">
          <h2 class="text-xs font-medium text-amber-700 dark:text-amber-300 uppercase tracking-wider">Action Needed</h2>
        </div>
        <ul class="divide-y divide-gray-100 dark:divide-[#3F4F43]">
          <li v-for="item in actionItems" :key="item.type">
            <Link :href="item.url"
              class="flex items-center justify-between gap-3 px-5 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></div>
                <span class="text-xs text-gray-800 dark:text-gray-200">{{ item.label }}</span>
              </div>
              <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </Link>
          </li>
        </ul>
      </div>
      <div v-else
        class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-6 text-center">
        <p class="text-xs text-gray-500 dark:text-gray-400">Everything's clear. No action items.</p>
      </div>

      <!-- Charts row -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        <!-- Applicant status breakdown -->
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-5">
          <h3 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider mb-4">Applicant Pipeline
          </h3>
          <div class="space-y-3">
            <div v-for="row in statusRows" :key="row.key">
              <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-medium text-gray-600 dark:text-gray-300 capitalize">
                  {{ row.key.replace('_', ' ') }}
                </span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ row.count }}</span>
              </div>
              <div class="h-2 rounded-full bg-gray-100 dark:bg-[#1C261E] overflow-hidden">
                <div class="h-full rounded-full transition-all" :class="row.color"
                  :style="{ width: row.percent + '%' }"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Monthly trend -->
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-5">
          <h3 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider mb-4">Applications —
            Last 6 Months</h3>
          <div v-if="!monthlyTrend.length" class="py-8 text-center text-[11px] text-gray-400">
            No submissions recorded.
          </div>
          <div v-else class="flex items-end gap-2 h-40">
            <div v-for="row in monthlyTrend" :key="row.month" class="flex-1 flex flex-col items-center gap-1.5">
              <span class="text-[10px] font-medium text-gray-700 dark:text-gray-300">{{ row.count }}</span>
              <div class="w-full rounded-t-md bg-emerald-500/70 dark:bg-emerald-500/60"
                :style="{ height: (row.count / maxMonthlyCount * 100) + '%', minHeight: '4px' }"></div>
              <span class="text-[9px] text-gray-400 dark:text-gray-500">{{ shortMonth(row.month) }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Lists row -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        <!-- Recent applications -->
        <div
          class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] overflow-hidden">
          <div class="px-5 py-3.5 border-b border-gray-100 dark:border-[#3F4F43] flex items-center justify-between">
            <h3 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Recent Applications
            </h3>
            <Link :href="route('admin.applicants.index')"
              class="text-[10px] font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] hover:underline">
              View All
            </Link>
          </div>
          <div v-if="!recentApplications.length" class="p-6 text-center text-[11px] text-gray-400">
            No applications yet.
          </div>
          <ul v-else class="divide-y divide-gray-100 dark:divide-[#3F4F43]">
            <li v-for="a in recentApplications" :key="a.id"
              class="px-5 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
              <div class="flex items-center gap-3">
                <div
                  class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-[#1C261E] text-[#004d08] dark:text-[#86EFAC] border border-emerald-200 dark:border-[#3F4F43] flex items-center justify-center text-xs font-medium shrink-0">
                  {{ initials(a.name) }}
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ a.name }}</p>
                  <p class="text-[10px] text-gray-400 truncate">
                    <span class="font-mono">{{ a.reference_number }}</span>
                    <span v-if="a.strand"> &bull; {{ a.strand }}</span>
                  </p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-medium uppercase tracking-wider border shrink-0"
                  :class="statusBadge(a.status)">
                  {{ a.status.replace('_', ' ') }}
                </span>
              </div>
            </li>
          </ul>
        </div>

        <!-- Upcoming exams -->
        <div
          class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] overflow-hidden">
          <div class="px-5 py-3.5 border-b border-gray-100 dark:border-[#3F4F43] flex items-center justify-between">
            <h3 class="text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Upcoming Exams</h3>
            <Link :href="route('admin.entrance-exams.index')"
              class="text-[10px] font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] hover:underline">
              View All
            </Link>
          </div>
          <div v-if="!upcomingExams.length" class="p-6 text-center text-[11px] text-gray-400">
            No upcoming exams scheduled.
          </div>
          <ul v-else class="divide-y divide-gray-100 dark:divide-[#3F4F43]">
            <li v-for="e in upcomingExams" :key="e.id"
              class="px-5 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
              <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                  <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ e.exam_name }}</p>
                  <p class="text-[10px] text-gray-400">
                    {{ formatDate(e.exam_date) }} at {{ e.exam_time }} &bull; {{ e.venue || 'TBA' }}
                  </p>
                </div>
                <span class="text-[10px] font-medium text-gray-500 dark:text-gray-400 shrink-0">
                  {{ e.applicant_count }}/{{ e.max_capacity }}
                </span>
              </div>
            </li>
          </ul>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  stats: { type: Object, default: () => ({}) },
  recentApplications: { type: Array, default: () => [] },
  upcomingExams: { type: Array, default: () => [] },
  monthlyTrend: { type: Array, default: () => [] },
  actionItems: { type: Array, default: () => [] },
  activeYear: { type: String, default: null },
  activeTerm: { type: String, default: null },
})

const statusRows = computed(() => {
  const rows = [
    { key: 'pending', count: props.stats.applicants_pending || 0, color: 'bg-amber-500' },
    { key: 'under_review', count: props.stats.applicants_under_review || 0, color: 'bg-blue-500' },
    { key: 'approved', count: props.stats.applicants_approved || 0, color: 'bg-sky-500' },
    { key: 'enrolled', count: props.stats.applicants_enrolled || 0, color: 'bg-emerald-500' },
  ]
  const total = rows.reduce((s, r) => s + r.count, 0) || 1
  return rows.map(r => ({ ...r, percent: Math.round((r.count / total) * 100) }))
})

const maxMonthlyCount = computed(() =>
  Math.max(1, ...props.monthlyTrend.map(r => r.count))
)

const initials = (name) => {
  if (!name) return '?'
  const parts = name.trim().split(' ')
  return parts.length === 1
    ? parts[0].charAt(0).toUpperCase()
    : (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
}

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { month: 'short', day: 'numeric' }) }
  catch { return iso }
}

const shortMonth = (ym) => {
  if (!ym) return ''
  const [_, m] = ym.split('-')
  return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][parseInt(m) - 1] || ym
}

const statusBadge = (status) => ({
  pending: 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
  under_review: 'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
  approved: 'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  enrolled: 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  rejected: 'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
  needs_resubmission: 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
}[status] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')
</script>