<template>
  <Head :title="`${classroom.subject} - Salawag LMS`" />

  <AuthenticatedLayout searchPlaceholder="Search learners (LRN), strand records, advisories..">

    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

      <!-- ═══════════════ HERO BANNER ═══════════════ -->
      <div v-observe
        class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center anim-fade-down">
        <img :src="heroImage" alt="Class Roster Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 flex flex-col justify-center">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 mb-3 sm:mb-4">
            <span class="bg-[#005506] text-white border border-emerald-400/30 text-[10px] sm:text-[11px] md:text-xs font-bold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <span>🏛️</span> DepEd Region IV-A • SDO Dasmariñas
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <span>📅</span> {{ classroom.term || 'No term' }}
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <span>👥</span> {{ students.length }} Enrolled
            </span>
          </div>

          <div class="space-y-1.5 sm:space-y-2 max-w-3xl">
            <h2 class="text-xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight">
              {{ classroom.subject || 'Untitled Subject' }}
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              <span class="font-black text-[#F9C20C]">{{ classroom.subject_code || '—' }}</span>
              • {{ classroom.section || '—' }}
              <span v-if="classroom.grade_level"> • Grade {{ classroom.grade_level }}</span>
            </p>
          </div>

          <div class="flex flex-wrap gap-2 mt-4">
            <BackButton fallback="teacher.classes.index"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              Back
            </BackButton>
          </div>
        </div>
      </div>

      <!-- ═══════════════ SNAPSHOT CARDS ═══════════════ -->
      <div v-observe class="anim-slide-up grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5"
        style="animation-delay: 100ms;" :key="`snapshot-${activeTab}`">
        <div v-for="(card, i) in snapshotCards" :key="i"
          class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
              {{ card.label }}
            </span>
            <div :class="['w-8 h-8 rounded-xl flex items-center justify-center shrink-0 border', card.iconClass]">
              <Icon :icon="card.icon" size="sm" />
            </div>
          </div>
          <div :class="['text-2xl sm:text-3xl font-black', card.valueClass || 'text-slate-900 dark:text-white']">
            {{ card.value }}
          </div>
          <p class="text-[11px] font-medium text-slate-500">{{ card.hint }}</p>
        </div>
      </div>

      <!-- ═══════════════ TAB BAR ═══════════════ -->
      <div v-observe
        class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-2 sm:p-3 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex items-center gap-1.5 sm:gap-2 overflow-x-auto no-scrollbar"
        style="animation-delay: 150ms;">

        <button v-for="tab in tabs" :key="tab.id" @click="selectTab(tab.id)"
          :class="[
            'px-4 py-2.5 rounded-2xl text-xs font-extrabold transition-all shrink-0 flex items-center gap-2 active:scale-95',
            activeTab === tab.id
              ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-sm'
              : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-[#232D26] hover:text-[#005506] dark:hover:text-[#86EFAC]'
          ]">
          <Icon :icon="tab.icon" size="xs" />
          {{ tab.label }}
          <span v-if="tab.count !== null"
            :class="[
              'text-[10px] px-1.5 py-0.5 rounded-full font-black',
              activeTab === tab.id
                ? 'bg-white/20 text-white dark:text-[#232D26]'
                : 'bg-slate-200/80 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300'
            ]">{{ tab.count }}</span>
        </button>
      </div>

      <!-- ═══════════════ MAIN CONTENT ═══════════════ -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] overflow-hidden">

        <div v-if="tabLoading[activeTab]"
          class="flex flex-col items-center justify-center py-20 space-y-3">
          <div class="w-10 h-10 border-4 border-emerald-200 dark:border-emerald-900/60 border-t-[#005506] dark:border-t-[#86EFAC] rounded-full animate-spin"></div>
          <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Loading {{ tabLabel(activeTab) }}…</p>
        </div>

        <Transition v-else name="tab-swap" mode="out-in">
          <div :key="activeTab" class="space-y-4 sm:space-y-5">

            <!-- ─── STUDENTS TAB ──────────────────────────────── -->
            <div v-if="activeTab === 'students'" class="space-y-4 sm:space-y-5">
              <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-[#3F4F43]">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
                    <Icon icon="users" size="md" />
                  </div>
                  <div>
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-800 dark:text-white">Enrolled Student Roster</h3>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                      Verified entries from class_students and students records
                    </p>
                  </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                  <button @click="openEnrollModal"
                    class="bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] text-[11px] sm:text-xs font-black px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl shadow-sm flex items-center gap-1.5 transition-all active:scale-95">
                    <Icon icon="plus" size="xs" />
                    Enroll Student
                  </button>

                  <div class="relative">
                    <button @click="showExportMenu = !showExportMenu"
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] sm:text-xs font-bold px-3 py-2 sm:py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5 transition-colors">
                      <Icon icon="download" size="xs" />
                      Export
                      <Icon icon="chevron-down" size="xs" />
                    </button>

                    <div v-if="showExportMenu"
                      class="absolute right-0 top-full mt-2 w-56 bg-white dark:bg-[#2D3A31] rounded-2xl shadow-xl border border-slate-200 dark:border-[#3F4F43] overflow-hidden z-30">
                      <a :href="route('exports.class-list', classroom.id)" target="_blank"
                        class="flex items-center gap-2 px-4 py-3 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border-b border-slate-100 dark:border-[#3F4F43]">
                        <Icon icon="document-text" size="sm" class="text-emerald-600 dark:text-[#86EFAC]" />
                        Class List (PDF)
                      </a>
                      <a :href="route('exports.gradebook', classroom.id)" target="_blank"
                        class="flex items-center gap-2 px-4 py-3 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-[#3F4F43]">
                        <Icon icon="chart-bar" size="sm" class="text-amber-600 dark:text-amber-400" />
                        Gradebook (Spreadsheet)
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
                <div class="relative w-full sm:w-80">
                  <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <Icon icon="search" size="sm" />
                  </span>
                  <input v-model="rosterSearch" type="text" placeholder="Filter by name, LRN, or email..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-xs text-slate-700 dark:text-slate-200 shadow-sm" />
                </div>

                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
                  <button v-for="f in statusFilters" :key="f.id" @click="statusFilter = f.id"
                    :class="[
                      'text-[11px] font-black px-3 py-1.5 rounded-xl transition-all shrink-0 active:scale-95',
                      statusFilter === f.id
                        ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-sm'
                        : 'bg-white dark:bg-[#2D3A31] text-slate-600 dark:text-slate-300 border border-slate-200/80 dark:border-[#3F4F43] hover:bg-slate-100 dark:hover:bg-[#3F4F43]'
                    ]">
                    {{ f.label }} ({{ f.count }})
                  </button>
                </div>
              </div>

              <div v-if="pagedStudents.length" class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
                <table class="w-full min-w-[760px] text-left border-collapse">
                  <thead>
                    <tr class="bg-[#F9F7F1] dark:bg-[#232D26] text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-[#3F4F43]">
                      <th class="py-3 px-3 sm:px-4 w-12 text-center">#</th>
                      <th class="py-3 px-3 sm:px-4">Student Name</th>
                      <th class="py-3 px-3 sm:px-4">LRN</th>
                      <th class="py-3 px-3 sm:px-4">Email</th>
                      <th class="py-3 px-3 sm:px-4">Sex</th>
                      <th class="py-3 px-3 sm:px-4">Contact</th>
                      <th class="py-3 px-3 sm:px-4 text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-xs font-medium text-slate-700 dark:text-slate-200">
                    <tr v-for="(s, idx) in pagedStudents" :key="s.id"
                      :class="[
                        'transition-colors',
                        s.status === 'dropped'
                          ? 'bg-rose-50/30 dark:bg-rose-950/10 opacity-75 hover:bg-rose-50/50 dark:hover:bg-rose-950/20'
                          : 'hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40'
                      ]">
                      <td class="py-3.5 px-3 sm:px-4 text-center font-bold text-slate-400">
                        {{ String((page - 1) * perPage + idx + 1).padStart(2, '0') }}
                      </td>

                      <td class="py-3.5 px-3 sm:px-4">
                        <div class="flex items-center gap-2.5">
                          <span :class="[
                            'w-8 h-8 rounded-full font-extrabold text-[11px] flex items-center justify-center shrink-0',
                            s.status === 'dropped'
                              ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300'
                              : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300'
                          ]">{{ initialsOf(s.name) }}</span>
                          <span :class="[
                            'font-extrabold',
                            s.status === 'dropped' ? 'text-slate-500 dark:text-slate-400 line-through' : 'text-slate-800 dark:text-white'
                          ]">{{ s.name || '—' }}</span>
                        </div>
                      </td>

                      <td class="py-3.5 px-3 sm:px-4 font-mono text-slate-600 dark:text-slate-300">{{ s.lrn || '—' }}</td>
                      <td class="py-3.5 px-3 sm:px-4 text-slate-600 dark:text-slate-300 truncate max-w-[220px]">{{ s.email || '—' }}</td>
                      <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-600 dark:text-slate-300 capitalize">{{ s.sex || '—' }}</td>
                      <td class="py-3.5 px-3 sm:px-4 font-mono text-slate-600 dark:text-slate-300">{{ s.contact_number || '—' }}</td>

                      <td class="py-3.5 px-3 sm:px-4 text-center">
                        <span :class="[
                          'text-[10px] sm:text-[11px] font-extrabold px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full inline-flex items-center gap-1 border',
                          s.status === 'active'
                            ? 'bg-emerald-50 dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] border-emerald-200/60 dark:border-emerald-900/40'
                            : s.status === 'completed'
                              ? 'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border-blue-200/60 dark:border-blue-900/40'
                              : 'bg-rose-100/80 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border-rose-200/80 dark:border-rose-900/40'
                        ]">
                          • {{ s.status ? (s.status.charAt(0).toUpperCase() + s.status.slice(1)) : 'Active' }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] p-12 rounded-2xl sm:rounded-3xl text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
                <Icon icon="users" size="xl" class="text-slate-400 mx-auto" />
                <p class="text-sm font-bold text-slate-800 dark:text-white">
                  {{ rosterSearch || statusFilter !== 'all' ? 'No learners match your filter' : 'No students enrolled yet' }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                  {{ rosterSearch || statusFilter !== 'all' ? 'Try a different search or clear your filters.' : 'Enrolled students will appear here once assigned to this class.' }}
                </p>
                <button v-if="rosterSearch || statusFilter !== 'all'" @click="resetRosterFilters"
                  class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold px-4 py-2 rounded-xl hover:bg-[#004105] transition-colors">
                  Reset Filters
                </button>
              </div>

              <div v-if="totalPages > 1" class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                  Showing <strong class="text-slate-800 dark:text-white">{{ pageStart }}</strong>
                  to <strong class="text-slate-800 dark:text-white">{{ pageEnd }}</strong>
                  of <strong class="text-slate-800 dark:text-white">{{ filteredStudents.length }}</strong> learners
                </p>
                <div class="flex items-center gap-1 sm:gap-1.5">
                  <button @click="page = Math.max(1, page - 1)" :disabled="page === 1"
                    :class="[
                      'px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] text-[11px] sm:text-xs font-bold transition-colors',
                      page === 1 ? 'text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-[#232D26] cursor-not-allowed' : 'text-slate-700 dark:text-slate-200 bg-white dark:bg-[#2D3A31] hover:bg-slate-100'
                    ]">
                    Prev
                  </button>
                  <button v-for="p in totalPages" :key="p" @click="page = p"
                    :class="[
                      'w-8 h-8 sm:w-9 sm:h-9 rounded-xl text-xs font-black flex items-center justify-center transition-colors',
                      page === p
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
              </div>
            </div>

            <!-- ─── ASSIGNMENTS TAB ────────────────────────────── -->
            <div v-else-if="activeTab === 'assignments'" class="space-y-4 sm:space-y-5">
              <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-[#3F4F43]">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
                    <Icon icon="clipboard-list" size="md" />
                  </div>
                  <div>
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-800 dark:text-white">Class Assignments</h3>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                      {{ tabData.assignments?.assignments?.length || 0 }} total • {{ assignmentsByStatus.published }} published • {{ assignmentsByStatus.draft }} draft
                    </p>
                  </div>
                </div>

                <div class="flex items-center gap-2">
                  <button @click="refreshTab('assignments')"
                    class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                    <Icon icon="arrow-right" size="xs" class="rotate-90" />
                    Refresh
                  </button>
                  <Link :href="route('teacher.classes.assignments.create', classroom.id)"
                    class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition-all active:scale-95">
                    <Icon icon="plus" size="xs" />
                    New Assignment
                  </Link>
                </div>
              </div>

              <div v-if="tabData.assignments?.assignments?.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="a in tabData.assignments.assignments" :key="a.id"
                  class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] space-y-3 hover:border-[#005506] dark:hover:border-[#86EFAC] hover:-translate-y-0.5 hover:shadow-md transition-all">
                  <div class="flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex items-center gap-1.5">
                      <span :class="categoryBadgeClass(a.category)">
                        {{ categoryLabel(a.category) }}
                      </span>
                      <span v-if="a.allow_late" class="text-[10px] font-bold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 px-2 py-0.5 rounded-md border border-amber-200/60 dark:border-amber-900/40">
                        Late OK
                      </span>
                    </div>
                    <span :class="a.is_published
                      ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC]'
                      : 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400'"
                      class="text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">
                      {{ a.is_published ? 'Published' : 'Draft' }}
                    </span>
                  </div>

                  <h4 class="font-extrabold text-slate-900 dark:text-white text-sm leading-snug line-clamp-2">
                    {{ a.title }}
                  </h4>

                  <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-white dark:bg-[#2D3A31] rounded-xl p-2 border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Points</span>
                      <span class="text-xs font-black text-slate-800 dark:text-slate-200">{{ a.points }}</span>
                    </div>
                    <div class="bg-white dark:bg-[#2D3A31] rounded-xl p-2 border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Turn-ins</span>
                      <span class="text-xs font-black text-slate-800 dark:text-slate-200">{{ a.submissions_count }}</span>
                    </div>
                    <div class="bg-white dark:bg-[#2D3A31] rounded-xl p-2 border border-slate-200/60 dark:border-[#3F4F43]">
                      <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Due</span>
                      <span class="text-[10px] font-black text-slate-800 dark:text-slate-200">
                        {{ a.due_at ? formatShort(a.due_at) : '—' }}
                      </span>
                    </div>
                  </div>

                  <div class="flex items-center gap-2 pt-2 border-t border-slate-200/60 dark:border-[#3F4F43]">
                    <Link :href="route('teacher.assignments.show', a.id)"
                      class="flex-1 bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] font-bold py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
                      <Icon icon="eye" size="xs" />
                      View
                    </Link>
                    <Link :href="route('teacher.assignments.edit', a.id)"
                      class="flex-1 bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] font-bold py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
                      <Icon icon="edit" size="xs" />
                      Edit
                    </Link>
                  </div>
                </div>
              </div>

              <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] p-12 rounded-2xl sm:rounded-3xl text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
                <Icon icon="clipboard-list" size="xl" class="text-slate-400 mx-auto" />
                <p class="text-sm font-bold text-slate-800 dark:text-white">No assignments yet</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Create your first assignment for this class.</p>
                <Link :href="route('teacher.classes.assignments.create', classroom.id)"
                  class="inline-flex bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl hover:bg-[#004105] transition-colors items-center gap-2">
                  <Icon icon="plus" size="xs" />
                  New Assignment
                </Link>
              </div>
            </div>

            <!-- ─── LESSONS TAB ────────────────────────────────── -->
            <div v-else-if="activeTab === 'lessons'" class="space-y-4 sm:space-y-5">
              <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-[#3F4F43]">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-900/40">
                    <Icon icon="book-open" size="md" />
                  </div>
                  <div>
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-800 dark:text-white">Learning Materials</h3>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                      {{ tabData.lessons?.lessons?.length || 0 }} lessons • {{ lessonsByStatus.published }} published
                    </p>
                  </div>
                </div>

                <div class="flex items-center gap-2">
                  <button @click="refreshTab('lessons')"
                    class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                    <Icon icon="arrow-right" size="xs" class="rotate-90" />
                    Refresh
                  </button>
                  <Link :href="route('teacher.classes.lessons.create', classroom.id)"
                    class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition-all active:scale-95">
                    <Icon icon="plus" size="xs" />
                    New Lesson
                  </Link>
                </div>
              </div>

              <div v-if="tabData.lessons?.lessons?.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="l in tabData.lessons.lessons" :key="l.id"
                  class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] space-y-3 hover:border-[#005506] dark:hover:border-[#86EFAC] hover:-translate-y-0.5 hover:shadow-md transition-all">
                  <div class="flex items-center justify-between gap-2">
                    <span class="bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] font-black text-[10px] px-2.5 py-0.5 rounded-md">
                      Lesson {{ String(l.position ?? 0).padStart(2, '0') }}
                    </span>
                    <span :class="l.is_published
                      ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC]'
                      : 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400'"
                      class="text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">
                      {{ l.is_published ? 'Published' : 'Draft' }}
                    </span>
                  </div>

                  <h4 class="font-extrabold text-slate-900 dark:text-white text-sm leading-snug line-clamp-2">
                    {{ l.title }}
                  </h4>

                  <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-3 font-medium">
                    {{ l.body_preview || 'No preview available.' }}
                  </p>

                  <div class="flex items-center gap-2 pt-2 border-t border-slate-200/60 dark:border-[#3F4F43]">
                    <Link :href="route('teacher.lessons.show', l.id)"
                      class="flex-1 bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] font-bold py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
                      <Icon icon="eye" size="xs" />
                      Read
                    </Link>
                    <Link :href="route('teacher.lessons.edit', l.id)"
                      class="flex-1 bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] font-bold py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
                      <Icon icon="edit" size="xs" />
                      Edit
                    </Link>
                  </div>
                </div>
              </div>

              <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] p-12 rounded-2xl sm:rounded-3xl text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
                <Icon icon="book-open" size="xl" class="text-slate-400 mx-auto" />
                <p class="text-sm font-bold text-slate-800 dark:text-white">No lessons yet</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Publish your first learning material for this class.</p>
                <Link :href="route('teacher.classes.lessons.create', classroom.id)"
                  class="inline-flex bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl hover:bg-[#004105] transition-colors items-center gap-2">
                  <Icon icon="plus" size="xs" />
                  New Lesson
                </Link>
              </div>
            </div>

            <!-- ─── GRADEBOOK TAB ──────────────────────────────── -->
            <div v-else-if="activeTab === 'gradebook'" class="space-y-4 sm:space-y-5">
              <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-[#3F4F43]">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
                    <Icon icon="chart-bar" size="md" />
                  </div>
                  <div>
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-800 dark:text-white">Live Gradebook</h3>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                      Weights: WW {{ tabData.gradebook?.weights?.written_work ?? '—' }}% •
                      PT {{ tabData.gradebook?.weights?.performance_task ?? '—' }}% •
                      QE {{ tabData.gradebook?.weights?.quarterly_exam ?? '—' }}%
                    </p>
                  </div>
                </div>

                <div class="flex items-center gap-2">
                  <button @click="refreshTab('gradebook')"
                    class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                    <Icon icon="arrow-right" size="xs" class="rotate-90" />
                    Refresh
                  </button>
                  <a :href="route('exports.gradebook', classroom.id)" target="_blank"
                    class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition-all active:scale-95">
                    <Icon icon="download" size="xs" />
                    Export
                  </a>
                </div>
              </div>

              <div v-if="tabData.gradebook?.students?.length"
                class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
                <table class="w-full min-w-[860px] text-left border-collapse">
                  <thead>
                    <tr class="bg-[#F9F7F1] dark:bg-[#232D26] text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-[#3F4F43]">
                      <th class="py-3 px-3 sm:px-4 w-12 text-center">#</th>
                      <th class="py-3 px-3 sm:px-4">Student</th>
                      <th class="py-3 px-3 sm:px-4 text-center bg-emerald-50/60 dark:bg-emerald-950/30">Written</th>
                      <th class="py-3 px-3 sm:px-4 text-center bg-amber-50/60 dark:bg-amber-950/30">Performance</th>
                      <th class="py-3 px-3 sm:px-4 text-center bg-blue-50/60 dark:bg-blue-950/30">Exam</th>
                      <th class="py-3 px-3 sm:px-4 text-center bg-emerald-100/60 dark:bg-emerald-950/50">Final</th>
                      <th class="py-3 px-3 sm:px-4 text-center">Remarks</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-xs font-medium text-slate-700 dark:text-slate-200">
                    <tr v-for="(s, idx) in tabData.gradebook.students" :key="s.id"
                      class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                      <td class="py-3.5 px-3 sm:px-4 text-center font-bold text-slate-400">
                        {{ String(idx + 1).padStart(2, '0') }}
                      </td>

                      <td class="py-3.5 px-3 sm:px-4">
                        <div class="flex items-center gap-2.5">
                          <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 font-extrabold text-[11px] flex items-center justify-center shrink-0">
                            {{ initialsOf(s.name) }}
                          </span>
                          <div class="min-w-0">
                            <p class="font-extrabold text-slate-800 dark:text-white truncate">{{ s.name }}</p>
                            <p class="text-[10px] font-mono text-slate-400 truncate">{{ s.lrn }}</p>
                          </div>
                        </div>
                      </td>

                      <td class="py-3.5 px-3 sm:px-4 text-center font-bold bg-emerald-50/40 dark:bg-emerald-950/20" :class="scoreColor(s.written_work)">
                        {{ formatScore(s.written_work) }}
                      </td>
                      <td class="py-3.5 px-3 sm:px-4 text-center font-bold bg-amber-50/40 dark:bg-amber-950/20" :class="scoreColor(s.performance_task)">
                        {{ formatScore(s.performance_task) }}
                      </td>
                      <td class="py-3.5 px-3 sm:px-4 text-center font-bold bg-blue-50/40 dark:bg-blue-950/20" :class="scoreColor(s.quarterly_exam)">
                        {{ formatScore(s.quarterly_exam) }}
                      </td>

                      <td class="py-3.5 px-3 sm:px-4 text-center bg-emerald-100/40 dark:bg-emerald-950/30">
                        <span class="text-base font-black" :class="scoreColor(s.final_grade)">
                          {{ formatScore(s.final_grade) }}
                        </span>
                      </td>

                      <td class="py-3.5 px-3 sm:px-4 text-center">
                        <span v-if="s.remarks" :class="remarksBadgeClass(s.remarks)"
                          class="inline-block text-[10px] font-black uppercase px-2.5 py-1 rounded-full border whitespace-nowrap">
                          {{ remarksLabel(s.remarks) }}
                        </span>
                        <span v-else-if="!s.is_complete" class="text-[10px] font-bold text-slate-400 dark:text-slate-500">
                          In Progress
                        </span>
                        <span v-else class="text-slate-400 text-[11px]">—</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] p-12 rounded-2xl sm:rounded-3xl text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
                <Icon icon="chart-bar" size="xl" class="text-slate-400 mx-auto" />
                <p class="text-sm font-bold text-slate-800 dark:text-white">No grades available yet</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Grades appear once you publish and grade at least one assessment.</p>
              </div>
            </div>

            <!-- ─── ATTENDANCE TAB ─────────────────────────────── -->
            <div v-else-if="activeTab === 'attendance'" class="space-y-4 sm:space-y-5">

              <!-- ══════════ MARKING VIEW ══════════ -->
              <template v-if="selectedDate">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-[#3F4F43]">
                  <div class="flex items-start gap-3">
                    <button @click="closeAttendanceSession"
                      class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0 transition-colors">
                      <Icon icon="arrow-left" size="md" />
                    </button>
                    <div>
                      <h3 class="text-base sm:text-lg font-extrabold text-slate-800 dark:text-white">
                        Mark Attendance — {{ formatLongDate(selectedDate) }}
                      </h3>
                      <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                        {{ attendanceRoster.length }} learner{{ attendanceRoster.length === 1 ? '' : 's' }} to record
                      </p>
                    </div>
                  </div>

                  <div class="flex items-center gap-2">
                    <button @click="closeAttendanceSession"
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-xs font-bold px-4 py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] transition-colors">
                      Cancel
                    </button>
                    <button @click="saveAttendance" :disabled="isSaving || !attendanceRoster.length"
                      class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black px-5 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition-all active:scale-95 disabled:opacity-50">
                      <Icon icon="check-circle" size="xs" />
                      {{ isSaving ? 'Saving…' : 'Save Attendance' }}
                    </button>
                  </div>
                </div>

                <div v-if="saveError"
                  class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl text-rose-700 dark:text-rose-300 text-xs">
                  {{ saveError }}
                </div>

                <div v-if="isMarkingLoading" class="py-16 text-center">
                  <div class="inline-block w-8 h-8 border-4 border-emerald-200 dark:border-emerald-900/60 border-t-[#005506] dark:border-t-[#86EFAC] rounded-full animate-spin"></div>
                  <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-3">Loading roster…</p>
                </div>

                <div v-else-if="attendanceRoster.length"
                  class="overflow-x-auto -mx-2 sm:mx-0 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-[#3F4F43]">
                  <table class="w-full min-w-[720px] text-left border-collapse">
                    <thead>
                      <tr class="bg-[#F9F7F1] dark:bg-[#232D26] text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200/80 dark:border-[#3F4F43]">
                        <th class="py-3 px-3 sm:px-4 w-12 text-center">#</th>
                        <th class="py-3 px-3 sm:px-4">Student</th>
                        <th class="py-3 px-3 sm:px-4 text-center">Status</th>
                        <th class="py-3 px-3 sm:px-4">Notes</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-[#3F4F43] text-xs font-medium text-slate-700 dark:text-slate-200">
                      <tr v-for="(r, idx) in attendanceRoster" :key="r.student_id"
                        class="hover:bg-slate-50/80 dark:hover:bg-[#232D26]/40 transition-colors">
                        <td class="py-3.5 px-3 sm:px-4 text-center font-bold text-slate-400">
                          {{ String(idx + 1).padStart(2, '0') }}
                        </td>
                        <td class="py-3.5 px-3 sm:px-4">
                          <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 font-extrabold text-[11px] flex items-center justify-center shrink-0">
                              {{ initialsOf(r.name) }}
                            </span>
                            <div class="min-w-0">
                              <p class="font-extrabold text-slate-800 dark:text-white truncate">{{ r.name }}</p>
                              <p class="text-[10px] font-mono text-slate-400 truncate">{{ r.lrn }}</p>
                            </div>
                          </div>
                        </td>
                        <td class="py-3.5 px-3 sm:px-4">
                          <div class="flex items-center justify-center gap-1">
                            <button v-for="st in ATT_STATUSES" :key="st.value"
                              type="button"
                              @click="r.status = st.value"
                              :class="[
                                'text-[10px] font-black px-2.5 py-1 rounded-lg border transition-all',
                                r.status === st.value
                                  ? st.chip + ' ring-2 ring-offset-1 ring-slate-300 dark:ring-[#3F4F43] dark:ring-offset-[#2D3A31]'
                                  : 'bg-white dark:bg-[#2D3A31] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43] hover:bg-slate-50 dark:hover:bg-[#3F4F43]'
                              ]">
                              {{ st.short }}
                            </button>
                          </div>
                        </td>
                        <td class="py-3.5 px-3 sm:px-4">
                          <input v-model="r.notes" type="text" placeholder="Optional note"
                            class="w-full px-2.5 py-1.5 text-[11px] rounded-lg bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-slate-700 dark:text-slate-200" />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] p-12 rounded-2xl sm:rounded-3xl text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
                  <Icon icon="users" size="xl" class="text-slate-400 mx-auto" />
                  <p class="text-sm font-bold text-slate-800 dark:text-white">No learners enrolled</p>
                  <p class="text-xs text-slate-500 dark:text-slate-400">Enroll students before marking attendance.</p>
                </div>
              </template>

              <!-- ══════════ SESSION LIST ══════════ -->
              <template v-else>
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-[#3F4F43]">
                  <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 flex items-center justify-center shrink-0 border border-sky-100 dark:border-sky-900/40">
                      <Icon icon="clipboard-check" size="md" />
                    </div>
                    <div>
                      <h3 class="text-base sm:text-lg font-extrabold text-slate-800 dark:text-white">Attendance Sessions</h3>
                      <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">
                        {{ tabData.attendance?.sessions?.length || 0 }} sessions recorded •
                        {{ tabData.attendance?.enrolled_students || 0 }} learners
                      </p>
                    </div>
                  </div>

                  <div class="flex items-center gap-2">
                    <button @click="refreshTab('attendance')"
                      class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center gap-1.5">
                      <Icon icon="arrow-right" size="xs" class="rotate-90" />
                      Refresh
                    </button>
                    <button @click="openAttendanceFor(todayIso)"
                      class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition-all active:scale-95">
                      <Icon icon="check-circle" size="xs" />
                      Mark Today
                    </button>
                  </div>
                </div>

                <div v-if="tabData.attendance?.sessions?.length" class="space-y-2">
                  <button v-for="(sess, i) in tabData.attendance.sessions" :key="`${sess.date}-${i}`"
                    type="button"
                    @click="openAttendanceFor(sess.date)"
                    class="w-full text-left bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:border-[#005506] dark:hover:border-[#86EFAC] hover:-translate-x-1 transition-all">
                    <div class="flex items-center gap-3">
                      <div class="w-11 h-11 rounded-2xl bg-white dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center shrink-0">
                        <Icon icon="calendar" size="md" class="text-[#005506] dark:text-[#86EFAC]" />
                      </div>
                      <div>
                        <p class="font-extrabold text-slate-900 dark:text-white text-sm">
                          {{ formatLongDate(sess.date) }}
                        </p>
                        <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                          {{ sess.total }} learners marked
                        </p>
                      </div>
                    </div>

                    <div class="flex items-center gap-1.5 flex-wrap">
                      <span class="bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-1 rounded-full border border-emerald-200/60 dark:border-emerald-900/40">
                        P: {{ sess.present }}
                      </span>
                      <span class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-black px-2.5 py-1 rounded-full border border-amber-200/60 dark:border-amber-900/40">
                        L: {{ sess.late }}
                      </span>
                      <span class="bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10px] font-black px-2.5 py-1 rounded-full border border-rose-200/60 dark:border-rose-900/40">
                        A: {{ sess.absent }}
                      </span>
                      <span class="bg-sky-100 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 text-[10px] font-black px-2.5 py-1 rounded-full border border-sky-200/60 dark:border-sky-900/40">
                        E: {{ sess.excused }}
                      </span>
                      <Icon icon="chevron-right" size="xs" class="text-slate-400 ml-1" />
                    </div>
                  </button>
                </div>

                <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] p-12 rounded-2xl sm:rounded-3xl text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-3">
                  <Icon icon="clipboard-check" size="xl" class="text-slate-400 mx-auto" />
                  <p class="text-sm font-bold text-slate-800 dark:text-white">No attendance sessions yet</p>
                  <p class="text-xs text-slate-500 dark:text-slate-400">Start by marking attendance for today.</p>
                  <button @click="openAttendanceFor(todayIso)"
                    class="inline-flex bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl hover:bg-[#004105] transition-colors items-center gap-2">
                    <Icon icon="plus" size="xs" />
                    Mark Today
                  </button>
                </div>
              </template>

            </div>

          </div>
        </Transition>
      </div>
    </div>

    <!-- ═══════════ ENROLL MODAL ═══════════ -->
    <div v-if="showEnrollModal"
      class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4"
      @click.self="showEnrollModal = false">
      <div class="bg-white dark:bg-[#2D3A31] rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-100 dark:border-[#3F4F43]">

        <div class="flex items-start justify-between gap-3">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
              <Icon icon="user-check" size="md" />
            </div>
            <div>
              <h3 class="font-black text-slate-900 dark:text-white text-base">Enroll Student to Class</h3>
              <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
                {{ classroom.subject }} • {{ classroom.section }}
              </p>
            </div>
          </div>
          <button @click="showEnrollModal = false"
            class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center">
            <Icon icon="x" size="xs" />
          </button>
        </div>

        <div class="bg-[#FEF9E7] dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-900/40 rounded-2xl p-4 space-y-2">
          <div class="flex items-start gap-2.5">
            <Icon icon="info" size="md" class="text-amber-700 dark:text-amber-400 shrink-0 mt-0.5" />
            <div class="text-xs text-amber-900 dark:text-amber-200 leading-relaxed space-y-1">
              <p class="font-bold">Enrollment is managed by the Registrar's Office.</p>
              <p>Teachers cannot add learners directly to a class. To request enrollment, coordinate with the Registrar or your School Head.</p>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
          <button @click="showEnrollModal = false"
            class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-[#3F4F43] text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-[#3F4F43] transition-colors">
            Close
          </button>
          <a href="mailto:registrar@salawagshs.edu.ph"
            class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black px-5 py-2.5 rounded-xl shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <Icon icon="send" size="xs" />
            Contact Registrar
          </a>
        </div>

      </div>
    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, reactive, watch, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/desktop-home-banner.png'
import BackButton from '@/Components/BackButton.vue'

/* ═════════════════════════════════════════════════════════ */
/* PROPS                                                     */
/* ═════════════════════════════════════════════════════════ */
const props = defineProps({
  classroom: { type: Object, default: () => ({}) },
  students:  { type: Array,  default: () => [] },
})

/* ═════════════════════════════════════════════════════════ */
/* SHARED UTILS — declared first so everything below can use  */
/* ═════════════════════════════════════════════════════════ */

/**
 * Normalize a date value to YYYY-MM-DD.
 * Handles: "2026-09-29", "2026-09-29T00:00:00.000000Z", Carbon objects,
 * and the { date: "...", value: "..." } envelope Inertia sometimes emits.
 */
function toDateParam(v) {
  if (!v) return ''
  if (typeof v === 'object') v = v.date ?? v.value ?? String(v)
  return String(v).trim().slice(0, 10)
}

const todayIso = new Date().toISOString().slice(0, 10)

/* ═════════════════════════════════════════════════════════ */
/* TABS STATE                                                */
/* ═════════════════════════════════════════════════════════ */
const activeTab = ref('students')

const tabData = reactive({
  students:    { students: props.students },
  assignments: null,
  lessons:     null,
  gradebook:   null,
  attendance:  null,
})

const tabLoading = reactive({
  students:    false,
  assignments: false,
  lessons:     false,
  gradebook:   false,
  attendance:  false,
})

const tabs = computed(() => [
  { id: 'students',    label: 'Students',    icon: 'users',           count: props.students.length },
  { id: 'assignments', label: 'Assignments', icon: 'clipboard-list',  count: tabData.assignments?.assignments?.length ?? null },
  { id: 'lessons',     label: 'Lessons',     icon: 'book-open',       count: tabData.lessons?.lessons?.length ?? null },
  { id: 'gradebook',   label: 'Gradebook',   icon: 'chart-bar',       count: null },
  { id: 'attendance',  label: 'Attendance',  icon: 'clipboard-check', count: tabData.attendance?.sessions?.length ?? null },
])

function tabLabel(id) {
  return tabs.value.find(t => t.id === id)?.label ?? 'content'
}

/* ═════════════════════════════════════════════════════════ */
/* TAB LOADING                                               */
/* ═════════════════════════════════════════════════════════ */
async function loadTab(id) {
  if (id === 'students' || tabData[id] || tabLoading[id]) return
  tabLoading[id] = true
  try {
    const url = {
      assignments: route('teacher.classes.assignments.index', props.classroom.id),
      lessons:     route('teacher.classes.lessons.index', props.classroom.id),
      gradebook:   route('teacher.classes.gradebook.show', props.classroom.id),
      attendance:  route('teacher.classes.attendance.index', props.classroom.id),
    }[id]
    if (!url) return
    const { data } = await axios.get(url, { headers: { Accept: 'application/json' } })
    tabData[id] = data
  } catch (e) {
    console.error(`Failed to load tab "${id}"`, e)
  } finally {
    tabLoading[id] = false
  }
}

function refreshTab(id) {
  tabData[id] = null
  loadTab(id)
}

function selectTab(id) {
  activeTab.value = id
  loadTab(id)
}

onMounted(() => {
  try {
    const params = new URLSearchParams(window.location.search)
    const tab = params.get('tab')
    if (tab && tabs.value.some(t => t.id === tab) && tab !== activeTab.value) {
      activeTab.value = tab
      loadTab(tab)
    }
  } catch (e) {
    // Malformed URL — stay on Students
  }
})

/* ═════════════════════════════════════════════════════════ */
/* ATTENDANCE MARKING STATE                                  */
/* ═════════════════════════════════════════════════════════ */
const selectedDate     = ref(null)
const attendanceRoster = ref([])
const isMarkingLoading = ref(false)
const isSaving         = ref(false)
const saveError        = ref('')

const ATT_STATUSES = [
  { value: 'present', label: 'Present', short: 'P', chip: 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-900/40' },
  { value: 'late',    label: 'Late',    short: 'L', chip: 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900/40' },
  { value: 'absent',  label: 'Absent',  short: 'A', chip: 'bg-rose-100 text-rose-800 border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-900/40' },
  { value: 'excused', label: 'Excused', short: 'E', chip: 'bg-sky-100 text-sky-800 border-sky-200 dark:bg-sky-950/60 dark:text-sky-300 dark:border-sky-900/40' },
]

async function openAttendanceFor(date) {
  const clean = toDateParam(date)
  if (!clean) return

  selectedDate.value     = clean
  attendanceRoster.value = []
  saveError.value        = ''
  isMarkingLoading.value = true

  try {
    const { data } = await axios.get(
      route('teacher.classes.attendance.session', {
        classroom: props.classroom.id,
        date:      clean,
      }),
      { headers: { Accept: 'application/json' } }
    )

    // Controller returns `students` — each row has `{ id, name, lrn, status, notes }`.
    // We normalize to `student_id` for our table + the save payload.
    attendanceRoster.value = (data.students || []).map(r => ({
      student_id: r.id,
      name:       r.name,
      lrn:        r.lrn,
      status:     r.status ?? 'present',
      notes:      r.notes ?? '',
    }))
  } catch (e) {
    saveError.value = e.response?.data?.message || 'Failed to load attendance roster.'
  } finally {
    isMarkingLoading.value = false
  }
}

async function saveAttendance() {
  if (!selectedDate.value || !attendanceRoster.value.length) return

  isSaving.value   = true
  saveError.value  = ''

  try {
    await axios.post(
      route('teacher.classes.attendance.mark', {
        classroom: props.classroom.id,
        date:      selectedDate.value,
      }),
      {
        // Controller expects `records` (MarkAttendanceRequest).
        records: attendanceRoster.value.map(r => ({
          student_id: r.student_id,
          status:     r.status,
          notes:      r.notes || null,
        })),
      },
      { headers: { Accept: 'application/json' } }
    )

    refreshTab('attendance')
    closeAttendanceSession()
  } catch (e) {
    saveError.value = e.response?.data?.message || 'Failed to save attendance.'
  } finally {
    isSaving.value = false
  }
}

function closeAttendanceSession() {
  selectedDate.value = null
  attendanceRoster.value = []
  saveError.value = ''
}

/* ═════════════════════════════════════════════════════════ */
/* SNAPSHOT CARDS (dynamic per tab)                          */
/* ═════════════════════════════════════════════════════════ */
const snapshotCards = computed(() => {
  if (activeTab.value === 'students') {
    return [
      { label: 'Total Roster',  icon: 'users',        iconClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40', value: props.students.length, hint: 'Learners on file' },
      { label: 'Active',        icon: 'check-circle', iconClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40', value: statusCounts.value.active, hint: 'Currently enrolled', valueClass: 'text-[#005506] dark:text-[#86EFAC]' },
      { label: 'F / M',         icon: 'user',         iconClass: 'bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-100 dark:border-amber-900/40', value: `${genderCounts.value.female}/${genderCounts.value.male}`, hint: 'Gender breakdown' },
      { label: 'Dropped',       icon: 'x-circle',     iconClass: 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-900/40', value: statusCounts.value.dropped, hint: 'Transferred / dropped', valueClass: 'text-rose-600 dark:text-rose-400' },
    ]
  }
  if (activeTab.value === 'assignments') {
    return [
      { label: 'Total',          icon: 'clipboard-list', iconClass: 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-900/40', value: assignmentsByStatus.value.total, hint: 'All assignments' },
      { label: 'Published',      icon: 'check-circle',   iconClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40', value: assignmentsByStatus.value.published, hint: 'Visible to learners', valueClass: 'text-[#005506] dark:text-[#86EFAC]' },
      { label: 'Draft',          icon: 'edit',           iconClass: 'bg-slate-50 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]', value: assignmentsByStatus.value.draft, hint: 'Not yet published' },
      { label: 'Submissions',    icon: 'arrow-right',    iconClass: 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-100 dark:border-blue-900/40', value: assignmentsByStatus.value.submissions, hint: 'Total turn-ins' },
    ]
  }
  if (activeTab.value === 'lessons') {
    return [
      { label: 'Total Lessons',  icon: 'book-open',      iconClass: 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-100 dark:border-blue-900/40', value: lessonsByStatus.value.total, hint: 'Across this class' },
      { label: 'Published',      icon: 'check-circle',   iconClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40', value: lessonsByStatus.value.published, hint: 'Visible to learners', valueClass: 'text-[#005506] dark:text-[#86EFAC]' },
      { label: 'Draft',          icon: 'edit',           iconClass: 'bg-slate-50 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]', value: lessonsByStatus.value.draft, hint: 'Not yet published' },
      { label: 'Latest',         icon: 'sparkles',       iconClass: 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-900/40', value: lessonsByStatus.value.latest, hint: 'Most recent title', valueClass: 'text-base font-black truncate' },
    ]
  }
  if (activeTab.value === 'gradebook') {
    const s = tabData.gradebook?.summary ?? {}
    return [
      { label: 'Class Size',     icon: 'users',       iconClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40', value: s.total_students ?? 0, hint: 'Learners with grades' },
      { label: 'Passing',        icon: 'check-circle', iconClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40', value: s.passing ?? 0, hint: 'Met expectations', valueClass: 'text-[#005506] dark:text-[#86EFAC]' },
      { label: 'Failing',        icon: 'alert-triangle', iconClass: 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-900/40', value: s.failing ?? 0, hint: 'Below 75', valueClass: 'text-rose-600 dark:text-rose-400' },
      { label: 'Class Average',  icon: 'chart-bar',   iconClass: 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-900/40', value: s.class_average ? Number(s.class_average).toFixed(2) : '—', hint: 'Weighted mean' },
    ]
  }
  if (activeTab.value === 'attendance') {
    const sessions = tabData.attendance?.sessions ?? []
    const tot = sessions.reduce((a, s) => ({
      present: a.present + (s.present || 0),
      absent:  a.absent  + (s.absent  || 0),
      late:    a.late    + (s.late    || 0),
      excused: a.excused + (s.excused || 0),
    }), { present: 0, absent: 0, late: 0, excused: 0 })
    const denom = tot.present + tot.absent + tot.late
    const rate = denom > 0 ? Math.round(((tot.present + tot.late) / denom) * 100) : 0
    return [
      { label: 'Sessions',        icon: 'calendar',       iconClass: 'bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border-sky-100 dark:border-sky-900/40', value: sessions.length, hint: 'Days recorded' },
      { label: 'Present',         icon: 'check-circle',   iconClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40', value: tot.present, hint: 'Total present', valueClass: 'text-[#005506] dark:text-[#86EFAC]' },
      { label: 'Late / Absent',   icon: 'clock',          iconClass: 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-900/40', value: `${tot.late}/${tot.absent}`, hint: 'Across sessions' },
      { label: 'Attendance Rate', icon: 'target',         iconClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40', value: `${rate}%`, hint: 'Present + Late / Marked', valueClass: 'text-[#005506] dark:text-[#86EFAC]' },
    ]
  }
  return []
})

/* ═════════════════════════════════════════════════════════ */
/* STUDENTS TAB                                              */
/* ═════════════════════════════════════════════════════════ */
const statusCounts = computed(() => {
  const s = { active: 0, dropped: 0, completed: 0 }
  props.students.forEach(st => {
    const k = st.status || 'active'
    s[k] = (s[k] || 0) + 1
  })
  return s
})

const genderCounts = computed(() => {
  const g = { male: 0, female: 0 }
  props.students.forEach(st => {
    const k = (st.sex || '').toLowerCase()
    if (k === 'male' || k === 'female') g[k]++
  })
  return g
})

const rosterSearch = ref('')
const statusFilter = ref('all')

const statusFilters = computed(() => [
  { id: 'all',       label: 'All',       count: props.students.length },
  { id: 'active',    label: 'Active',    count: statusCounts.value.active || 0 },
  { id: 'dropped',   label: 'Dropped',   count: statusCounts.value.dropped || 0 },
  { id: 'completed', label: 'Completed', count: statusCounts.value.completed || 0 },
].filter(f => f.id === 'all' || f.count > 0))

const filteredStudents = computed(() => {
  const q = rosterSearch.value.trim().toLowerCase()
  return props.students.filter(s => {
    const matchesStatus = statusFilter.value === 'all' || (s.status || 'active') === statusFilter.value
    const matchesSearch = !q ||
      (s.name || '').toLowerCase().includes(q) ||
      (s.lrn || '').toLowerCase().includes(q) ||
      (s.email || '').toLowerCase().includes(q)
    return matchesStatus && matchesSearch
  })
})

const perPage = 15
const page = ref(1)
watch([rosterSearch, statusFilter], () => { page.value = 1 })

const totalPages    = computed(() => Math.max(1, Math.ceil(filteredStudents.value.length / perPage)))
const pageStart     = computed(() => filteredStudents.value.length === 0 ? 0 : (page.value - 1) * perPage + 1)
const pageEnd       = computed(() => Math.min(page.value * perPage, filteredStudents.value.length))
const pagedStudents = computed(() => {
  const start = (page.value - 1) * perPage
  return filteredStudents.value.slice(start, start + perPage)
})

function resetRosterFilters() {
  rosterSearch.value = ''
  statusFilter.value = 'all'
}

/* ═════════════════════════════════════════════════════════ */
/* DERIVED STATS                                             */
/* ═════════════════════════════════════════════════════════ */
const assignmentsByStatus = computed(() => {
  const list = tabData.assignments?.assignments ?? []
  return {
    total:       list.length,
    published:   list.filter(a => a.is_published).length,
    draft:       list.filter(a => !a.is_published).length,
    submissions: list.reduce((sum, a) => sum + (a.submissions_count || 0), 0),
  }
})

const lessonsByStatus = computed(() => {
  const list = tabData.lessons?.lessons ?? []
  return {
    total:     list.length,
    published: list.filter(l => l.is_published).length,
    draft:     list.filter(l => !l.is_published).length,
    latest:    list[0]?.title ?? '—',
  }
})

/* ═════════════════════════════════════════════════════════ */
/* EXPORTS / ENROLL MODAL                                    */
/* ═════════════════════════════════════════════════════════ */
const showExportMenu  = ref(false)
const showEnrollModal = ref(false)

function openEnrollModal() { showEnrollModal.value = true }

/* ═════════════════════════════════════════════════════════ */
/* HELPERS                                                   */
/* ═════════════════════════════════════════════════════════ */
function initialsOf(name) {
  if (!name) return '?'
  const parts = String(name).trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
}

function formatScore(v) {
  if (v === null || v === undefined || v === '') return '—'
  const n = parseFloat(v)
  return isNaN(n) ? '—' : n.toFixed(2)
}

function scoreColor(v) {
  if (v === null || v === undefined || v === '') return 'text-slate-400 dark:text-slate-500'
  const n = parseFloat(v)
  if (isNaN(n)) return 'text-slate-400 dark:text-slate-500'
  if (n >= 90) return 'text-[#005506] dark:text-[#86EFAC]'
  if (n >= 85) return 'text-teal-700 dark:text-teal-400'
  if (n >= 80) return 'text-blue-700 dark:text-blue-400'
  if (n >= 75) return 'text-amber-700 dark:text-amber-400'
  return 'text-rose-600 dark:text-rose-400'
}

function remarksLabel(r) {
  return {
    outstanding:               'Outstanding',
    very_satisfactory:         'Very Satisfactory',
    satisfactory:              'Satisfactory',
    fairly_satisfactory:       'Fairly Satisfactory',
    did_not_meet_expectations: 'Did Not Meet',
  }[r] || r
}

function remarksBadgeClass(r) {
  return {
    outstanding:               'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40',
    very_satisfactory:         'bg-teal-100 dark:bg-teal-950/60 text-teal-800 dark:text-teal-300 border-teal-200 dark:border-teal-900/40',
    satisfactory:              'bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-900/40',
    fairly_satisfactory:       'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-900/40',
    did_not_meet_expectations: 'bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-300 border-rose-200 dark:border-rose-900/40',
  }[r] || 'bg-slate-100 text-slate-700 border-slate-200'
}

function categoryLabel(cat) {
  return {
    written_work:     'Written Work',
    performance_task: 'Performance Task',
    quarterly_exam:   'Quarterly Exam',
  }[cat] || 'General'
}

function categoryBadgeClass(cat) {
  const base = 'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-md border'
  const map = {
    written_work:     'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-[#86EFAC] border-emerald-200/60 dark:border-emerald-900/40',
    performance_task: 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/60 dark:border-amber-900/40',
    quarterly_exam:   'bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border-blue-200/60 dark:border-blue-900/40',
  }
  return `${base} ${map[cat] || 'bg-slate-100 dark:bg-[#232D26] text-slate-700 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'}`
}

function formatShort(v) {
  if (!v) return '—'
  try { return new Date(v).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) }
  catch { return '—' }
}

function formatLongDate(v) {
  const s = toDateParam(v)
  if (!s) return '—'
  const d = new Date(s + 'T00:00:00')
  if (isNaN(d.getTime())) return String(v)
  return d.toLocaleDateString('en-US', {
    weekday: 'long', month: 'long', day: 'numeric', year: 'numeric',
  })
}

/* ═════════════════════════════════════════════════════════ */
/* ANIMATIONS                                                */
/* ═════════════════════════════════════════════════════════ */
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
@keyframes fadeInDown  { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up  { animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

.tab-swap-enter-active { transition: opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1), transform 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
.tab-swap-leave-active { transition: opacity 0.18s ease-in, transform 0.18s ease-in; }
.tab-swap-enter-from { opacity: 0; transform: translateY(14px); }
.tab-swap-leave-to   { opacity: 0; transform: translateY(-8px); }

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
</style>