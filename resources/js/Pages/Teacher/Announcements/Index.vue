<template>
  <Head title="Announcements & Class Bulletins - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search announcements, class news, bulletins..">

    <div class="relative z-10 px-4 sm:px-6 md:px-10 pb-24 space-y-6 flex-1 mt-4">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[240px] flex flex-col justify-center p-6 sm:p-8 md:p-10">
        <img :src="heroImage" alt="Announcements Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 max-w-3xl space-y-4">
          <div class="flex flex-wrap items-center gap-2.5">
            <div class="inline-flex items-center gap-2 bg-[#F9C20C] text-[#2C3E2D] font-black text-xs px-4 py-1.5 rounded-full shadow-sm tracking-wide">
              <Icon icon="megaphone" size="xs" />
              ANNOUNCEMENTS
            </div>
            <div class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-xs font-bold px-4 py-1.5 rounded-full shadow-sm">
              <Icon icon="clipboard-list" size="xs" />
              {{ stats.total }} {{ stats.total === 1 ? 'Bulletin' : 'Bulletins' }}
            </div>
            <div v-if="stats.pinned > 0"
              class="inline-flex items-center gap-2 bg-amber-500/90 text-white border border-amber-300/30 text-xs font-black px-4 py-1.5 rounded-full shadow-sm">
              <Icon icon="star" size="xs" />
              {{ stats.pinned }} Pinned
            </div>
            <div v-if="stats.draft > 0"
              class="inline-flex items-center gap-2 bg-slate-500/80 text-white border border-slate-300/30 text-xs font-black px-4 py-1.5 rounded-full shadow-sm">
              <Icon icon="edit" size="xs" />
              {{ stats.draft }} Draft{{ stats.draft === 1 ? '' : 's' }}
            </div>
          </div>

          <div class="space-y-2">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-white tracking-tight leading-tight">
              Announcements & Class Bulletins
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium max-w-2xl">
              Publish updates, reminders, and class news across every section you teach. Pin critical notices to the top of each class feed.
            </p>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <button @click="openCreate"
              class="inline-flex items-center gap-1.5 bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] px-3.5 py-2 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95">
              <Icon icon="plus" size="xs" />
              New Announcement
            </button>
          </div>
        </div>
      </div>

      <!-- STATS -->
      <div v-observe class="anim-slide-up grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5" style="animation-delay: 100ms;">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Posts</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="megaphone" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ stats.total }}</div>
          <p class="text-[11px] font-medium text-slate-500">Across {{ classrooms.length }} classes</p>
        </div>

        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Published</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="check-circle" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-[#005506] dark:text-[#86EFAC]">{{ stats.published }}</div>
          <p class="text-[11px] font-medium text-slate-500">Visible to learners</p>
        </div>

        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Drafts</span>
            <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0 border border-slate-200 dark:border-[#3F4F43]">
              <Icon icon="edit" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-slate-700 dark:text-slate-200">{{ stats.draft }}</div>
          <p class="text-[11px] font-medium text-slate-500">Not yet published</p>
        </div>

        <div :class="stats.pinned > 0
          ? 'bg-amber-50 dark:bg-amber-950/30 border-amber-200/80 dark:border-amber-900/40'
          : 'bg-white dark:bg-[#2D3A31] border-slate-200/60 dark:border-[#3F4F43]'"
          class="rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span :class="stats.pinned > 0 ? 'text-amber-800 dark:text-amber-300' : 'text-slate-500 dark:text-slate-400'"
              class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider">Pinned</span>
            <div :class="stats.pinned > 0
              ? 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900/50'
              : 'bg-slate-100 dark:bg-[#3F4F43] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'"
              class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 border">
              <Icon icon="star" size="sm" />
            </div>
          </div>
          <div :class="stats.pinned > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white'"
            class="text-2xl sm:text-3xl font-black">{{ stats.pinned }}</div>
          <p :class="stats.pinned > 0 ? 'text-amber-700 dark:text-amber-400 font-bold' : 'text-slate-500'"
            class="text-[11px] font-medium">
            {{ stats.pinned > 0 ? 'Featured at top of feed' : 'No pinned posts' }}
          </p>
        </div>
      </div>

      <!-- FILTER BAR -->
      <div v-observe
        class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-3.5 sm:p-4 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3"
        style="animation-delay: 150ms;">

        <div class="flex flex-col lg:flex-row lg:items-center gap-3">
          <!-- Class picker — MOBILE ONLY (desktop uses the right-side panel) -->
          <div class="relative w-full lg:hidden">
            <select v-model="classFilter"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-100 text-xs font-extrabold px-3.5 py-2.5 pr-16 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer truncate">
              <option value="all">All Classes ({{ announcements.length }})</option>
              <option v-for="c in classrooms" :key="c.id" :value="c.id">
                {{ c.subject }} — {{ c.section }} ({{ c.announcements_count }})
              </option>
            </select>

            <!-- Clear button — only shows when a class is selected -->
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
            <input v-model="search" type="text" placeholder="Search announcement title or body..."
              class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-xs font-semibold text-slate-700 dark:text-slate-200" />
          </div>
        </div>
      </div>

      <!-- TWO-COLUMN -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-start">

        <!-- LEFT: ANNOUNCEMENTS LIST -->
        <div class="lg:col-span-8 space-y-5 w-full min-w-0">

          <div v-if="groupedByClass.length" class="space-y-4">
            <div v-for="group in groupedByClass" :key="group.class_id"
              v-observe
              class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4">

              <!-- Class header -->
              <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
                <div class="flex items-start gap-3 min-w-0">
                  <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center font-black text-xs shrink-0 border border-emerald-100 dark:border-emerald-900/40">
                    <Icon icon="academic-cap" size="md" />
                  </div>
                  <div class="space-y-0.5 min-w-0">
                    <h3 class="text-sm sm:text-base font-black text-slate-900 dark:text-white truncate">
                      {{ group.subject }}
                    </h3>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">
                      {{ group.section }} • {{ group.subject_code }}
                    </p>
                  </div>
                </div>
                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-500 shrink-0">
                  <span>{{ group.announcements.length }} {{ group.announcements.length === 1 ? 'Post' : 'Posts' }}</span>
                </div>
              </div>

              <!-- Announcements in this class -->
              <div class="space-y-3">
                <div v-for="a in group.announcements" :key="a.id"
                  :class="[
                    'bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border transition-all',
                    a.is_pinned
                      ? 'border-amber-300/80 dark:border-amber-900/60 bg-amber-50/40 dark:bg-amber-950/20'
                      : 'border-slate-200/80 dark:border-[#3F4F43] hover:border-slate-300 dark:hover:border-[#4a5c50]'
                  ]">

                  <div class="flex items-start gap-3">
                    <!-- Pin indicator / icon -->
                    <div :class="a.is_pinned
                      ? 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900/60'
                      : (a.is_published
                          ? 'bg-emerald-50 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40'
                          : 'bg-slate-100 dark:bg-[#2D3A31] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]')"
                      class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 border font-bold">
                      <Icon :icon="a.is_pinned ? 'star' : (a.is_published ? 'megaphone' : 'edit')" size="sm" />
                    </div>

                    <div class="flex-1 min-w-0 space-y-1.5">
                      <div class="flex items-center gap-2 flex-wrap">
                        <span v-if="a.is_pinned"
                          class="bg-[#F9C20C] text-[#2C3E2D] text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full">
                          PINNED
                        </span>
                        <span :class="a.is_published
                          ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC]'
                          : 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400'"
                          class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full">
                          {{ a.is_published ? 'Published' : 'Draft' }}
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400">
                          {{ a.published_human || formatShort(a.created_at) }}
                        </span>
                      </div>

                      <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white truncate">
                        {{ a.title }}
                      </h4>

                      <p class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 font-medium line-clamp-2">
                        {{ a.body_preview || 'No preview available.' }}
                      </p>
                    </div>
                  </div>

                  <!-- Actions -->
                  <div class="flex items-center justify-end gap-1.5 mt-3 pt-2 border-t border-slate-200/60 dark:border-[#3F4F43]">
                    <button @click="togglePin(a)"
                      :disabled="pinningId === a.id"
                      :title="a.is_pinned ? 'Unpin' : 'Pin to top'"
                      :class="a.is_pinned
                        ? 'bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900/50'
                        : 'bg-white dark:bg-[#2D3A31] text-slate-500 dark:text-slate-300 hover:bg-amber-50 dark:hover:bg-amber-950/40 border-slate-200 dark:border-[#3F4F43]'"
                      class="w-8 h-8 rounded-xl border flex items-center justify-center transition-colors shrink-0 disabled:opacity-50">
                      <Icon icon="star" size="xs" />
                    </button>
                    <Link :href="route('teacher.announcements.show', a.id)"
                      class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#3F4F43] transition-colors"
                      title="View announcement">
                      <Icon icon="eye" size="xs" />
                    </Link>
                    <button @click="confirmDelete = a"
                      class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors"
                      title="Delete">
                      <Icon icon="trash" size="xs" />
                    </button>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <!-- EMPTY -->
          <div v-else
            class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-12 shadow-sm border border-dashed border-slate-300 dark:border-[#3F4F43] text-center space-y-3">
            <Icon icon="megaphone" size="xl" class="text-slate-400 mx-auto" />
            <p class="text-sm font-bold text-slate-800 dark:text-white">
              {{ announcements.length === 0 ? 'No announcements yet' : 'No announcements match your filters' }}
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              {{ announcements.length === 0 ? 'Post your first bulletin to get started.' : 'Try clearing the filters.' }}
            </p>
            <button v-if="announcements.length === 0" @click="openCreate"
              class="inline-flex bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl hover:bg-[#004105] transition-colors items-center gap-2">
              <Icon icon="plus" size="xs" />
              New Announcement
            </button>
          </div>
        </div>

        <!-- RIGHT: CLASSES PANEL — DESKTOP ONLY (mobile uses the top dropdown) -->
        <div class="hidden lg:block lg:col-span-4 space-y-5 sm:space-y-6 w-full min-w-0">

          <!-- Class summary -->
          <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4"
            style="animation-delay: 200ms;">

            <div class="flex items-center justify-between border-b border-slate-100 dark:border-[#3F4F43] pb-3">
              <div class="flex items-center gap-2">
                <Icon icon="academic-cap" size="md" class="text-[#005506] dark:text-[#86EFAC]" />
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Your Classes</h3>
              </div>
              <span class="bg-emerald-100 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] text-[10px] font-black px-2.5 py-1 rounded-full border border-emerald-200 dark:border-emerald-900/40">
                {{ classrooms.length }}
              </span>
            </div>

            <div v-if="classrooms.length" class="space-y-2.5">
              <button v-for="c in classrooms" :key="c.id"
                @click="classFilter = c.id"
                :class="[
                  'w-full bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-2xl border flex items-center justify-between gap-3 transition-all text-left active:scale-95',
                  classFilter === c.id
                    ? 'border-[#005506] dark:border-[#86EFAC] ring-2 ring-emerald-200 dark:ring-emerald-900/40'
                    : 'border-slate-200/80 dark:border-[#3F4F43] hover:border-slate-300 dark:hover:border-[#4a5c50]'
                ]">
                <div class="min-w-0">
                  <p class="text-xs font-black text-slate-900 dark:text-white truncate">{{ c.subject }}</p>
                  <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 truncate">
                    {{ c.section }} • {{ c.subject_code }}
                  </p>
                </div>
                <span class="bg-white dark:bg-[#2D3A31] text-slate-700 dark:text-slate-200 text-[10px] font-black px-2.5 py-1 rounded-full border border-slate-200 dark:border-[#3F4F43] shrink-0">
                  {{ c.announcements_count }} {{ c.announcements_count === 1 ? 'post' : 'posts' }}
                </span>
              </button>
            </div>

            <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-6 text-center border border-dashed border-slate-300 dark:border-[#3F4F43]">
              <p class="text-xs text-slate-500 dark:text-slate-400 italic">No classes assigned yet.</p>
            </div>

            <button v-if="classFilter !== 'all'" @click="classFilter = 'all'"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-800 dark:text-slate-200 font-bold text-xs py-2.5 rounded-2xl border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center gap-2 transition-colors">
              <Icon icon="x" size="xs" />
              Clear Class Filter
            </button>
          </div>

          <!-- Quick help -->
          <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3"
            style="animation-delay: 300ms;">
            <div class="flex items-center gap-2 border-b border-slate-100 dark:border-[#3F4F43] pb-3">
              <Icon icon="sparkles" size="md" class="text-amber-600 dark:text-amber-400" />
              <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Bulletin Tips</h3>
            </div>
            <ul class="space-y-2 text-[11px] text-slate-600 dark:text-slate-300 font-medium">
              <li class="flex items-start gap-2">
                <Icon icon="check-circle" size="xs" class="text-[#005506] dark:text-[#86EFAC] mt-0.5 shrink-0" />
                <span>Pin critical notices — they jump to the top of the student feed.</span>
              </li>
              <li class="flex items-start gap-2">
                <Icon icon="check-circle" size="xs" class="text-[#005506] dark:text-[#86EFAC] mt-0.5 shrink-0" />
                <span>Save as draft to prepare the message, then publish when ready.</span>
              </li>
              <li class="flex items-start gap-2">
                <Icon icon="check-circle" size="xs" class="text-[#005506] dark:text-[#86EFAC] mt-0.5 shrink-0" />
                <span>Set an expiry date so old reminders auto-retire.</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>

    <!-- CREATE MODAL -->
    <div v-if="createOpen"
      class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-start justify-center p-4 overflow-y-auto"
      @click.self="closeCreate">
      <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-2xl w-full max-w-2xl p-6 space-y-5 border border-slate-100 dark:border-[#3F4F43] my-8">

        <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
          <div>
            <h3 class="font-black text-slate-900 dark:text-white text-lg">New Announcement</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-0.5">
              Post a bulletin to one of your classes.
            </p>
          </div>
          <button @click="closeCreate"
            class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors shrink-0">
            <Icon icon="x" size="xs" />
          </button>
        </div>

        <form @submit.prevent="submitCreate" class="space-y-4">
          <div v-if="createError"
            class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl text-rose-700 dark:text-rose-300 text-xs">
            {{ createError }}
          </div>

          <div class="space-y-1">
            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Class *</label>
            <select v-model="createForm.class_id"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer">
              <option value="">— Select class —</option>
              <option v-for="c in classrooms" :key="c.id" :value="c.id">
                {{ c.subject }} — {{ c.section }}
              </option>
            </select>
          </div>

          <div class="space-y-1">
            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Title *</label>
            <input v-model="createForm.title" type="text" placeholder="e.g. Quiz postponed to Monday"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
          </div>

          <div class="space-y-1">
            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Body *</label>
            <textarea v-model="createForm.body" rows="5"
              placeholder="Write the message body here…"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-3 text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] resize-y"></textarea>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
              <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Expires at (optional)</label>
              <input v-model="createForm.expires_at" type="datetime-local"
                class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
            </div>

            <div class="space-y-1">
              <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Image (optional)</label>
              <input ref="fileInput" type="file" accept="image/*" @change="onImagePicked"
                class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#005506] dark:file:bg-[#86EFAC] file:text-white dark:file:text-[#232D26] hover:file:bg-[#004105] cursor-pointer" />
              <p v-if="createForm.image" class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">
                {{ createForm.image.name }}
              </p>
            </div>
          </div>

          <div class="flex flex-wrap gap-4 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
            <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-200 cursor-pointer">
              <input type="checkbox" v-model="createForm.is_pinned" class="rounded border-slate-300 text-[#005506] focus:ring-[#005506]" />
              Pin to top of class feed
            </label>
            <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-200 cursor-pointer">
              <input type="checkbox" v-model="createForm.is_published" class="rounded border-slate-300 text-[#005506] focus:ring-[#005506]" />
              Publish immediately
            </label>
          </div>

          <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-[#3F4F43]">
            <button type="button" @click="closeCreate"
              class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white text-xs font-bold transition-colors">
              Cancel
            </button>
            <button type="submit" :disabled="creating || !createForm.class_id"
              class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] px-6 py-2.5 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2">
              <Icon icon="megaphone" size="xs" />
              {{ creating ? 'Posting…' : (createForm.is_published ? 'Publish Announcement' : 'Save Draft') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- DELETE CONFIRM MODAL -->
    <div v-if="confirmDelete"
      class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4"
      @click.self="confirmDelete = null">
      <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-2xl w-full max-w-md p-6 space-y-4 border border-slate-100 dark:border-[#3F4F43]">
        <div class="flex items-start gap-3">
          <div class="w-11 h-11 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40">
            <Icon icon="trash" size="md" />
          </div>
          <div class="min-w-0">
            <h3 class="font-black text-slate-900 dark:text-white text-base">Delete this announcement?</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mt-1 leading-relaxed line-clamp-3">
              "{{ confirmDelete.title }}"
            </p>
          </div>
        </div>

        <p class="text-[11px] text-slate-500 dark:text-slate-400 bg-[#F9F7F1] dark:bg-[#232D26] rounded-xl p-3 border border-slate-200/80 dark:border-[#3F4F43]">
          This cannot be undone. Students will no longer see this bulletin.
        </p>

        <div class="flex items-center justify-end gap-2 pt-1">
          <button @click="confirmDelete = null"
            class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white text-xs font-bold transition-colors">
            Cancel
          </button>
          <button @click="destroyConfirmed" :disabled="deleting"
            class="bg-rose-600 hover:bg-rose-700 text-white px-6 py-2.5 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2">
            <Icon icon="trash" size="xs" />
            {{ deleting ? 'Deleting…' : 'Delete' }}
          </button>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { confirmAction, showError } from '@/Pages/useSweetAlert'
import { ref, reactive, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/lessons-hero.jpg'

/* ─── PROPS ───────────────────────────────────────────── */
const props = defineProps({
  announcements: { type: Array,  default: () => [] },
  classrooms:    { type: Array,  default: () => [] },
  stats:         { type: Object, default: () => ({ total: 0, published: 0, draft: 0, pinned: 0 }) },
  active_term:   { type: String, default: null },
})

/* ─── FILTERS ─────────────────────────────────────────── */
const classFilter  = ref('all')
const statusFilter = ref('all')
const search       = ref('')

/* Announcements after class filter only — used to drive
   the status pill counts so they always match what's rendered. */
const classScoped = computed(() => {
  if (classFilter.value === 'all') return props.announcements
  return props.announcements.filter(a => a.classroom_id === classFilter.value)
})

const statusFilters = computed(() => [
  { id: 'all',       label: 'All',       count: classScoped.value.length },
  { id: 'pinned',    label: 'Pinned',    count: classScoped.value.filter(a => a.is_pinned).length },
  { id: 'published', label: 'Published', count: classScoped.value.filter(a => a.is_published && !a.is_pinned).length },
  { id: 'draft',     label: 'Drafts',    count: classScoped.value.filter(a => !a.is_published).length },
].filter(f => f.id === 'all' || f.count > 0))

const filtered = computed(() => {
  const q = search.value.trim().toLowerCase()
  return props.announcements.filter(a => {
    if (classFilter.value !== 'all' && a.classroom_id !== classFilter.value) return false
    if (statusFilter.value === 'pinned' && !a.is_pinned) return false
    if (statusFilter.value === 'published' && (!a.is_published || a.is_pinned)) return false
    if (statusFilter.value === 'draft' && a.is_published) return false
    if (q && !(
      (a.title || '').toLowerCase().includes(q) ||
      (a.body_preview || '').toLowerCase().includes(q)
    )) return false
    return true
  })
})

/* Group by class, preserving the class order defined by classrooms prop */
const groupedByClass = computed(() => {
  const groups = new Map()

  // Seed empty groups for every classroom so the panel shows all sections
  props.classrooms.forEach(c => {
    groups.set(c.id, {
      class_id:     c.id,
      subject:      c.subject,
      subject_code: c.subject_code,
      section:      c.section,
      announcements: [],
    })
  })

  filtered.value.forEach(a => {
    if (!groups.has(a.classroom_id)) {
      groups.set(a.classroom_id, {
        class_id:     a.classroom_id,
        subject:      a.subject ?? 'Untitled',
        subject_code: '',
        section:      a.section ?? '',
        announcements: [],
      })
    }
    groups.get(a.classroom_id).announcements.push(a)
  })

  // Sort announcements within each group: pinned first, then by published date desc
  for (const g of groups.values()) {
    g.announcements.sort((a, b) => {
      if (a.is_pinned !== b.is_pinned) return a.is_pinned ? -1 : 1
      const ta = new Date(a.published_at || a.created_at || 0).getTime()
      const tb = new Date(b.published_at || b.created_at || 0).getTime()
      return tb - ta
    })
  }

  // Only return groups that have at least one announcement after filtering
  return [...groups.values()].filter(g => g.announcements.length > 0)
})

/* ─── PIN TOGGLE ──────────────────────────────────────── */
const pinningId = ref(null)

function togglePin(a) {
  pinningId.value = a.id
  axios.put(route('teacher.announcements.toggle-pin', a.id))
    .then(() => router.reload({ only: ['announcements', 'stats'], preserveScroll: true, preserveState: true }))
    .catch(e => showError(e.response?.data?.message || 'Could not toggle pin.'))
    .finally(() => { pinningId.value = null })
}

/* ─── CREATE MODAL ────────────────────────────────────── */
const createOpen  = ref(false)
const creating    = ref(false)
const createError = ref('')
const fileInput   = ref(null)

const createForm = reactive({
  class_id:     '',
  title:        '',
  body:         '',
  expires_at:   '',
  is_pinned:    false,
  is_published: false,
  image:        null,
})

function openCreate() {
  createError.value = ''
  createForm.class_id     = props.classrooms[0]?.id ?? ''
  createForm.title        = ''
  createForm.body         = ''
  createForm.expires_at   = ''
  createForm.is_pinned    = false
  createForm.is_published = false
  createForm.image        = null
  if (fileInput.value) fileInput.value.value = ''
  createOpen.value = true
}

function closeCreate() {
  createOpen.value = false
  createError.value = ''
}

function onImagePicked(e) {
  const f = e.target.files?.[0]
  if (!f) return
  if (f.size > 5 * 1024 * 1024) {
    createError.value = 'Image must be under 5 MB.'
    if (fileInput.value) fileInput.value.value = ''
    createForm.image = null
    return
  }
  createError.value = ''
  createForm.image = f
}

function submitCreate() {
  if (!createForm.class_id) {
    createError.value = 'Please pick a class.'
    return
  }

  creating.value = true
  createError.value = ''

  const fd = new FormData()
  fd.append('title',        createForm.title)
  fd.append('body',         createForm.body)
  if (createForm.expires_at) fd.append('expires_at', createForm.expires_at)
  fd.append('is_pinned',    createForm.is_pinned ? '1' : '0')
  fd.append('is_published', createForm.is_published ? '1' : '0')
  if (createForm.image) fd.append('image', createForm.image)

  axios.post(route('teacher.classes.announcements.store', createForm.class_id), fd, {
    headers: { 'Content-Type': 'multipart/form-data', Accept: 'application/json' },
  })
    .then(() => {
      createOpen.value = false
      router.reload({ only: ['announcements', 'classrooms', 'stats'], preserveScroll: true, preserveState: true })
    })
    .catch(e => {
      if (e.response?.status === 422) {
        const errs = e.response.data?.errors || {}
        const first = Object.values(errs)[0]
        createError.value = Array.isArray(first) ? first[0] : (first || 'Validation failed.')
      } else {
        createError.value = e.response?.data?.message || 'Could not save announcement.'
      }
    })
    .finally(() => { creating.value = false })
}

/* ─── DELETE ──────────────────────────────────────────── */
const confirmDelete = ref(null)
const deleting      = ref(false)

function destroyConfirmed() {
  if (!confirmDelete.value) return
  deleting.value = true
  axios.delete(route('teacher.announcements.destroy', confirmDelete.value.id))
    .then(() => {
      confirmDelete.value = null
      router.reload({ only: ['announcements', 'classrooms', 'stats'], preserveScroll: true, preserveState: true })
    })
    .catch(e => showError(e.response?.data?.message || 'Delete failed.'))
    .finally(() => { deleting.value = false })
}

/* ─── HELPERS ─────────────────────────────────────────── */
function formatShort(v) {
  if (!v) return '—'
  try {
    return new Date(v).toLocaleDateString('en-US', {
      month: 'short', day: 'numeric', year: 'numeric',
    })
  } catch { return '—' }
}

/* ─── ANIMATIONS ──────────────────────────────────────── */
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