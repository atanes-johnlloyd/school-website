<template>
  <AdminLayout>
    <div class="space-y-6 pb-10 font-['Inter']">

      <!-- Hero + Filters -->
      <div class="relative z-20 bg-[#004d08] text-white rounded-2xl p-6 md:p-8 shadow-lg border border-emerald-900/40">

        <!-- Watermark — clipped to hero bounds -->
        <div class="absolute inset-0 overflow-hidden rounded-2xl pointer-events-none">
          <div class="absolute -right-6 -bottom-8 opacity-10 text-9xl font-['Anton'] select-none text-white">
            DATA
          </div>
        </div>

        <!-- Hero content -->
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="bg-amber-400/20 text-amber-300 text-xs font-medium px-2.5 py-0.5 rounded-md border border-amber-400/30">SYSTEM</span>
              <span class="text-emerald-200 text-xs font-normal">&bull; Reports &amp; Analytics</span>
            </div>
            <h1 class="font-['Anton'] text-3xl md:text-4xl tracking-wide uppercase">
              Reports <span class="text-amber-400">&amp; Analytics</span>
            </h1>
            <p class="text-emerald-100/80 text-sm mt-1 max-w-xl font-normal">
              Operational metrics across admissions, enrollment, academics, and exams.
            </p>
          </div>

          <div class="flex flex-wrap items-center gap-2 shrink-0">
            <select v-model="filters.school_year_id" @change="fetchData()"
                    class="appearance-none pl-3 pr-8 py-2.5 text-xs bg-emerald-900/60 border border-emerald-700/50 text-white rounded-xl focus:ring-2 focus:ring-amber-400 focus:outline-none font-normal cursor-pointer">
              <option value="" class="text-black">All School Years</option>
              <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id" class="text-black">
                {{ sy.label }}
              </option>
            </select>

            <select v-model="filters.track_id" @change="fetchData()"
                    class="appearance-none pl-3 pr-8 py-2.5 text-xs bg-emerald-900/60 border border-emerald-700/50 text-white rounded-xl focus:ring-2 focus:ring-amber-400 focus:outline-none font-normal cursor-pointer">
              <option value="" class="text-black">All Tracks</option>
              <option v-for="t in tracks" :key="t.id" :value="t.id" class="text-black">
                {{ t.name }}
              </option>
            </select>

            <!-- Export Dropdown -->
            <div class="relative">
              <button @click="showExport = !showExport"
                      class="inline-flex items-center gap-1.5 bg-emerald-900/60 hover:bg-emerald-900 text-emerald-50 border border-emerald-700/50 font-medium px-4 py-2.5 rounded-xl transition-all active:scale-95 text-xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5m0 0l5-5m-5 5V3" />
                </svg>
                Export CSV
                <svg class="w-3 h-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <div v-if="showExport"
                   class="absolute right-0 mt-1 w-56 bg-white dark:bg-[#2D3A31] border border-gray-200 dark:border-[#3F4F43] rounded-xl shadow-lg z-30 overflow-hidden py-1">
                <a v-for="exp in exportOptions" :key="exp.slug"
                   :href="`/admin/exports/${exp.slug}`"
                   @click="showExport = false"
                   class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors whitespace-nowrap">
                  <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5m0 0l5-5m-5 5V3" />
                  </svg>
                  {{ exp.label }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Overlay for dropdown -->
      <div v-if="showExport" @click="showExport = false" class="fixed inset-0 z-10"></div>

      <!-- Loading -->
      <div v-if="isLoading && !stats" class="py-16 text-center">
        <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading analytics…</p>
      </div>

      <template v-else-if="stats">

        <!-- KPI Row — 6 cards, icon-box pattern matching other pages -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">

          <!-- Applicants -->
          <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
            <div class="space-y-1">
              <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Applicants</p>
              <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.applicant_stats.total || 0 }}</p>
              <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">{{ stats.applicant_stats.pending || 0 }} pending</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
          </div>

          <!-- Students -->
          <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
            <div class="space-y-1">
              <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Students</p>
              <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.student_stats.total || 0 }}</p>
              <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">{{ stats.student_stats.active || 0 }} active</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
              </svg>
            </div>
          </div>

          <!-- Teachers -->
          <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
            <div class="space-y-1">
              <p class="text-[10px] font-medium uppercase text-sky-500 tracking-wider">Teachers</p>
              <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.teacher_stats.total || 0 }}</p>
              <span class="text-[10px] text-sky-600 dark:text-sky-400 font-normal">{{ stats.teacher_stats.active || 0 }} active</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-[#1C261E] border border-sky-100 dark:border-[#3F4F43] flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
          </div>

          <!-- Sections -->
          <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
            <div class="space-y-1">
              <p class="text-[10px] font-medium uppercase text-violet-500 tracking-wider">Sections</p>
              <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.section_stats.total_sections || 0 }}</p>
              <span class="text-[10px] text-violet-600 dark:text-violet-400 font-normal">{{ stats.section_stats.available || 0 }} slots free</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-[#1C261E] border border-violet-100 dark:border-[#3F4F43] flex items-center justify-center text-violet-600 dark:text-violet-400 shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9m4 0v10" />
              </svg>
            </div>
          </div>

          <!-- Occupancy -->
          <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
            <div class="space-y-1">
              <p class="text-[10px] font-medium uppercase text-blue-500 tracking-wider">Occupancy</p>
              <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.section_stats.occupancy_rate || 0 }}%</p>
              <span class="text-[10px] text-blue-600 dark:text-blue-400 font-normal">
                {{ stats.section_stats.enrolled || 0 }}/{{ stats.section_stats.total_capacity || 0 }} seats
              </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-[#1C261E] border border-blue-100 dark:border-[#3F4F43] flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
          </div>

          <!-- Pass Rate -->
          <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
            <div class="space-y-1">
              <p class="text-[10px] font-medium uppercase text-rose-500 tracking-wider">Pass Rate</p>
              <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ stats.exam_summary.passRate || 0 }}%</p>
              <span class="text-[10px] text-rose-600 dark:text-rose-400 font-normal">{{ stats.exam_summary.passed || 0 }} passed</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-[#1C261E] border border-rose-100 dark:border-[#3F4F43] flex items-center justify-center text-rose-600 dark:text-rose-400 shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Applicant Pipeline + Exam Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

          <!-- Applicant Pipeline -->
          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-5">
            <div class="flex items-center justify-between mb-4">
              <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">Applicant Pipeline</h4>
              <span class="text-[10px] text-gray-400">{{ stats.applicant_stats.total || 0 }} total</span>
            </div>

            <div class="space-y-3">
              <div v-for="row in applicantPipelineRows" :key="row.key">
                <div class="flex items-center justify-between mb-1">
                  <span class="text-[11px] font-medium text-gray-600 dark:text-gray-300 capitalize">
                    {{ row.label }}
                  </span>
                  <span class="text-xs font-medium text-gray-900 dark:text-white">
                    {{ row.count }}
                    <span class="text-[10px] font-normal text-gray-400">({{ row.percent }}%)</span>
                  </span>
                </div>
                <div class="h-2 rounded-full bg-gray-100 dark:bg-[#1C261E] overflow-hidden">
                  <div class="h-full rounded-full transition-all"
                       :class="row.color"
                       :style="{ width: row.percent + '%' }"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Exam Summary -->
            <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-5 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">
                Exam Results
                </h4>
                <span class="text-[10px] text-gray-400">
                {{ stats.exam_summary.total || 0 }} recorded
                </span>
            </div>

            <div class="flex-1 flex items-center justify-center">
                <div class="flex flex-col sm:flex-row items-center gap-6 w-full">

                <!-- Donut -->
                <div class="relative w-32 h-32 shrink-0">
                    <svg viewBox="0 0 36 36" class="w-full h-full -rotate-90">
                    <circle cx="18" cy="18" r="15.9155" fill="none"
                            class="stroke-gray-100 dark:stroke-[#1C261E]"
                            stroke-width="3" />
                    <circle cx="18" cy="18" r="15.9155" fill="none"
                            class="stroke-emerald-500 transition-all duration-700"
                            stroke-width="3"
                            :stroke-dasharray="`${stats.exam_summary.passRate || 0} 100`"
                            stroke-linecap="round" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-['Anton'] text-gray-900 dark:text-white leading-none">
                        {{ stats.exam_summary.passRate || 0 }}<span class="text-base text-gray-400">%</span>
                    </span>
                    <span class="text-[9px] uppercase tracking-wider text-gray-400 mt-1">Pass Rate</span>
                    </div>
                </div>

                <!-- Legend 2×2 -->
                <div class="grid grid-cols-2 gap-2.5 flex-1 w-full">
                    <div class="p-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/50">
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <p class="text-[9px] uppercase tracking-wider text-emerald-700 dark:text-emerald-400 font-semibold">Passed</p>
                    </div>
                    <p class="text-lg font-medium text-gray-900 dark:text-white leading-none">
                        {{ stats.exam_summary.passed || 0 }}
                    </p>
                    </div>

                    <div class="p-2.5 rounded-lg bg-red-50 dark:bg-red-950/30 border border-red-100 dark:border-red-900/50">
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        <p class="text-[9px] uppercase tracking-wider text-red-700 dark:text-red-400 font-semibold">Failed</p>
                    </div>
                    <p class="text-lg font-medium text-gray-900 dark:text-white leading-none">
                        {{ stats.exam_summary.failed || 0 }}
                    </p>
                    </div>

                    <div class="p-2.5 rounded-lg bg-amber-50 dark:bg-amber-950/30 border border-amber-100 dark:border-amber-900/50">
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <p class="text-[9px] uppercase tracking-wider text-amber-700 dark:text-amber-400 font-semibold">Absent</p>
                    </div>
                    <p class="text-lg font-medium text-gray-900 dark:text-white leading-none">
                        {{ stats.exam_summary.absent || 0 }}
                    </p>
                    </div>

                    <div class="p-2.5 rounded-lg bg-sky-50 dark:bg-sky-950/30 border border-sky-100 dark:border-sky-900/50">
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                        <p class="text-[9px] uppercase tracking-wider text-sky-700 dark:text-sky-400 font-semibold">Pending</p>
                    </div>
                    <p class="text-lg font-medium text-gray-900 dark:text-white leading-none">
                        {{ stats.exam_summary.pending || 0 }}
                    </p>
                    </div>
                </div>

                </div>
            </div>
            </div>
        </div>

        <!-- Monthly Trend -->
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-5">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">
            Applications — Last 6 Months
            </h4>
            <span class="text-[10px] text-gray-400">{{ trendTotal }} total submissions</span>
        </div>

        <div v-if="!stats.monthly_trend.length" class="py-10 text-center text-[11px] text-gray-400">
            No submissions recorded for this period.
        </div>
        <div v-else class="flex gap-3 h-48">
            <div v-for="row in stats.monthly_trend" :key="row.month"
                class="flex-1 flex flex-col justify-end items-center gap-1.5 h-full">

            <!-- Count label — sits right above the bar -->
            <span class="text-[10px] font-medium text-gray-700 dark:text-gray-300 leading-none">
                {{ row.count }}
            </span>

            <!-- Bar — grows up from the baseline -->
            <div class="w-full rounded-t-md bg-emerald-500 transition-colors"
                :style="{ height: Math.max(6, Math.round((row.count / maxMonthlyCount) * 130)) + 'px' }"
                :title="`${row.count} applications in ${shortMonth(row.month)} ${row.month.split('-')[0]}`"></div>

            <!-- Month label — baseline, always at bottom -->
            <span class="text-[9px] text-gray-400 dark:text-gray-500 uppercase tracking-wider leading-none">
                {{ shortMonth(row.month) }}
            </span>
            </div>
        </div>
        </div>

        <!-- Strand Distribution + Section Occupancy -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-5">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-4">Applicants by Strand</h4>

            <div v-if="!stats.strand_distribution.length" class="py-8 text-center text-[11px] text-gray-400">
              No data.
            </div>
            <div v-else class="space-y-2.5 max-h-72 overflow-y-auto pr-1">
              <div v-for="row in stats.strand_distribution" :key="row.strand">
                <div class="flex items-center justify-between mb-1">
                  <span class="text-[11px] text-gray-700 dark:text-gray-300 truncate pr-2">{{ row.strand }}</span>
                  <span class="text-xs font-medium text-gray-900 dark:text-white shrink-0">{{ row.count }}</span>
                </div>
                <div class="h-1.5 rounded-full bg-gray-100 dark:bg-[#1C261E] overflow-hidden">
                  <div class="h-full rounded-full bg-sky-500"
                       :style="{ width: (row.count / maxStrandCount * 100) + '%' }"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-5">
            <div class="flex items-center justify-between mb-4">
              <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">Section Occupancy</h4>
              <span class="text-[10px] text-gray-400">{{ stats.section_occupancy.length }} sections</span>
            </div>

            <div v-if="!stats.section_occupancy.length" class="py-8 text-center text-[11px] text-gray-400">
              No sections.
            </div>
            <div v-else class="space-y-2.5 max-h-72 overflow-y-auto pr-1">
              <div v-for="s in stats.section_occupancy" :key="s.section">
                <div class="flex items-center justify-between mb-1 gap-2">
                  <span class="text-[11px] text-gray-700 dark:text-gray-300 truncate">{{ s.section }}</span>
                  <span class="text-[10px] font-medium shrink-0"
                        :class="s.percentage >= 95 ? 'text-red-600 dark:text-red-400'
                              : s.percentage >= 80 ? 'text-amber-600 dark:text-amber-400'
                              : 'text-emerald-600 dark:text-emerald-400'">
                    {{ s.enrolled }}/{{ s.capacity }}
                  </span>
                </div>
                <div class="h-1.5 rounded-full bg-gray-100 dark:bg-[#1C261E] overflow-hidden">
                  <div class="h-full rounded-full transition-all"
                       :class="s.percentage >= 95 ? 'bg-red-500'
                             : s.percentage >= 80 ? 'bg-amber-500'
                             : 'bg-emerald-500'"
                       :style="{ width: Math.min(100, s.percentage) + '%' }"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Grades + Attendance + Exam Counts -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-5">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-4">Grades</h4>
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-gray-600 dark:text-gray-300">Total Graded</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.grade_summary.total_graded || 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-emerald-600 dark:text-emerald-400">Passing</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.grade_summary.passing || 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-red-600 dark:text-red-400">Failing</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.grade_summary.failing || 0 }}</span>
              </div>
              <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
                <span class="text-[11px] text-gray-600 dark:text-gray-300">Average</span>
                <span class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ stats.grade_summary.average ?? '—' }}
                </span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-gray-600 dark:text-gray-300">Passing Rate</span>
                <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                  {{ stats.grade_summary.passing_rate ?? 0 }}%
                </span>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-5">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-4">Attendance</h4>
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-gray-600 dark:text-gray-300">Total Records</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.attendance_summary.total_records || 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-emerald-600 dark:text-emerald-400">Present</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.attendance_summary.present || 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-red-600 dark:text-red-400">Absent</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.attendance_summary.absent || 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-amber-600 dark:text-amber-400">Late</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.attendance_summary.late || 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-sky-600 dark:text-sky-400">Excused</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.attendance_summary.excused || 0 }}</span>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-5">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-4">Exams</h4>
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-gray-600 dark:text-gray-300">Total Scheduled</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.exam_stats.total || 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-sky-600 dark:text-sky-400">Upcoming</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.exam_stats.upcoming || 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-blue-600 dark:text-blue-400">Ongoing</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.exam_stats.ongoing || 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-emerald-600 dark:text-emerald-400">Completed</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.exam_stats.completed || 0 }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-[11px] text-gray-400">Cancelled</span>
                <span class="text-xs font-medium text-gray-900 dark:text-white">{{ stats.exam_stats.cancelled || 0 }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Applications + Recent Enrollments -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 dark:border-[#3F4F43] flex items-center justify-between">
              <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">Recent Applications</h4>
              <Link :href="route('admin.applicants.index')"
                    class="text-[10px] font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] hover:underline">
                View All
              </Link>
            </div>
            <div v-if="!stats.recent_applications.length" class="p-6 text-center text-[11px] text-gray-400">
              No applications.
            </div>
            <ul v-else class="divide-y divide-gray-100 dark:divide-[#3F4F43]">
              <li v-for="a in stats.recent_applications" :key="a.reference_number"
                  class="px-5 py-3 flex items-center justify-between gap-3">
                <div class="min-w-0">
                  <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ a.name }}</p>
                  <p class="text-[10px] text-gray-400 truncate">
                    <span class="font-mono">{{ a.reference_number }}</span>
                    <span v-if="a.strand"> &bull; {{ a.strand }}</span>
                  </p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[9px] font-medium uppercase tracking-wider border shrink-0"
                      :class="applicantBadge(a.status)">
                  {{ a.status.replace('_', ' ') }}
                </span>
              </li>
            </ul>
          </div>

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 dark:border-[#3F4F43] flex items-center justify-between">
              <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">Recent Enrollments</h4>
              <Link :href="route('admin.enrollments.index')"
                    class="text-[10px] font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] hover:underline">
                View All
              </Link>
            </div>
            <div v-if="!stats.recent_enrollments.length" class="p-6 text-center text-[11px] text-gray-400">
              No recent enrollments.
            </div>
            <ul v-else class="divide-y divide-gray-100 dark:divide-[#3F4F43]">
              <li v-for="e in stats.recent_enrollments" :key="e.student_name + e.enrolled_at"
                  class="px-5 py-3">
                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ e.student_name }}</p>
                <p class="text-[10px] text-gray-400 truncate">
                  {{ e.section || 'No section' }}
                  <span v-if="e.strand"> &bull; {{ e.strand }}</span>
                  <span v-if="e.enrolled_at"> &bull; {{ formatRelative(e.enrolled_at) }}</span>
                </p>
              </li>
            </ul>
          </div>
        </div>

        <!-- Upcoming Exams -->
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] overflow-hidden">
          <div class="px-5 py-3.5 border-b border-gray-100 dark:border-[#3F4F43] flex items-center justify-between">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">Upcoming Exams</h4>
            <Link :href="route('admin.entrance-exams.index')"
                  class="text-[10px] font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] hover:underline">
              View All
            </Link>
          </div>
          <div v-if="!stats.upcoming_exams.length" class="p-6 text-center text-[11px] text-gray-400">
            No upcoming exams scheduled.
          </div>
          <ul v-else class="divide-y divide-gray-100 dark:divide-[#3F4F43]">
            <li v-for="e in stats.upcoming_exams" :key="e.id"
                class="px-5 py-3 flex items-center justify-between gap-3">
              <div class="min-w-0">
                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ e.exam_name }}</p>
                <p class="text-[10px] text-gray-400 truncate">
                  {{ formatDate(e.exam_date) }} at {{ e.exam_time }}
                  <span v-if="e.venue"> &bull; {{ e.venue }}</span>
                  <span v-if="e.track"> &bull; {{ e.track }}</span>
                </p>
              </div>
              <span class="text-[10px] font-medium text-gray-500 dark:text-gray-400 shrink-0">
                {{ e.applicant_count }} assigned
              </span>
            </li>
          </ul>
        </div>

      </template>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  schoolYears:  { type: Array, default: () => [] },
  tracks:       { type: Array, default: () => [] },
  activeYearId: { type: Number, default: null },
})

const filters = reactive({
  school_year_id: '',
  track_id: '',
})

const stats = ref(null)
const isLoading = ref(false)
const showExport = ref(false)

const exportOptions = [
  { slug: 'enrollments', label: 'Enrollments' },
  { slug: 'applicants',  label: 'Applicants' },
  { slug: 'students',    label: 'Students' },
  { slug: 'teachers',    label: 'Teachers' },
]

onMounted(() => {
  if (props.activeYearId) filters.school_year_id = props.activeYearId
  fetchData()
})

const fetchData = async () => {
  isLoading.value = true
  const params = {}
  if (filters.school_year_id) params.school_year_id = filters.school_year_id
  if (filters.track_id)       params.track_id       = filters.track_id
  try {
    const { data } = await axios.get('/admin/reports/data', { params })
    stats.value = data
  } catch (e) {
    console.error('Failed to load reports:', e)
  } finally {
    isLoading.value = false
  }
}

const applicantPipelineRows = computed(() => {
  if (!stats.value) return []
  const s = stats.value.applicant_stats
  const total = s.total || 1
  const rows = [
    { key: 'pending',            label: 'Pending',            count: s.pending,            color: 'bg-amber-500' },
    { key: 'under_review',       label: 'Under Review',       count: s.under_review,       color: 'bg-blue-500' },
    { key: 'approved',           label: 'Approved',           count: s.approved,           color: 'bg-sky-500' },
    { key: 'enrolled',           label: 'Enrolled',           count: s.enrolled,           color: 'bg-emerald-500' },
    { key: 'needs_resubmission', label: 'Needs Resubmission', count: s.needs_resubmission, color: 'bg-orange-500' },
    { key: 'rejected',           label: 'Rejected',           count: s.rejected,           color: 'bg-red-500' },
  ]
  return rows.map(r => ({ ...r, percent: Math.round(((r.count || 0) / total) * 100) }))
})

const maxMonthlyCount = computed(() => {
  if (!stats.value?.monthly_trend?.length) return 1
  return Math.max(1, ...stats.value.monthly_trend.map(r => r.count))
})

const trendTotal = computed(() =>
  stats.value?.monthly_trend?.reduce((sum, r) => sum + r.count, 0) || 0
)

const maxStrandCount = computed(() => {
  if (!stats.value?.strand_distribution?.length) return 1
  return Math.max(1, ...stats.value.strand_distribution.map(r => r.count))
})

const shortMonth = (ym) => {
  if (!ym) return ''
  const [, m] = ym.split('-')
  return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][parseInt(m) - 1] || ym
}

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { month: 'short', day: 'numeric' }) }
  catch { return iso }
}

const formatRelative = (iso) => {
  if (!iso) return ''
  try {
    const diff = (Date.now() - new Date(iso).getTime()) / 1000
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago'
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago'
    return Math.floor(diff / 86400) + 'd ago'
  } catch { return '' }
}

const applicantBadge = (status) => ({
  pending:            'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
  under_review:       'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
  approved:           'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  enrolled:           'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  rejected:           'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
  needs_resubmission: 'bg-orange-50 dark:bg-orange-950/50 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-800',
}[status] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')
</script>