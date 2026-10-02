<template>
  <Head title="Official Electronic Class Record - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search grade records, student LRN, competencies..">

    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

      <!-- EMPTY STATE: no classes -->
      <div v-if="!classroom" class="bg-white dark:bg-[#2D3A31] rounded-3xl p-12 shadow-sm border border-dashed border-slate-300 dark:border-[#3F4F43] text-center space-y-3 mt-8">
        <Icon icon="chart-bar" size="xl" class="text-slate-400 mx-auto" />
        <p class="text-base font-bold text-slate-800 dark:text-white">No classes assigned</p>
        <p class="text-xs text-slate-500 dark:text-slate-400">You need at least one class assigned to see its gradebook.</p>
      </div>

      <template v-else>

        <!-- ═══════════════════════════════════════════════ -->
        <!-- HERO BANNER                                     -->
        <!-- ═══════════════════════════════════════════════ -->
        <div v-observe
          class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[280px] flex flex-col justify-center anim-fade-down print:hidden"
          style="animation-delay: 50ms;">
          <img :src="heroImage" alt="Gradebook Background"
            class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
          <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

          <div class="relative z-10 p-4 sm:p-8 md:p-10 flex flex-col justify-center space-y-4 sm:space-y-6">
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
              <span class="bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-[11px] md:text-xs font-black uppercase tracking-wider px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1">
                <span>🏛️</span> DEPED REGION IV-A • SDO DASMARIÑAS
              </span>
              <span class="bg-white/15 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm">
                🛡️ DepEd Order No. 8, s. 2015 Compliant
              </span>
              <span class="bg-white/15 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm">
                🎓 {{ classroom.subject || 'Subject' }} • {{ classroom.section || 'Section' }}
              </span>
            </div>

            <div class="space-y-2 sm:space-y-3 max-w-2xl">
              <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                Official Electronic Class Record (E-Class Record)
              </h2>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Automated computation for Senior High School STEM Strand. Live score transmutation,
                continuous transmuted MPS analytics, and direct export to DepEd SF-9 (Report Card) and
                SF-10.
              </p>
            </div>
          </div>
        </div>

        <!-- ═══════════════════════════════════════════════ -->
        <!-- 4 METRIC CARDS                                  -->
        <!-- ═══════════════════════════════════════════════ -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 print:hidden">

          <!-- CLASS WEIGHTED MEAN -->
          <div v-observe
            class="anim-slide-up col-span-1 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3 sm:space-y-4"
            style="animation-delay: 100ms;">
            <div class="flex items-start justify-between">
              <span class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[130px] leading-tight">
                CLASS WEIGHTED MEAN
              </span>
              <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-emerald-100/70 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                  <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z" />
                </svg>
              </div>
            </div>

            <div class="space-y-1 sm:space-y-2">
              <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-4xl font-black text-[#005506] dark:text-[#86EFAC]">
                  {{ classAverageCard.value }}
                </span>
              </div>
              <div>
                <span class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full inline-block">
                  {{ classAverageCard.label }}
                </span>
              </div>
            </div>

            <div class="flex flex-wrap items-center justify-between text-[10px] sm:text-xs font-bold pt-2 border-t border-slate-100 dark:border-[#3F4F43] gap-1">
              <span class="text-slate-500 dark:text-slate-400">Target: 85.0%</span>
              <span :class="classAverageCard.positive ? 'text-[#005506] dark:text-[#86EFAC] font-black' : 'text-rose-600 dark:text-rose-400 font-black'">
                {{ classAverageCard.delta }}
              </span>
            </div>
          </div>

          <!-- SUBMISSIONS COMPLETE -->
          <div v-observe
            class="anim-slide-up col-span-1 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3 sm:space-y-4"
            style="animation-delay: 200ms;">
            <div class="flex items-start justify-between">
              <span class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[130px] leading-tight">
                SUBMISSIONS COMPLETE
              </span>
              <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-amber-100/70 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                  <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-2 14l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                </svg>
              </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
              <div class="flex items-baseline">
                <span class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white">{{ submissionsCard.complete }}</span>
                <span class="text-xs sm:text-base font-bold text-slate-500 dark:text-slate-400">/{{ submissionsCard.total }}</span>
              </div>
              <span class="bg-[#FEF9E7] dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[10px] sm:text-xs font-bold px-2 sm:px-3 py-1 rounded-xl sm:rounded-2xl border border-amber-200/60 dark:border-amber-900/40 text-center leading-tight">
                {{ submissionsCard.percent }}%<br><span class="font-normal text-[9px] sm:text-[10px]">Logged</span>
              </span>
            </div>

            <div class="w-full h-2 sm:h-2.5 bg-slate-100 dark:bg-[#232D26] rounded-full overflow-hidden">
              <div class="h-full bg-[#F9C20C] rounded-full transition-all" :style="{ width: submissionsCard.percent + '%' }"></div>
            </div>
          </div>

          <!-- AT-RISK LEARNERS -->
          <div v-observe
            class="anim-slide-up col-span-1 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3 sm:space-y-4"
            style="animation-delay: 300ms;">
            <div class="flex items-start justify-between">
              <span class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[130px] leading-tight">
                AT-RISK LEARNERS
              </span>
              <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-rose-100/70 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                  <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" />
                </svg>
              </div>
            </div>

            <div class="flex items-center gap-1.5 sm:gap-2">
              <span class="text-2xl sm:text-4xl font-black text-rose-600 dark:text-rose-400">{{ summary.failing || 0 }}</span>
              <span class="bg-rose-100/80 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10px] sm:text-xs font-bold px-2 sm:px-3 py-0.5 sm:py-1 rounded-full border border-rose-200/60 dark:border-rose-900/40">
                Intervention
              </span>
            </div>

            <div class="flex flex-wrap items-center justify-between text-[10px] sm:text-xs font-bold pt-2 border-t border-slate-100 dark:border-[#3F4F43] gap-1">
              <span class="text-slate-500 dark:text-slate-400">Below 75 threshold</span>
              <span class="text-rose-600 dark:text-rose-400 font-black">
                {{ summary.failing > 0 ? 'Priority' : 'Clear' }}
              </span>
            </div>
          </div>

          <!-- SCORE SYNC STATUS -->
          <div v-observe
            class="anim-slide-up col-span-1 bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3 sm:space-y-4"
            style="animation-delay: 400ms;">
            <div class="flex items-start justify-between">
              <span class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 max-w-[130px] leading-tight">
                SCORE SYNC STATUS
              </span>
              <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-emerald-100/70 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                  <path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z" />
                </svg>
              </div>
            </div>

            <div class="flex items-center justify-between gap-1.5 sm:gap-2">
              <span class="text-lg sm:text-2xl font-extrabold text-slate-900 dark:text-white leading-tight">
                Auto-<br>saved
              </span>
              <span class="bg-[#005506] text-white text-[10px] sm:text-[11px] font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full shadow-sm">
                Secure
              </span>
            </div>

            <div class="flex flex-wrap items-center justify-between text-[10px] sm:text-xs font-bold pt-2 border-t border-slate-100 dark:border-[#3F4F43] gap-1">
              <span class="text-slate-500 dark:text-slate-400">Live compute</span>
              <span class="text-slate-900 dark:text-white font-black">{{ students.length }} rows</span>
            </div>
          </div>

        </div>

        <!-- ═══════════════════════════════════════════════ -->
        <!-- CLASS SELECTOR + WEIGHT SCHEME                   -->
        <!-- ═══════════════════════════════════════════════ -->
        <div v-observe
          class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-6 print:hidden"
          style="animation-delay: 450ms;">

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 flex-1">

            <!-- Class selector -->
            <div>
              <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">
                Select Class
              </label>
              <div class="relative">
                <select :value="classroom.id" @change="changeClass($event.target.value)" :disabled="switching"
                  class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-extrabold px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer disabled:opacity-60">
                  <option v-for="c in classrooms" :key="c.id" :value="c.id">
                    {{ c.subject }} — {{ c.section }}
                  </option>
                </select>
                <svg class="w-4 h-4 fill-current text-slate-500 absolute right-3 top-2.5 sm:top-3 pointer-events-none" viewBox="0 0 24 24">
                  <path d="M7 10l5 5 5-5z" />
                </svg>
              </div>
            </div>

            <!-- Subject code -->
            <div>
              <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">
                Subject Code
              </label>
              <div class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-extrabold px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
                {{ classroom.code || '—' }}
              </div>
            </div>

            <!-- Term -->
            <div>
              <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">
                Term Horizon
              </label>
              <div class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-extrabold px-3 py-2 sm:px-3.5 sm:py-2.5 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
                {{ classroom.term || activeTerm || '—' }}
              </div>
            </div>
          </div>

          <!-- Weight scheme -->
          <div class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] space-y-1.5 sm:space-y-2 shrink-0">
            <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">
              WEIGHT SCHEME
            </span>
            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
              <span class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[11px] sm:text-xs font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full border border-emerald-200/60 dark:border-emerald-900/40 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#005506] dark:bg-[#86EFAC]"></span>
                Written ({{ weights.written_work }}%)
              </span>
              <span class="bg-amber-100/80 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 text-[11px] sm:text-xs font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full border border-amber-200/60 dark:border-amber-900/40 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                Performance ({{ weights.performance_task }}%)
              </span>
              <span class="bg-emerald-100/80 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[11px] sm:text-xs font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full border border-emerald-200/60 dark:border-emerald-900/40 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#005506] dark:bg-[#86EFAC]"></span>
                Exam ({{ weights.quarterly_exam }}%)
              </span>
            </div>
          </div>
        </div>

        <!-- ═══════════════════════════════════════════════ -->
        <!-- MASTER TABLE                                    -->
        <!-- ═══════════════════════════════════════════════ -->
        <div v-observe
          class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4 sm:space-y-6 print:shadow-none print:border-none"
          style="animation-delay: 500ms;">

          <!-- Toolbar -->
          <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] print:hidden">

            <!-- Search -->
            <div class="relative flex-1 w-full min-w-0">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                <Icon icon="search" size="sm" />
              </span>
              <input v-model="search" type="text" placeholder="Search student by name or LRN..."
                class="w-full pl-10 pr-4 py-2 sm:py-2.5 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-sm" />
            </div>

            <!-- Actions: use flex-wrap, NO overflow, so absolute dropdowns render -->
            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 shrink-0">

              <!-- Filter dropdown — wrapped in a non-clipping parent -->
              <div class="relative">
                <button @click="showFilterMenu = !showFilterMenu"
                  class="w-9 h-9 rounded-xl bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center transition-colors shadow-sm"
                  :class="remarkFilter !== 'all' ? 'ring-2 ring-[#005506] dark:ring-[#86EFAC]' : ''">
                  <Icon icon="filter" size="sm" />
                </button>
                <div v-if="showFilterMenu"
                  class="absolute right-0 top-full mt-2 w-56 bg-white dark:bg-[#2D3A31] rounded-2xl shadow-xl border border-slate-200 dark:border-[#3F4F43] overflow-hidden z-50">
                  <div class="px-4 py-2.5 border-b border-slate-100 dark:border-[#3F4F43] bg-slate-50 dark:bg-[#232D26]">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Filter by Standing</span>
                  </div>
                  <button v-for="opt in remarkFilterOptions" :key="opt.id"
                    @click="remarkFilter = opt.id; showFilterMenu = false"
                    :class="[
                      'w-full text-left px-4 py-2.5 text-xs font-bold transition-colors border-b border-slate-100 dark:border-[#3F4F43] last:border-b-0 flex items-center justify-between gap-2',
                      remarkFilter === opt.id
                        ? 'bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC]'
                        : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-[#3F4F43]'
                    ]">
                    <span>{{ opt.label }}</span>
                    <span class="text-[10px] font-black bg-slate-200/80 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300 px-2 py-0.5 rounded-full">
                      {{ opt.count }}
                    </span>
                  </button>
                </div>
              </div>

              <!-- Export -->
              <a :href="route('exports.gradebook', classroom.id)" target="_blank"
                class="bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-extrabold px-2.5 sm:px-3.5 py-2 sm:py-2.5 rounded-xl border border-slate-200 dark:border-[#3F4F43] flex items-center gap-1.5 transition-colors shadow-sm">
                <Icon icon="download" size="xs" class="text-slate-500" />
                Export
              </a>

              <!-- Print -->
              <button @click="printGradebook"
                class="bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-extrabold px-2.5 sm:px-3.5 py-2 sm:py-2.5 rounded-xl border border-slate-200 dark:border-[#3F4F43] flex items-center gap-1.5 transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5 fill-current text-slate-500" viewBox="0 0 24 24">
                  <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z" />
                </svg>
                Print
              </button>
            </div>
          </div>

          <!-- Table -->
          <div v-if="pagedStudents.length"
            class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
            <table class="w-full min-w-[780px] text-center border-collapse text-xs font-medium">
              <thead>
                <tr class="bg-[#EAE7DF]/70 dark:bg-[#232D26] text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-300 border-b border-slate-200/80 dark:border-[#3F4F43]">
                  <th class="py-2.5 sm:py-3 px-2 sm:px-3 border-r border-slate-200/80 dark:border-[#3F4F43]" rowspan="2">No.</th>
                  <th class="py-2.5 sm:py-3 px-3 sm:px-4 text-left border-r border-slate-200/80 dark:border-[#3F4F43] min-w-[180px]" rowspan="2">Learner Details</th>

                  <th v-for="cat in categoryGroups" :key="cat.key"
                    :colspan="cat.assignments.length + 2"
                    :class="[
                      'py-2.5 sm:py-3 px-3 sm:px-4 border-r border-slate-200/80 dark:border-[#3F4F43]',
                      cat.color === 'amber'
                        ? 'bg-amber-50/80 dark:bg-amber-950/40 text-amber-900 dark:text-amber-300'
                        : 'bg-emerald-100/60 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC]'
                    ]">
                    {{ cat.label }} ({{ cat.weight }}%)
                  </th>

                  <th class="py-2.5 sm:py-3 px-2 sm:px-3 bg-[#EAE7DF] dark:bg-[#1E241E] border-r border-slate-200/80 dark:border-[#3F4F43]" rowspan="2">
                    Initial<br><span class="text-[9px] sm:text-[10px] font-normal text-slate-500">(100%)</span>
                  </th>
                  <th class="py-2.5 sm:py-3 px-3 sm:px-4 bg-[#005506] text-white dark:bg-[#86EFAC] dark:text-[#232D26] border-r border-slate-200/80 dark:border-[#3F4F43]" rowspan="2">
                    Transmuted<br>Grade
                  </th>
                  <th class="py-2.5 sm:py-3 px-3 sm:px-4 border-r border-slate-200/80 dark:border-[#3F4F43]" rowspan="2">
                    Standing
                  </th>
                </tr>

                <tr class="bg-[#F9F7F1] dark:bg-[#1E241E] text-[9px] sm:text-[10px] font-bold text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-[#3F4F43]">
                  <template v-for="cat in categoryGroups" :key="`sub-${cat.key}`">
                    <th v-for="a in cat.assignments" :key="a.id"
                      class="py-2 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43]">
                      {{ shortTitle(a.title) }}<br>
                      <span class="font-normal text-slate-400">/{{ formatPoints(a.points) }}</span>
                    </th>
                    <th :class="[
                      'py-2 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] font-black',
                      cat.color === 'amber' ? 'bg-amber-50/50 text-amber-900 dark:text-amber-300' : 'bg-emerald-50/50 text-[#005506] dark:text-[#86EFAC]'
                    ]">
                      Tot<br><span class="font-normal">/{{ formatPoints(categoryPossible(cat)) }}</span>
                    </th>
                    <th :class="[
                      'py-2 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] font-black',
                      cat.color === 'amber' ? 'bg-amber-100/50 text-amber-900 dark:text-amber-300' : 'bg-emerald-100/50 text-[#005506] dark:text-[#86EFAC]'
                    ]">
                      {{ cat.weight }}%
                    </th>
                  </template>
                </tr>
              </thead>

              <tbody class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-slate-700 dark:text-slate-200">
                <tr v-for="(student, idx) in pagedStudents" :key="student.id"
                  :class="[
                    'transition-colors',
                    student.remarks === 'did_not_meet_expectations'
                      ? 'bg-rose-50/40 dark:bg-rose-950/20 hover:bg-rose-50/70'
                      : 'hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40'
                  ]">

                  <td class="py-3 px-2 sm:px-3 font-bold border-r border-slate-200/80 dark:border-[#3F4F43]"
                    :class="student.remarks === 'did_not_meet_expectations' ? 'text-rose-700 dark:text-rose-400' : ''">
                    {{ String((page - 1) * perPage + idx + 1).padStart(2, '0') }}
                  </td>

                  <td class="py-3 px-3 sm:px-4 text-left border-r border-slate-200/80 dark:border-[#3F4F43]">
                    <p :class="[
                      'font-extrabold',
                      student.remarks === 'did_not_meet_expectations' ? 'text-rose-700 dark:text-rose-400' : 'text-slate-900 dark:text-white'
                    ]">{{ student.name || '—' }}</p>
                    <p class="text-[10px] sm:text-[11px] font-mono text-slate-400">
                      {{ student.lrn || '—' }}
                      <span v-if="student.remarks === 'did_not_meet_expectations'" class="text-rose-600 dark:text-rose-400 font-extrabold"> • Flagged</span>
                    </p>
                  </td>

                  <template v-for="cat in categoryGroups" :key="`cell-${student.id}-${cat.key}`">
                    <td v-for="a in cat.assignments" :key="`cell-${student.id}-${a.id}`"
                      class="py-3 px-1.5 sm:px-2 border-r border-slate-200 dark:border-[#3F4F43] font-bold"
                      :class="scoreColorText(student.grades?.[a.id]?.grade)">
                      {{ student.grades?.[a.id]?.grade !== undefined ? formatPoints(student.grades[a.id].grade) : '—' }}
                    </td>
                    <td :class="[
                      'py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] font-black',
                      student.remarks === 'did_not_meet_expectations'
                        ? 'bg-rose-100/50 text-rose-800'
                        : cat.color === 'amber' ? 'bg-amber-50/40 text-amber-900 dark:text-amber-300' : 'bg-emerald-50/40 text-[#005506] dark:text-[#86EFAC]'
                    ]">
                      {{ formatPoints(categoryTotals(student, cat).earned) }}
                    </td>
                    <td :class="[
                      'py-3 px-2 sm:px-3 border-r border-slate-200 dark:border-[#3F4F43] font-black',
                      student.remarks === 'did_not_meet_expectations'
                        ? 'bg-rose-100/80 text-rose-800'
                        : cat.color === 'amber' ? 'bg-amber-100/40 text-amber-900 dark:text-amber-300' : 'bg-emerald-100/40 text-[#005506] dark:text-[#86EFAC]'
                    ]">
                      {{ categoryWeighted(student, cat) }}
                    </td>
                  </template>

                  <td class="py-3 px-2 sm:px-3 font-bold border-r border-slate-200/80 dark:border-[#3F4F43] bg-slate-50 dark:bg-[#232D26]"
                    :class="student.remarks === 'did_not_meet_expectations' ? 'text-rose-900 bg-rose-100/40' : ''">
                    {{ formatPoints(student.final_grade) }}
                  </td>

                  <td class="py-3 px-3 sm:px-4 font-black text-base sm:text-lg border-r border-slate-200/80 dark:border-[#3F4F43]"
                    :class="transmutedClass(student)">
                    {{ transmute(student.final_grade) }}
                  </td>

                  <td class="py-3 px-3 sm:px-4">
                    <span :class="remarksBadge(student.remarks)"
                      class="text-[10px] sm:text-[11px] font-extrabold px-2 sm:px-3 py-0.5 sm:py-1 rounded-full inline-flex items-center gap-1 border whitespace-nowrap">
                      {{ remarksLabel(student.remarks) }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] p-12 rounded-2xl sm:rounded-3xl text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
            <Icon icon="users" size="xl" class="text-slate-400 mx-auto" />
            <p class="text-sm font-bold text-slate-800 dark:text-white">
              {{ students.length === 0 ? 'No students enrolled in this class' : 'No students match your filter' }}
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              {{ students.length === 0 ? 'Enroll learners to populate the class record.' : 'Try clearing the search or resetting the filter.' }}
            </p>
            <button v-if="students.length > 0 && (search || remarkFilter !== 'all')" @click="resetFilters"
              class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold px-4 py-2 rounded-xl hover:bg-[#004105] transition-colors">
              Reset Filters
            </button>
          </div>

          <!-- ═══════════════════════════════════════════════ -->
          <!-- PAGINATION FOOTER                                -->
          <!-- ═══════════════════════════════════════════════ -->
          <div v-if="filteredStudents.length > 0"
            class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 pt-2 print:hidden">

            <!-- Rows-per-page selector -->
            <div class="flex items-center gap-2 text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-semibold">
              <span>Show</span>
              <div class="relative">
                <select :value="perPage" @change="changePerPage($event.target.value)"
                  class="bg-white dark:bg-[#2D3A31] text-slate-800 dark:text-slate-200 text-xs font-extrabold pl-3 pr-8 py-1.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer">
                  <option :value="10">10</option>
                  <option :value="25">25</option>
                  <option :value="50">50</option>
                  <option :value="100">100</option>
                  <option :value="9999">All</option>
                </select>
                <svg class="w-3 h-3 fill-current text-slate-400 absolute right-2.5 top-2 pointer-events-none" viewBox="0 0 24 24">
                  <path d="M7 10l5 5 5-5z" />
                </svg>
              </div>
              <span>rows</span>
            </div>

            <!-- Count text -->
            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium text-center order-last sm:order-none w-full sm:w-auto">
              Showing <strong class="text-slate-800 dark:text-white">{{ pageStart }}</strong>
              to <strong class="text-slate-800 dark:text-white">{{ pageEnd }}</strong>
              of <strong class="text-slate-800 dark:text-white">{{ filteredStudents.length }}</strong> learners
              <span v-if="filteredStudents.length !== students.length" class="text-slate-400">
                (filtered from {{ students.length }})
              </span>
            </p>

            <!-- Page numbers -->
            <div v-if="totalPages > 1" class="flex items-center gap-1 sm:gap-1.5">
              <button @click="page = Math.max(1, page - 1)" :disabled="page === 1"
                :class="[
                  'px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] text-[11px] sm:text-xs font-bold transition-colors',
                  page === 1 ? 'text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-[#232D26] cursor-not-allowed' : 'text-slate-700 dark:text-slate-200 bg-white dark:bg-[#2D3A31] hover:bg-slate-100'
                ]">
                Prev
              </button>

              <button v-for="p in visiblePages" :key="p"
                :disabled="p === '...'"
                @click="p !== '...' && (page = p)"
                :class="[
                  'min-w-[32px] h-8 sm:min-w-[36px] sm:h-9 rounded-xl text-xs font-black flex items-center justify-center transition-colors',
                  p === '...'
                    ? 'text-slate-400 cursor-default'
                    : page === p
                      ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-sm'
                      : 'hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300'
                ]">
                {{ p }}
              </button>

              <button @click="page = Math.min(totalPages, page + 1)" :disabled="page === totalPages"
                :class="[
                  'px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] text-[11px] sm:text-xs font-bold transition-colors',
                  page === totalPages ? 'text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-[#232D26] cursor-not-allowed' : 'text-slate-700 dark:text-slate-200 bg-white dark:bg-[#2D3A31] hover:bg-slate-100'
                ]">
                Next
              </button>
            </div>

            <!-- Class Average -->
            <div class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium shrink-0 order-last sm:order-none">
              Avg:
              <strong class="text-[#005506] dark:text-[#86EFAC]">
                {{ summary.class_average ? parseFloat(summary.class_average).toFixed(2) : '—' }}
              </strong>
            </div>
          </div>
        </div>

        <!-- ═══════════════════════════════════════════════ -->
        <!-- ANALYTICS + TRANSMUTATION                       -->
        <!-- ═══════════════════════════════════════════════ -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8 print:hidden">

          <!-- COHORT DISTRIBUTION -->
          <div v-observe
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-6"
            style="animation-delay: 600ms;">

            <div class="space-y-4 sm:space-y-6">
              <div class="flex items-start justify-between gap-3 sm:gap-4">
                <div class="space-y-0.5 sm:space-y-1">
                  <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-[#005506] dark:text-[#86EFAC]">
                    Cohort Distribution
                  </span>
                  <h3 class="text-lg sm:text-2xl font-black text-slate-900 dark:text-white">Quarter Breakdown</h3>
                </div>
                <div class="bg-[#EAF3EC] dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border border-emerald-200 dark:border-emerald-900/40 px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-xl sm:rounded-2xl text-[11px] sm:text-xs font-extrabold shrink-0 shadow-sm text-center">
                  N = {{ students.length }} <br><span class="text-[9px] sm:text-[10px] font-medium">Learners</span>
                </div>
              </div>

              <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                Transmuted distributions based on DepEd DO 8, s. 2015 standards.
              </p>

              <div class="space-y-3 sm:space-y-4 pt-1 sm:pt-2">
                <div v-for="bucket in cohortDistribution" :key="bucket.label" class="space-y-1">
                  <div class="flex items-center justify-between text-[11px] sm:text-xs font-bold">
                    <span class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                      <span :class="['w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full', bucket.color]"></span>
                      {{ bucket.label }} ({{ bucket.sub }})
                    </span>
                    <span :class="bucket.text">{{ bucket.count }} ({{ bucket.percent }}%)</span>
                  </div>
                  <div class="w-full h-2.5 sm:h-3 rounded-full bg-slate-100 dark:bg-[#232D26] overflow-hidden">
                    <div :class="['h-full rounded-full transition-all duration-500', bucket.color]"
                      :style="{ width: bucket.percent + '%' }"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="pt-4 sm:pt-6 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[11px] sm:text-xs">
              <span class="font-bold text-slate-700 dark:text-slate-300">
                Passing Rate:
                <span class="text-[#005506] dark:text-[#86EFAC] font-black">
                  {{ passingRate }}%
                </span>
              </span>
              <span class="font-bold text-slate-500 dark:text-slate-400">
                {{ summary.failing || 0 }} below threshold
              </span>
            </div>
          </div>

          <!-- TRANSMUTATION TABLE -->
          <div v-observe
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-6"
            style="animation-delay: 700ms;">

            <div class="space-y-4 sm:space-y-6">
              <div class="flex items-start justify-between gap-4">
                <div class="space-y-0.5 sm:space-y-1">
                  <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-amber-700 dark:text-amber-400">
                    Official Matrix
                  </span>
                  <h3 class="text-lg sm:text-2xl font-black text-slate-900 dark:text-white">
                    DepEd Transmutation Table
                  </h3>
                </div>
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] text-slate-700 dark:text-slate-300 flex items-center justify-center shrink-0 shadow-sm">
                  <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                  </svg>
                </div>
              </div>

              <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                Standard DepEd Conversion scale from Initial Grade to Transmuted Quarterly Grade.
              </p>

              <div class="overflow-x-auto rounded-xl sm:rounded-2xl border border-slate-200/60 dark:border-[#3F4F43]">
                <table class="w-full text-left border-collapse text-xs font-medium">
                  <thead>
                    <tr class="bg-[#F9F7F1] dark:bg-[#232D26] text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-300 border-b border-slate-200/60 dark:border-[#3F4F43]">
                      <th class="py-2.5 sm:py-3 px-3 sm:px-4">Initial Score Range</th>
                      <th class="py-2.5 sm:py-3 px-3 sm:px-4 text-right">Transmuted Grade</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-slate-700 dark:text-slate-200 font-semibold text-[11px] sm:text-xs">
                    <tr v-for="row in transmutationRows" :key="row.grade" class="hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40">
                      <td class="py-2.5 sm:py-3 px-3 sm:px-4 font-mono font-bold text-slate-900 dark:text-white">
                        {{ row.range }}
                      </td>
                      <td class="py-2.5 sm:py-3 px-3 sm:px-4 text-right font-black text-[#005506] dark:text-[#86EFAC]">
                        {{ row.grade }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="pt-4 sm:pt-6 border-t border-slate-100 dark:border-[#3F4F43] flex items-center justify-between text-[11px] sm:text-xs">
              <span class="text-slate-500 dark:text-slate-400">Base 60 Transmutation</span>
              <span class="font-extrabold text-[#005506] dark:text-[#86EFAC]">
                DepEd DO 8, s. 2015
              </span>
            </div>
          </div>

        </div>

      </template>

    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/gradebook.png'

/* ═══════════════════════════════════════════════════════ */
/* PROPS                                                    */
/* ═══════════════════════════════════════════════════════ */
const props = defineProps({
  classrooms:  { type: Array,  default: () => [] },
  classroom:   { type: Object, default: null },
  weights:     { type: Object, default: () => ({ written_work: 0, performance_task: 0, quarterly_exam: 0 }) },
  assignments: { type: Array,  default: () => [] },
  students:    { type: Array,  default: () => [] },
  summary:     { type: Object, default: () => ({ total_students: 0, passing: 0, failing: 0, class_average: null }) },
  activeTerm:  { type: String, default: null },
})

/* ═══════════════════════════════════════════════════════ */
/* CLASS SWITCHING                                          */
/* ═══════════════════════════════════════════════════════ */
const switching = ref(false)

function changeClass(id) {
  if (!id || id === props.classroom?.id) return
  switching.value = true
  router.get(route('teacher.gradebook.index'), { class_id: id }, {
    preserveScroll: true,
    onFinish: () => { switching.value = false },
  })
}

/* ═══════════════════════════════════════════════════════ */
/* FILTERS                                                  */
/* ═══════════════════════════════════════════════════════ */
const search = ref('')
const remarkFilter = ref('all')
const showFilterMenu = ref(false)

const remarkFilterOptions = computed(() => [
  { id: 'all',     label: 'All Learners',  count: props.students.length },
  { id: 'passing', label: 'Passing',       count: props.students.filter(s => s.remarks && s.remarks !== 'did_not_meet_expectations').length },
  { id: 'failing', label: 'At Risk',       count: props.students.filter(s => s.remarks === 'did_not_meet_expectations').length },
])

const filteredStudents = computed(() => {
  let list = props.students
  const q = search.value.trim().toLowerCase()
  if (q) {
    list = list.filter(s =>
      (s.name || '').toLowerCase().includes(q) ||
      (s.lrn || '').toLowerCase().includes(q)
    )
  }
  if (remarkFilter.value === 'passing') {
    list = list.filter(s => s.remarks && s.remarks !== 'did_not_meet_expectations')
  } else if (remarkFilter.value === 'failing') {
    list = list.filter(s => s.remarks === 'did_not_meet_expectations')
  }
  return list
})

function resetFilters() {
  search.value = ''
  remarkFilter.value = 'all'
}

/* ═══════════════════════════════════════════════════════ */
/* PAGINATION                                               */
/* ═══════════════════════════════════════════════════════ */
const perPage = ref(10)
const page = ref(1)

// Reset to page 1 whenever filters change
watch([search, remarkFilter, perPage], () => { page.value = 1 })
watch(() => props.classroom?.id, () => { page.value = 1 })

const totalPages = computed(() => {
  if (perPage.value === 9999) return 1
  return Math.max(1, Math.ceil(filteredStudents.value.length / perPage.value))
})

const pageStart = computed(() =>
  filteredStudents.value.length === 0 ? 0 : (page.value - 1) * perPage.value + 1
)
const pageEnd = computed(() =>
  Math.min(page.value * perPage.value, filteredStudents.value.length)
)

const pagedStudents = computed(() => {
  if (perPage.value === 9999) return filteredStudents.value
  const start = (page.value - 1) * perPage.value
  return filteredStudents.value.slice(start, start + perPage.value)
})

const visiblePages = computed(() => {
  const total = totalPages.value
  const cur = page.value
  if (total <= 7) {
    return Array.from({ length: total }, (_, i) => i + 1)
  }
  const pages = [1]
  if (cur > 3) pages.push('...')
  for (let i = Math.max(2, cur - 1); i <= Math.min(total - 1, cur + 1); i++) {
    pages.push(i)
  }
  if (cur < total - 2) pages.push('...')
  pages.push(total)
  return pages
})

function changePerPage(val) {
  perPage.value = parseInt(val, 10)
}

/* ═══════════════════════════════════════════════════════ */
/* CATEGORY GROUPS                                          */
/* ═══════════════════════════════════════════════════════ */
const categoryGroups = computed(() => {
  const cats = [
    { key: 'written_work',     label: 'Written Works',     color: 'emerald' },
    { key: 'performance_task', label: 'Performance Tasks', color: 'amber' },
    { key: 'quarterly_exam',   label: 'Quarterly Exam',    color: 'emerald' },
  ]
  return cats
    .map(c => ({
      ...c,
      weight: props.weights?.[c.key] ?? 0,
      assignments: props.assignments.filter(a => a.category === c.key),
    }))
    .filter(c => c.assignments.length > 0)
})

function categoryPossible(cat) {
  return cat.assignments.reduce((sum, a) => sum + (parseFloat(a.points) || 0), 0)
}

function categoryTotals(student, cat) {
  let earned = 0
  cat.assignments.forEach(a => {
    const g = student.grades?.[a.id]
    if (g) earned += parseFloat(g.grade) || 0
  })
  return { earned }
}

function categoryWeighted(student, cat) {
  const avg = student[cat.key]
  if (avg === null || avg === undefined) return '—'
  const n = parseFloat(avg)
  if (isNaN(n)) return '—'
  return ((n / 100) * cat.weight).toFixed(1)
}

/* ═══════════════════════════════════════════════════════ */
/* METRIC CARDS                                             */
/* ═══════════════════════════════════════════════════════ */
const classAverageCard = computed(() => {
  const avg = props.summary?.class_average
  if (avg === null || avg === undefined) {
    return { value: '—', label: 'No Data', delta: '—', positive: true }
  }
  const n = parseFloat(avg)
  if (isNaN(n)) return { value: '—', label: 'No Data', delta: '—', positive: true }

  let label = 'Needs Improvement'
  if (n >= 90) label = 'Outstanding'
  else if (n >= 85) label = 'Very Satisfactory'
  else if (n >= 80) label = 'Satisfactory'
  else if (n >= 75) label = 'Fairly Satisfactory'

  const target = 85
  const diff = n - target
  const delta = (diff >= 0 ? '+' : '') + diff.toFixed(1) + '%'
  return { value: n.toFixed(1) + '%', label, delta, positive: diff >= 0 }
})

const submissionsCard = computed(() => {
  const total = props.students.length
  const complete = props.students.filter(s => s.is_complete).length
  const percent = total > 0 ? Math.round((complete / total) * 1000) / 10 : 0
  return { complete, total, percent }
})

/* ═══════════════════════════════════════════════════════ */
/* COHORT DISTRIBUTION                                      */
/* ═══════════════════════════════════════════════════════ */
const cohortDistribution = computed(() => {
  const buckets = [
    { label: '95 – 100', sub: 'Highest Honors',    color: 'bg-[#005506] dark:bg-[#86EFAC]', text: 'text-[#005506] dark:text-[#86EFAC]', min: 95, max: 100.01 },
    { label: '90 – 94',  sub: 'With Honors',       color: 'bg-[#F9C20C]',                   text: 'text-amber-700 dark:text-amber-400', min: 90, max: 95 },
    { label: '85 – 89',  sub: 'Very Satisfactory', color: 'bg-emerald-600',                 text: 'text-emerald-700 dark:text-emerald-400', min: 85, max: 90 },
    { label: '80 – 84',  sub: 'Satisfactory',      color: 'bg-slate-400',                   text: 'text-slate-600 dark:text-slate-400', min: 80, max: 85 },
    { label: 'Below 80', sub: 'Did Not Meet',      color: 'bg-red-600',                     text: 'text-red-600 dark:text-red-400', min: -1, max: 80 },
  ]
  const total = props.students.length
  return buckets.map(b => {
    const count = props.students.filter(s => {
      const g = parseFloat(s.final_grade)
      return !isNaN(g) && g >= b.min && g < b.max
    }).length
    return { ...b, count, percent: total > 0 ? Math.round((count / total) * 100) : 0 }
  })
})

const passingRate = computed(() => {
  const total = props.students.length
  if (total === 0) return 0
  const passing = props.students.filter(s => s.remarks && s.remarks !== 'did_not_meet_expectations').length
  return Math.round((passing / total) * 100)
})

/* ═══════════════════════════════════════════════════════ */
/* TRANSMUTATION                                            */
/* ═══════════════════════════════════════════════════════ */
const transmutationRows = computed(() => [
  { range: '100.00',         grade: 100 },
  { range: '98.40 – 99.99',  grade: 99  },
  { range: '96.80 – 98.39',  grade: 98  },
  { range: '95.20 – 96.79',  grade: 97  },
  { range: '93.60 – 95.19',  grade: 96  },
  { range: '92.00 – 93.59',  grade: 95  },
  { range: '90.40 – 91.99',  grade: 94  },
  { range: '88.80 – 90.39',  grade: 93  },
  { range: '87.20 – 88.79',  grade: 92  },
  { range: '85.60 – 87.19',  grade: 91  },
  { range: '84.00 – 85.59',  grade: 90  },
])

function transmute(initial) {
  if (initial === null || initial === undefined || initial === '') return '—'
  let n = parseFloat(initial)
  if (isNaN(n)) return '—'
  if (n >= 100) return 100
  if (n < 60) n = 60
  return Math.max(60, 100 - Math.ceil((100 - n) / 1.6))
}

function transmutedClass(student) {
  if (student.remarks === 'did_not_meet_expectations') return 'text-rose-700 dark:text-rose-400 bg-rose-100 dark:bg-rose-950/60'
  const t = transmute(student.final_grade)
  if (t === '—') return 'text-slate-400 bg-slate-50 dark:bg-[#232D26]'
  if (t >= 90) return 'text-[#005506] dark:text-[#86EFAC] bg-emerald-50 dark:bg-emerald-950/60'
  if (t >= 85) return 'text-amber-800 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40'
  if (t >= 75) return 'text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40'
  return 'text-rose-700 dark:text-rose-400 bg-rose-100 dark:bg-rose-950/60'
}

/* ═══════════════════════════════════════════════════════ */
/* HELPERS                                                  */
/* ═══════════════════════════════════════════════════════ */
function formatPoints(v) {
  if (v === null || v === undefined || v === '') return '—'
  const n = parseFloat(v)
  if (isNaN(n)) return '—'
  return Number.isInteger(n) ? String(n) : n.toFixed(1)
}

function shortTitle(title) {
  if (!title) return '—'
  const words = String(title).trim().split(/\s+/)
  if (words.length >= 2) {
    return words.slice(0, 3).map(w => w[0]).join('').toUpperCase()
  }
  return String(title).slice(0, 5)
}

function scoreColorText(v) {
  if (v === null || v === undefined || v === '') return 'text-slate-300 dark:text-slate-600'
  const n = parseFloat(v)
  if (isNaN(n)) return 'text-slate-300 dark:text-slate-600'
  if (n >= 90) return 'text-[#005506] dark:text-[#86EFAC]'
  if (n >= 75) return 'text-slate-700 dark:text-slate-200'
  return 'text-rose-600 dark:text-rose-400'
}

function remarksLabel(r) {
  return {
    outstanding:               '✔ Outstanding',
    very_satisfactory:         '✔ Very Satisfactory',
    satisfactory:              '✔ Satisfactory',
    fairly_satisfactory:       '✔ Fairly Satisfactory',
    did_not_meet_expectations: '⚠ Intervention',
  }[r] || '—'
}

function remarksBadge(r) {
  return {
    outstanding:               'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    very_satisfactory:         'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    satisfactory:              'bg-amber-100 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 border-amber-200 dark:border-amber-900/40',
    fairly_satisfactory:       'bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-900/40',
    did_not_meet_expectations: 'bg-rose-100 dark:bg-rose-950/80 text-rose-800 dark:text-rose-300 border-rose-200 dark:border-rose-900/40',
  }[r] || 'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'
}

function printGradebook() {
  window.print()
}

/* ═══════════════════════════════════════════════════════ */
/* ANIMATIONS                                               */
/* ═══════════════════════════════════════════════════════ */
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

@keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up { animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

@media print {
  .print\:hidden { display: none !important; }
}

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-sm :deep(.text-lg)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-sm :deep(.text-xl)   { font-size: 1.125rem !important; line-height: 1.75rem !important; }
.text-scale-sm :deep(.text-3xl)  { font-size: 1.5rem !important; line-height: 2rem !important; }
.text-scale-sm :deep(.sm\:text-4xl) { font-size: 1.875rem !important; line-height: 2.25rem !important; }
.text-scale-sm :deep(.md\:text-5xl) { font-size: 2.25rem !important; line-height: 1 !important; }

.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
.text-scale-lg :deep(.text-lg)   { font-size: 1.25rem !important; line-height: 1.75rem !important; }
.text-scale-lg :deep(.text-xl)   { font-size: 1.5rem !important; line-height: 2rem !important; }
.text-scale-lg :deep(.text-3xl)  { font-size: 2.25rem !important; line-height: 2.5rem !important; }
.text-scale-lg :deep(.sm\:text-4xl) { font-size: 2.75rem !important; line-height: 1 !important; }
.text-scale-lg :deep(.md\:text-5xl) { font-size: 3.5rem !important; line-height: 1 !important; }
</style>