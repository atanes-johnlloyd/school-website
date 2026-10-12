<template>
  <Head title="Create Assignment - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search assignments, tasks, rubric..">

    <div class="space-y-4 sm:space-y-6">

      <!-- HERO -->
      <div v-observe
        class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center anim-fade-down">
        <img :src="heroImage" alt="Create Assignment Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 flex flex-col justify-center space-y-3 sm:space-y-4">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <span class="bg-[#F9C20C] text-[#2C3E2D] border border-amber-300/30 text-[10px] sm:text-[11px] md:text-xs font-black uppercase tracking-wider px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="plus" size="xs" />
              New Assignment
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="book-open" size="xs" />
              {{ classroom?.subject || '—' }}
              <span v-if="classroom?.section"> • {{ classroom.section }}</span>
            </span>
          </div>

          <div class="space-y-1.5 sm:space-y-2 max-w-3xl">
            <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
              Create New Assignment
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              Define the task brief, category, and submission parameters. Publish now or save as draft.
            </p>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <BackButton fallback="teacher.tasks.index"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              Cancel
            </BackButton>
          </div>
        </div>
      </div>

      <!-- FORM -->
      <form @submit.prevent="submit"
        v-observe
        class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-5 max-w-3xl"
        style="animation-delay: 100ms;">

        <!-- Title -->
        <div class="space-y-1">
          <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Assignment Title <span class="text-rose-500">*</span>
          </label>
          <input v-model="form.title" type="text" placeholder="e.g. Chapter 1 Problem Set"
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] dark:focus:ring-[#86EFAC] transition-all" />
          <p v-if="form.errors.title" class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1">{{ form.errors.title }}</p>
        </div>

        <!-- Category -->
        <div class="space-y-1">
          <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Category <span class="text-rose-500">*</span>
          </label>
          <select v-model="form.category"
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] dark:focus:ring-[#86EFAC] appearance-none cursor-pointer transition-all">
            <option value="written_work">Written Work (WW)</option>
            <option value="performance_task">Performance Task (PT)</option>
            <option value="quarterly_exam">Quarterly Exam (QE)</option>
          </select>
          <p v-if="form.errors.category" class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1">{{ form.errors.category }}</p>
        </div>

        <!-- Instructions -->
        <div class="space-y-1">
          <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Instructions
          </label>
          <textarea v-model="form.instructions" rows="5"
            placeholder="Provide guidelines, submission rules, or task details..."
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-4 text-xs sm:text-sm font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] dark:focus:ring-[#86EFAC] transition-all"></textarea>
          <p v-if="form.errors.instructions" class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1">{{ form.errors.instructions }}</p>
        </div>

        <!-- Due date + Points -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-1">
            <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
              Due Date & Time <span class="text-rose-500">*</span>
            </label>
            <input v-model="form.due_at" type="datetime-local"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] dark:focus:ring-[#86EFAC] transition-all" />
            <p v-if="form.errors.due_at" class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1">{{ form.errors.due_at }}</p>
          </div>

          <div class="space-y-1">
            <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
              Max Points <span class="text-rose-500">*</span>
            </label>
            <input v-model="form.points" type="number" min="1"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] dark:focus:ring-[#86EFAC] transition-all" />
            <p v-if="form.errors.points" class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1">{{ form.errors.points }}</p>
          </div>
        </div>

        <!-- Toggles -->
        <div class="flex flex-wrap gap-4 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
          <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-200 cursor-pointer">
            <input type="checkbox" v-model="form.allow_late" class="rounded border-slate-300 text-[#005506] focus:ring-[#005506]" />
            Allow late submissions
          </label>
          <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-200 cursor-pointer">
            <input type="checkbox" v-model="form.is_published" class="rounded border-slate-300 text-[#005506] focus:ring-[#005506]" />
            Publish immediately
          </label>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
          <BackButton fallback="teacher.tasks.index"
              class="px-5 py-2.5 text-slate-600 hover:text-slate-800 text-xs font-bold transition-colors inline-flex items-center gap-1.5">
              Cancel
            </BackButton>
          <button type="submit" :disabled="form.processing"
            class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] px-6 py-2.5 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2">
            <Icon icon="plus" size="xs" />
            {{ form.processing ? 'Creating…' : 'Create Assignment' }}
          </button>
        </div>
      </form>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import heroImage from '../../../../assets/img/local/assignment.png'
import BackButton from '@/Components/BackButton.vue'
import { useFlash } from '@/Composables/useFlash'

const props = defineProps({
  classroom: { type: Object, default: () => ({}) },
})

const flash = useFlash()

const form = useForm({
  title: '',
  category: 'written_work',
  instructions: '',
  due_at: '',
  points: 100,
  allow_late: true,
  is_published: false,
})

function submit() {
  form.post(route('teacher.classes.assignments.store', props.classroom.id), {
    onSuccess: () => flash.success('Assignment created.'),
    onError: () => flash.error('Please fix the highlighted fields.'),
  })
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
@keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up { animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>