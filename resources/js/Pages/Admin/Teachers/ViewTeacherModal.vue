<template>
  <Modal :show="show" @close="closeModal" max-width="2xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[88vh]">

      <!-- Header -->
      <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <div class="min-w-0">
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white truncate">
              Faculty Profile
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate">
              <template v-if="teacher">
                {{ teacher.user.name }} &bull;
                <span class="font-mono">{{ teacher.employee_no }}</span>
              </template>
              <template v-else>Loading…</template>
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <span v-if="teacher"
                class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
                :class="teacher.is_active
                  ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'
                  : 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800'">
            <span :class="['w-1.5 h-1.5 rounded-full', teacher.is_active ? 'bg-emerald-500' : 'bg-amber-500']"></span>
            {{ teacher.is_active ? 'Active' : 'Inactive' }}
          </span>
          <button type="button" @click="closeModal"
                  class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- TABS -->
      <div v-if="teacher && !isLoading"
        class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 px-4">
        <nav class="flex gap-1">
          <button v-for="t in tabs" :key="t.key" @click="activeTab = t.key"
            :class="[
              'px-4 py-2.5 text-[11px] font-medium uppercase tracking-wider transition-colors border-b-2 -mb-px cursor-pointer',
              activeTab === t.key
                ? 'text-[#004d08] dark:text-[#86EFAC] border-[#004d08] dark:border-[#86EFAC]'
                : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-200',
            ]">
            {{ t.label }}
          </button>
        </nav>
      </div>

      <!-- Body -->
      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">

        <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <div v-if="isLoading" class="py-16 text-center">
          <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading faculty profile…</p>
        </div>

        <!-- ═══════ PROFILE ═══════ -->
        <template v-else-if="teacher && activeTab === 'profile'">
          <section>
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Employment</h4>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Employee No.</p>
                <p class="text-xs font-mono text-gray-900 dark:text-white">{{ teacher.employee_no }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Date Hired</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ formatDate(teacher.date_hired) }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Department</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ teacher.department || '—' }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Specialization</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ teacher.specialization || '—' }}</p>
              </div>
            </div>
          </section>

          <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Personal Information</h4>
            <div class="grid grid-cols-2 gap-3">
              <div class="col-span-2">
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Full Name</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ teacher.user.name }}</p>
              </div>
              <div class="col-span-2">
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Email</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ teacher.user.email }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Sex</p>
                <p class="text-xs text-gray-900 dark:text-white capitalize">{{ teacher.sex || '—' }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Date of Birth</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ formatDate(teacher.date_of_birth) }}</p>
              </div>
            </div>
          </section>
        </template>

        <!-- ═══════ SCHEDULE ═══════ -->
        <template v-else-if="teacher && activeTab === 'schedule'">
          <div v-if="scheduleLoading" class="py-12 text-center">
            <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading schedule…</p>
          </div>

          <template v-else-if="schedule">
            <!-- Header row with Add button -->
            <div class="flex items-center justify-between gap-3 flex-wrap">
              <div class="text-xs text-gray-600 dark:text-gray-300">
                <span class="font-bold text-gray-900 dark:text-white">{{ schedule.classes.length }}</span>
                {{ schedule.classes.length === 1 ? 'class' : 'classes' }}
                <span v-if="schedule.term"> &bull; {{ schedule.term.name }}</span>
              </div>
              <button @click="openAddClass"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 rounded-lg transition-colors cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Assign Class
              </button>
            </div>

            <div v-if="!schedule.classes.length" class="py-10 text-center text-gray-400 dark:text-gray-500">
              <p class="text-xs">No classes assigned.</p>
              <p class="text-[11px] mt-1">Use "Assign Class" to add one.</p>
            </div>

            <div v-else class="space-y-3">
              <div v-for="day in daysOfWeek" :key="day" class="rounded-xl border border-gray-200/80 dark:border-[#3F4F43] overflow-hidden">
                <div class="px-3 py-2 bg-gray-50 dark:bg-[#232D26] border-b border-gray-100 dark:border-[#3F4F43]">
                  <p class="text-[10px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                    {{ capitalize(day) }}
                  </p>
                </div>
                <div v-if="!sessionsForDay(day).length" class="px-3 py-2.5 text-[11px] text-gray-400 italic">
                  No classes
                </div>
                <ul v-else class="divide-y divide-gray-100 dark:divide-[#3F4F43]">
                  <li v-for="s in sessionsForDay(day)" :key="s.class_id + s.time_start">
                    <button type="button"
                      @click="openClassDetails(s)"
                      class="w-full text-left flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors cursor-pointer">
                      <div class="text-[10px] font-mono text-gray-500 dark:text-gray-400 shrink-0 w-20">
                        {{ s.time_start }}–{{ s.time_end }}
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                          {{ s.subject }}
                        </p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">
                          {{ s.section }}
                          <span v-if="s.room"> • {{ s.room }}</span>
                        </p>
                      </div>
                      <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                      </svg>
                    </button>
                  </li>
                </ul>
              </div>
            </div>
          </template>
        </template>

      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end gap-2">
        <button type="button" @click="closeModal"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          Close
        </button>
        <button v-if="teacher" type="button" @click="$emit('edit', teacher)"
                class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] hover:bg-emerald-900 text-white rounded-xl transition-all shadow-md active:scale-95 cursor-pointer">
          Edit Faculty
        </button>
      </div>

    </div>

    <!-- ═══════ CLASS EDIT MODAL ═══════ -->
    <Modal :show="showDetailsModal" @close="closeDetails" max-width="lg">
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">
        <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <div class="min-w-0">
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white truncate">
                {{ activeClass?.subject_code || 'Class' }}
              </h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate">
                {{ activeClass?.subject }}
              </p>
            </div>
          </div>
          <button type="button" @click="closeDetails"
            class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-4">
          <div v-if="detailsError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
            {{ detailsError }}
          </div>
          <div v-if="detailsLoading" class="py-10 text-center">
            <div class="inline-block w-5 h-5 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          </div>

          <template v-else>
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
              <div class="grid grid-cols-3 gap-3">
                <div>
                  <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Section</p>
                  <p class="text-xs text-gray-900 dark:text-white">{{ activeClass?.section || '—' }}</p>
                </div>
                <div>
                  <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Slot</p>
                  <p class="text-xs text-gray-900 dark:text-white">{{ activeClass?.slot_start }}–{{ activeClass?.slot_end }}</p>
                </div>
                <div>
                  <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Meets</p>
                  <p class="text-xs text-gray-900 dark:text-white">{{ activeClass?.days_label || '—' }}</p>
                </div>
              </div>
              <div class="mt-3 pt-3 border-t border-gray-200/80 dark:border-[#3F4F43] flex items-center justify-between">
                <p class="text-[10px] text-gray-500 dark:text-gray-400">
                  Change teacher or room. For time changes, edit the section.
                </p>
                <a v-if="activeClass?.section_id"
                  :href="route('admin.sections.view', activeClass.section_id)"
                  class="text-[10px] font-bold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] hover:underline shrink-0 ml-2">
                  Open Section →
                </a>
              </div>
            </div>

            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Assigned Teacher</label>
              <select v-model.number="detailsForm.teacher_id"
                class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option :value="null">— Unassigned —</option>
                <option v-for="t in detailsOptions.teachers" :key="t.id" :value="t.id" :disabled="t.is_busy && !t.is_current">
                  {{ t.name }}
                  <template v-if="t.is_current"> (current)</template>
                  <template v-else-if="t.is_busy"> — busy</template>
                </option>
              </select>
            </div>

            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Room <span class="text-gray-400 font-normal">({{ detailsOptions.classroom?.room_type || 'regular' }})</span>
              </label>
              <select v-model.number="detailsForm.room_id"
                class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option v-for="r in detailsOptions.rooms" :key="r.id" :value="r.id" :disabled="r.is_busy && !r.is_current">
                  {{ r.code }} — {{ r.name }}
                  <template v-if="r.is_current"> (current)</template>
                  <template v-else-if="r.is_busy"> — booked</template>
                </option>
              </select>
            </div>
          </template>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex items-center justify-between gap-2">
          <button type="button" @click="unassignTeacher"
            :disabled="detailsSaving || detailsForm.teacher_id === null"
            class="px-3.5 py-2 text-xs font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 border border-red-200 dark:border-red-900 rounded-xl transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
            Unassign Teacher
          </button>
          <div class="flex gap-2">
            <button type="button" @click="closeDetails"
              class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
              Cancel
            </button>
            <button type="button" @click="saveClassDetails" :disabled="detailsSaving || !detailsDirty"
              class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] hover:bg-emerald-900 text-white rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
              {{ detailsSaving ? 'Saving…' : 'Save Changes' }}
            </button>
          </div>
        </div>
      </div>
    </Modal>

        <!-- ═══════ ASSIGN CLASS PICKER ═══════ -->
    <Modal :show="showAssignModal" @close="closeAssign" max-width="2xl">
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[85vh]">

        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
              </svg>
            </div>
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
                Assign Class
              </h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
                {{ teacher?.user?.name }} &bull; {{ schedule?.term?.name || 'Current term' }}
              </p>
            </div>
          </div>
          <button type="button" @click="closeAssign"
                  class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Filters -->
        <div class="px-6 pt-4 pb-2 flex items-center gap-3 flex-wrap">
          <div class="relative flex-1 min-w-[200px]">
            <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input v-model="assignSearch" type="text" placeholder="Search subject, section, teacher..."
                   class="w-full pl-10 pr-4 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
          </div>
          <label class="flex items-center gap-2 text-[11px] text-gray-600 dark:text-gray-300 cursor-pointer select-none shrink-0">
            <input type="checkbox" v-model="assignAssignableOnly"
                   class="w-3.5 h-3.5 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer" />
            Assignable only
          </label>
        </div>

        <!-- Legend -->
        <div class="px-6 pb-2 flex items-center gap-3 flex-wrap text-[10px]">
          <span class="inline-flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Unassigned
          </span>
          <span class="inline-flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Taken — will need confirmation
          </span>
          <span class="inline-flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
            <span class="w-2 h-2 rounded-full bg-gray-400"></span> Locked or busy
          </span>
        </div>

        <!-- List -->
        <div class="flex-1 min-h-0 overflow-y-auto px-6 py-4 space-y-1.5">
          <div v-if="assignLoading" class="py-10 text-center">
            <div class="inline-block w-5 h-5 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          </div>

          <div v-else-if="!filteredAssignCandidates.length" class="py-10 text-center">
            <p class="text-xs text-gray-400 dark:text-gray-500">
              {{ assignClasses.length === 0
                ? 'No classes available to assign.'
                : 'No classes match your filters.' }}
            </p>
            <p v-if="assignAssignableOnly && assignClasses.length > 0"
               class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">
              Try unchecking "Assignable only" to see locked or busy classes.
            </p>
          </div>

          <button v-for="c in filteredAssignCandidates" :key="c.id"
            type="button"
            :disabled="! c.available || assignSavingId === c.id"
            @click="assignClass(c)"
            class="w-full text-left p-3 rounded-xl border transition-colors"
            :class="[
              c.available
                ? 'bg-white dark:bg-[#2D3A31] border-gray-200 dark:border-[#3F4F43] hover:border-emerald-400 cursor-pointer'
                : 'bg-gray-50 dark:bg-[#232D26] border-gray-200 dark:border-[#3F4F43] opacity-60 cursor-not-allowed',
            ]">
            <div class="flex items-start justify-between gap-3">
              <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                  <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                    {{ c.subject_code }} — {{ c.subject }}
                  </p>
                  <span v-if="c.status === 'taken'"
                        class="px-1.5 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                    Taken
                  </span>
                  <span v-else-if="c.status === 'locked_advisory'"
                        class="px-1.5 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-gray-200 dark:bg-[#232D26] text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-[#3F4F43]">
                    Locked
                  </span>
                  <span v-else
                        class="px-1.5 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                    Unassigned
                  </span>
                </div>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate mt-1">
                  {{ c.section }} &bull; {{ sessionsLabel(c.schedules) }}
                </p>
                <p class="text-[10px] mt-1"
                   :class="c.status === 'taken' ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400'">
                  <template v-if="c.status === 'locked_advisory'">
                    Locked — {{ c.current_teacher }} advises this section
                  </template>
                  <template v-else-if="c.status === 'taken'">
                    Currently taught by {{ c.current_teacher }}
                  </template>
                  <template v-else>
                    No teacher assigned
                  </template>
                </p>
              </div>
              <span v-if="! c.available && c.status !== 'locked_advisory' && c.conflict"
                    class="text-[9px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 shrink-0">
                Busy
              </span>
              <span v-else-if="! c.available && c.status === 'locked_advisory'"
                    class="text-[9px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 shrink-0">
                Locked
              </span>
              <span v-else-if="assignSavingId === c.id"
                    class="text-[9px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 shrink-0">
                Assigning…
              </span>
              <span v-else-if="c.status === 'taken'"
                    class="text-[9px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 shrink-0">
                Take Over
              </span>
              <span v-else
                    class="text-[9px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 shrink-0">
                Assign
              </span>
            </div>
            <p v-if="! c.available && c.conflict" class="text-[10px] text-amber-700 dark:text-amber-400 mt-1.5 italic">
              Conflicts with your class at {{ capitalize(c.conflict.day) }} {{ c.conflict.time_start }}–{{ c.conflict.time_end }}
            </p>
          </button>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end">
          <button @click="closeAssign"
                  class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
            Close
          </button>
        </div>
      </div>
    </Modal>
  </Modal>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'
import { useFlash } from '@/Composables/useFlash'
import { useConfirm } from '@/Composables/useConfirm'

const props = defineProps({
  show:    { type: Boolean, default: false },
  teacher: { type: Object,  default: null },
})

const emit  = defineEmits(['close', 'edit'])
const flash = useFlash()
const confirm = useConfirm()

const teacher      = ref(null)
const isLoading    = ref(false)
const generalError = ref('')
const activeTab    = ref('profile')

const schedule        = ref(null)
const scheduleLoading = ref(false)

const daysOfWeek = ['monday','tuesday','wednesday','thursday','friday']

const tabs = [
  { key: 'profile',  label: 'Profile' },
  { key: 'schedule', label: 'Schedule' },
]

/* ─── Lifecycle ─────────────────────────────────────────────── */

watch(() => props.show, async (open) => {
  if (open && props.teacher?.id) {
    reset()
    await fetchDetails()
  }
})

watch(activeTab, (tab) => {
  if (tab === 'schedule' && ! schedule.value) fetchSchedule()
})

const reset = () => {
  teacher.value = null
  generalError.value = ''
  isLoading.value = false
  activeTab.value = 'profile'
  schedule.value = null

  // Fully reset inner modal state — only happens when the outer modal opens.
  showDetailsModal.value = false
  showAssignModal.value = false
  activeClass.value = null
  detailsOptions.value = { classroom: null, teachers: [], rooms: [] }
  detailsError.value = ''
  assignClasses.value = []
  assignSavingId.value = null
}

const fetchDetails = async () => {
  isLoading.value = true
  try {
    const { data } = await axios.get(`/admin/teachers/${props.teacher.id}`)
    teacher.value = data.teacher
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to load faculty details.'
  } finally {
    isLoading.value = false
  }
}

const fetchSchedule = async () => {
  scheduleLoading.value = true
  try {
    const { data } = await axios.get(`/admin/teachers/${props.teacher.id}/schedule`)
    schedule.value = data
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to load schedule.'
  } finally {
    scheduleLoading.value = false
  }
}

/* ─── Session helpers ───────────────────────────────────────── */

const sessionsForDay = (day) => {
  if (! schedule.value?.classes) return []
  const out = []
  for (const c of schedule.value.classes) {
    for (const s of (c.schedules || [])) {
      if (s.day_of_week !== day) continue
      out.push({
        class_id:     c.id,
        subject:      c.subject,
        subject_code: c.subject_code,
        section:      c.section,
        section_id:   c.section_id,
        room:         s.room,
        time_start:   s.time_start,
        time_end:     s.time_end,
      })
    }
  }
  return out.sort((a, b) => a.time_start.localeCompare(b.time_start))
}

const sessionsLabel = (schedules) => {
  if (! schedules?.length) return 'No schedule'
  const days = [...new Set(schedules.map(s => s.day_of_week))]
  const first = schedules[0]
  const dayLabel = days.length === 5 ? 'Mon–Fri' : days.map(capitalize).join(', ')
  return `${dayLabel} ${first.time_start}–${first.time_end}`
}

const capitalize = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1) : ''

const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' }) }
  catch { return iso }
}

/* ─── Class details (edit existing) ─────────────────────────── */

const showDetailsModal = ref(false)
const activeClass      = ref(null)
const detailsOptions   = ref({ classroom: null, teachers: [], rooms: [] })
const detailsForm      = reactive({ teacher_id: null, room_id: null })
const detailsOriginal  = reactive({ teacher_id: null, room_id: null })
const detailsError     = ref('')
const detailsLoading   = ref(false)
const detailsSaving    = ref(false)

const detailsDirty = computed(() =>
  detailsForm.teacher_id !== detailsOriginal.teacher_id ||
  detailsForm.room_id    !== detailsOriginal.room_id
)

const openClassDetails = async (s) => {
  activeClass.value = {
    id:           s.class_id,
    subject:      s.subject,
    subject_code: s.subject_code,
    section:      s.section,
    section_id:   s.section_id,
    slot_start:   s.time_start,
    slot_end:     s.time_end,
    days_label:   null,
  }

  const klass = (schedule.value?.classes || []).find(c => c.id === s.class_id)
  if (klass) {
    const days = [...new Set((klass.schedules || []).map(x => x.day_of_week))]
    activeClass.value.days_label = days.length === 5 ? 'Mon–Fri' : days.map(capitalize).join(', ')
  }

  detailsError.value = ''
  detailsLoading.value = true
  showDetailsModal.value = true

  try {
    const { data } = await axios.get(
      route('admin.sections.classes.edit-options', [s.section_id, s.class_id])
    )
    detailsOptions.value = data
    detailsForm.teacher_id     = data.classroom?.teacher_id ?? null
    detailsForm.room_id        = data.classroom?.room_id ?? null
    detailsOriginal.teacher_id = detailsForm.teacher_id
    detailsOriginal.room_id    = detailsForm.room_id
  } catch (e) {
    detailsError.value = e.response?.data?.message || 'Failed to load options.'
  } finally {
    detailsLoading.value = false
  }
}

const closeDetails = () => {
  showDetailsModal.value = false
  // NOTE: Do NOT clear activeClass/detailsOptions here — the modal is still
  // animating out and its template depends on this data. It gets replaced
  // the next time openClassDetails() runs.
  detailsError.value = ''
}

const saveClassDetails = async () => {
  if (! activeClass.value) return
  detailsSaving.value = true
  detailsError.value = ''
  try {
    await axios.put(
      route('admin.sections.classes.update', [activeClass.value.section_id, activeClass.value.id]),
      { teacher_id: detailsForm.teacher_id, room_id: detailsForm.room_id }
    )
    flash.success(`${activeClass.value.subject} updated.`)
    closeDetails()
    await fetchSchedule()
  } catch (e) {
    detailsError.value = e.response?.data?.message || 'Failed to save.'
    flash.error(detailsError.value)
  } finally {
    detailsSaving.value = false
  }
}

const unassignTeacher = async () => {
  if (! activeClass.value) return
  detailsSaving.value = true
  detailsError.value = ''
  try {
    await axios.put(
      route('admin.sections.classes.update', [activeClass.value.section_id, activeClass.value.id]),
      { teacher_id: null, room_id: detailsForm.room_id }
    )
    flash.success(`${activeClass.value.subject} unassigned.`)
    closeDetails()
    await fetchSchedule()
  } catch (e) {
    detailsError.value = e.response?.data?.message || 'Failed to unassign.'
    flash.error(detailsError.value)
  } finally {
    detailsSaving.value = false
  }
}

/* ─── Assign class picker ───────────────────────────────────── */

const showAssignModal      = ref(false)
const assignClasses        = ref([])
const assignLoading        = ref(false)
const assignSavingId       = ref(null)
const assignSearch         = ref('')
const assignAssignableOnly = ref(true)

const filteredAssignCandidates = computed(() => {
  let list = assignClasses.value

  if (assignAssignableOnly.value) {
    // Hide locked-advisory and busy; keep unassigned and taken.
    list = list.filter(c => c.status !== 'locked_advisory' && ! c.conflict)
  }

  const q = assignSearch.value.trim().toLowerCase()
  if (! q) return list

  return list.filter(c =>
    (c.subject ?? '').toLowerCase().includes(q) ||
    (c.subject_code ?? '').toLowerCase().includes(q) ||
    (c.section ?? '').toLowerCase().includes(q) ||
    (c.current_teacher ?? '').toLowerCase().includes(q)
  )
})

const openAddClass = async () => {
  showAssignModal.value = true
  assignClasses.value = []
  assignSearch.value = ''
  assignAssignableOnly.value = true
  assignLoading.value = true
  try {
    const { data } = await axios.get(`/admin/teachers/${props.teacher.id}/available-classes`)
    assignClasses.value = data.classes || []
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to load classes.')
  } finally {
    assignLoading.value = false
  }
}

const closeAssign = () => {
  showAssignModal.value = false
  assignSavingId.value = null
  // NOTE: Do NOT clear assignClasses here — keeps the list visible during
  // the close transition. openAddClass() resets it before each open.
}

const assignClass = async (c) => {
  if (! c.available || assignSavingId.value) return

  // If taken by another teacher, confirm the takeover.
  if (c.status === 'taken') {
    const { confirmed } = await confirm({
      title: 'Reassign Class',
      message: `Take "${c.subject}" (${c.section}) away from ${c.current_teacher}?`,
      details: [
        `${c.current_teacher} will no longer teach this class.`,
        'Their other classes are unaffected.',
      ],
      confirmLabel: 'Take Over',
      variant: 'warning',
    })
    if (! confirmed) return
  }

  assignSavingId.value = c.id
  try {
    await axios.put(
      route('admin.sections.classes.update', [c.section_id, c.id]),
      { teacher_id: props.teacher.id }
    )
    flash.success(
      c.status === 'taken'
        ? `${c.subject} reassigned from ${c.current_teacher}.`
        : `${c.subject} assigned.`
    )
    closeAssign()
    await fetchSchedule()
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to assign.')
  } finally {
    assignSavingId.value = null
  }
}

/* ─── Close ─────────────────────────────────────────────────── */

const closeModal = () => {
  emit('close')
}
</script>