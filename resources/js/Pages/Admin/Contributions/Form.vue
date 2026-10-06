<template>
  <Head :title="`${isEdit ? 'Edit' : 'New'} Contribution - Salawag LMS`" />

  <AdminLayout searchPlaceholder="Search...">
    <div class="relative z-10 px-4 sm:px-6 md:px-10 pb-24 space-y-6 flex-1 mt-4 w-full">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] flex flex-col justify-center p-6 sm:p-8 md:p-10">
        <img :src="heroImage" alt="Contribution Form"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 max-w-3xl space-y-4">
          <div class="flex flex-wrap items-center gap-2.5">
            <div class="inline-flex items-center gap-2 bg-[#F9C20C] text-[#2C3E2D] font-black text-xs px-4 py-1.5 rounded-full shadow-sm tracking-wide">
              <Icon :icon="isEdit ? 'edit' : 'plus'" size="xs" />
              {{ isEdit ? 'EDIT CONTRIBUTION' : 'NEW CONTRIBUTION' }}
            </div>
          </div>

          <div class="space-y-2">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-white tracking-tight leading-tight">
              {{ isEdit ? 'Update Campaign' : 'Create Campaign' }}
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium max-w-2xl">
              {{ isEdit
                ? 'Edit the details, deadline, or guardian-consent rules for this contribution.'
                : 'Choose whether this is a section-wide activity or a subject-specific fee.' }}
            </p>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <BackButton fallback="teacher.contributions.index"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              Cancel
            </BackButton>
          </div>
        </div>
      </div>

      <!-- FORM -->
      <form @submit.prevent="submit"
        v-observe
        class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-5 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-6"
        style="animation-delay: 100ms;">

        <!-- TOP ERROR BANNER -->
        <div v-if="formError"
          class="p-4 bg-rose-50 dark:bg-rose-950/40 border-2 border-rose-200 dark:border-rose-800 rounded-2xl flex items-start gap-3">
          <Icon icon="alert-circle" size="md" class="text-rose-600 dark:text-rose-400 shrink-0 mt-0.5" />
          <div class="text-xs">
            <p class="font-black text-rose-800 dark:text-rose-300 mb-0.5">Could not save</p>
            <p class="text-rose-700 dark:text-rose-300 font-medium">{{ formError }}</p>
          </div>
        </div>

        <!-- Replace scope picker section with this: -->
        <div v-if="!isEdit" class="space-y-3">
        <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Target Scope <span class="text-rose-500">*</span>
        </label>

        <div class="grid grid-cols-2 lg:grid-cols-5 gap-2">
            <button v-for="s in scopeOptions" :key="s.id" type="button" @click="form.scope = s.id"
            :class="[
                'py-3 px-3 rounded-xl text-xs font-black transition-all border-2 text-left',
                form.scope === s.id
                ? 'bg-emerald-50 dark:bg-emerald-950/60 border-[#005506] dark:border-[#86EFAC] text-[#005506] dark:text-[#86EFAC]'
                : 'bg-[#F9F7F1] dark:bg-[#232D26] border-slate-200 dark:border-[#3F4F43] text-slate-600 dark:text-slate-300 hover:border-slate-300'
            ]">
            <Icon :icon="s.icon" size="sm" />
            <div class="mt-1.5">{{ s.label }}</div>
            </button>
        </div>

        <!-- Contextual picker based on scope -->
        <div v-if="form.scope === 'section'" class="space-y-2">
            <p class="text-[11px] font-black uppercase tracking-wider text-slate-500">Pick Sections</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-72 overflow-y-auto p-1">
            <button v-for="s in advisorySections" :key="s.id" type="button" @click="toggleItem(selectedSectionIds, s.id)"
                :class="[
                'text-left p-3 rounded-xl border-2 text-xs font-bold transition-all',
                selectedSectionIds.includes(s.id)
                    ? 'bg-emerald-50 dark:bg-emerald-950/40 border-[#005506] dark:border-[#86EFAC]'
                    : 'bg-[#F9F7F1] dark:bg-[#232D26] border-slate-200 dark:border-[#3F4F43]'
                ]">
                <p class="font-extrabold truncate">{{ s.name }}</p>
                <p class="text-[10px] text-slate-500">{{ s.strand_code }} • Grade {{ s.grade_level }} • {{ s.students_count }} students</p>
            </button>
            </div>
        </div>

        <div v-else-if="form.scope === 'class'" class="space-y-2">
            <p class="text-[11px] font-black uppercase tracking-wider text-slate-500">Pick Classes</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-72 overflow-y-auto p-1">
            <button v-for="c in classrooms" :key="c.id" type="button" @click="toggleItem(selectedClassIds, c.id)"
                :class="[
                'text-left p-3 rounded-xl border-2 text-xs font-bold transition-all',
                selectedClassIds.includes(c.id)
                    ? 'bg-emerald-50 dark:bg-emerald-950/40 border-[#005506] dark:border-[#86EFAC]'
                    : 'bg-[#F9F7F1] dark:bg-[#232D26] border-slate-200 dark:border-[#3F4F43]'
                ]">
                <p class="font-extrabold truncate">{{ c.subject }}</p>
                <p class="text-[10px] text-slate-500">{{ c.section }} • {{ c.students_count }} students</p>
            </button>
            </div>
        </div>

        <div v-else-if="form.scope === 'strand'" class="space-y-2">
            <p class="text-[11px] font-black uppercase tracking-wider text-slate-500">Pick Strand</p>
            <select v-model="form.strand_id"
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-xs font-semibold">
            <option value="">— Select strand —</option>
            <option v-for="s in strands" :key="s.id" :value="s.id">{{ s.code }} — {{ s.name }}</option>
            </select>
        </div>

        <div v-else-if="form.scope === 'grade'" class="space-y-2">
            <p class="text-[11px] font-black uppercase tracking-wider text-slate-500">Pick Grade Level</p>
            <div class="grid grid-cols-2 gap-2">
            <button type="button" @click="form.grade_level = '11'"
                :class="form.grade_level === '11' ? 'bg-[#005506] text-white' : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600'"
                class="py-3 rounded-xl text-xs font-black border-2 border-transparent transition-all">
                Grade 11
            </button>
            <button type="button" @click="form.grade_level = '12'"
                :class="form.grade_level === '12' ? 'bg-[#005506] text-white' : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600'"
                class="py-3 rounded-xl text-xs font-black border-2 border-transparent transition-all">
                Grade 12
            </button>
            </div>
        </div>

        <div v-else-if="form.scope === 'school'" class="bg-sky-50 dark:bg-sky-950/30 border border-sky-200/80 dark:border-sky-900/50 rounded-xl p-4 text-xs text-sky-900 dark:text-sky-200">
            <p class="font-black">School-wide contribution</p>
            <p class="mt-1 opacity-90">All enrolled students in the selected school year will be assigned.</p>
        </div>

        <!-- School Year (for strand/grade/school scopes) -->
        <div v-if="['strand','grade','school'].includes(form.scope)" class="space-y-1">
            <label class="text-[10px] font-black uppercase tracking-wider text-slate-500">School Year *</label>
            <select v-model="form.school_year_id"
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-xs font-semibold">
            <option value="">— Select school year —</option>
            <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id">
                {{ sy.label }}{{ sy.is_active ? ' (active)' : '' }}
            </option>
            </select>
        </div>
        </div>

        <!-- ═══ EDIT MODE: show existing scope ═══ -->
        <div v-if="isEdit" class="bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-900/40 rounded-2xl p-4 space-y-1">
          <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-300">
            {{ contribution.scope === 'section' ? 'Section-wide contribution for' : 'Class contribution for' }}
          </span>
          <p class="text-sm font-extrabold text-slate-900 dark:text-white">
            {{ contribution.display_name }}
          </p>
        </div>

        <!-- ═══ SECTION SCOPE: picker ═══ -->
        <div v-if="!isEdit && form.scope === 'section'" class="space-y-3">
          <div class="flex items-center justify-between gap-3">
            <div class="space-y-0.5">
              <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Advisory Section <span class="text-rose-500">*</span>
              </label>
              <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                All enrolled students in the section will be assigned.
              </p>
            </div>
            <span v-if="selectedSectionIds.length"
              class="bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40 text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full border shrink-0">
              {{ selectedSectionIds.length }} selected
            </span>
          </div>

          <p v-if="fieldError('section_ids')" class="text-rose-600 dark:text-rose-400 text-[11px] font-bold flex items-center gap-1 -mt-1">
            <Icon icon="alert-circle" size="xs" />
            {{ fieldError('section_ids') }}
          </p>

          <div v-if="advisorySections.length" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <button v-for="s in advisorySections" :key="s.id"
              type="button"
              @click="toggleSection(s.id)"
              :class="[
                'text-left bg-[#F9F7F1] dark:bg-[#232D26] border-2 rounded-2xl p-4 transition-all flex items-start gap-3 group relative',
                selectedSectionIds.includes(s.id)
                  ? 'border-[#005506] dark:border-[#86EFAC] bg-emerald-50/60 dark:bg-emerald-950/30 ring-2 ring-emerald-200 dark:ring-emerald-900/40 shadow-sm'
                  : 'border-slate-200/80 dark:border-[#3F4F43] hover:border-[#005506]/60 dark:hover:border-[#86EFAC]/60 active:scale-[0.98]'
              ]">
              <div :class="[
                'w-5 h-5 rounded-md border-2 flex items-center justify-center shrink-0 mt-0.5 transition-all',
                selectedSectionIds.includes(s.id)
                  ? 'bg-[#005506] dark:bg-[#86EFAC] border-[#005506] dark:border-[#86EFAC]'
                  : 'border-slate-300 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31]'
              ]">
                <Icon v-if="selectedSectionIds.includes(s.id)" icon="check" size="xs" class="text-white dark:text-[#232D26]" />
              </div>

              <div class="flex-1 min-w-0">
                <p class="text-sm font-extrabold text-slate-900 dark:text-white truncate">
                  {{ s.name }}
                </p>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate mt-0.5">
                  <span v-if="s.strand_code">{{ s.strand_code }} • </span>
                  Grade {{ s.grade_level }}
                </p>
                <div class="flex items-center gap-2 mt-2">
                  <span class="bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-black px-2 py-0.5 rounded-full border border-amber-200 dark:border-amber-900/40">
                    Advisory
                  </span>
                  <span class="text-[10px] font-bold text-[#005506] dark:text-[#86EFAC]">
                    {{ s.students_count }} {{ s.students_count === 1 ? 'student' : 'students' }}
                  </span>
                </div>
              </div>
            </button>
          </div>

          <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-6 text-center border border-dashed border-slate-300 dark:border-[#3F4F43]">
            <p class="text-xs text-slate-500 dark:text-slate-400 italic">
              You are not an adviser of any section. Switch to <strong>Per Class</strong> scope instead.
            </p>
          </div>
        </div>

        <!-- ═══ CLASS SCOPE: picker ═══ -->
        <div v-if="!isEdit && form.scope === 'class'" class="space-y-3">
          <div class="flex items-center justify-between gap-3">
            <div class="space-y-0.5">
              <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Target Classes <span class="text-rose-500">*</span>
              </label>
              <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                Pick one or more classes you teach.
              </p>
            </div>
            <span v-if="selectedClassIds.length"
              class="bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40 text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full border shrink-0">
              {{ selectedClassIds.length }} selected
            </span>
          </div>

          <p v-if="fieldError('class_ids')" class="text-rose-600 dark:text-rose-400 text-[11px] font-bold flex items-center gap-1 -mt-1">
            <Icon icon="alert-circle" size="xs" />
            {{ fieldError('class_ids') }}
          </p>

          <div v-if="classrooms.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <button v-for="c in classrooms" :key="c.id"
              type="button"
              @click="toggleClass(c.id)"
              :class="[
                'text-left bg-[#F9F7F1] dark:bg-[#232D26] border-2 rounded-2xl p-3.5 transition-all flex items-start gap-3 group relative',
                selectedClassIds.includes(c.id)
                  ? 'border-[#005506] dark:border-[#86EFAC] bg-emerald-50/60 dark:bg-emerald-950/30 ring-2 ring-emerald-200 dark:ring-emerald-900/40 shadow-sm'
                  : 'border-slate-200/80 dark:border-[#3F4F43] hover:border-[#005506]/60 dark:hover:border-[#86EFAC]/60 active:scale-[0.98]'
              ]">
              <div :class="[
                'w-5 h-5 rounded-md border-2 flex items-center justify-center shrink-0 mt-0.5 transition-all',
                selectedClassIds.includes(c.id)
                  ? 'bg-[#005506] dark:bg-[#86EFAC] border-[#005506] dark:border-[#86EFAC]'
                  : 'border-slate-300 dark:border-[#3F4F43] bg-white dark:bg-[#2D3A31]'
              ]">
                <Icon v-if="selectedClassIds.includes(c.id)" icon="check" size="xs" class="text-white dark:text-[#232D26]" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white truncate">{{ c.subject }}</p>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate mt-0.5">
                  {{ c.section }}<span v-if="c.grade_level"> • Grade {{ c.grade_level }}</span>
                </p>
                <p class="text-[10px] font-bold text-[#005506] dark:text-[#86EFAC] mt-1">
                  {{ c.students_count }} {{ c.students_count === 1 ? 'student' : 'students' }}
                </p>
              </div>
            </button>
          </div>

          <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-6 text-center border border-dashed border-slate-300 dark:border-[#3F4F43]">
            <p class="text-xs text-slate-500 dark:text-slate-400 italic">No classes assigned to you yet.</p>
          </div>

          <div v-if="classrooms.length > 1" class="flex items-center justify-end gap-2 pt-1">
            <button type="button" @click="selectAllClasses"
              :disabled="selectedClassIds.length === classrooms.length"
              class="text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-[#005506] dark:hover:text-[#86EFAC] px-3 py-1.5 rounded-lg border border-slate-200/80 dark:border-[#3F4F43] hover:bg-slate-50 dark:hover:bg-[#3F4F43] disabled:opacity-40 transition-colors">
              Select All ({{ classrooms.length }})
            </button>
            <button type="button" @click="clearClassSelection"
              :disabled="selectedClassIds.length === 0"
              class="text-[11px] font-bold text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 px-3 py-1.5 rounded-lg border border-rose-200/80 dark:border-rose-900/40 hover:bg-rose-50 dark:hover:bg-rose-950/30 disabled:opacity-40 transition-colors">
              Clear
            </button>
          </div>
        </div>

        <!-- ═══ TITLE ═══ -->
        <div class="space-y-1 pt-4 border-t border-slate-100 dark:border-[#3F4F43]"
          :data-error="!!fieldError('title')">
          <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Title <span class="text-rose-500">*</span>
          </label>
          <input v-model="form.title" type="text" placeholder="e.g. Field Trip Fund — Baguio Educational Tour"
            :class="[
              'w-full bg-[#F9F7F1] dark:bg-[#232D26] border rounded-xl px-4 py-3 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2',
              fieldError('title')
                ? 'border-rose-400 dark:border-rose-500 focus:ring-rose-400'
                : 'border-slate-200/80 dark:border-[#3F4F43] focus:ring-[#005506]'
            ]" />
          <p v-if="fieldError('title')" class="text-rose-600 dark:text-rose-400 text-[11px] font-bold mt-1 flex items-center gap-1">
            <Icon icon="alert-circle" size="xs" />
            {{ fieldError('title') }}
          </p>
        </div>

        <!-- ═══ PURPOSE + DEADLINE ═══ -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-1">
            <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Purpose (short)</label>
            <input v-model="form.purpose" type="text" placeholder="e.g. Educational field trip"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
          </div>
          <div class="space-y-1" :data-error="!!fieldError('deadline_at')">
            <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Deadline</label>
            <input v-model="form.deadline_at" type="datetime-local"
              :class="[
                'w-full bg-[#F9F7F1] dark:bg-[#232D26] border rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2',
                fieldError('deadline_at')
                  ? 'border-rose-400 dark:border-rose-500 focus:ring-rose-400'
                  : 'border-slate-200/80 dark:border-[#3F4F43] focus:ring-[#005506]'
              ]" />
            <p v-if="fieldError('deadline_at')" class="text-rose-600 dark:text-rose-400 text-[11px] font-bold mt-1 flex items-center gap-1">
              <Icon icon="alert-circle" size="xs" />
              {{ fieldError('deadline_at') }}
            </p>
          </div>
        </div>

        <!-- ═══ DESCRIPTION ═══ -->
        <div class="space-y-1">
          <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Description</label>
          <textarea v-model="form.description" rows="3" placeholder="What is this contribution for? Explain briefly to students and guardians."
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-4 text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] resize-none"></textarea>
        </div>

        <!-- ═══ AMOUNT TYPE ═══ -->
        <div class="space-y-2 pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
          <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Amount Type <span class="text-rose-500">*</span>
          </label>
          <div class="grid grid-cols-2 gap-3">
            <button type="button" @click="form.amount_type = 'fixed'"
              :class="[
                'py-3 px-4 rounded-xl text-xs font-black transition-all border-2',
                form.amount_type === 'fixed'
                  ? 'bg-emerald-50 dark:bg-emerald-950/60 border-[#005506] dark:border-[#86EFAC] text-[#005506] dark:text-[#86EFAC]'
                  : 'bg-[#F9F7F1] dark:bg-[#232D26] border-slate-200 dark:border-[#3F4F43] text-slate-600 dark:text-slate-300 hover:border-slate-300 dark:hover:border-[#4a5c50]'
              ]">
              <div class="text-sm">Fixed Amount</div>
              <div class="text-[10px] font-semibold opacity-70 mt-0.5">Same price for all students</div>
            </button>
            <button type="button" @click="form.amount_type = 'open'"
              :class="[
                'py-3 px-4 rounded-xl text-xs font-black transition-all border-2',
                form.amount_type === 'open'
                  ? 'bg-emerald-50 dark:bg-emerald-950/60 border-[#005506] dark:border-[#86EFAC] text-[#005506] dark:text-[#86EFAC]'
                  : 'bg-[#F9F7F1] dark:bg-[#232D26] border-slate-200 dark:border-[#3F4F43] text-slate-600 dark:text-slate-300 hover:border-slate-300 dark:hover:border-[#4a5c50]'
              ]">
              <div class="text-sm">Open Amount</div>
              <div class="text-[10px] font-semibold opacity-70 mt-0.5">Student picks the amount</div>
            </button>
          </div>
        </div>

        <!-- ═══ AMOUNT FIELDS ═══ -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div v-if="form.amount_type === 'fixed'" class="space-y-1" :data-error="!!fieldError('amount')">
            <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
              Amount (₱) <span class="text-rose-500">*</span>
            </label>
            <input v-model.number="form.amount" type="number" min="1" step="0.01"
              :class="[
                'w-full bg-[#F9F7F1] dark:bg-[#232D26] border rounded-xl px-4 py-3 text-sm font-black text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2',
                fieldError('amount')
                  ? 'border-rose-400 dark:border-rose-500 focus:ring-rose-400'
                  : 'border-slate-200/80 dark:border-[#3F4F43] focus:ring-[#005506]'
              ]" />
            <p v-if="fieldError('amount')" class="text-rose-600 dark:text-rose-400 text-[11px] font-bold mt-1 flex items-center gap-1">
              <Icon icon="alert-circle" size="xs" />
              {{ fieldError('amount') }}
            </p>
          </div>
          <div v-else class="space-y-1">
            <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Minimum (₱)</label>
            <input v-model.number="form.min_amount" type="number" min="1" step="0.01"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-sm font-black text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
          </div>
          <div class="space-y-1">
            <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Target (optional)</label>
            <input v-model.number="form.target_amount" type="number" min="1" step="0.01"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-sm font-black text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
          </div>
        </div>

        <!-- ═══ TOGGLES ═══ -->
        <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
          <label class="flex items-start gap-3 cursor-pointer group">
            <input type="checkbox" v-model="form.requires_guardian_consent"
              class="mt-0.5 rounded border-slate-300 text-[#005506] focus:ring-[#005506] w-4 h-4 cursor-pointer" />
            <div>
              <span class="text-xs font-bold text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">
                Require guardian approval before payment
              </span>
              <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Recommended for minors — protects the school and the teacher.</p>
            </div>
          </label>

          <label class="flex items-start gap-3 cursor-pointer group">
            <input type="checkbox" v-model="form.is_required"
              class="mt-0.5 rounded border-slate-300 text-[#005506] focus:ring-[#005506] w-4 h-4 cursor-pointer" />
            <div>
              <span class="text-xs font-bold text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">
                Mark as required
              </span>
              <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">All assigned students must pay (e.g., graduation fee).</p>
            </div>
          </label>

          <label class="flex items-start gap-3 cursor-pointer group">
            <input type="checkbox" v-model="form.is_published"
              class="mt-0.5 rounded border-slate-300 text-[#005506] focus:ring-[#005506] w-4 h-4 cursor-pointer" />
            <div>
              <span class="text-xs font-bold text-slate-700 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">
                Publish immediately
              </span>
              <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Uncheck to save as draft and publish later.</p>
            </div>
          </label>
        </div>

        <!-- ═══ PREVIEW BANNER ═══ -->
        <div v-if="!isEdit && selectedCount > 0"
          class="bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-900/40 rounded-2xl p-4 flex items-start gap-3">
          <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0">
            <Icon icon="sparkles" size="sm" />
          </div>
          <div class="text-xs text-emerald-900 dark:text-emerald-200 leading-relaxed">
            <p class="font-black">
              {{ selectedCount }} {{ form.scope === 'section' ? 'section-wide' : 'class' }} contribution{{ selectedCount === 1 ? '' : 's' }} will be created
            </p>
            <p class="font-medium opacity-90 mt-0.5">
              Total of <strong>{{ totalStudentsAcrossSelected }} students</strong> across
              <strong>{{ selectedCount }} {{ form.scope === 'section' ? 'section' : 'class' }}{{ selectedCount === 1 ? '' : 'es' }}</strong>.
              Each gets its own campaign and roster.
            </p>
            <p v-if="form.is_published" class="font-black text-[#004d08] dark:text-[#86EFAC] mt-1.5">
              📧 Guardian emails will be sent automatically on save.
            </p>
          </div>
        </div>

        <!-- ═══ ACTIONS ═══ -->
        <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
          <BackButton fallback="teacher.contributions.index"
            class="px-5 py-2.5 text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white text-xs font-bold transition-colors inline-flex items-center gap-1.5">
            Cancel
          </BackButton>
          <button type="submit" :disabled="submitting || (!isEdit && selectedCount === 0)"
            class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] px-6 py-2.5 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2">
            <Icon :icon="isEdit ? 'check-circle' : 'plus'" size="xs" />
            {{ submitting
              ? 'Saving…'
              : (isEdit ? 'Save Changes'
                : (selectedCount === 0
                  ? 'Create Contribution'
                  : `Create ${selectedCount} Contribution${selectedCount === 1 ? '' : 's'}`)) }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BackButton from '@/Components/BackButton.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/assignment.png'

const props = defineProps({
  advisorySections: { type: Array,  default: () => [] },
  classrooms:       { type: Array,  default: () => [] },
  contribution:     { type: Object, default: null },
})

const isEdit = computed(() => !!props.contribution?.id)

const defaultScope = props.advisorySections.length > 0 ? 'section' : 'class'

const form = ref({
  scope:                     props.contribution?.scope ?? defaultScope,
  title:                     props.contribution?.title ?? '',
  purpose:                   props.contribution?.purpose ?? '',
  description:               props.contribution?.description ?? '',
  amount_type:               props.contribution?.amount_type ?? 'fixed',
  amount:                    props.contribution?.amount ?? null,
  min_amount:                props.contribution?.min_amount ?? null,
  target_amount:             props.contribution?.target_amount ?? null,
  deadline_at:               props.contribution?.deadline_at?.slice(0, 16) ?? '',
  is_required:               props.contribution?.is_required ?? false,
  is_published:              props.contribution?.is_published ?? false,
  requires_guardian_consent: props.contribution?.requires_guardian_consent ?? true,
})

const selectedSectionIds = ref(
  props.contribution?.scope === 'section' && props.contribution?.section?.id
    ? [props.contribution.section.id]
    : props.advisorySections.map(s => s.id)
)

const selectedClassIds = ref(
  props.contribution?.scope === 'class' && props.contribution?.classroom?.id
    ? [props.contribution.classroom.id]
    : []
)

const submitting  = ref(false)
const formError   = ref('')
const fieldErrors = ref({})

const selectedCount = computed(() =>
  form.value.scope === 'section'
    ? selectedSectionIds.value.length
    : selectedClassIds.value.length
)

const totalStudentsAcrossSelected = computed(() => {
  if (form.value.scope === 'section') {
    return props.advisorySections
      .filter(s => selectedSectionIds.value.includes(s.id))
      .reduce((sum, s) => sum + (s.students_count || 0), 0)
  }
  return props.classrooms
    .filter(c => selectedClassIds.value.includes(c.id))
    .reduce((sum, c) => sum + (c.students_count || 0), 0)
})

function setScope(scope) {
  if (scope === 'section' && !props.advisorySections.length) return
  form.value.scope = scope
  if (fieldErrors.value.section_ids) delete fieldErrors.value.section_ids
  if (fieldErrors.value.class_ids) delete fieldErrors.value.class_ids
}

function toggleSection(id) {
  const idx = selectedSectionIds.value.indexOf(id)
  if (idx >= 0) selectedSectionIds.value.splice(idx, 1)
  else selectedSectionIds.value.push(id)
  if (fieldErrors.value.section_ids) delete fieldErrors.value.section_ids
}

function toggleClass(id) {
  const idx = selectedClassIds.value.indexOf(id)
  if (idx >= 0) selectedClassIds.value.splice(idx, 1)
  else selectedClassIds.value.push(id)
  if (fieldErrors.value.class_ids) delete fieldErrors.value.class_ids
}

function selectAllClasses() {
  selectedClassIds.value = props.classrooms.map(c => c.id)
  if (fieldErrors.value.class_ids) delete fieldErrors.value.class_ids
}

function clearClassSelection() {
  selectedClassIds.value = []
}

function fieldError(field) {
  const err = fieldErrors.value[field]
  if (!err) return null
  return Array.isArray(err) ? err[0] : err
}

function prevalidate() {
  const errs = {}

  if (!isEdit.value && selectedCount.value === 0) {
    errs[form.value.scope === 'section' ? 'section_ids' : 'class_ids'] =
      ['Pick at least one ' + (form.value.scope === 'section' ? 'section' : 'class') + '.']
  }

  if (!form.value.title || !form.value.title.trim()) {
    errs.title = ['Title is required.']
  }

  if (form.value.amount_type === 'fixed') {
    const amt = Number(form.value.amount)
    if (!amt || amt <= 0) {
      errs.amount = ['Enter an amount greater than zero.']
    }
  }

  fieldErrors.value = errs
  return Object.keys(errs).length === 0
}

function scrollToFirstError() {
  setTimeout(() => {
    const el = document.querySelector('[data-error="true"]')
    el?.scrollIntoView({ behavior: 'smooth', block: 'center' })
  }, 50)
}

async function submit() {
  formError.value = ''
  fieldErrors.value = {}

  if (!prevalidate()) {
    formError.value = 'Please fix the highlighted fields below.'
    scrollToFirstError()
    return
  }

  submitting.value = true

  try {
    if (isEdit.value) {
      await axios.put(route('teacher.contributions.update', props.contribution.id), form.value)
      router.visit(route('teacher.contributions.show', props.contribution.id))
    } else {
      const payload = {
        ...form.value,
        section_ids: form.value.scope === 'section' ? selectedSectionIds.value : undefined,
        class_ids:   form.value.scope === 'class'   ? selectedClassIds.value   : undefined,
        }
      const { data } = await axios.post(route('teacher.contributions.store'), payload)
      router.visit(data.redirect || route('teacher.contributions.index'))
    }
  } catch (e) {
    const status = e.response?.status
    const serverErrors = e.response?.data?.errors || {}

    if (status === 422) {
      fieldErrors.value = serverErrors
      formError.value = 'Please fix the highlighted fields below.'
      scrollToFirstError()
    } else {
      formError.value = e.response?.data?.message || 'Something went wrong. Please try again.'
    }
  } finally {
    submitting.value = false
  }
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

const scopeOptions = [
  { id: 'section', label: 'Sections', icon: 'users' },
  { id: 'class',   label: 'Classes',  icon: 'book-open' },
  { id: 'strand',  label: 'Strand',   icon: 'globe' },
  { id: 'grade',   label: 'Grade',    icon: 'academic-cap' },
  { id: 'school',  label: 'School',   icon: 'school' },
]

function toggleItem(arrRef, id) {
  const idx = arrRef.value.indexOf(id)
  if (idx >= 0) arrRef.value.splice(idx, 1)
  else arrRef.value.push(id)
}
</script>

<style scoped>
@keyframes pulse-opacity { 0%, 100% { opacity: 0.88; } 50% { opacity: 0.65; } }
.animate-overlay { animation: pulse-opacity 6s infinite ease-in-out; }

@keyframes fadeInDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideUpFade { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }

.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up { animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>