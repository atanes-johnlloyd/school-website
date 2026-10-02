<template>
  <Head title="Teacher Daily Attendance - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search student attendance logs, LRN, remarks..">

    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

      <div v-if="!classroom"
        class="bg-white dark:bg-[#2D3A31] rounded-3xl p-12 shadow-sm border border-dashed border-slate-300 dark:border-[#3F4F43] text-center space-y-3 mt-8">
        <Icon icon="clipboard-check" size="xl" class="text-slate-400 mx-auto" />
        <p class="text-base font-bold text-slate-800 dark:text-white">No classes assigned</p>
        <p class="text-xs text-slate-500 dark:text-slate-400">You need at least one class assigned to mark attendance.</p>
      </div>

      <template v-else>

        <!-- HERO -->
        <div v-observe
          class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center anim-fade-down"
          style="animation-delay: 50ms;">
          <img :src="heroImage" alt="Daily Attendance Background"
            class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
          <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

          <div class="relative z-10 p-4 sm:p-8 md:p-10 flex flex-col justify-center space-y-4 sm:space-y-6">
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
              <span class="bg-[#005506] text-white border border-emerald-400/30 text-[10px] sm:text-[11px] md:text-xs font-bold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                <Icon icon="calendar" size="xs" />
                {{ today }}
              </span>
              <span class="bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-[11px] md:text-xs font-black uppercase tracking-wider px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                <Icon icon="check-circle" size="xs" />
                {{ active_term || 'Active Term' }}
              </span>
              <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full" :class="liveStats.unmarked > 0 ? 'bg-amber-400 animate-pulse' : 'bg-emerald-400'"></span>
                {{ liveStats.marked }}/{{ liveStats.enrolled }} marked
              </span>
            </div>

            <div class="space-y-2 sm:space-y-3 max-w-3xl">
              <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                Teacher Daily Attendance & RFID Log Tracker
              </h2>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Real-time student gate entry logs, automated advisory tracking, and daily presence
                verification for senior high school classes.
              </p>
            </div>

            <div class="flex flex-wrap items-center gap-y-2 gap-x-3 sm:gap-x-6 text-white/90 text-[11px] sm:text-sm font-semibold pt-2 border-t border-white/15">
              <div class="flex items-center gap-1.5 sm:gap-2">
                <Icon icon="academic-cap" size="sm" class="text-amber-300" />
                {{ classroom.section || '—' }}
              </div>
              <div class="hidden sm:inline text-white/40">•</div>
              <div class="flex items-center gap-1.5 sm:gap-2">
                <Icon icon="book-open" size="sm" class="text-emerald-300" />
                {{ classroom.subject || '—' }}
              </div>
              <div class="hidden sm:inline text-white/40">•</div>
              <div class="flex items-center gap-1.5 sm:gap-2">
                <Icon icon="calendar" size="sm" class="text-amber-300" />
                {{ formatLongDate(date) }}
              </div>
              <template v-if="classroom.building || classroom.room">
                <div class="hidden sm:inline text-white/40">•</div>
                <div class="flex items-center gap-1.5 sm:gap-2">
                  <Icon icon="school" size="sm" class="text-emerald-300" />
                  {{ [classroom.building, classroom.room].filter(Boolean).join(' • ') }}
                </div>
              </template>
            </div>
          </div>
        </div>

        <!-- CLASS + DATE SELECTOR -->
        <div v-observe
          class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col md:flex-row md:items-center justify-between gap-3"
          style="animation-delay: 80ms;">

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 flex-1">
            <div>
              <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">
                Class
              </label>
              <div class="relative">
                <select :value="classroom.id" @change="changeClass($event.target.value)" :disabled="switching"
                  class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-extrabold px-3 py-2.5 pr-8 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer disabled:opacity-60">
                  <option v-for="c in classrooms" :key="c.id" :value="c.id">
                    {{ c.subject }} — {{ c.section }}
                  </option>
                </select>
                <Icon icon="chevron-down" size="xs" class="absolute right-3 top-3.5 text-slate-400 pointer-events-none" />
              </div>
            </div>

            <div>
              <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">
                Date
              </label>
              <input type="date" :value="date" @change="changeDate($event.target.value)" :max="todayIso"
                class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-200 text-xs font-extrabold px-3 py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] cursor-pointer" />
            </div>
          </div>

          <div class="flex items-center gap-2 shrink-0">
            <button @click="markAllPresent" :disabled="savingAll"
              class="flex-1 md:flex-none bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl shadow-sm transition-all active:scale-95 disabled:opacity-60 flex items-center justify-center gap-2">
              <Icon icon="check-circle" size="xs" />
              {{ savingAll ? 'Saving...' : 'Mark All Present' }}
            </button>
          </div>
        </div>

        <!-- 5-CARD METRICS (LIVE from local state) -->
        <div class="relative z-10 grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-5">

          <!-- ENROLLED -->
          <div v-observe
            class="anim-slide-up col-span-1 bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-6"
            style="animation-delay: 100ms;">
            <div class="space-y-2 sm:space-y-4">
              <div class="flex items-start justify-between">
                <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                  Enrolled<br>Roster
                </span>
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-700">
                  <Icon icon="users" size="md" />
                </div>
              </div>
              <div class="flex items-baseline gap-1.5 sm:gap-2">
                <span class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white">{{ liveStats.enrolled }}</span>
                <span class="text-[10px] sm:text-xs font-medium text-slate-500 dark:text-slate-400">Learners</span>
              </div>
            </div>
            <div class="pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43] flex flex-wrap items-center justify-between text-[10px] sm:text-xs gap-1">
              <span class="text-slate-500 dark:text-slate-400">{{ classroom.section || '—' }}</span>
              <span class="font-bold text-slate-800 dark:text-white">{{ genderCounts.female }}F / {{ genderCounts.male }}M</span>
            </div>
          </div>

          <!-- PRESENT -->
          <div v-observe
            class="anim-slide-up col-span-1 bg-[#F2FCF4] dark:bg-emerald-950/30 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-emerald-200/80 dark:border-emerald-900/40 flex flex-col justify-between space-y-4 sm:space-y-6"
            style="animation-delay: 200ms;">
            <div class="space-y-2 sm:space-y-4">
              <div class="flex items-start justify-between">
                <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-[#005506] dark:text-[#86EFAC]">Present<br>(Verified)</span>
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-200 dark:border-emerald-800">
                  <Icon icon="check-circle" size="md" />
                </div>
              </div>
              <div class="flex items-baseline gap-2 sm:gap-3">
                <span class="text-2xl sm:text-4xl font-black text-[#005506] dark:text-[#86EFAC]">{{ liveStats.present }}</span>
                <span class="text-[10px] sm:text-[11px] font-extrabold bg-emerald-200/60 dark:bg-emerald-900/60 text-[#005506] dark:text-[#86EFAC] px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full">
                  {{ presentRate }}%
                </span>
              </div>
            </div>
            <div class="pt-3 sm:pt-4 border-t border-emerald-200/60 dark:border-emerald-900/40 flex items-center justify-between text-[10px] sm:text-xs font-bold">
              <span class="text-[#005506] dark:text-[#86EFAC]">In-Seat</span>
              <span class="text-emerald-800 dark:text-[#86EFAC]">
                {{ liveStats.unmarked === 0 ? 'Complete' : `${liveStats.unmarked} pending` }}
              </span>
            </div>
          </div>

          <!-- TARDY -->
          <div v-observe
            class="anim-slide-up col-span-1 bg-[#FFFDF4] dark:bg-amber-950/20 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-amber-200/80 dark:border-amber-900/40 flex flex-col justify-between space-y-4 sm:space-y-6"
            style="animation-delay: 300ms;">
            <div class="space-y-2 sm:space-y-4">
              <div class="flex items-start justify-between">
                <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-amber-700 dark:text-amber-400">Tardy<br>/ Late</span>
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-200 dark:border-amber-800">
                  <Icon icon="clock" size="md" />
                </div>
              </div>
              <div class="flex items-baseline gap-2 sm:gap-3">
                <span class="text-2xl sm:text-4xl font-black text-amber-600 dark:text-amber-400">{{ liveStats.late }}</span>
                <span class="text-[9px] sm:text-[10px] font-black bg-amber-200/60 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full uppercase tracking-wider">
                  {{ liveStats.late === 0 ? 'No late' : 'Grace' }}
                </span>
              </div>
            </div>
            <div class="pt-3 sm:pt-4 border-t border-amber-200/60 dark:border-amber-900/40 flex items-center justify-between text-[10px] sm:text-xs font-bold">
              <span class="text-amber-800 dark:text-amber-400">Same-day</span>
              <span class="text-orange-600 dark:text-orange-400">{{ liveStats.late > 0 ? 'SMS log' : '—' }}</span>
            </div>
          </div>

          <!-- EXCUSED -->
          <div v-observe
            class="anim-slide-up col-span-1 bg-[#F4F8FE] dark:bg-sky-950/20 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-sky-200/80 dark:border-sky-900/40 flex flex-col justify-between space-y-4 sm:space-y-6"
            style="animation-delay: 400ms;">
            <div class="space-y-2 sm:space-y-4">
              <div class="flex items-start justify-between">
                <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-sky-700 dark:text-sky-400">Excused<br>Slip</span>
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-sky-100 dark:bg-sky-900/50 text-sky-700 dark:text-sky-400 flex items-center justify-center shrink-0 border border-sky-200 dark:border-sky-800">
                  <Icon icon="document-text" size="md" />
                </div>
              </div>
              <div class="flex items-baseline gap-2 sm:gap-3">
                <span class="text-2xl sm:text-4xl font-black text-sky-700 dark:text-sky-400">{{ liveStats.excused }}</span>
                <span class="text-[9px] sm:text-[10px] font-bold bg-sky-100 dark:bg-sky-900/60 text-sky-800 dark:text-sky-300 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full">
                  {{ liveStats.excused === 0 ? 'None' : 'With slip' }}
                </span>
              </div>
            </div>
            <div class="pt-3 sm:pt-4 border-t border-sky-200/60 dark:border-sky-900/40 flex items-center justify-between text-[10px] sm:text-xs font-bold">
              <span class="text-sky-800 dark:text-sky-300">Verified</span>
              <span class="text-sky-900 dark:text-sky-200 font-extrabold">{{ liveStats.excused > 0 ? 'Guardian' : 'Clean' }}</span>
            </div>
          </div>

          <!-- ABSENT -->
          <div v-observe
            class="anim-slide-up col-span-2 sm:col-span-2 lg:col-span-1 bg-[#FEF2F2] dark:bg-rose-950/20 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-rose-200/80 dark:border-rose-900/40 flex flex-col justify-between space-y-4 sm:space-y-6"
            style="animation-delay: 500ms;">
            <div class="space-y-2 sm:space-y-4">
              <div class="flex items-start justify-between">
                <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-rose-700 dark:text-rose-400">Absent / Unexcused</span>
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-200 dark:border-rose-800">
                  <Icon icon="x-circle" size="md" />
                </div>
              </div>
              <div class="flex items-baseline gap-2 sm:gap-3">
                <span class="text-2xl sm:text-4xl font-black text-rose-700 dark:text-rose-400">{{ liveStats.absent }}</span>
                <span class="text-[10px] sm:text-[11px] font-bold px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full"
                  :class="liveStats.absent === 0 ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC]' : 'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300'">
                  {{ absentRate }}% Rate
                </span>
              </div>
            </div>
            <div class="pt-3 sm:pt-4 border-t border-rose-200/60 dark:border-rose-900/40 flex items-center justify-between text-[10px] sm:text-xs font-bold">
              <span class="text-slate-600 dark:text-slate-400">{{ liveStats.absent === 0 ? 'Zero Cutting' : 'Follow up' }}</span>
              <span :class="liveStats.absent === 0 ? 'text-[#005506] dark:text-[#86EFAC]' : 'text-rose-600 dark:text-rose-400 font-black'">
                {{ liveStats.absent === 0 ? 'Perfect Class' : 'Priority' }}
              </span>
            </div>
          </div>

        </div>

        <!-- SF-2 ROSTER -->
        <div v-observe
          class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4 sm:space-y-6"
          style="animation-delay: 600ms;">

          <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 sm:gap-4 bg-[#F9F7F1] dark:bg-[#232D26] p-3 sm:p-4 rounded-2xl border border-slate-200/60 dark:border-[#3F4F43]">
            <div class="relative w-full md:w-96">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                <Icon icon="search" size="sm" />
              </span>
              <input v-model="search" type="text" placeholder="Search learner by name, LRN, or seat..."
                class="w-full pl-10 pr-4 py-2 sm:py-2.5 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#006907] text-xs text-slate-700 dark:text-slate-200 shadow-sm" />
            </div>

            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
              <button v-for="f in statusFilters" :key="f.id" @click="statusFilter = f.id"
                :class="[
                  'px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-bold shadow-sm transition-all active:scale-95',
                  statusFilter === f.id
                    ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                    : 'bg-white dark:bg-[#2D3A31] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200 dark:border-[#3F4F43]'
                ]">
                {{ f.label }} ({{ f.count }})
              </button>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 border-b border-slate-100 dark:border-[#3F4F43] pb-3 sm:pb-4">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                <Icon icon="clipboard-check" size="md" />
              </div>
              <div>
                <h3 class="text-sm sm:text-lg font-black text-slate-800 dark:text-white">Learners Daily SF–2 Roster</h3>
                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                  DepEd SF-2 Sequence • Click P / L / A / E to mark
                </p>
              </div>
            </div>

            <div class="self-start sm:self-auto bg-[#FEF9E7] dark:bg-amber-950/40 border border-amber-200/60 dark:border-amber-900/40 px-3 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-bold text-amber-900 dark:text-amber-300 flex items-center gap-2">
              <span class="font-black text-amber-600 dark:text-amber-400">1–Tap:</span>
              <span class="bg-white dark:bg-amber-900 px-2 py-0.5 rounded shadow-sm">P • L • A • E</span>
            </div>
          </div>

          <div v-if="filteredStudents.length"
            class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl sm:rounded-2xl border border-slate-200/60 dark:border-[#3F4F43]">
            <table class="w-full min-w-[780px] text-left border-collapse">
              <thead>
                <tr class="bg-[#F9F7F1] dark:bg-[#232D26] text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-[#3F4F43]">
                  <th class="py-3 px-3 sm:px-4 w-12 text-center">#</th>
                  <th class="py-3 px-3 sm:px-4">Learner & LRN</th>
                  <th class="py-3 px-3 sm:px-4">Sync Status</th>
                  <th class="py-3 px-3 sm:px-4">Period Status</th>
                  <th class="py-3 px-3 sm:px-4">Notes</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-xs font-medium text-slate-700 dark:text-slate-200">
                <tr v-for="(student, idx) in filteredStudents" :key="student.id"
                  :class="[
                    'transition-colors',
                    student.status === 'absent' ? 'bg-rose-50/40 dark:bg-rose-950/20 hover:bg-rose-50/70' :
                    student.status === 'late'   ? 'bg-amber-50/30 dark:bg-amber-950/10 hover:bg-amber-50/60' :
                    'hover:bg-slate-50/60 dark:hover:bg-[#232D26]/40'
                  ]">

                  <td class="py-3 sm:py-4 px-3 sm:px-4 text-center font-bold text-slate-400">
                    {{ String(idx + 1).padStart(2, '0') }}
                  </td>

                  <td class="py-3 sm:py-4 px-3 sm:px-4">
                    <div class="flex items-center gap-2.5 sm:gap-3">
                      <span aria-hidden="true"
                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 text-[9px] sm:text-[10px] font-bold flex items-center justify-center shrink-0 border border-slate-200 dark:border-[#3F4F43]">
                        {{ initialsOf(student.name) }}
                      </span>
                      <div class="min-w-0">
                        <p class="font-bold text-slate-900 dark:text-white text-xs truncate">{{ student.name || '—' }}</p>
                        <p class="text-[10px] sm:text-[11px] text-slate-500 font-mono truncate">
                          LRN: {{ student.lrn || '—' }}
                        </p>
                      </div>
                    </div>
                  </td>

                  <td class="py-3 sm:py-4 px-3 sm:px-4">
                    <div v-if="student.marked_at" class="flex items-start gap-1.5 text-emerald-700 dark:text-emerald-400 font-bold">
                      <Icon icon="check-circle" size="sm" class="shrink-0 mt-0.5" />
                      <div>
                        <span class="block text-xs">Saved</span>
                        <span class="text-[10px] sm:text-[11px] font-normal text-slate-500 dark:text-slate-400 block">
                          {{ formatTimeAgo(student.marked_at) }}
                        </span>
                      </div>
                    </div>
                    <div v-else class="flex items-start gap-1.5 text-slate-400 font-medium">
                      <Icon icon="clock" size="sm" class="shrink-0 mt-0.5" />
                      <div>
                        <span class="block text-xs text-slate-600 dark:text-slate-300">Not marked</span>
                        <span class="text-[10px] sm:text-[11px] text-slate-400 block">Pending entry</span>
                      </div>
                    </div>
                  </td>

                  <td class="py-3 sm:py-4 px-3 sm:px-4">
                    <div class="flex items-center gap-1 font-bold">
                      <button v-for="opt in statusOptions" :key="opt.id"
                        @click="setStatus(student.id, opt.id)"
                        :title="opt.label"
                        :class="[
                          'w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-[10px] sm:text-[11px] font-black transition-all active:scale-95',
                          student.status === opt.id
                            ? opt.activeClass
                            : 'bg-slate-100 dark:bg-[#3F4F43] text-slate-400 hover:bg-slate-200 dark:hover:bg-[#4a5c50]'
                        ]">
                        {{ opt.letter }}
                      </button>
                    </div>
                  </td>

                  <td class="py-3 sm:py-4 px-3 sm:px-4 text-slate-600 dark:text-slate-400 text-[10px] sm:text-[11px]">
                    <input v-model="student.notes" @change="saveNote(student)" type="text" placeholder="Add note…"
                      class="w-full bg-transparent border-b border-dashed border-slate-300 dark:border-[#3F4F43] focus:outline-none focus:border-[#005506] text-[11px] text-slate-700 dark:text-slate-200 py-1" />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-12 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
            <Icon icon="users" size="xl" class="text-slate-400 mx-auto" />
            <p class="text-sm font-bold text-slate-800 dark:text-white">
              {{ students.length === 0 ? 'No students enrolled in this class' : 'No students match your filter' }}
            </p>
            <button v-if="students.length > 0 && (search || statusFilter !== 'all')" @click="resetFilters"
              class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold px-4 py-2 rounded-xl hover:bg-[#004105] transition-colors">
              Reset Filters
            </button>
          </div>

          <div class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 pt-2">
            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium text-center sm:text-left">
              Showing <strong class="text-slate-800 dark:text-white">{{ filteredStudents.length }}</strong>
              of <strong class="text-slate-800 dark:text-white">{{ students.length }}</strong> learners
              •
              <span :class="liveStats.unmarked === 0 ? 'text-emerald-700 dark:text-emerald-400 font-bold' : 'text-amber-700 dark:text-amber-400 font-bold'">
                {{ liveStats.unmarked === 0 ? '✓ All marked' : `${liveStats.unmarked} awaiting` }}
              </span>
            </p>

            <div v-if="saving" class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-500 font-bold flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
              Saving changes…
            </div>
            <div v-else class="text-[11px] sm:text-xs text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-2">
              <Icon icon="check-circle" size="xs" />
              All changes saved
            </div>
          </div>

        </div>

        <!-- ANALYTICS -->
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

          <!-- SF-2 SYNC -->
          <div v-observe
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-6"
            style="animation-delay: 700ms;">
            <div class="space-y-3 sm:space-y-4">
              <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/30">
                    <Icon icon="document-text" size="md" />
                  </div>
                  <div>
                    <h3 class="text-sm sm:text-base font-black text-slate-800 dark:text-white">DepEd Form SF–2 Sync</h3>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">Daily Track</p>
                  </div>
                </div>
              </div>

              <p class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                Daily Attendance Report of Senior High School Learners under DepEd Cavite Attendance Management.
              </p>

              <div class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-xl sm:rounded-2xl p-3 sm:p-4 border border-slate-200/80 dark:border-[#3F4F43] space-y-3 sm:space-y-4">
                <div class="flex items-center justify-between text-[11px] sm:text-xs font-black">
                  <span class="text-slate-700 dark:text-slate-300">{{ currentMonthLabel }} Daily Track</span>
                  <span class="text-[#005506] dark:text-[#86EFAC]">{{ monthly_sessions }} of 21 days</span>
                </div>

                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                  <template v-if="weekChips.length">
                    <div v-for="chip in weekChips" :key="chip.date"
                      :class="[
                        'w-9 h-10 sm:w-10 sm:h-11 rounded-lg sm:rounded-xl flex flex-col items-center justify-center text-[9px] sm:text-[10px] font-bold shadow-sm transition-all',
                        chip.isToday
                          ? 'bg-[#F9C20C] text-[#2C3E2D] border border-amber-300'
                          : 'bg-white dark:bg-[#2D3A31] border border-emerald-200 dark:border-emerald-900/50 text-[#005506] dark:text-[#86EFAC]'
                      ]">
                      <span>{{ chip.label }}</span>
                      <span class="text-[10px] sm:text-xs">{{ chip.isToday ? '📅' : '✓' }}</span>
                    </div>
                  </template>
                  <template v-else>
                    <div class="text-[11px] text-slate-400 dark:text-slate-500 italic py-2">
                      No sessions logged this month yet.
                    </div>
                  </template>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-200/60 dark:border-[#3F4F43]">
                  <span class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">Month-to-Date ADA:</span>
                  <span class="text-sm sm:text-base font-black text-[#005506] dark:text-[#86EFAC]">{{ monthAda }}%</span>
                </div>
              </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
              <Link :href="route('teacher.classes.attendance.index', classroom.id)"
                class="flex-1 bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-800 dark:text-slate-200 border border-slate-200/80 dark:border-[#3F4F43] py-2 sm:py-2.5 px-3 sm:px-4 rounded-xl text-[11px] sm:text-xs font-bold transition-colors text-center">
                View All Sessions
              </Link>
              <button @click="printSf2"
                class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-800 dark:text-slate-200 border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center transition-colors shrink-0">
                <Icon icon="download" size="sm" />
              </button>
            </div>
          </div>

          <!-- TREND GRAPH -->
          <div v-observe
            class="anim-slide-up bg-white/90 dark:bg-[#2D3A31]/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-4 sm:space-y-6"
            style="animation-delay: 800ms;">
            <div class="space-y-3 sm:space-y-4">
              <div class="flex items-start justify-between gap-3 sm:gap-4">
                <div>
                  <h3 class="text-sm sm:text-base font-black text-slate-800 dark:text-white leading-tight">
                    {{ classroom.section }} Attendance Trend
                  </h3>
                  <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                    Last 7 sessions • Division target: 95.0%
                  </p>
                </div>

                <div class="bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border border-emerald-200/60 dark:border-emerald-900/40 px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-xl sm:rounded-2xl text-center shrink-0 shadow-sm">
                  <span class="text-xs sm:text-sm font-black block">{{ monthAda }}%</span>
                  <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider block">Avg</span>
                </div>
              </div>

              <div class="relative w-full h-28 sm:h-36 pt-2">
                <svg class="w-full h-full overflow-visible" viewBox="0 0 300 100" preserveAspectRatio="none">
                  <defs>
                    <linearGradient id="attendanceGradient" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" stop-color="#005506" stop-opacity="0.25" />
                      <stop offset="100%" stop-color="#005506" stop-opacity="0.0" />
                    </linearGradient>
                  </defs>

                  <line x1="0" y1="30" x2="300" y2="30" stroke="currentColor" class="text-slate-200 dark:text-[#3F4F43]" stroke-dasharray="3 3" stroke-width="1" />
                  <line x1="0" y1="65" x2="300" y2="65" stroke="currentColor" class="text-slate-200 dark:text-[#3F4F43]" stroke-dasharray="3 3" stroke-width="1" />

                  <polygon v-if="trendPolygon" :points="trendPolygon" fill="url(#attendanceGradient)" />
                  <polyline v-if="trendPolyline" fill="none" stroke="#005506" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" :points="trendPolyline" />

                  <circle v-if="trendPoints.length" :cx="trendPoints[trendPoints.length - 1].x" :cy="trendPoints[trendPoints.length - 1].y" r="4" fill="#F9C20C" stroke="#005506" stroke-width="2" />
                </svg>
                <div v-if="!trendPoints.length" class="absolute inset-0 flex items-center justify-center text-xs text-slate-400 dark:text-slate-500 italic">
                  No session data yet
                </div>
              </div>
            </div>

            <div class="grid grid-cols-4 gap-1.5 sm:gap-2 pt-3 sm:pt-4 border-t border-slate-100 dark:border-[#3F4F43] text-center">
              <div v-for="(w, i) in trendCells" :key="i" class="space-y-0.5"
                :class="i === trendCells.length - 1 ? 'bg-emerald-50 dark:bg-emerald-950/40 rounded-lg sm:rounded-xl p-1 border border-emerald-100 dark:border-emerald-900/30' : ''">
                <span class="text-[9px] sm:text-[10px] font-bold block"
                  :class="i === trendCells.length - 1 ? 'text-[#005506] dark:text-[#86EFAC]' : 'text-slate-400 dark:text-slate-500'">
                  {{ w.label }}
                </span>
                <span class="text-[11px] sm:text-xs font-bold block"
                  :class="i === trendCells.length - 1 ? 'font-black text-[#005506] dark:text-[#86EFAC]' : 'text-slate-700 dark:text-slate-300'">
                  {{ w.value }}
                </span>
              </div>
            </div>
          </div>

        </div>
      </template>

    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/attendace.png'

const props = defineProps({
  classrooms:      { type: Array,  default: () => [] },
  classroom:       { type: Object, default: null },
  date:            { type: String, default: null },
  students:        { type: Array,  default: () => [] },
  stats:           { type: Object, default: null },
  weekly_sessions: { type: Array,  default: () => [] },
  monthly_sessions:{ type: Number, default: 0 },
  today:           { type: String, default: null },
  active_term:     { type: String, default: null },
})

const students = ref(props.students.map(s => ({ ...s })))
watch(() => props.students, (val) => { students.value = val.map(s => ({ ...s })) }, { deep: true })

const search = ref('')
const statusFilter = ref('all')
const switching = ref(false)
const savingAll = ref(false)
const saving = ref(false)

let saveTimer = null
let saveQueue = new Set()

/* ─── Live stats derived from local state ─────────────── */
const liveStats = computed(() => {
  const list = students.value
  let present = 0, late = 0, absent = 0, excused = 0, unmarked = 0
  list.forEach(s => {
    switch (s.status) {
      case 'present': present++; break
      case 'late':    late++;    break
      case 'absent':  absent++;  break
      case 'excused': excused++; break
      default:        unmarked++; break
    }
  })
  return {
    enrolled: list.length,
    present, late, absent, excused, unmarked,
    marked: list.length - unmarked,
  }
})

/* ─── Navigation ──────────────────────────────────────── */
function changeClass(id) {
  if (!id || id === props.classroom?.id) return
  switching.value = true
  router.get(route('teacher.attendance.index'), { class_id: id, date: props.date }, {
    preserveScroll: true,
    onFinish: () => { switching.value = false },
  })
}

function changeDate(val) {
  if (!val || val === props.date) return
  switching.value = true
  router.get(route('teacher.attendance.index'), { class_id: props.classroom?.id, date: val }, {
    preserveScroll: true,
    onFinish: () => { switching.value = false },
  })
}

/* ─── Status + autosave ───────────────────────────────── */
const statusOptions = [
  { id: 'present', letter: 'P', label: 'Present',  activeClass: 'bg-[#005506] text-white shadow-sm' },
  { id: 'late',    letter: 'L', label: 'Late',     activeClass: 'bg-orange-600 text-white shadow-sm' },
  { id: 'absent',  letter: 'A', label: 'Absent',   activeClass: 'bg-rose-600 text-white shadow-sm' },
  { id: 'excused', letter: 'E', label: 'Excused',  activeClass: 'bg-blue-600 text-white shadow-sm' },
]

function setStatus(studentId, status) {
  const s = students.value.find(x => x.id === studentId)
  if (!s) return
  s.status = status
  s.marked_at = new Date().toISOString()
  queueSave()
}

function saveNote() {
  queueSave()
}

function queueSave() {
  saving.value = true
  clearTimeout(saveTimer)
  saveTimer = setTimeout(flushSave, 400)
}

async function flushSave() {
  const records = students.value
    .filter(s => s.status)
    .map(s => ({
      student_id: s.id,
      status: s.status,
      notes: s.notes || null,
    }))

  if (records.length === 0) { saving.value = false; return }

  try {
    await axios.post(
      route('teacher.classes.attendance.mark', { classroom: props.classroom.id, date: props.date }),
      { records }
    )
  } catch (e) {
    console.error('Save failed', e)
  } finally {
    saving.value = false
  }
}

async function markAllPresent() {
  savingAll.value = true
  const now = new Date().toISOString()
  students.value.forEach(s => {
    s.status = 'present'
    s.marked_at = now
  })
  await flushSave()
  savingAll.value = false
}

/* ─── Filters ─────────────────────────────────────────── */
const statusFilters = computed(() => [
  { id: 'all',      label: 'All',      count: students.value.length },
  { id: 'present',  label: 'Present',  count: students.value.filter(s => s.status === 'present').length },
  { id: 'late',     label: 'Late',     count: students.value.filter(s => s.status === 'late').length },
  { id: 'absent',   label: 'Absent',   count: students.value.filter(s => s.status === 'absent').length },
  { id: 'excused',  label: 'Excused',  count: students.value.filter(s => s.status === 'excused').length },
  { id: 'unmarked', label: 'Unmarked', count: students.value.filter(s => !s.status).length },
].filter(f => f.id === 'all' || f.count > 0))

const filteredStudents = computed(() => {
  const q = search.value.trim().toLowerCase()
  return students.value.filter(s => {
    const matchesFilter = statusFilter.value === 'all'
      || (statusFilter.value === 'unmarked' ? !s.status : s.status === statusFilter.value)
    const matchesSearch = !q
      || (s.name || '').toLowerCase().includes(q)
      || (s.lrn || '').toLowerCase().includes(q)
    return matchesFilter && matchesSearch
  })
})

function resetFilters() {
  search.value = ''
  statusFilter.value = 'all'
}

/* ─── Rates (from local state) ────────────────────────── */
const genderCounts = computed(() => {
  const g = { male: 0, female: 0 }
  students.value.forEach(s => {
    const k = (s.sex || '').toLowerCase()
    if (k === 'male' || k === 'female') g[k]++
  })
  return g
})

const presentRate = computed(() => {
  if (!liveStats.value.enrolled) return 0
  return Math.round((liveStats.value.present / liveStats.value.enrolled) * 100)
})

const absentRate = computed(() => {
  if (!liveStats.value.enrolled) return 0
  return Math.round(((liveStats.value.absent + liveStats.value.late) / liveStats.value.enrolled) * 100)
})

/* ─── SF-2 sync helpers ───────────────────────────────── */
const todayIso = new Date().toISOString().slice(0, 10)

const currentMonthLabel = computed(() =>
  new Date().toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
)

/** Safely extract a YYYY-MM-DD string from anything the server might send. */
function toDateKey(v) {
  if (!v) return null
  if (typeof v === 'object' && v !== null) {
    v = v.date ?? v.value ?? String(v)
  }
  const s = String(v).trim()
  // Handles "2026-10-02", "2026-10-02T00:00:00.000000Z", ISO with offset, etc.
  const m = s.match(/^(\d{4}-\d{2}-\d{2})/)
  return m ? m[1] : null
}

const weekChips = computed(() => {
  return (props.weekly_sessions || [])
    .map(s => {
      const key = toDateKey(s.date)
      if (!key) return null
      const d = new Date(key + 'T00:00:00')
      if (isNaN(d.getTime())) return null
      const dayLetter = d.toLocaleDateString('en-US', { weekday: 'short' }).slice(0, 1)
      return {
        date: key,
        label: `${dayLetter}${d.getDate()}`,
        isToday: key === todayIso,
      }
    })
    .filter(Boolean)
})

const monthAda = computed(() => {
  const sessions = props.weekly_sessions || []
  if (sessions.length === 0) return 0
  let totalPresent = 0, totalMarked = 0
  sessions.forEach(s => {
    totalPresent += (s.present || 0) + (s.late || 0)
    totalMarked += (s.total || 0)
  })
  if (totalMarked === 0) return 0
  return Math.round((totalPresent / totalMarked) * 1000) / 10
})

/* ─── Trend graph ─────────────────────────────────────── */
const trendPoints = computed(() => {
  const sessions = props.weekly_sessions || []
  if (sessions.length === 0) return []

  const rates = sessions.map(s => {
    const total = s.total || 0
    return total > 0 ? ((s.present + s.late) / total) * 100 : 0
  })

  const n = rates.length
  return rates.map((rate, i) => {
    const x = n === 1 ? 150 : (i / (n - 1)) * 300
    const clamped = Math.max(60, Math.min(100, rate))
    const y = 100 - ((clamped - 60) / 40) * 95
    return { x, y, rate }
  })
})

const trendPolyline = computed(() => {
  if (trendPoints.value.length === 0) return ''
  if (trendPoints.value.length === 1) {
    const p = trendPoints.value[0]
    return `${p.x},${p.y}`
  }
  return trendPoints.value.map(p => `${p.x},${p.y}`).join(' ')
})

const trendPolygon = computed(() => {
  if (trendPoints.value.length < 2) return ''
  const first = trendPoints.value[0]
  const last = trendPoints.value[trendPoints.value.length - 1]
  const body = trendPoints.value.map(p => `${p.x},${p.y}`).join(' ')
  return `${body} ${last.x},100 ${first.x},100`
})

const trendCells = computed(() => {
  const sessions = props.weekly_sessions || []
  const cells = sessions.slice(-4).map((s, i) => {
    const total = s.total || 0
    const rate = total > 0 ? Math.round(((s.present + s.late) / total) * 1000) / 10 : 0
    return { label: `Wk ${i + 1}`, value: rate + '%' }
  })
  while (cells.length < 4) {
    cells.unshift({ label: `Wk ${4 - cells.length}`, value: '—' })
  }
  return cells
})

/* ─── Helpers ─────────────────────────────────────────── */
function initialsOf(name) {
  if (!name) return '?'
  const parts = String(name).trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
}

function formatLongDate(v) {
  const key = toDateKey(v)
  if (!key) return '—'
  const d = new Date(key + 'T00:00:00')
  if (isNaN(d.getTime())) return String(v ?? '—')
  return d.toLocaleDateString('en-US', {
    weekday: 'long', month: 'long', day: 'numeric', year: 'numeric',
  })
}

function formatTimeAgo(v) {
  if (!v) return ''
  try {
    const diff = Math.round((Date.now() - new Date(v).getTime()) / 60000)
    if (diff < 1) return 'Just now'
    if (diff < 60) return `${diff}m ago`
    if (diff < 1440) return `${Math.round(diff / 60)}h ago`
    return new Date(v).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
  } catch { return '' }
}

function printSf2() {
  window.print()
}

/* ─── Animations ──────────────────────────────────────── */
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