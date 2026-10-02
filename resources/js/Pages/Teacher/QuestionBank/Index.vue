<template>
  <Head title="Question Bank - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search questions, banks, categories..">

    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-24 space-y-6 flex-1 mt-2 max-w-full">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[260px] flex flex-col justify-center">
        <img :src="heroImage" alt="Question Bank Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 max-w-3xl space-y-3 sm:space-y-4">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <div class="inline-flex items-center gap-1.5 sm:gap-2 bg-[#F9C20C] text-[#2C3E2D] text-[10px] sm:text-xs font-black uppercase tracking-wider px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
              <Icon icon="book-open" size="xs" />
              Question Bank
            </div>
            <div class="inline-flex items-center gap-1.5 sm:gap-2 bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
              <Icon icon="sparkles" size="xs" />
              {{ stats.total }} items
            </div>
          </div>

          <div class="space-y-2 sm:space-y-3">
            <h2 class="text-xl sm:text-3xl md:text-5xl font-black text-white tracking-tight leading-tight">
              Question Bank Manager
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              Author and curate reusable questions across all your classes, ready to attach to any quiz.
            </p>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <button @click="openCreate"
              class="inline-flex items-center gap-1.5 bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] px-3.5 py-2 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95">
              <Icon icon="plus" size="xs" />
              New Question
            </button>
            <button @click="showImportModal = true"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              <Icon icon="upload" size="xs" />
              Import CSV
            </button>
            <Link :href="route('teacher.quizzes.index')"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              <Icon icon="arrow-left" size="xs" />
              Quiz Hub
            </Link>
          </div>
        </div>
      </div>

      <!-- STATS -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Total</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="book-open" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ stats.total }}</div>
          <p class="text-[11px] font-medium text-slate-500">Reusable items</p>
        </div>
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Multiple Choice</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="check-circle" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-[#005506] dark:text-[#86EFAC]">{{ stats.mc }}</div>
          <p class="text-[11px] font-medium text-slate-500">With options</p>
        </div>
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">True / False</span>
            <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
              <Icon icon="info" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-amber-700 dark:text-amber-400">{{ stats.tf }}</div>
          <p class="text-[11px] font-medium text-slate-500">Binary</p>
        </div>
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Essay / Short</span>
            <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-900/40">
              <Icon icon="edit" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-blue-700 dark:text-blue-400">{{ stats.open }}</div>
          <p class="text-[11px] font-medium text-slate-500">Manual grading</p>
        </div>
      </div>

      <!-- FILTER BAR -->
      <div v-observe class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-3.5 sm:p-4 shadow-sm border border-slate-200/60 dark:border-[#3F4F43]" style="animation-delay: 200ms;">
        <div class="flex flex-col lg:flex-row lg:items-center gap-3">
          <div class="relative w-full lg:w-72 shrink-0">
            <select v-model="subjectFilter"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-100 text-xs font-extrabold px-3.5 py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer pr-8 truncate">
              <option value="all">All My Subjects</option>
              <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.code }} — {{ s.name }}</option>
            </select>
            <Icon icon="chevron-down" size="xs" class="absolute right-3 top-3.5 text-slate-400 pointer-events-none" />
          </div>
          <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 lg:pb-0">
            <button v-for="f in typeFilters" :key="f.id" @click="typeFilter = f.id"
              :class="[
                'text-[11px] font-black px-3.5 py-1.5 sm:py-2 rounded-xl shadow-sm transition-all shrink-0 active:scale-95',
                typeFilter === f.id
                  ? 'bg-[#005506] dark:bg-[#86EFAC] text-white dark:text-[#232D26]'
                  : 'bg-[#F9F7F1] dark:bg-[#232D26] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43]'
              ]">
              {{ f.label }} ({{ f.count }})
            </button>
          </div>
          <div class="relative flex-1 min-w-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
              <Icon icon="search" size="sm" />
            </span>
            <input v-model="search" type="text" placeholder="Search question text..."
              class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-xs font-semibold text-slate-700 dark:text-slate-200" />
          </div>
        </div>
      </div>

      <!-- LIST -->
      <div v-if="filteredQuestions.length" class="space-y-3">
        <div v-for="(q, idx) in filteredQuestions" :key="q.id"
          v-observe
          :style="{ animationDelay: `${idx * 30}ms` }"
          class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3 hover:shadow-md transition-all">

          <div class="flex items-start justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-2 flex-wrap">
              <span :class="typeBadgeClass(q.type)">{{ typeLabel(q.type) }}</span>
              <span v-if="q.subject" class="bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-[10px] font-bold px-2 py-0.5 rounded-md border border-blue-100 dark:border-blue-900/40">
                {{ q.subject.code }}
              </span>
            </div>
            <span class="text-[10px] font-black text-slate-500 dark:text-slate-400">
              {{ q.points }} {{ q.points == 1 ? 'pt' : 'pts' }}
            </span>
          </div>

          <p class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 leading-relaxed whitespace-pre-wrap">
            {{ q.question_text }}
          </p>

          <div v-if="q.options?.length" class="space-y-1.5 pt-1">
            <div v-for="(opt, i) in q.options" :key="opt.id"
              :class="[
                'flex items-center gap-2 px-3 py-2 rounded-lg text-[11px] font-bold border',
                opt.is_correct
                  ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-900/50 text-emerald-800 dark:text-[#86EFAC]'
                  : 'bg-[#F9F7F1] dark:bg-[#232D26] border-slate-200/80 dark:border-[#3F4F43] text-slate-700 dark:text-slate-300'
              ]">
              <span :class="opt.is_correct ? 'text-[#005506] dark:text-[#86EFAC]' : 'text-slate-400'"
                class="font-black shrink-0">{{ String.fromCharCode(65 + i) }}.</span>
              <span class="truncate flex-1">{{ opt.option_text }}</span>
              <Icon v-if="opt.is_correct" icon="check-circle" size="xs" class="shrink-0" />
            </div>
          </div>

          <div v-if="q.explanation" class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] space-y-1">
            <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block">Explanation</span>
            <p class="text-[11px] text-slate-600 dark:text-slate-300 leading-relaxed">{{ q.explanation }}</p>
          </div>

          <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
            <button @click="openEdit(q)"
              class="flex-1 bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 text-[11px] font-bold py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-1.5 transition-colors">
              <Icon icon="edit" size="xs" />
              Edit
            </button>
            <button @click="confirmDelete = q"
              class="flex-1 bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-rose-50 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 text-[11px] font-bold py-2 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] hover:border-rose-200 flex items-center justify-center gap-1.5 transition-colors">
              <Icon icon="trash" size="xs" />
              Delete
            </button>
          </div>
        </div>
      </div>

      <div v-else
        class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-12 shadow-sm border border-dashed border-slate-300 dark:border-[#3F4F43] text-center space-y-3">
        <Icon icon="book-open" size="xl" class="text-slate-400 mx-auto" />
        <p class="text-sm font-bold text-slate-800 dark:text-white">
          {{ stats.total === 0 ? 'Your bank is empty' : 'No questions match your filters' }}
        </p>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          {{ stats.total === 0 ? 'Add your first question or import a CSV to get started.' : 'Try clearing the filters.' }}
        </p>
      </div>

    </div>

    <!-- CREATE / EDIT MODAL -->
    <div v-if="modal.open" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-start justify-center p-4 overflow-y-auto"
      @click.self="closeModal">
      <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-2xl w-full max-w-2xl p-6 space-y-5 border border-slate-100 dark:border-[#3F4F43] my-8">

        <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
          <div>
            <h3 class="font-black text-slate-900 dark:text-white text-lg">
              {{ modal.mode === 'create' ? 'New Question' : 'Edit Question' }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-0.5">
              Saved to your personal bank. Attach to any quiz later.
            </p>
          </div>
          <button @click="closeModal"
            class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-slate-200 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors shrink-0">
            <Icon icon="x" size="xs" />
          </button>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div v-if="formError" class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl text-rose-700 dark:text-rose-300 text-xs">
            {{ formError }}
          </div>

          <div class="space-y-1">
            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Type *</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
              <button v-for="t in typeOptions" :key="t.id" type="button" @click="setType(t.id)"
                :class="[
                  'py-2 px-3 rounded-xl text-xs font-black flex items-center justify-center gap-1.5 transition-all',
                  form.type === t.id
                    ? 'bg-emerald-50 dark:bg-emerald-950/60 border-2 border-[#005506] dark:border-[#86EFAC] text-[#005506] dark:text-[#86EFAC] shadow-sm'
                    : 'bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200 dark:border-[#3F4F43] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#3F4F43]'
                ]">
                <span>{{ t.icon }}</span>
                {{ t.label }}
              </button>
            </div>
          </div>

          <div class="space-y-1">
            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Subject *</label>
            <select v-model="form.subject_id"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer">
              <option value="">— Select subject —</option>
              <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.code }} — {{ s.name }}</option>
            </select>
          </div>

          <div class="space-y-1">
            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Question Text *</label>
            <textarea v-model="form.question_text" rows="4"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-3.5 text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] resize-none"></textarea>
          </div>

          <div class="space-y-1">
            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Points</label>
            <input v-model.number="form.points" type="number" min="0.5" step="0.5"
              class="w-full sm:w-32 bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
          </div>

          <div v-if="form.type === 'multiple_choice'" class="space-y-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
            <div class="flex items-center justify-between">
              <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Options</label>
              <button type="button" @click="addOption"
                class="text-[11px] font-black text-[#005506] dark:text-[#86EFAC] hover:underline flex items-center gap-1">
                <Icon icon="plus" size="xs" /> Add Option
              </button>
            </div>
            <div v-for="(opt, i) in form.options" :key="i" class="flex items-center gap-2">
              <input type="radio" :checked="opt.is_correct" @change="setCorrectOption(i)" name="correct_option"
                class="w-4 h-4 text-[#005506] focus:ring-[#005506] shrink-0" />
              <span class="text-xs font-black text-slate-500 w-4 shrink-0">{{ String.fromCharCode(65 + i) }}</span>
              <input v-model="opt.option_text" type="text"
                class="flex-1 bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3 py-2 text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
              <button type="button" @click="removeOption(i)"
                class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-[#232D26] hover:bg-rose-100 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center transition-colors shrink-0">
                <Icon icon="x" size="xs" />
              </button>
            </div>
          </div>

          <div v-else-if="form.type === 'true_false'" class="space-y-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Correct Answer</label>
            <div class="flex items-center gap-2">
              <label class="flex-1 bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 flex items-center gap-2 cursor-pointer">
                <input type="radio" :checked="form.options[0]?.is_correct" @change="setTrueFalse(true)"
                  name="tf_correct" class="w-4 h-4 text-[#005506] focus:ring-[#005506]" />
                <span class="text-xs font-black text-slate-700 dark:text-slate-200">True</span>
              </label>
              <label class="flex-1 bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 flex items-center gap-2 cursor-pointer">
                <input type="radio" :checked="form.options[1]?.is_correct" @change="setTrueFalse(false)"
                  name="tf_correct" class="w-4 h-4 text-[#005506] focus:ring-[#005506]" />
                <span class="text-xs font-black text-slate-700 dark:text-slate-200">False</span>
              </label>
            </div>
          </div>

          <div v-else-if="form.type === 'short_answer'" class="space-y-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Expected Answer</label>
            <input v-model="form.options[0].option_text" type="text" placeholder="Correct answer (case-insensitive match)"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506]" />
          </div>

          <div class="space-y-1 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
            <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Explanation (optional)</label>
            <textarea v-model="form.explanation" rows="2"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-3 text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] resize-none"></textarea>
          </div>

          <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-[#3F4F43]">
            <button type="button" @click="closeModal"
              class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white text-xs font-bold transition-colors">
              Cancel
            </button>
            <button type="submit" :disabled="saving"
              class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] px-6 py-2.5 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2">
              <Icon icon="check-circle" size="xs" />
              {{ saving ? 'Saving…' : (modal.mode === 'create' ? 'Create Question' : 'Save Changes') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- DELETE CONFIRM MODAL -->
    <div v-if="confirmDelete" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4"
      @click.self="confirmDelete = null">
      <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-2xl w-full max-w-md p-6 space-y-4 border border-slate-100 dark:border-[#3F4F43]">
        <div class="flex items-start gap-3">
          <div class="w-11 h-11 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40">
            <Icon icon="trash" size="md" />
          </div>
          <div class="min-w-0">
            <h3 class="font-black text-slate-900 dark:text-white text-base">Delete this question?</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mt-1 leading-relaxed line-clamp-3">
              "{{ confirmDelete.question_text }}"
            </p>
          </div>
        </div>

        <p class="text-[11px] text-slate-500 dark:text-slate-400 bg-[#F9F7F1] dark:bg-[#232D26] rounded-xl p-3 border border-slate-200/80 dark:border-[#3F4F43]">
          This cannot be undone. If the question is attached to any quiz, deletion will be refused.
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

    <!-- IMPORT MODAL -->
    <ImportQuestionsModal
      :show="showImportModal"
      :subjects="subjects"
      @close="showImportModal = false"
      @imported="onImported" />

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import ImportQuestionsModal from './ImportQuestionsModal.vue'
import heroImage from '../../../../assets/img/local/quiz-hub-hero.jpg'

const props = defineProps({
  questions:  { type: Object, default: () => ({ data: [], links: [] }) },
  subjects:   { type: Array,  default: () => [] },
})

/* ─── Filters ─────────────────────────────────────────── */
const subjectFilter = ref('all')
const typeFilter    = ref('all')
const search        = ref('')

const questions = computed(() => props.questions?.data ?? [])

const typeFilters = computed(() => [
  { id: 'all',             label: 'All',   count: questions.value.length },
  { id: 'multiple_choice', label: 'MC',    count: questions.value.filter(q => q.type === 'multiple_choice').length },
  { id: 'true_false',      label: 'T/F',   count: questions.value.filter(q => q.type === 'true_false').length },
  { id: 'short_answer',    label: 'Short', count: questions.value.filter(q => q.type === 'short_answer').length },
  { id: 'essay',           label: 'Essay', count: questions.value.filter(q => q.type === 'essay').length },
].filter(f => f.id === 'all' || f.count > 0))

const filteredQuestions = computed(() => {
  const q = search.value.trim().toLowerCase()
  return questions.value.filter(item => {
    if (subjectFilter.value !== 'all' && item.subject_id !== subjectFilter.value) return false
    if (typeFilter.value !== 'all' && item.type !== typeFilter.value) return false
    if (q && !(item.question_text || '').toLowerCase().includes(q)) return false
    return true
  })
})

const stats = computed(() => {
  const list = questions.value
  return {
    total: list.length,
    mc:    list.filter(q => q.type === 'multiple_choice').length,
    tf:    list.filter(q => q.type === 'true_false').length,
    open:  list.filter(q => ['short_answer', 'essay'].includes(q.type)).length,
  }
})

/* ─── Modal state ────────────────────────────────────── */
const modal = reactive({ open: false, mode: 'create', editingId: null })
const confirmDelete = ref(null)
const showImportModal = ref(false)
const saving = ref(false)
const deleting = ref(false)
const formError = ref('')

const form = reactive({
  subject_id: '',
  type: 'multiple_choice',
  question_text: '',
  points: 1,
  explanation: '',
  options: [
    { option_text: '', is_correct: true },
    { option_text: '', is_correct: false },
  ],
})

function resetForm() {
  form.subject_id = props.subjects[0]?.id ?? ''
  form.type = 'multiple_choice'
  form.question_text = ''
  form.points = 1
  form.explanation = ''
  form.options = [
    { option_text: '', is_correct: true },
    { option_text: '', is_correct: false },
  ]
  formError.value = ''
}

function openCreate() {
  resetForm()
  modal.mode = 'create'
  modal.editingId = null
  modal.open = true
}

function openEdit(question) {
  resetForm()
  modal.mode = 'edit'
  modal.editingId = question.id
  form.subject_id    = question.subject_id ?? props.subjects[0]?.id ?? ''
  form.type          = question.type
  form.question_text = question.question_text
  form.points        = parseFloat(question.points) || 1
  form.explanation   = question.explanation ?? ''
  form.options = (question.options || []).map(o => ({
    option_text: o.option_text,
    is_correct: !!o.is_correct,
  }))
  if (!form.options.length) {
    form.options = [{ option_text: '', is_correct: true }, { option_text: '', is_correct: false }]
  }
  modal.open = true
}

function closeModal() {
  modal.open = false
  modal.editingId = null
  resetForm()
}

/* ─── Options helpers ────────────────────────────────── */
function addOption() {
  if (form.options.length >= 6) return
  form.options.push({ option_text: '', is_correct: false })
}
function removeOption(i) {
  if (form.options.length <= 2) return
  const wasCorrect = form.options[i].is_correct
  form.options.splice(i, 1)
  if (wasCorrect) form.options[0].is_correct = true
}
function setCorrectOption(i) {
  form.options.forEach((opt, idx) => { opt.is_correct = idx === i })
}
function setTrueFalse(isTrue) {
  form.options = [
    { option_text: 'True',  is_correct: !!isTrue },
    { option_text: 'False', is_correct: !isTrue },
  ]
}
function setType(type) {
  form.type = type
  if (type === 'multiple_choice') {
    if (form.options.length < 2) {
      form.options = [
        { option_text: '', is_correct: true },
        { option_text: '', is_correct: false },
      ]
    }
  } else if (type === 'true_false') {
    form.options = [
      { option_text: 'True',  is_correct: true },
      { option_text: 'False', is_correct: false },
    ]
  } else if (type === 'short_answer') {
    form.options = [{ option_text: '', is_correct: true }]
  } else {
    form.options = []
  }
}

/* ─── CRUD via axios (controller is JSON-only) ───────── */
async function submit() {
  formError.value = ''
  saving.value = true

  const payload = {
    subject_id:    form.subject_id,
    category:      null,
    type:          form.type,
    question_text: form.question_text,
    points:        form.points,
    explanation:   form.explanation || null,
    options:       form.options,
  }

  try {
    if (modal.mode === 'create') {
      await axios.post(route('teacher.questions.store'), payload)
    } else {
      await axios.put(route('teacher.questions.update', modal.editingId), payload)
    }
    modal.open = false
    resetForm()
    router.reload({ only: ['questions', 'subjects'] })
  } catch (e) {
    if (e.response?.status === 422) {
      const errs = e.response.data?.errors || {}
      const first = Object.values(errs)[0]
      formError.value = Array.isArray(first) ? first[0] : (first || 'Validation failed.')
    } else {
      formError.value = e.response?.data?.message || 'Save failed. Please try again.'
    }
  } finally {
    saving.value = false
  }
}

async function destroyConfirmed() {
  if (!confirmDelete.value) return
  deleting.value = true
  try {
    await axios.delete(route('teacher.questions.destroy', confirmDelete.value.id))
    confirmDelete.value = null
    router.reload({ only: ['questions', 'subjects'] })
  } catch (e) {
    alert(e.response?.data?.message || 'Delete failed.')
  } finally {
    deleting.value = false
  }
}

function onImported() {
  router.reload({ only: ['questions', 'subjects'] })
}

/* ─── Helpers ────────────────────────────────────────── */
function typeLabel(type) {
  return {
    multiple_choice: 'Multiple Choice',
    true_false:      'True / False',
    short_answer:    'Short Answer',
    essay:           'Essay',
  }[type] || type
}

function typeBadgeClass(type) {
  const base = 'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-md border'
  const map = {
    multiple_choice: 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-[#86EFAC] border-emerald-200/60 dark:border-emerald-900/40',
    true_false:      'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200/60 dark:border-amber-900/40',
    short_answer:    'bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border-blue-200/60 dark:border-blue-900/40',
    essay:           'bg-violet-100 dark:bg-violet-950/60 text-violet-800 dark:text-violet-300 border-violet-200/60 dark:border-violet-900/40',
  }
  return `${base} ${map[type] || 'bg-slate-100 dark:bg-[#232D26] text-slate-700 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'}`
}

const typeOptions = [
  { id: 'multiple_choice', label: 'Multiple Choice', icon: '🔘' },
  { id: 'true_false',      label: 'True / False',    icon: '💬' },
  { id: 'short_answer',    label: 'Short Answer',    icon: '✏️' },
  { id: 'essay',           label: 'Essay',           icon: '📝' },
]

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