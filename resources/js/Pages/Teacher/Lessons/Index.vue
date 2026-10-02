<template>
  <Head title="DepEd LR Resources & Lesson Log Hub - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search lessons, DLL logs, module decks..">

    <div class="relative z-10 px-4 sm:px-6 md:px-10 pb-24 space-y-6 flex-1 mt-4">

      <!-- HERO -->
      <div v-observe
        class="anim-fade-down relative w-full rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[240px] flex flex-col justify-center p-6 sm:p-8 md:p-10">
        <img :src="heroImage" alt="Lessons Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05] dark:bg-[#152B1C] animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 max-w-3xl space-y-4">
          <div class="flex flex-wrap items-center gap-2.5">
            <div class="inline-flex items-center gap-2 bg-[#F9C20C] text-[#2C3E2D] font-black text-xs px-4 py-1.5 rounded-full shadow-sm tracking-wide">
              <Icon icon="book-open" size="xs" />
              LESSONS & LEARNING RESOURCES
            </div>
            <div class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-xs font-bold px-4 py-1.5 rounded-full shadow-sm">
              <Icon icon="clipboard-list" size="xs" />
              {{ stats.total }} {{ stats.total === 1 ? 'Resource' : 'Resources' }}
            </div>
            <div v-if="stats.draft > 0"
              class="inline-flex items-center gap-2 bg-amber-500/90 text-white border border-amber-300/30 text-xs font-black px-4 py-1.5 rounded-full shadow-sm">
              <Icon icon="edit" size="xs" />
              {{ stats.draft }} Draft{{ stats.draft === 1 ? '' : 's' }}
            </div>
          </div>

          <div class="space-y-2">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-white tracking-tight leading-tight">
              Section Files & Learning Resource Hub
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium max-w-2xl">
              Upload, organize, and distribute handouts, slide decks, lab manuals, and lesson materials across all your enrolled sections.
            </p>
          </div>

          <!-- New Lesson picker -->
          <div class="flex flex-wrap gap-2 pt-2">
            <div class="relative">
              <button ref="newLessonBtn" @click="toggleClassPicker"
                  class="inline-flex items-center gap-1.5 bg-[#F9C20C] hover:bg-amber-400 text-[#2C3E2D] px-3.5 py-2 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95">
                  <Icon icon="plus" size="xs" />
                  New Lesson
                  <Icon icon="chevron-down" size="xs" />
                </button>
              <div v-if="showClassPicker"
                class="absolute left-0 top-full mt-2 w-72 bg-white dark:bg-[#2D3A31] rounded-2xl shadow-xl border border-slate-200 dark:border-[#3F4F43] overflow-hidden z-40">
                <div class="px-4 py-2.5 border-b border-slate-100 dark:border-[#3F4F43] bg-slate-50 dark:bg-[#232D26]">
                  <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Pick a Class</span>
                </div>
                <Link v-for="c in classrooms" :key="c.id"
                  :href="route('teacher.classes.lessons.create', c.id)"
                  @click="showClassPicker = false"
                  class="block px-4 py-3 border-b border-slate-100 dark:border-[#3F4F43] last:border-b-0 hover:bg-slate-50 dark:hover:bg-[#3F4F43] transition-colors">
                  <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">{{ c.subject }}</p>
                  <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">{{ c.section }} • {{ c.subject_code }}</p>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- STATS -->
      <div v-observe class="anim-slide-up grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5" style="animation-delay: 100ms;">
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Lessons</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-900/40">
              <Icon icon="book-open" size="sm" />
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
          <p class="text-[11px] font-medium text-slate-500">Visible to students</p>
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

        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] flex flex-col justify-between space-y-3">
          <div class="flex items-start justify-between">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Attachments</span>
            <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-900/40">
              <Icon icon="paper-clip" size="sm" />
            </div>
          </div>
          <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ stats.attachments }}</div>
          <p class="text-[11px] font-medium text-slate-500">Files uploaded</p>
        </div>
      </div>

      <!-- FILTER BAR -->
      <div v-observe
        class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-3.5 sm:p-4 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3"
        style="animation-delay: 150ms;">

        <div class="flex flex-col lg:flex-row lg:items-center gap-3">
          <!-- Class picker -->
          <div class="relative w-full lg:w-72 shrink-0">
            <select v-model="classFilter"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] text-slate-800 dark:text-slate-100 text-xs font-extrabold px-3.5 py-2.5 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] appearance-none cursor-pointer pr-8 truncate">
              <option value="all">All Classes ({{ lessons.length }})</option>
              <option v-for="c in classrooms" :key="c.id" :value="c.id">
                {{ c.subject }} — {{ c.section }} ({{ c.lessons_count }})
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

          <!-- Search -->
          <div class="relative flex-1 min-w-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
              <Icon icon="search" size="sm" />
            </span>
            <input v-model="search" type="text" placeholder="Search lesson title or content..."
              class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] focus:outline-none focus:ring-2 focus:ring-[#005506] text-xs font-semibold text-slate-700 dark:text-slate-200" />
          </div>
        </div>
      </div>

      <!-- TWO-COLUMN -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-start">

        <!-- LEFT: LESSON LIST -->
        <div class="lg:col-span-8 space-y-5 w-full min-w-0">

          <div v-if="filteredLessons.length" class="space-y-4">
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
                  <span>{{ group.lessons.length }} {{ group.lessons.length === 1 ? 'Lesson' : 'Lessons' }}</span>
                </div>
              </div>

              <!-- Lessons in this class -->
              <div class="space-y-3">
                <div v-for="lesson in group.lessons" :key="lesson.id"
                  :class="[
                    'bg-[#F9F7F1] dark:bg-[#232D26] p-3.5 sm:p-4 rounded-xl sm:rounded-2xl border flex flex-col sm:flex-row sm:items-center justify-between gap-3 transition-all',
                    drawerLesson?.id === lesson.id
                      ? 'border-amber-400 dark:border-amber-500 ring-2 ring-amber-300/50'
                      : 'border-slate-200/80 dark:border-[#3F4F43] hover:border-slate-300 dark:hover:border-[#4a5c50]'
                  ]">

                  <div class="flex items-start gap-3 min-w-0 flex-1">
                    <div :class="lesson.is_published
                      ? 'bg-emerald-50 dark:bg-emerald-950 text-[#005506] dark:text-[#86EFAC] border-emerald-100 dark:border-emerald-900/40'
                      : 'bg-slate-100 dark:bg-[#2D3A31] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-[#3F4F43]'"
                      class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 border font-bold">
                      <Icon :icon="lesson.is_published ? 'book-open' : 'edit'" size="sm" />
                    </div>
                    <div class="space-y-0.5 min-w-0 flex-1">
                      <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white truncate">
                          {{ lesson.title }}
                        </h4>
                        <span :class="lesson.is_published
                          ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC]'
                          : 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400'"
                          class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full shrink-0">
                          {{ lesson.is_published ? 'Published' : 'Draft' }}
                        </span>
                      </div>
                      <p class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 font-medium line-clamp-2">
                        {{ lesson.body_preview || 'No preview available.' }}
                      </p>
                      <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold text-slate-500 pt-0.5">
                        <span v-if="lesson.attachments_count > 0" class="text-emerald-700 dark:text-emerald-400 inline-flex items-center gap-1">
                          <Icon icon="paper-clip" size="xs" />
                          {{ lesson.attachments_count }} {{ lesson.attachments_count === 1 ? 'file' : 'files' }}
                        </span>
                        <span>•</span>
                        <span>{{ formatShort(lesson.created_at) }}</span>
                      </div>
                    </div>
                  </div>

                  <div class="flex items-center gap-1.5 shrink-0">
                    <Link :href="route('teacher.lessons.edit', lesson.id)"
                      class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#3F4F43] transition-colors"
                      title="Edit lesson">
                      <Icon icon="edit" size="xs" />
                    </Link>
                    <Link :href="route('teacher.lessons.show', lesson.id)"
                      class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#3F4F43] transition-colors"
                      title="View lesson">
                      <Icon icon="eye" size="xs" />
                    </Link>
                    <button @click="destroy(lesson)"
                      class="w-8 h-8 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors"
                      title="Delete lesson">
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
            <Icon icon="book-open" size="xl" class="text-slate-400 mx-auto" />
            <p class="text-sm font-bold text-slate-800 dark:text-white">
              {{ lessons.length === 0 ? 'No lessons yet' : 'No lessons match your filters' }}
            </p>
            <p class="text-xs text-slate-500 dark:text-slate-400">
              {{ lessons.length === 0 ? 'Upload your first learning material to get started.' : 'Try clearing the filters.' }}
            </p>
          </div>
        </div>

        <!-- RIGHT: CLASSES PANEL -->
        <div class="lg:col-span-4 space-y-5 sm:space-y-6 w-full min-w-0">

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
                  {{ c.lessons_count }} {{ c.lessons_count === 1 ? 'lesson' : 'lessons' }}
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
              <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">Resource Tips</h3>
            </div>
            <ul class="space-y-2 text-[11px] text-slate-600 dark:text-slate-300 font-medium">
              <li class="flex items-start gap-2">
                <Icon icon="check-circle" size="xs" class="text-[#005506] dark:text-[#86EFAC] mt-0.5 shrink-0" />
                <span>Attach PDFs, slides, and images up to 10 MB per file.</span>
              </li>
              <li class="flex items-start gap-2">
                <Icon icon="check-circle" size="xs" class="text-[#005506] dark:text-[#86EFAC] mt-0.5 shrink-0" />
                <span>Save as draft to prepare materials before publishing.</span>
              </li>
              <li class="flex items-start gap-2">
                <Icon icon="check-circle" size="xs" class="text-[#005506] dark:text-[#86EFAC] mt-0.5 shrink-0" />
                <span>Students only see lessons after you publish them.</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>
    <!-- Class picker (teleported so it isn't clipped by hero overflow-hidden) -->
<Teleport to="body">
  <div v-if="showClassPicker"
    class="fixed z-[100] w-72 bg-white dark:bg-[#2D3A31] rounded-2xl shadow-xl border border-slate-200 dark:border-[#3F4F43] overflow-hidden"
    :style="{ top: pickerPos.top + 'px', left: pickerPos.left + 'px' }">
    <div class="px-4 py-2.5 border-b border-slate-100 dark:border-[#3F4F43] bg-slate-50 dark:bg-[#232D26]">
      <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Pick a Class</span>
    </div>
    <div class="max-h-72 overflow-y-auto">
      <Link v-for="c in classrooms" :key="c.id"
        :href="route('teacher.classes.lessons.create', c.id)"
        @click="showClassPicker = false"
        class="block px-4 py-3 border-b border-slate-100 dark:border-[#3F4F43] last:border-b-0 hover:bg-slate-50 dark:hover:bg-[#3F4F43] transition-colors">
        <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">{{ c.subject }}</p>
        <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">{{ c.section }} • {{ c.subject_code }}</p>
      </Link>
      <div v-if="!classrooms.length" class="px-4 py-6 text-center text-[11px] text-slate-400 italic">
        No classes assigned.
      </div>
    </div>
  </div>

  <!-- Backdrop to close on outside click -->
  <div v-if="showClassPicker" @click="showClassPicker = false"
    class="fixed inset-0 z-[99] bg-transparent"></div>
</Teleport>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, reactive, onMounted, onBeforeUnmount } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/lessons-hero.jpg'

const newLessonBtn = ref(null)
const pickerPos = reactive({ top: 0, left: 0 })

function toggleClassPicker() {
  if (showClassPicker.value) {
    showClassPicker.value = false
    return
  }
  const rect = newLessonBtn.value?.getBoundingClientRect()
  if (rect) {
    pickerPos.top = rect.bottom + 8
    pickerPos.left = rect.left
    // Guard against right-edge overflow
    const maxLeft = window.innerWidth - 288 - 16  // 288 = w-72
    if (pickerPos.left > maxLeft) pickerPos.left = maxLeft
  }
  showClassPicker.value = true
}

function onWindowChange() {
  if (!showClassPicker.value) return
  const rect = newLessonBtn.value?.getBoundingClientRect()
  if (rect) {
    pickerPos.top = rect.bottom + 8
    pickerPos.left = Math.min(rect.left, window.innerWidth - 288 - 16)
  }
}

onMounted(() => {
  window.addEventListener('resize', onWindowChange)
  window.addEventListener('scroll', onWindowChange, true)
})
onBeforeUnmount(() => {
  window.removeEventListener('resize', onWindowChange)
  window.removeEventListener('scroll', onWindowChange, true)
})

function destroy(lesson) {
  if (!confirm(`Delete "${lesson.title}"? This cannot be undone.`)) return
  router.delete(route('teacher.lessons.destroy', lesson.id), {
    preserveScroll: true,
  })
}
const props = defineProps({
  lessons:     { type: Array,  default: () => [] },
  classrooms:  { type: Array,  default: () => [] },
  stats:       { type: Object, default: () => ({ total: 0, published: 0, draft: 0, attachments: 0 }) },
  active_term: { type: String, default: null },
})

/* ─── Filters ─────────────────────────────────────────── */
const classFilter  = ref('all')
const statusFilter = ref('all')
const search       = ref('')

const statusFilters = computed(() => [
  { id: 'all',       label: 'All',       count: props.lessons.length },
  { id: 'published', label: 'Published', count: props.lessons.filter(l => l.is_published).length },
  { id: 'draft',     label: 'Drafts',    count: props.lessons.filter(l => !l.is_published).length },
].filter(f => f.id === 'all' || f.count > 0))

const filteredLessons = computed(() => {
  const q = search.value.trim().toLowerCase()
  return props.lessons.filter(l => {
    if (classFilter.value !== 'all' && l.class_id !== classFilter.value) return false
    if (statusFilter.value === 'published' && !l.is_published) return false
    if (statusFilter.value === 'draft' && l.is_published) return false
    if (q && !(
      (l.title || '').toLowerCase().includes(q) ||
      (l.body_preview || '').toLowerCase().includes(q)
    )) return false
    return true
  })
})

/* Group filtered lessons by class */
const groupedByClass = computed(() => {
  const groups = new Map()
  filteredLessons.value.forEach(l => {
    if (!groups.has(l.class_id)) {
      groups.set(l.class_id, {
        class_id: l.class_id,
        subject: l.subject,
        subject_code: l.subject_code,
        section: l.section,
        lessons: [],
      })
    }
    groups.get(l.class_id).lessons.push(l)
  })
  return [...groups.values()]
})

/* ─── New lesson picker ───────────────────────────────── */
const showClassPicker = ref(false)

/* ─── Drawer (unused but kept for parity) ─────────────── */
const drawerLesson = ref(null)

/* ─── Helpers ─────────────────────────────────────────── */
function formatShort(v) {
  if (!v) return '—'
  try {
    return new Date(v).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
  } catch { return '—' }
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
</script>

<style scoped>
@keyframes pulse-opacity { 0%, 100% { opacity: 0.88; } 50% { opacity: 0.65; } }
.animate-overlay { animation: pulse-opacity 6s infinite ease-in-out; }

@keyframes slideUpFade { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }

.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up { animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
</style>