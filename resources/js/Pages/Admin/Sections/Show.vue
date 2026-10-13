<template>
  <AdminLayout>
    <div class="space-y-6 pb-10 font-['Inter']">

      <!-- HERO BANNER — matches Sections/Index -->
      <div
        class="relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[240px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <img
          :src="heroImage || 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?q=80&w=1600&auto=format&fit=crop'"
          alt="Section Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>
        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0"></div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-2 sm:space-y-3 max-w-2xl">
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
              <span class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                <span>🏫</span> SECTION
              </span>
              <span class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full" :class="section.is_fully_scheduled ? 'bg-emerald-400' : 'bg-amber-400'"></span>
                {{ section.is_fully_scheduled ? 'Schedule Complete' : 'Incomplete Schedule' }}
              </span>
            </div>

            <div class="space-y-1 sm:space-y-1.5">
              <h1 class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                <span>{{ section.name }}</span>
              </h1>
              <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
                Grade {{ section.grade_level }} &bull; {{ section.strand_name }} &bull;
                {{ section.school_year }} &bull;
                {{ section.adviser ? `Adviser: ${section.adviser}` : 'No adviser assigned' }}
              </p>
            </div>
          </div>

          <div class="flex items-center gap-2 shrink-0 flex-wrap sm:flex-nowrap pt-2 md:pt-0">
            <Link :href="route('admin.sections.index')"
              class="inline-flex items-center justify-center gap-1.5 bg-black/30 hover:bg-black/40 backdrop-blur-md text-white border border-white/20 font-bold px-3.5 py-2.5 sm:py-3 rounded-xl transition-all text-xs cursor-pointer">
              ← Back
            </Link>
            <button @click="confirmRegenerate"
              class="inline-flex items-center justify-center gap-1.5 bg-[#F9C20C] hover:bg-[#e0ae0a] text-[#2C3E2D] font-black px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl transition-all shadow-md active:scale-95 text-xs sm:text-sm cursor-pointer">
              Regenerate Schedule
            </button>
          </div>
        </div>
      </div>

      <!-- INCOMPLETE WARNING -->
      <div v-if="!section.is_fully_scheduled"
        class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-2xl p-4 flex items-start gap-3">
        <div class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-amber-700 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <div class="min-w-0">
          <p class="text-xs font-bold text-amber-800 dark:text-amber-200">Incomplete schedule</p>
          <p class="text-[11px] text-amber-700 dark:text-amber-300 mt-0.5">
            {{ pendingClasses.length }} class{{ pendingClasses.length === 1 ? '' : 'es' }} currently have no scheduled time.
            Enrollment is blocked until every class is placed.
          </p>
        </div>
      </div>

      <!-- STAT CARDS -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-emerald-500 tracking-wider">Enrolled</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ enrolled }}</p>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">of {{ section.max_capacity }} capacity</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-emerald-700 dark:text-[#86EFAC] shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
        </div>

        <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-sky-500 tracking-wider">Classes</p>
            <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ totalClasses }}</p>
            <span class="text-[10px] text-sky-600 dark:text-sky-400 font-normal">across all terms</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-[#1C261E] border border-sky-100 dark:border-[#3F4F43] flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
        </div>

        <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-amber-500 tracking-wider">Adviser</p>
            <p class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[180px]">{{ section.adviser || 'Unassigned' }}</p>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-normal">of this section</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-[#1C261E] border border-amber-100 dark:border-[#3F4F43] flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
        </div>

        <div class="text-left p-5 rounded-2xl border shadow-xs flex items-center justify-between bg-white dark:bg-[#2D3A31] border-gray-200/80 dark:border-[#3F4F43]">
          <div class="space-y-1">
            <p class="text-[10px] font-medium uppercase text-violet-500 tracking-wider">Home Room</p>
            <p class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[180px]">{{ section.home_room || '—' }}</p>
            <span class="text-[10px] text-violet-600 dark:text-violet-400 font-normal">default room</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-[#1C261E] border border-violet-100 dark:border-[#3F4F43] flex items-center justify-center text-violet-600 dark:text-violet-400 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- TERM TABS + GRID -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] shadow-xs overflow-hidden">
        <div class="border-b border-gray-100 dark:border-[#3F4F43] bg-gray-50/50 dark:bg-[#232D26]/50 px-3">
          <nav class="flex gap-1 overflow-x-auto">
            <button v-for="(t, termId) in terms" :key="termId"
              @click="activeTermId = Number(termId)"
              :class="[
                'px-4 py-3 text-xs font-medium uppercase tracking-wider transition-colors border-b-2 -mb-px cursor-pointer whitespace-nowrap',
                activeTermId === Number(termId)
                  ? 'text-[#004d08] dark:text-[#86EFAC] border-[#004d08] dark:border-[#86EFAC]'
                  : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-200',
              ]">
              {{ t.term.name }}
              <span v-if="t.term.is_active" class="ml-1.5 text-[9px] text-emerald-600">●</span>
            </button>
          </nav>
        </div>

        <!-- PENDING TRAY -->
        <div v-if="pendingClasses.length"
          class="p-4 border-b border-amber-100 dark:border-amber-900/40 bg-amber-50/40 dark:bg-amber-950/10">
          <p class="text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-300 mb-2">
            Unplaced Classes ({{ pendingClasses.length }})
          </p>
          <div class="flex flex-wrap gap-2">
            <div v-for="c in pendingClasses" :key="c.id"
              class="flex items-center gap-3 p-2.5 rounded-xl bg-white dark:bg-[#2D3A31] border border-amber-200 dark:border-amber-800">
              <div class="min-w-0">
                <p class="text-[11px] font-bold text-gray-900 dark:text-white">{{ c.subject_code }}</p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate max-w-[160px]">
                  {{ c.subject }} <span v-if="c.teacher">• {{ c.teacher }}</span>
                </p>
              </div>
              <button @click="openPlace(c)"
                class="shrink-0 text-[10px] font-bold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 transition-colors cursor-pointer">
                Place
              </button>
            </div>
          </div>
        </div>

        <div class="p-4 sm:p-5">
          <div v-if="!currentTerm || !currentTerm.classes.length"
            class="py-12 text-center text-gray-400 dark:text-gray-500">
            <p class="text-xs">No classes for this term yet.</p>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="w-full min-w-[720px] border-collapse">
              <thead>
                <tr class="text-[10px] font-medium uppercase text-gray-500 dark:text-gray-400 tracking-wider">
                  <th class="py-3 px-3 text-left border-b border-gray-200 dark:border-[#3F4F43] w-32">Slot</th>
                  <th v-for="day in dayOrder" :key="day"
                    class="py-3 px-3 text-left border-b border-gray-200 dark:border-[#3F4F43]">
                    {{ capitalize(day) }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <template v-for="slot in slots" :key="slot.index">
                  <tr v-if="slot.index === recessAfterSlot + 1" class="select-none">
                    <td :colspan="dayOrder.length + 1" class="py-1.5 px-3">
                      <div class="text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 flex items-center gap-2">
                        <span class="h-px flex-1 bg-gray-200 dark:bg-[#3F4F43]"></span>
                        <span>Recess</span>
                        <span class="h-px flex-1 bg-gray-200 dark:bg-[#3F4F43]"></span>
                      </div>
                    </td>
                  </tr>
                  <tr class="border-b border-gray-100 dark:border-[#3F4F43]">
                    <td class="py-3 px-3 align-top">
                      <div class="text-[11px] font-mono font-semibold text-gray-900 dark:text-white">
                        {{ slot.start }}–{{ slot.end }}
                      </div>
                      <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                        Slot {{ slot.index }}
                      </div>
                    </td>
                    <td v-for="day in dayOrder" :key="day" class="py-2 px-2 align-top">
                      <div v-if="cellFor(slot.index, day)"
                        @click="openClassDetails(cellFor(slot.index, day))"
                        class="group relative rounded-lg border border-emerald-200/80 dark:border-emerald-900/50 bg-emerald-50/50 dark:bg-emerald-950/30 p-2 cursor-pointer hover:border-emerald-400 dark:hover:border-emerald-500 transition-colors">
                        <div class="text-[11px] font-bold text-[#005506] dark:text-[#86EFAC] truncate">
                          {{ cellFor(slot.index, day).subject_code }}
                        </div>
                        <div class="text-[10px] text-gray-600 dark:text-gray-400 truncate mt-0.5">
                          {{ cellFor(slot.index, day).subject }}
                        </div>
                        <div class="text-[9px] text-gray-400 dark:text-gray-500 truncate mt-0.5">
                          {{ cellFor(slot.index, day).teacher || 'No teacher' }}
                          <span v-if="cellFor(slot.index, day).room"> • {{ cellFor(slot.index, day).room }}</span>
                        </div>
                      </div>
                      <div v-else class="rounded-lg border border-dashed border-gray-200 dark:border-[#3F4F43] p-2 text-center">
                        <span class="text-[10px] text-gray-400">Free</span>
                      </div>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- CLASS DETAILS / EDIT MODAL -->
      <Modal :show="showDetailsModal" @close="closeDetails" max-width="lg">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">

          <!-- Header -->
          <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
            <div class="flex items-center gap-3 min-w-0">
              <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <div class="min-w-0">
                <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white truncate">
                  {{ activeClass?.subject_code || 'Class Details' }}
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

          <!-- Body -->
          <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-4">

            <div v-if="detailsError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
              {{ detailsError }}
            </div>

            <!-- Schedule strip -->
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
              <div class="grid grid-cols-3 gap-3">
                <div>
                  <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Section</p>
                  <p class="text-xs text-gray-900 dark:text-white">{{ section.name }}</p>
                </div>
                <div>
                  <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Slot</p>
                  <p class="text-xs text-gray-900 dark:text-white">
                    {{ activeClass?.slot_start }}–{{ activeClass?.slot_end }}
                  </p>
                </div>
                <div>
                  <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Meets</p>
                  <p class="text-xs text-gray-900 dark:text-white">{{ activeClass?.days_label || '—' }}</p>
                </div>
              </div>
            </div>

            <!-- Teacher -->
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Assigned Teacher
              </label>
              <select v-model.number="detailsForm.teacher_id"
                class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option :value="null">— Unassigned —</option>
                <option v-for="t in detailsOptions.teachers" :key="t.id" :value="t.id"
                  :disabled="t.is_busy && !t.is_current">
                  {{ t.name }}
                  <template v-if="t.is_current"> (current)</template>
                  <template v-else-if="t.is_busy"> — busy at this time</template>
                </option>
              </select>
              <p v-if="selectedTeacherIsBusy" class="mt-1.5 text-[10px] text-amber-600 dark:text-amber-400">
                ⚠ This teacher already has a class at this time. Save will reject.
              </p>
            </div>

            <!-- Room -->
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Room <span class="text-gray-400 font-normal">({{ detailsOptions.classroom?.room_type || 'regular' }})</span>
              </label>
              <select v-model.number="detailsForm.room_id"
                class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer">
                <option v-for="r in detailsOptions.rooms" :key="r.id" :value="r.id"
                  :disabled="r.is_busy && !r.is_current">
                  {{ r.code }} — {{ r.name }}
                  <template v-if="r.is_current"> (current)</template>
                  <template v-else-if="r.is_busy"> — booked</template>
                </option>
              </select>
              <p v-if="selectedRoomIsBusy" class="mt-1.5 text-[10px] text-amber-600 dark:text-amber-400">
                ⚠ This room is already booked at this time. Save will reject.
              </p>
            </div>

          </div>

          <!-- Footer -->
          <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex items-center justify-between gap-2">
            <button type="button"
              @click="confirmRemoveFromSchedule"
              class="px-3.5 py-2 text-xs font-normal text-red-700 dark:text-red-400 bg-red-50 hover:bg-red-100 dark:bg-red-950/50 border border-red-200 dark:border-red-900 rounded-xl transition-colors cursor-pointer">
              Remove from Schedule
            </button>

            <div class="flex gap-2">
              <button type="button" @click="closeDetails"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
                Cancel
              </button>
              <button type="button"
                @click="saveClassDetails"
                :disabled="detailsSaving || !detailsDirty"
                class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] hover:bg-emerald-900 text-white rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
                {{ detailsSaving ? 'Saving…' : 'Save Changes' }}
              </button>
            </div>
          </div>

        </div>
      </Modal>

      <!-- PLACE MODAL -->
      <Modal :show="showPlaceModal" @close="closePlace" max-width="lg">
        <div class="p-6 bg-white dark:bg-[#2D3A31] rounded-3xl border border-gray-200 dark:border-[#3F4F43] font-['Inter']">
          <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100 dark:border-[#3F4F43]">
            <div>
              <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white">
                Place Class
              </h3>
              <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal mt-0.5">
                {{ activeClass?.subject }}
                <span v-if="activeClass?.teacher"> • {{ activeClass.teacher }}</span>
              </p>
            </div>
            <button type="button" @click="closePlace"
              class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div v-if="loadingSlots" class="py-10 text-center">
            <div class="inline-block w-5 h-5 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          </div>

          <div v-else class="space-y-2">
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-3">
              Choose a slot. Blocked slots show why they're unavailable.
            </p>

            <div v-for="slot in slotCandidates" :key="slot.slot_index"
              class="p-3 rounded-xl border transition-colors"
              :class="slot.available
                ? 'bg-emerald-50/40 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/50 hover:border-emerald-400 cursor-pointer'
                : 'bg-gray-50 dark:bg-[#232D26] border-gray-200 dark:border-[#3F4F43] opacity-60'"
              @click="slot.available && applyPlacement(slot)">

              <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                  <div class="text-xs font-bold text-gray-900 dark:text-white">
                    {{ slot.start }}–{{ slot.end }}
                  </div>
                  <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">
                    <template v-if="slot.days.length">
                      Meets: <span class="font-medium">{{ slot.days.map(capitalize).join(', ') }}</span>
                    </template>
                    <template v-else>
                      {{ slot.reason }}
                    </template>
                  </div>
                </div>

                <span v-if="slot.available"
                  class="text-[10px] font-bold uppercase tracking-wider text-[#005506] dark:text-[#86EFAC] shrink-0">
                  Place Here
                </span>
                <span v-else
                  class="text-[10px] font-bold uppercase tracking-wider text-gray-400 shrink-0">
                  Blocked
                </span>
              </div>
            </div>
          </div>

          <div class="flex justify-end gap-2.5 pt-4 mt-4 border-t border-gray-100 dark:border-[#3F4F43]">
            <button type="button" @click="closePlace"
              class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
              Close
            </button>
          </div>
        </div>
      </Modal>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import { useFlash } from '@/Composables/useFlash'
import { useConfirm } from '@/Composables/useConfirm'

const props = defineProps({
  section:         { type: Object, required: true },
  terms:           { type: Object, required: true },
  enrolled:        { type: Number, default: 0 },
  slots:           { type: Array,  default: () => [] },
  recessAfterSlot: { type: Number, default: 1 },
  dayOrder:        { type: Array,  default: () => ['monday','tuesday','wednesday','thursday','friday'] },
})

const flash   = useFlash()
const confirm = useConfirm()

const activeTermId = ref(null)
onMounted(() => {
  const entries = Object.entries(props.terms)
  const active = entries.find(([, t]) => t.term.is_active)
  activeTermId.value = Number((active ?? entries[0])?.[0] ?? null)
})

const currentTerm = computed(() => props.terms[activeTermId.value] ?? null)

const pendingClasses = computed(() =>
  (currentTerm.value?.classes || []).filter(c => ! c.is_scheduled)
)

const totalClasses = computed(() =>
  Object.values(props.terms).reduce((sum, t) => sum + (t.classes?.length || 0), 0)
)

const cellFor = (slotIndex, day) => {
  if (!currentTerm.value) return null
  const slot = props.slots[slotIndex]
  if (!slot) return null

  for (const c of currentTerm.value.classes) {
    if (! c.is_scheduled) continue
    const match = c.schedules.find(s =>
      s.day_of_week === day &&
      s.time_start === slot.start &&
      s.time_end === slot.end
    )
    if (match) {
      return {
        ...c,
        room: match.room,
        room_id: match.room_id,
        slot_index: slot.index,
        slot_start: slot.start,
        slot_end: slot.end,
        days_label: null, // computed in openClassDetails
      }
    }
  }
  return null
}

const capitalize = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1) : ''

/* ─── CLASS DETAILS / EDIT ─────────────────────────────────────── */

const showDetailsModal   = ref(false)
const activeClass        = ref(null)
const detailsOptions     = ref({ classroom: null, teachers: [], rooms: [] })
const detailsForm        = reactive({ teacher_id: null, room_id: null })
const detailsOriginal    = reactive({ teacher_id: null, room_id: null })
const detailsError       = ref('')
const detailsLoading     = ref(false)
const detailsSaving      = ref(false)

const detailsDirty = computed(() =>
  detailsForm.teacher_id !== detailsOriginal.teacher_id ||
  detailsForm.room_id    !== detailsOriginal.room_id
)

const selectedTeacherIsBusy = computed(() => {
  if (detailsForm.teacher_id === null) return false
  const t = detailsOptions.value.teachers.find(x => x.id === detailsForm.teacher_id)
  return t ? (t.is_busy && !t.is_current) : false
})

const selectedRoomIsBusy = computed(() => {
  if (detailsForm.room_id === null) return false
  const r = detailsOptions.value.rooms.find(x => x.id === detailsForm.room_id)
  return r ? (r.is_busy && !r.is_current) : false
})

const openClassDetails = async (klass) => {
  detailsError.value = ''
  detailsLoading.value = true
  showDetailsModal.value = true

  // Days label from the schedules
  const days = [...new Set((klass.schedules || []).map(s => s.day_of_week))]

  activeClass.value = {
    ...klass,
    days_label: days.length === 5 ? 'Mon–Fri' : days.map(capitalize).join(', '),
  }

  try {
    const { data } = await axios.get(
      route('admin.sections.classes.edit-options', [props.section.id, klass.id])
    )
    detailsOptions.value = data

    detailsForm.teacher_id    = data.classroom?.teacher_id ?? null
    detailsForm.room_id       = data.classroom?.room_id ?? null
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
  activeClass.value = null
  detailsOptions.value = { classroom: null, teachers: [], rooms: [] }
  detailsError.value = ''
}

const saveClassDetails = async () => {
  if (! activeClass.value) return
  detailsSaving.value = true
  detailsError.value = ''

  try {
    await axios.put(
      route('admin.sections.classes.update', [props.section.id, activeClass.value.id]),
      {
        teacher_id: detailsForm.teacher_id,
        room_id:    detailsForm.room_id,
      }
    )
    flash.success(`${activeClass.value.subject} updated.`)
    closeDetails()
    router.reload({ only: ['terms', 'section'] })
  } catch (e) {
    detailsError.value = e.response?.data?.message || 'Failed to save.'
    flash.error(detailsError.value)
  } finally {
    detailsSaving.value = false
  }
}

const confirmRemoveFromSchedule = async () => {
  if (! activeClass.value) return
  const klass = activeClass.value

  const { confirmed } = await confirm({
    title: 'Remove from Schedule',
    message: `Remove ${klass.subject} from the weekly schedule?`,
    details: [
      'The class stays on the section.',
      'Enrollment will be blocked until every class is placed.',
      'Use the "Place" button above the grid to reassign a slot.',
    ],
    confirmLabel: 'Remove',
    variant: 'warning',
  })
  if (!confirmed) return

  try {
    await axios.delete(
      route('admin.sections.classes.unschedule', [props.section.id, klass.id])
    )
    flash.success(`${klass.subject} removed from schedule.`)
    closeDetails()
    router.reload({ only: ['terms', 'section', 'enrolled'] })
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to remove.')
  }
}

/* ─── PLACE ────────────────────────────────────────────────────── */

const showPlaceModal = ref(false)
const slotCandidates = ref([])
const loadingSlots   = ref(false)

const openPlace = async (klass) => {
  activeClass.value = klass
  showPlaceModal.value = true
  slotCandidates.value = []
  loadingSlots.value = true

  try {
    const { data } = await axios.get(
      route('admin.sections.classes.available-slots', [props.section.id, klass.id])
    )
    slotCandidates.value = data.candidates
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to load alternatives.')
    showPlaceModal.value = false
  } finally {
    loadingSlots.value = false
  }
}

const closePlace = () => {
  showPlaceModal.value = false
  activeClass.value = null
  slotCandidates.value = []
}

const applyPlacement = async (slot) => {
  if (!activeClass.value) return
  try {
    await axios.put(
      route('admin.sections.classes.reschedule', [props.section.id, activeClass.value.id]),
      { slot_index: slot.slot_index, days: slot.days }
    )
    flash.success(`${activeClass.value.subject} placed at ${slot.start}–${slot.end}.`)
    closePlace()
    router.reload({ only: ['terms', 'section', 'enrolled'] })
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to place.')
  }
}

/* ─── REGENERATE ───────────────────────────────────────────────── */

const confirmRegenerate = async () => {
  const { confirmed } = await confirm({
    title: 'Regenerate Schedule',
    message: `Regenerate the schedule for ${props.section.name}?`,
    details: [
      'All current classes and their schedules will be deleted.',
      'A fresh schedule will be built from the curriculum.',
      'Manual changes (unscheduled classes, reassigned teachers) will be lost.',
    ],
    confirmLabel: 'Regenerate',
    variant: 'danger',
  })
  if (!confirmed) return

  try {
    await axios.post(route('admin.sections.regenerate', props.section.id))
    flash.success('Schedule regenerated.')
    router.reload({ only: ['terms', 'section', 'enrolled'] })
  } catch (e) {
    flash.error(e.response?.data?.message || 'Failed to regenerate.')
  }
}
</script>