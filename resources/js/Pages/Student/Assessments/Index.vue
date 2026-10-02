<template>
  <Head title="Assessments - Salawag LMS" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop searchPlaceholder="Search assignments, quizzes, or due dates..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size" />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16">

        <!-- HERO -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="space-y-1 relative z-10">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
              <span class="text-white">STUDENT</span>
              <span class="animated-stroke-text">ASSESSMENTS</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              "Your journey to knowledge starts with one click."
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs animate-spin-slow">★</span>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center pt-2 relative z-10">
            <div class="lg:col-span-7 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-inner flex items-center justify-center transition-transform hover:scale-105 duration-300">
                <Icon icon="clipboard-list" size="xl" class="text-white" />
              </div>
              <div class="space-y-1 text-center sm:text-left">
                <div class="inline-block bg-white/15 backdrop-blur-sm text-emerald-100 text-[11px] font-bold px-3 py-0.5 rounded-full border border-white/10 animate-float-soft">
                  {{ active_term || 'Active Term' }}
                </div>
                <h2 class="font-bold text-lg sm:text-xl text-white tracking-tight leading-snug">
                  Academic Submissions & Rubrics
                </h2>
                <p class="text-xs text-emerald-100/70 font-medium">
                  {{ counts.all }} total deliverables across your classes
                </p>
              </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-2 gap-3">
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                <span class="text-[11px] font-medium text-emerald-100/80">Pending</span>
                <div class="text-xl font-extrabold text-amber-300 my-0.5">{{ counts.pending }}</div>
                <span class="text-[10px] text-emerald-100/70">Action needed</span>
              </div>
              <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl py-3 px-4 flex flex-col justify-between hover:-translate-y-1 hover:bg-white/15 transition-all duration-300">
                <span class="text-[11px] font-medium text-emerald-100/80">Graded</span>
                <div class="text-xl font-extrabold text-white my-0.5">{{ counts.graded }}</div>
                <span class="text-[10px] text-emerald-100/70">Results released</span>
              </div>
            </div>
          </div>
        </div>

        <!-- WORKSPACE -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

          <!-- FILTER & SEARCH -->
          <div class="space-y-3 pb-4 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="flex flex-wrap items-center justify-between gap-3">
              <div class="flex items-center gap-1.5 bg-[#f5f7f2] dark:bg-[#232D26] p-1.5 rounded-2xl border border-slate-200/80 dark:border-[#3F4F43] overflow-x-auto w-full sm:w-auto">
                <button v-for="tab in filterTabs" :key="tab.id" @click="selectedTab = tab.id"
                  :class="[
                    'px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all shrink-0 flex items-center gap-1.5 cursor-pointer active:scale-95',
                    selectedTab === tab.id
                      ? 'bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] shadow-xs'
                      : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'
                  ]">
                  <span>{{ tab.label }}</span>
                  <span :class="[
                    'text-[10px] px-1.5 py-0.2 rounded-full font-black transition-colors',
                    selectedTab === tab.id ? 'bg-white/20 text-white dark:text-[#232D26]' : 'bg-slate-200/80 dark:bg-[#3F4F43] text-slate-700 dark:text-slate-300'
                  ]">{{ tab.count }}</span>
                </button>
              </div>

              <div class="relative w-full sm:w-72">
                <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 dark:text-slate-500">
                  <Icon icon="search" size="sm" />
                </span>
                <input v-model="searchQuery" type="text" placeholder="Search tasks or rubrics..."
                  class="w-full bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl pl-8 pr-8 py-2 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] transition-all" />
                <button v-if="searchQuery" @click="searchQuery = ''"
                  class="absolute inset-y-0 right-2.5 flex items-center text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 text-xs font-bold">
                  ✕
                </button>
              </div>
            </div>
          </div>

          <!-- DUAL COLUMN: LIST + DRAWER -->
          <div class="grid-drawer-wrapper items-start" :class="{ 'drawer-active': isDrawerOpen }">

            <!-- LEFT: TASK LIST -->
            <div class="min-w-0 space-y-8">

              <!-- PRIORITY QUEUE -->
              <div v-if="filteredPriorityTasks.length > 0" class="space-y-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base sm:text-lg">Urgency Priority Queue</h3>
                  </div>
                  <span class="text-xs font-bold text-amber-800 dark:text-amber-300 bg-amber-100/80 dark:bg-amber-950/60 px-2.5 py-1 rounded-full border border-amber-200/80 dark:border-amber-500/30">
                    {{ filteredPriorityTasks.length }} Actionable
                  </span>
                </div>

                <div class="grid grid-cols-1 gap-4">
                  <div v-for="task in filteredPriorityTasks" :key="task.id"
                    :class="[
                      'bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-5 border transition-all duration-300 space-y-3 relative overflow-hidden hover:-translate-y-1 hover:shadow-md',
                      activeDrawerTask?.id === task.id
                        ? 'border-amber-400 shadow-md ring-2 ring-amber-300/50'
                        : 'border-slate-200/80 dark:border-[#3F4F43] hover:border-slate-300'
                    ]">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                      <span class="text-[11px] font-extrabold uppercase text-[#004d08] dark:text-[#86EFAC] bg-white dark:bg-[#2D3A31] px-2.5 py-0.5 rounded-md border border-slate-200/60 dark:border-[#3F4F43]">
                        {{ categoryLabel(task.category) }} • {{ task.subject }}
                      </span>
                      <span class="bg-rose-500 text-white text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full shadow-xs flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                        {{ urgencyLabel(task) }}
                      </span>
                    </div>

                    <div class="space-y-1">
                      <h4 class="font-black text-slate-900 dark:text-white text-sm sm:text-base leading-snug">{{ task.title }}</h4>
                      <p class="text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed">{{ task.description }}</p>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-200/60 dark:border-[#3F4F43]">
                      <div class="flex items-center gap-3 text-xs font-bold text-slate-600 dark:text-slate-300 flex-wrap">
                        <span class="inline-flex items-center gap-1">
                          <Icon icon="award" size="xs" class="text-amber-600" />
                          {{ task.points }} Points
                        </span>
                        <span v-if="task.type === 'quiz'" class="inline-flex items-center gap-1 text-slate-500">
                          <Icon icon="clock" size="xs" />
                          {{ task.time_limit_minutes }} min
                        </span>
                        <span v-else-if="task.status === 'submitted'" class="text-emerald-700 dark:text-emerald-400 inline-flex items-center gap-1">
                          <Icon icon="check-circle" size="xs" /> Submitted
                        </span>
                      </div>

                      <button @click="openDrawer(task)"
                        class="bg-amber-400 hover:bg-amber-500 text-slate-900 text-xs font-black px-4 py-2 rounded-xl transition-all shadow-xs flex items-center gap-1.5 cursor-pointer transform hover:-translate-y-0.5 active:scale-95">
                        <Icon icon="arrow-right" size="xs" />
                        Open Drawer
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- GENERAL PIPELINE -->
              <div v-if="filteredGeneralTasks.length > 0" class="space-y-4">
                <h3 class="font-extrabold text-slate-900 dark:text-white text-base sm:text-lg border-b border-slate-200/60 dark:border-[#3F4F43] pb-2">
                  Classwork Pipeline
                </h3>

                <div class="grid grid-cols-1 gap-3">
                  <div v-for="task in filteredGeneralTasks" :key="task.id"
                    :class="[
                      'bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 border transition-all duration-300 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:-translate-x-1 hover:shadow-md',
                      activeDrawerTask?.id === task.id
                        ? 'border-[#004d08] dark:border-[#86EFAC] bg-emerald-50/50 dark:bg-emerald-950/20'
                        : 'border-slate-200/80 dark:border-[#3F4F43] hover:border-slate-300'
                    ]">
                    <div class="space-y-1 flex-1 min-w-0">
                      <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-extrabold uppercase text-[#004d08] dark:text-[#86EFAC] bg-white dark:bg-[#2D3A31] px-2 py-0.5 rounded border border-slate-200 dark:border-[#3F4F43]">
                          {{ categoryLabel(task.category) }}
                        </span>
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 truncate">
                          {{ task.subject }} — {{ task.instructor }}
                        </span>
                      </div>
                      <h4 class="font-bold text-slate-900 dark:text-white text-sm truncate">{{ task.title }}</h4>
                      <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1">{{ task.description }}</p>
                    </div>

                    <div class="flex sm:flex-col items-center sm:items-end justify-between gap-2 shrink-0 border-t sm:border-t-0 border-slate-200/60 dark:border-[#3F4F43] pt-2 sm:pt-0">
                      <span class="text-xs font-bold text-slate-600 dark:text-slate-300">
                        {{ task.due_at ? formatShort(task.due_at) : 'No due date' }}
                      </span>
                      <button @click="openDrawer(task)"
                        class="bg-white dark:bg-[#2D3A31] hover:bg-[#004d08] dark:hover:bg-[#86EFAC] text-[#004d08] dark:text-[#86EFAC] hover:text-white dark:hover:text-[#232D26] border border-[#004d08] dark:border-[#86EFAC] text-xs font-bold px-3 py-1.5 rounded-xl transition-all cursor-pointer active:scale-95">
                        View & Submit
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- EMPTY -->
              <div v-if="filteredTasks.length === 0"
                class="text-center py-12 space-y-3 bg-[#f5f7f2] dark:bg-[#232D26] rounded-3xl border border-dashed border-slate-300 dark:border-[#3F4F43]">
                <div class="flex justify-center text-slate-400">
                  <Icon icon="search" size="xl" />
                </div>
                <div class="text-sm font-extrabold text-slate-800 dark:text-slate-200">No assessments found</div>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                  Nothing matches your current filter. Try clearing search or check back later.
                </p>
                <button @click="resetFilters"
                  class="bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold px-4 py-2 rounded-xl hover:bg-[#003805] transition-colors cursor-pointer active:scale-95">
                  Reset Filters
                </button>
              </div>

            </div>

            <!-- RIGHT: DRAWER -->
            <div class="drawer-column min-w-0">
              <div v-if="activeDrawerTask"
                class="bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/90 dark:border-[#3F4F43] rounded-3xl p-5 sm:p-6 shadow-xl space-y-5 sticky top-6 drawer-inner-content">

                <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-[#3F4F43]">
                  <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-pulse"></span>
                    <span class="text-xs font-black uppercase text-[#004d08] dark:text-[#86EFAC] tracking-wider">
                      {{ activeDrawerTask.type === 'quiz' ? 'QUIZ DETAILS' : 'SUBMISSION' }}
                    </span>
                  </div>
                  <button @click="closeDrawer"
                    class="w-7 h-7 rounded-full bg-white dark:bg-[#2D3A31] hover:bg-slate-200 dark:hover:bg-[#3F4F43] text-slate-600 dark:text-slate-200 flex items-center justify-center font-bold transition-all cursor-pointer hover:scale-105 active:scale-95">
                    <Icon icon="x" size="xs" />
                  </button>
                </div>

                <div class="flex items-start justify-between gap-3">
                  <div class="min-w-0">
                    <h3 class="font-black text-slate-900 dark:text-white text-lg leading-snug">{{ activeDrawerTask.subject }}</h3>
                    <p class="text-xs font-bold text-slate-600 dark:text-slate-300 mt-0.5">{{ activeDrawerTask.title }}</p>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-1">
                      {{ activeDrawerTask.instructor }}
                    </p>
                  </div>
                  <div class="bg-amber-100 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 px-3 py-1 rounded-xl text-center shrink-0 border border-amber-200/80 dark:border-amber-500/30">
                    <span class="text-xs font-extrabold block">{{ activeDrawerTask.points }} Pts</span>
                    <span class="text-[9px] font-bold uppercase text-amber-700 dark:text-amber-400">Total</span>
                  </div>
                </div>

                <!-- Category breakdown -->
                <div class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] space-y-2.5 shadow-2xs">
                  <div class="flex items-center justify-between text-xs font-extrabold text-slate-700 dark:text-slate-200 border-b border-slate-100 dark:border-[#3F4F43] pb-1.5">
                    <span>ASSESSMENT INFO</span>
                    <span class="text-[#004d08] dark:text-[#86EFAC]">{{ categoryLabel(activeDrawerTask.category) }}</span>
                  </div>
                  <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between items-center text-slate-700 dark:text-slate-300 font-semibold">
                      <span>Due</span>
                      <span class="font-bold text-slate-900 dark:text-white">{{ formatDate(activeDrawerTask.due_at) }}</span>
                    </div>
                    <div v-if="activeDrawerTask.type === 'quiz' && activeDrawerTask.time_limit_minutes" class="flex justify-between items-center text-slate-700 dark:text-slate-300 font-semibold">
                      <span>Time Limit</span>
                      <span class="font-bold text-slate-900 dark:text-white">{{ activeDrawerTask.time_limit_minutes }} minutes</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-700 dark:text-slate-300 font-semibold">
                      <span>Status</span>
                      <span :class="statusColor(activeDrawerTask.status)" class="font-bold capitalize">{{ activeDrawerTask.status }}</span>
                    </div>
                  </div>
                </div>

                <!-- Graded result -->
                <div v-if="activeDrawerTask.status === 'graded' && activeDrawerTask.submission"
                  class="bg-emerald-50/90 dark:bg-emerald-950/30 border-2 border-[#005506]/30 dark:border-[#86EFAC]/30 rounded-2xl p-4 space-y-2">
                  <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-wider text-[#005506] dark:text-[#86EFAC]">Graded</span>
                    <span class="text-2xl font-black text-[#005506] dark:text-[#86EFAC]">
                      {{ activeDrawerTask.submission.grade }}
                      <span class="text-sm font-normal text-slate-500 dark:text-slate-400">/ {{ activeDrawerTask.points }}</span>
                    </span>
                  </div>
                  <p v-if="activeDrawerTask.submission.feedback" class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed pt-2 border-t border-emerald-200/60 dark:border-emerald-900/40">
                    <span class="font-bold">Feedback:</span> {{ activeDrawerTask.submission.feedback }}
                  </p>
                </div>

                <!-- Quiz CTA -->
                <div v-if="activeDrawerTask.type === 'quiz'" class="space-y-2">
                  <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                    This quiz opens in a secure proctored window. Click below to begin.
                  </p>
                  <Link :href="activeDrawerTask.show_url"
                    class="w-full bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-xs font-black py-3.5 rounded-2xl transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                    <Icon icon="arrow-right" size="sm" />
                    {{ activeDrawerTask.submission ? 'View Attempt' : 'Open Quiz' }}
                  </Link>
                </div>

                <!-- Assignment submission form -->
                <form v-else-if="activeDrawerTask.status !== 'graded'" @submit.prevent="submitWork" class="space-y-4">

                  <div v-if="activeDrawerTask.submission?.has_file"
                    class="bg-white dark:bg-[#2D3A31] rounded-2xl p-3 border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                      <Icon icon="paper-clip" size="md" class="text-emerald-600 dark:text-[#86EFAC] shrink-0" />
                      <div class="min-w-0">
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block truncate">Existing file</span>
                        <span class="text-[10px] text-emerald-700 dark:text-[#86EFAC] font-semibold block">Ready for review</span>
                      </div>
                    </div>
                    <a :href="activeDrawerTask.submission.download_url" target="_blank"
                      class="bg-[#004d08] dark:bg-[#86EFAC] text-white dark:text-[#232D26] text-xs font-bold px-3 py-1.5 rounded-lg shrink-0">
                      Download
                    </a>
                  </div>

                  <div class="space-y-1.5">
                    <label class="text-[10px] font-extrabold uppercase text-slate-500 dark:text-slate-400 tracking-wider block">
                      TEXT RESPONSE
                    </label>
                    <textarea v-model="form.text_content" rows="4"
                      placeholder="Add your response, notes, or a link to your work..."
                      class="w-full bg-white dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl p-3 text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC] transition-all"></textarea>
                    <p v-if="form.errors.text_content" class="text-rose-600 dark:text-rose-400 text-xs font-semibold">
                      {{ form.errors.text_content }}
                    </p>
                  </div>

                  <div class="space-y-1.5">
                    <label class="text-[10px] font-extrabold uppercase text-slate-500 dark:text-slate-400 tracking-wider block">
                      ATTACH FILE (OPTIONAL)
                    </label>
                    <input ref="fileInput" type="file" @change="handleFile"
                      class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#004d08] dark:file:bg-[#86EFAC] file:text-white dark:file:text-[#232D26] hover:file:bg-[#003805] cursor-pointer" />
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">
                      PDF, DOC, DOCX, XLSX, PPTX, ZIP, images • Max 10 MB
                    </p>
                    <p v-if="form.errors.file" class="text-rose-600 dark:text-rose-400 text-xs font-semibold">
                      {{ form.errors.file }}
                    </p>
                  </div>

                  <button type="submit" :disabled="form.processing"
                    class="w-full bg-amber-400 hover:bg-amber-500 text-slate-900 text-xs sm:text-sm font-black py-3.5 rounded-2xl transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer transform hover:-translate-y-0.5 active:scale-95 disabled:opacity-50">
                    <Icon icon="send" size="sm" />
                    {{ form.processing ? 'Submitting...' : (activeDrawerTask.submission ? 'Resubmit Assignment' : 'Turn In Assignment') }}
                  </button>

                  <div class="flex items-center justify-between text-[10px] text-slate-400 dark:text-slate-500 font-semibold pt-1">
                    <span>Encrypted submission</span>
                    <span>Task ID: {{ activeDrawerTask.id }}</span>
                  </div>
                </form>

                <!-- Graded assignment: locked -->
                <div v-else class="bg-white dark:bg-[#2D3A31] rounded-2xl p-4 border border-slate-200/80 dark:border-[#3F4F43] text-center space-y-1">
                  <Icon icon="lock-closed" size="lg" class="text-slate-400 mx-auto" />
                  <p class="text-xs font-bold text-slate-700 dark:text-slate-300">Submission locked after grading</p>
                </div>

              </div>
            </div>

          </div>

        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  assessments: { type: Array,  default: () => [] },
  counts:      { type: Object, default: () => ({ all: 0, pending: 0, in_progress: 0, graded: 0, overdue: 0 }) },
  active_term: { type: String, default: null },
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')
const selectedTab = ref('all')
const searchQuery = ref('')
const isDrawerOpen = ref(false)
const activeDrawerTask = ref(null)
const fileInput = ref(null)

const form = useForm({ text_content: '', file: null })

const allTasks = computed(() => props.assessments ?? [])

const filterTabs = computed(() => [
  { id: 'all',         label: 'All',           count: props.counts.all },
  { id: 'pending',     label: 'Pending',       count: props.counts.pending },
  { id: 'in_progress', label: 'Submitted',     count: props.counts.in_progress },
  { id: 'graded',      label: 'Graded',        count: props.counts.graded },
  { id: 'overdue',     label: 'Overdue',       count: props.counts.overdue },
])

const filteredTasks = computed(() => {
  return allTasks.value.filter(task => {
    const matchesTab = selectedTab.value === 'all' || task.status === selectedTab.value
    const q = searchQuery.value.toLowerCase().trim()
    const matchesSearch = !q ||
      (task.title || '').toLowerCase().includes(q) ||
      (task.description || '').toLowerCase().includes(q) ||
      (task.subject || '').toLowerCase().includes(q)
    return matchesTab && matchesSearch
  })
})

const filteredPriorityTasks = computed(() =>
  filteredTasks.value.filter(t =>
    t.status === 'pending' && t.days_left !== null && t.days_left <= 2
  )
)

const filteredGeneralTasks = computed(() =>
  filteredTasks.value.filter(t => !filteredPriorityTasks.value.some(p => p.id === t.id))
)

function openDrawer(task) {
  activeDrawerTask.value = task
  isDrawerOpen.value = true
  form.text_content = task.submission?.text_content ?? ''
  form.file = null
  form.clearErrors()
}

function closeDrawer() {
  isDrawerOpen.value = false
  setTimeout(() => {
    activeDrawerTask.value = null
    form.reset()
    form.clearErrors()
  }, 400)
}

function handleFile(e) {
  form.file = e.target.files[0] ?? null
}

function submitWork() {
  if (!activeDrawerTask.value || activeDrawerTask.value.type !== 'assignment') return
  form.post(route('student.assignments.submit', activeDrawerTask.value.raw_id), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      closeDrawer()
    },
  })
}

function resetFilters() {
  selectedTab.value = 'all'
  searchQuery.value = ''
}

function categoryLabel(cat) {
  return {
    written_work:     'Written Work',
    performance_task: 'Performance Task',
    quarterly_exam:   'Quarterly Exam',
  }[cat] || 'General'
}

function urgencyLabel(task) {
  if (task.days_left === null) return 'PENDING'
  if (task.days_left <= 0) return 'DUE TODAY'
  if (task.days_left === 1) return 'DUE TOMORROW'
  return `${task.days_left} DAYS LEFT`
}

function statusColor(status) {
  return {
    pending:     'text-amber-700 dark:text-amber-400',
    submitted:   'text-blue-700 dark:text-blue-400',
    late:        'text-rose-700 dark:text-rose-400',
    in_progress: 'text-blue-700 dark:text-blue-400',
    graded:      'text-[#004d08] dark:text-[#86EFAC]',
    overdue:     'text-rose-700 dark:text-rose-400',
  }[status] || 'text-slate-500'
}

function formatDate(value) {
  if (!value) return '—'
  try {
    return new Date(value).toLocaleString('en-US', {
      month: 'short', day: 'numeric', year: 'numeric',
      hour: 'numeric', minute: '2-digit', hour12: true,
    })
  } catch { return value }
}

function formatShort(value) {
  if (!value) return '—'
  try {
    return new Date(value).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
  } catch { return value }
}
</script>

<style scoped>
.animated-stroke-text { color: transparent; -webkit-text-stroke: 1.5px #ffffff; }

@keyframes fadeInDown  { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px);  } to { opacity: 1; transform: translateY(0); } }
@keyframes floatSoft   { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
@keyframes sheenMove   { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
@keyframes spinSlow    { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

.animate-fade-in-down  { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
.animate-float-soft    { animation: floatSoft 3s ease-in-out infinite; }
.animate-sheen         { animation: sheenMove 4s ease-in-out infinite; }
.animate-spin-slow     { display: inline-block; animation: spinSlow 12s linear infinite; }

.grid-drawer-wrapper {
  display: grid;
  grid-template-columns: 1fr 0fr;
  gap: 0px;
  transition: grid-template-columns 0.4s cubic-bezier(0.4, 0, 0.2, 1), gap 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: grid-template-columns, gap;
}
@media (min-width: 1024px) {
  .grid-drawer-wrapper.drawer-active {
    grid-template-columns: 7fr 5fr;
    gap: 24px;
    transition: grid-template-columns 0.8s cubic-bezier(0.16, 1, 0.3, 1), gap 0.8s cubic-bezier(0.16, 1, 0.3, 1);
  }
}
.drawer-column { overflow: hidden; }
.drawer-inner-content {
  opacity: 0;
  transform: translateX(32px) scale(0.97);
  transition: opacity 0.25s ease-out, transform 0.25s ease-out;
  will-change: opacity, transform;
}
.drawer-active .drawer-inner-content {
  opacity: 1;
  transform: translateX(0) scale(1);
  transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.25s, transform 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.25s;
}

.text-scale-sm :deep(.text-xs)   { font-size: 0.65rem !important; line-height: 0.85rem !important; }
.text-scale-sm :deep(.text-sm)   { font-size: 0.75rem !important; line-height: 1rem !important; }
.text-scale-sm :deep(.text-base) { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-xs)   { font-size: 0.875rem !important; line-height: 1.25rem !important; }
.text-scale-lg :deep(.text-sm)   { font-size: 1rem !important; line-height: 1.5rem !important; }
.text-scale-lg :deep(.text-base) { font-size: 1.125rem !important; line-height: 1.75rem !important; }
</style>