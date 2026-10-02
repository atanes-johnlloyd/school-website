<template>
  <Head title="Assignment Hub - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search assignments, classes, tasks..">

    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

      <!-- HERO -->
      <div v-observe
        class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center anim-fade-down">
        <img :src="heroImage" alt="Assignment Hub Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 flex flex-col justify-center space-y-3 sm:space-y-4">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <span class="bg-[#005506] text-white border border-emerald-400/30 text-[10px] sm:text-[11px] md:text-xs font-bold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="clipboard-list" size="xs" />
              {{ stats.total }} {{ stats.total === 1 ? 'Task' : 'Tasks' }}
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="academic-cap" size="xs" />
              {{ classrooms.length }} {{ classrooms.length === 1 ? 'Class' : 'Classes' }}
            </span>
            <span v-if="stats.pending_grading > 0"
              class="bg-rose-500 text-white border border-rose-300/30 text-[10px] sm:text-[11px] md:text-xs font-black px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5 animate-pulse">
              <Icon icon="alert-triangle" size="xs" />
              {{ stats.pending_grading }} Need Grading
            </span>
          </div>

          <div class="space-y-1.5 sm:space-y-2 max-w-3xl">
            <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
              Assignment & Classwork Hub
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              All assignments across every class you teach. Filter, search, grade in queue.
            </p>
          </div>

          <!-- New Assignment class picker -->
          <div class="flex flex-wrap gap-2 pt-2">
            <div class="relative">
              <button @click="showClassPicker = !showClassPicker"
                class="inline-flex items-center gap-1.5 bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] px-3.5 py-2 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95">
                <Icon icon="plus" size="xs" />
                New Assignment
                <Icon icon="chevron-down" size="xs" />
              </button>
              <div v-if="showClassPicker"
                class="absolute left-0 top-full mt-2 w-72 bg-white dark:bg-[#2D3A31] rounded-2xl shadow-xl border border-slate-200 dark:border-[#3F4F43] overflow-hidden z-40">
                <div class="px-4 py-2.5 border-b border-slate-100 dark:border-[#3F4F43] bg-slate-50 dark:bg-[#232D26]">
                  <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Pick a Class</span>
                </div>
                <Link v-for="c in classrooms" :key="c.id"
                  :href="route('teacher.classes.assignments.create', c.id)"
                  @click="showClassPicker = false"
                  class="block px-4 py-3 border-b border-slate-100 dark:border-[#3F4F43] last:border-b-0 hover:bg-slate-50 dark:hover:bg-[#3F4F43] transition-colors">
                  <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">{{ c.subject }}</p>
                  <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">{{ c.section }} • {{ c.code }}</p>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- STATS -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3"
          style="animation-delay: 100ms;">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Tasks</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="clipboard-list" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ stats.total }}</div>
          <p class="text-[11px] font-medium text-slate-500">Across {{ classrooms.length }} classes</p>
        </div>

        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3"
          style="animation-delay: 150ms;">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Published</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="check-circle" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-[#005506] dark:text-[#86EFAC]">{{ stats.published }}</div>
          <p class="text-[11px] font-medium text-slate-500">Visible to students</p>
        </div>

        <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3"
          style="animation-delay: 200ms;">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Turn-ins</span>
            <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-900/40">
              <Icon icon="users" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ stats.total_submissions }}</div>
          <p class="text-[11px] font-medium text-slate-500">Submissions received</p>
        </div>

        <div v-observe
          :class="stats.pending_grading > 0
            ? 'bg-rose-50 dark:bg-rose-950/30 border-rose-200/80 dark:border-rose-900/40'
            : 'bg-white dark:bg-[#2D3A31] border-slate-200/60 dark:border-[#3F4F43]'"
          class="anim-slide-up rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border flex flex-col justify-between space-y-3"
          style="animation-delay: 250ms;">
          <div class="flex items-start justify-between">
            <span :class="stats.pending_grading > 0 ? 'text-rose-700 dark:text-rose-400' : 'text-slate-500 dark:text-slate-400'"
              class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider">Needs Grading</span>
            <div :class="stats.pending_grading > 0
              ? 'bg-rose-100 dark:bg-rose-900/50 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-900/50'
              : 'bg-slate-100 dark:bg-[#3F4F43] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'"
              class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 border">
              <Icon icon="alert-triangle" size="sm" />
            </div>
          </div>
          <div :class="stats.pending_grading > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-white'"
            class="text-2xl sm:text-3xl font-black">{{ stats.pending_grading }}</div>
          <p :class="stats.pending_grading > 0 ? 'text-rose-700 dark:text-rose-400 font-bold' : 'text-slate-500'"
            class="text-[11px] font-medium">
            {{ stats.pending_grading > 0 ? 'Priority action needed' : 'All caught up' }}
          </p>
        </div>
      </div>

      <!-- FILTER BAR -->
      <div v-observe
        class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-3.5 sm:p-4 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3"
        style="animation-delay: 300ms;">

        <!-- Row 1: Class + status -->
        <div class="flex flex-col lg:flex-row lg:items-center gap-3">
          <!-- Class selector -->
          <div class="relative w-full lg:w-72 shrink-0">
            <select v-model="classFilter"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-100 text-xs font-extrabold px-3.5 py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer pr-8 truncate">
              <option value="all">All Classes ({{ assignments.length }})</option>
              <option v-for="c in classrooms" :key="c.id" :value="c.id">
                {{ c.subject }} — {{ c.section }}
              </option>
            </select>
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
        </div>

        <!-- Row 2: Category -->
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pt-1 border-t border-slate-100 dark:border-[#3F4F43]">
          <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 shrink-0 pr-1">Category</span>
          <button v-for="f in categoryFilters" :key="f.id" @click="categoryFilter = f.id"
            :class="[
              'text-[11px] font-bold px-3 py-1.5 rounded-xl transition-all shrink-0 active:scale-95',
              categoryFilter === f.id
                ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border border-emerald-300/60 dark:border-emerald-900/50 font-black'
                : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43]'
            ]">
            {{ f.label }} ({{ f.count }})
          </button>
        </div>
      </div>

      <!-- MAIN LAYOUT: LIST + DRAWER -->
      <div class="flex flex-col lg:flex-row gap-4 sm:gap-6 items-start">

        <!-- LEFT: ASSIGNMENT LIST -->
        <div class="flex-1 min-w-0 space-y-4 w-full">

          <div v-if="filteredAssignments.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="(a, idx) in filteredAssignments" :key="a.id"
              v-observe
              :style="{ animationDelay: `${idx * 40}ms` }"
              :class="[
                'anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm border flex flex-col justify-between space-y-3.5 transition-all',
                drawerAssignment?.id === a.id
                  ? 'border-amber-400 dark:border-amber-500 ring-2 ring-amber-300/50'
                  : 'border-slate-200/60 dark:border-[#3F4F43] hover:-translate-y-1 hover:shadow-md'
              ]">

              <div class="space-y-3">
                <!-- Tags -->
                <div class="flex items-center justify-between gap-2 flex-wrap">
                  <div class="flex items-center gap-1.5 flex-wrap">
                    <span :class="categoryBadgeClass(a.category)">
                      {{ categoryLabel(a.category) }}
                    </span>
                    <span class="bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-md">
                      {{ a.points }} pts
                    </span>
                  </div>
                  <span :class="a.is_published
                    ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC]'
                    : 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400'"
                    class="text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">
                    {{ a.is_published ? 'Published' : 'Draft' }}
                  </span>
                </div>

                <!-- Class context -->
                <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500 dark:text-slate-400">
                  <Icon icon="academic-cap" size="xs" />
                  <span class="truncate">{{ a.subject }} • {{ a.section }}</span>
                </div>

                <!-- Title -->
                <h4 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white leading-snug line-clamp-2">
                  {{ a.title }}
                </h4>

                <!-- Meta -->
                <div class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] space-y-2 text-xs">
                  <div class="flex items-center justify-between text-slate-600 dark:text-slate-300 font-semibold">
                    <span class="flex items-center gap-1.5">
                      <Icon icon="calendar" size="xs" class="text-slate-400" />
                      Due
                    </span>
                    <span :class="isOverdue(a) ? 'text-rose-600 dark:text-rose-400 font-bold' : 'text-slate-800 dark:text-slate-200 font-bold'">
                      {{ a.due_at ? formatShort(a.due_at) : '—' }}
                    </span>
                  </div>

                  <div class="flex items-center justify-between font-semibold">
                    <span class="flex items-center gap-1.5 text-slate-600 dark:text-slate-300">
                      <Icon icon="users" size="xs" class="text-slate-400" />
                      Turn-ins
                    </span>
                    <span class="text-slate-800 dark:text-slate-200 font-bold">
                      {{ a.submissions_count }}
                    </span>
                  </div>

                  <div v-if="a.pending_count > 0" class="flex items-center justify-between font-bold">
                    <span class="flex items-center gap-1.5 text-rose-600 dark:text-rose-400">
                      <Icon icon="alert-triangle" size="xs" />
                      Pending grading
                    </span>
                    <span class="text-rose-700 dark:text-rose-400 font-black">
                      {{ a.pending_count }}
                    </span>
                  </div>

                  <div v-else-if="a.graded_count > 0" class="flex items-center justify-between font-bold">
                    <span class="flex items-center gap-1.5 text-emerald-700 dark:text-[#86EFAC]">
                      <Icon icon="check-circle" size="xs" />
                      All graded
                    </span>
                    <span class="text-emerald-700 dark:text-[#86EFAC] font-black">
                      {{ a.graded_count }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                <button v-if="a.pending_count > 0"
                  @click="openDrawer(a)"
                  class="w-full bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] text-[11px] font-black py-2.5 rounded-xl shadow-sm flex items-center justify-center gap-1.5 transition-all active:scale-95">
                  <Icon icon="clipboard-check" size="xs" />
                  Open Grading Queue ({{ a.pending_count }})
                </button>
                <button v-else
                  @click="openDrawer(a)"
                  class="w-full bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] font-bold py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
                  <Icon icon="eye" size="xs" />
                  Review Submissions
                </button>

                <div class="grid grid-cols-2 gap-2">
                  <Link :href="route('teacher.assignments.edit', a.id)"
                    class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] font-bold py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
                    <Icon icon="edit" size="xs" />
                    Edit
                  </Link>
                  <Link :href="route('teacher.assignments.show', a.id)"
                    class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] font-bold py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
                    <Icon icon="external-link" size="xs" />
                    Details
                  </Link>
                </div>
              </div>
            </div>
          </div>

          <!-- EMPTY -->
          <div v-else
            class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-12 shadow-sm border border-dashed border-slate-300 dark:border-[#3F4F43] text-center space-y-3">
            <Icon icon="clipboard-list" size="xl" class="text-slate-400 mx-auto" />
            <p class="text-sm font-bold text-slate-800 dark:text-white">
              {{ assignments.length === 0 ? 'No assignments yet' : 'No assignments match your filters' }}
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              {{ assignments.length === 0 ? 'Create your first assignment to get started.' : 'Try clearing some filters.' }}
            </p>
          </div>
        </div>

        <!-- RIGHT: DRAWER -->
        <Transition name="drawer-slide">
          <div v-if="drawerAssignment"
            class="w-full lg:w-[420px] xl:w-[480px] shrink-0 sticky top-4">

            <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-xl border border-slate-200 dark:border-[#3F4F43] space-y-4">

              <!-- Header -->
              <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
                <div class="min-w-0">
                  <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">GRADING QUEUE</span>
                  <h3 class="text-sm font-extrabold text-slate-900 dark:text-white truncate">
                    {{ drawerAssignment.title }}
                  </h3>
                  <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                    {{ drawerAssignment.subject }} • {{ drawerAssignment.section }}
                  </p>
                </div>
                <button @click="closeDrawer"
                  class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors shrink-0">
                  <Icon icon="x" size="xs" />
                </button>
              </div>

              <!-- Loading -->
              <div v-if="drawerLoading" class="flex flex-col items-center justify-center py-12 space-y-3">
                <div class="w-8 h-8 border-4 border-emerald-200 dark:border-emerald-900/60 border-t-[#005506] dark:border-t-[#86EFAC] rounded-full animate-spin"></div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Loading submissions…</p>
              </div>

              <!-- Empty -->
              <div v-else-if="!drawerSubmissions.length"
                class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-8 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
                <Icon icon="users" size="lg" class="text-slate-400 mx-auto" />
                <p class="text-xs font-bold text-slate-700 dark:text-white">No submissions to grade</p>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">Students haven't turned anything in yet.</p>
              </div>

              <!-- Submission -->
              <template v-else>
                <!-- Queue nav -->
                <div class="flex items-center justify-between bg-[#F9F7F1] dark:bg-[#232D26] p-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43]">
                  <button @click="prevInQueue" :disabled="drawerIndex === 0"
                    class="w-8 h-8 rounded-lg bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                    <Icon icon="chevron-left" size="xs" />
                  </button>
                  <div class="text-center">
                    <span class="text-xs font-black text-slate-900 dark:text-white block">
                      {{ drawerIndex + 1 }} of {{ drawerSubmissions.length }}
                    </span>
                    <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">
                      {{ currentSubmission?.status === 'graded' ? 'Graded' : 'Pending' }}
                    </span>
                  </div>
                  <button @click="nextInQueue" :disabled="drawerIndex >= drawerSubmissions.length - 1"
                    class="w-8 h-8 rounded-lg bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                    <Icon icon="chevron-right" size="xs" />
                  </button>
                </div>

                <!-- Student -->
                <div v-if="currentSubmission"
                  class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-2">
                  <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-[#005506] text-white font-extrabold text-xs flex items-center justify-center shrink-0">
                      {{ initialsOf(currentSubmission.student_name) }}
                    </div>
                    <div class="min-w-0">
                      <p class="text-xs font-extrabold text-slate-900 dark:text-white truncate">
                        {{ currentSubmission.student_name }}
                      </p>
                      <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 truncate">
                        {{ currentSubmission.submitted_at ? formatShort(currentSubmission.submitted_at) : '—' }}
                        <span v-if="currentSubmission.status === 'late'" class="text-amber-600 dark:text-amber-400"> • Late</span>
                      </p>
                    </div>
                  </div>
                  <span :class="statusColor(currentSubmission.status)"
                    class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full border shrink-0">
                    {{ currentSubmission.status }}
                  </span>
                </div>

                <!-- Submission content -->
                <div class="space-y-2">
                  <div v-if="currentSubmission?.text_content"
                    class="bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-3 text-xs text-slate-700 dark:text-slate-300 max-h-32 overflow-auto space-y-1">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Student Note</span>
                    <p class="whitespace-pre-wrap">{{ currentSubmission.text_content }}</p>
                  </div>

                  <div v-if="currentSubmission?.has_file"
                    class="bg-emerald-50/60 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-900/40 rounded-xl p-3 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                      <Icon icon="paper-clip" size="sm" class="text-emerald-700 dark:text-[#86EFAC] shrink-0" />
                      <span class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate">Attached file</span>
                    </div>
                    <a :href="currentSubmission.download_url" target="_blank"
                      class="bg-[#005506] hover:bg-[#004105] text-white text-[11px] font-bold px-2.5 py-1.5 rounded-lg shrink-0">
                      Download
                    </a>
                  </div>

                  <div v-if="!currentSubmission?.text_content && !currentSubmission?.has_file"
                    class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-xl p-4 text-center border border-dashed border-slate-300 dark:border-[#3F4F43]">
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 italic">No content submitted</p>
                  </div>
                </div>

                <!-- Grade form -->
                <div class="space-y-3 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                  <div class="space-y-1">
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                      Grade (out of {{ drawerAssignment.points }})
                    </label>
                    <input v-model="gradeForm.grade" type="number" step="0.01" min="0" :max="drawerAssignment.points"
                      placeholder="e.g. 95"
                      class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] dark:focus:ring-[#86EFAC]" />
                    <p v-if="gradeForm.errors.grade" class="text-rose-600 dark:text-rose-400 text-[10px] font-semibold mt-1">
                      {{ gradeForm.errors.grade }}
                    </p>
                  </div>

                  <div class="space-y-1">
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                      Feedback
                    </label>
                    <textarea v-model="gradeForm.feedback" rows="3"
                      placeholder="Optional notes for the student…"
                      class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-3 text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] dark:focus:ring-[#86EFAC]"></textarea>
                  </div>

                  <div class="flex items-center gap-2 pt-1">
                    <button @click="saveGrade(false)" :disabled="gradeForm.processing"
                      class="flex-1 bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-xs font-bold py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors disabled:opacity-60">
                      <Icon icon="check-circle" size="xs" />
                      {{ gradeForm.processing ? 'Saving…' : 'Save' }}
                    </button>
                    <button @click="saveGrade(true)"
                      :disabled="gradeForm.processing || drawerIndex >= drawerSubmissions.length - 1"
                      class="flex-1 bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] text-xs font-black py-2.5 rounded-xl shadow-sm flex items-center justify-center gap-1.5 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                      <Icon icon="arrow-right" size="xs" />
                      Save & Next
                    </button>
                  </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between text-[10px] text-slate-400 dark:text-slate-500 font-semibold pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
                  <span>{{ pendingInQueue }} pending in queue</span>
                  <Link :href="route('teacher.assignments.show', drawerAssignment.id)"
                    class="hover:text-[#005506] dark:hover:text-[#86EFAC] transition-colors">
                    Full detail →
                  </Link>
                </div>
              </template>

            </div>
          </div>
        </Transition>

      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/assignment.png'

/* ═══════════════════════════════════════════════════════ */
const props = defineProps({
  assignments: { type: Array,  default: () => [] },
  classrooms:  { type: Array,  default: () => [] },
  stats:       { type: Object, default: () => ({ total: 0, published: 0, draft: 0, total_submissions: 0, pending_grading: 0 }) },
  active_term: { type: String, default: null },
})

/* ─── Filters ─────────────────────────────────────────── */
const classFilter    = ref('all')
const statusFilter   = ref('all')
const categoryFilter = ref('all')

const statusFilters = computed(() => [
  { id: 'all',           label: 'All',           count: props.assignments.length },
  { id: 'needs_grading', label: 'Needs Grading', count: props.assignments.filter(a => a.pending_count > 0).length },
  { id: 'published',     label: 'Published',     count: props.assignments.filter(a => a.is_published).length },
  { id: 'draft',         label: 'Drafts',        count: props.assignments.filter(a => !a.is_published).length },
].filter(f => f.id === 'all' || f.count > 0))

const categoryFilters = computed(() => [
  { id: 'all',              label: 'All',              count: props.assignments.length },
  { id: 'written_work',     label: 'Written Work',     count: props.assignments.filter(a => a.category === 'written_work').length },
  { id: 'performance_task', label: 'Performance Task', count: props.assignments.filter(a => a.category === 'performance_task').length },
  { id: 'quarterly_exam',   label: 'Quarterly Exam',   count: props.assignments.filter(a => a.category === 'quarterly_exam').length },
].filter(f => f.id === 'all' || f.count > 0))

const filteredAssignments = computed(() => {
  return props.assignments.filter(a => {
    if (classFilter.value !== 'all' && a.classroom_id !== classFilter.value) return false
    if (categoryFilter.value !== 'all' && a.category !== categoryFilter.value) return false
    if (statusFilter.value === 'needs_grading' && a.pending_count === 0) return false
    if (statusFilter.value === 'published' && !a.is_published) return false
    if (statusFilter.value === 'draft' && a.is_published) return false
    return true
  })
})

/* ─── New Assignment picker ───────────────────────────── */
const showClassPicker = ref(false)

/* ─── Drawer state ────────────────────────────────────── */
const drawerAssignment  = ref(null)
const drawerSubmissions = ref([])
const drawerIndex       = ref(0)
const drawerLoading     = ref(false)

const gradeForm = useForm({ grade: '', feedback: '' })

const currentSubmission = computed(() => drawerSubmissions.value[drawerIndex.value] ?? null)

const pendingInQueue = computed(() =>
  drawerSubmissions.value.filter(s => s.graded_at === null).length
)

async function openDrawer(assignment) {
  drawerAssignment.value = assignment
  drawerSubmissions.value = []
  drawerIndex.value = 0
  drawerLoading.value = true
  gradeForm.clearErrors()
  gradeForm.reset()

  try {
    const { data } = await axios.get(
      route('teacher.assignments.show', assignment.id),
      { headers: { Accept: 'application/json' } }
    )

    // Filter out students with no submission and sort ungraded first
    const list = (data.submissions || [])
      .filter(s => s.status && s.status !== 'not_submitted')
      .sort((a, b) => {
        const aUngraded = a.graded_at === null
        const bUngraded = b.graded_at === null
        if (aUngraded !== bUngraded) return aUngraded ? -1 : 1
        return new Date(a.submitted_at) - new Date(b.submitted_at)
      })

    drawerSubmissions.value = list
    syncFormToIndex()
  } catch (e) {
    console.error('Failed to load submissions', e)
  } finally {
    drawerLoading.value = false
  }
}

function closeDrawer() {
  drawerAssignment.value = null
  drawerSubmissions.value = []
  drawerIndex.value = 0
  gradeForm.reset()
  gradeForm.clearErrors()
}

function syncFormToIndex() {
  const s = currentSubmission.value
  if (!s) {
    gradeForm.grade = ''
    gradeForm.feedback = ''
    return
  }
  gradeForm.grade = s.grade ?? ''
  gradeForm.feedback = s.feedback ?? ''
  gradeForm.clearErrors()
}

watch(drawerIndex, syncFormToIndex)

function nextInQueue() {
  if (drawerIndex.value < drawerSubmissions.value.length - 1) drawerIndex.value++
}

function prevInQueue() {
  if (drawerIndex.value > 0) drawerIndex.value--
}

async function saveGrade(advance = false) {
  const submission = currentSubmission.value
  if (!submission) return

  gradeForm.put(route('teacher.submissions.grade', submission.id), {
    preserveScroll: true,
    onSuccess: () => {
      // Update local copy so the UI reflects the grade immediately
      const idx = drawerSubmissions.value.findIndex(s => s.id === submission.id)
      if (idx !== -1) {
        drawerSubmissions.value[idx] = {
          ...drawerSubmissions.value[idx],
          grade: gradeForm.grade,
          feedback: gradeForm.feedback,
          status: 'graded',
          graded_at: new Date().toISOString(),
        }
      }
      if (advance) {
        // Advance to the next ungraded or the next item
        const nextUngraded = drawerSubmissions.value.findIndex(
          (s, i) => i > drawerIndex.value && s.graded_at === null
        )
        if (nextUngraded !== -1) {
          drawerIndex.value = nextUngraded
        } else if (drawerIndex.value < drawerSubmissions.value.length - 1) {
          drawerIndex.value++
        }
      }
    },
  })
}

/* ─── Helpers ─────────────────────────────────────────── */
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

function statusColor(status) {
  return {
    submitted:     'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-900/40',
    late:          'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-900/40',
    graded:        'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-950/60 dark:text-[#86EFAC] dark:border-emerald-900/40',
    not_submitted: 'bg-slate-100 text-slate-500 border-slate-200 dark:bg-[#232D26] dark:text-slate-400 dark:border-[#3F4F43]',
  }[status] || 'bg-slate-100 text-slate-700'
}

function initialsOf(name) {
  if (!name) return '?'
  const parts = String(name).trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
}

function isOverdue(a) {
  return a.due_at && new Date(a.due_at) < new Date()
}

function formatShort(v) {
  if (!v) return '—'
  try {
    return new Date(v).toLocaleDateString('en-US', {
      month: 'short', day: 'numeric',
      hour: 'numeric', minute: '2-digit', hour12: true,
    })
  } catch { return '—' }
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

/* Drawer slide animation */
.drawer-slide-enter-active {
  transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1),
              transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.drawer-slide-leave-active {
  transition: opacity 0.2s ease-in,
              transform 0.2s ease-in;
}
.drawer-slide-enter-from {
  opacity: 0;
  transform: translateX(30px);
}
.drawer-slide-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
</style>