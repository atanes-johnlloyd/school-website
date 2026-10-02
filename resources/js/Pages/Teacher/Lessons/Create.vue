<template>
  <Head title="Create Lesson - Salawag LMS" />

  <AuthenticatedLayout searchPlaceholder="Search lessons, resources..">

    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

      <!-- HERO -->
      <div v-observe
        class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center anim-fade-down">
        <img :src="heroImage" alt="Create Lesson Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 flex flex-col justify-center space-y-3 sm:space-y-4">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <span class="bg-[#F9C20C] text-[#2C3E2D] border border-amber-300/30 text-[10px] sm:text-[11px] md:text-xs font-black uppercase tracking-wider px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="plus" size="xs" />
              New Lesson
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="academic-cap" size="xs" />
              {{ classroom?.subject || '—' }}
              <span v-if="classroom?.section"> • {{ classroom.section }}</span>
            </span>
          </div>

          <div class="space-y-1.5 sm:space-y-2 max-w-3xl">
            <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
              Create Learning Material
            </h2>
            <p class="text-white/90 text-xs sm:text-sm md:text-base leading-relaxed font-medium">
              Upload lesson content and attachments for {{ classroom?.subject || 'this class' }}.
            </p>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <BackButton fallback="teacher.resources.index"
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

        <div class="space-y-1">
          <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Lesson Title <span class="text-rose-500">*</span>
          </label>
          <input v-model="form.title" type="text" placeholder="e.g. Module 4: Electromagnetic Induction"
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] dark:focus:ring-[#86EFAC] transition-all" />
          <p v-if="form.errors.title" class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1">{{ form.errors.title }}</p>
        </div>

        <div class="space-y-1">
          <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Lesson Body <span class="text-rose-500">*</span>
          </label>
          <textarea v-model="form.body" rows="8"
            placeholder="Paste or type the lesson content here. Plain text and simple formatting supported."
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-4 text-xs sm:text-sm font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] dark:focus:ring-[#86EFAC] transition-all resize-y"></textarea>
          <p v-if="form.errors.body" class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1">{{ form.errors.body }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-1">
            <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
              Position (order)
            </label>
            <input v-model.number="form.position" type="number" min="0"
              class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#005506] dark:focus:ring-[#86EFAC] transition-all" />
            <p class="text-[10px] text-slate-400 font-medium">Lower numbers appear first.</p>
          </div>

          <div class="space-y-1">
            <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
              Publish
            </label>
            <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-200 cursor-pointer bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 w-full">
              <input type="checkbox" v-model="form.is_published" class="rounded border-slate-300 text-[#005506] focus:ring-[#005506]" />
              Publish immediately (visible to students)
            </label>
          </div>
        </div>

        <!-- Attachments -->
        <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
          <label class="block text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Attachments (optional, max 5 files)
          </label>
          <input ref="fileInput" type="file" multiple
            @change="handleFiles"
            accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.zip,.mp4,.mp3"
            class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#005506] dark:file:bg-[#86EFAC] file:text-white dark:file:text-[#232D26] hover:file:bg-[#004105] cursor-pointer" />
          <p class="text-[10px] text-slate-400 font-medium">
            PDF, DOC/DOCX, PPT/PPTX, XLS/XLSX, TXT, JPG/PNG, ZIP, MP4, MP3 • Max 10 MB each
          </p>
          <p v-if="form.errors.attachments" class="text-rose-600 dark:text-rose-400 text-xs font-semibold">{{ form.errors.attachments }}</p>

          <ul v-if="selectedFiles.length" class="space-y-1.5 pt-1">
            <li v-for="(f, i) in selectedFiles" :key="i"
              class="flex items-center justify-between gap-2 bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-3 py-2">
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200 truncate">{{ f.name }}</span>
              <button type="button" @click="removeFile(i)"
                class="text-rose-600 dark:text-rose-400 hover:text-rose-800 transition-colors shrink-0">
                <Icon icon="x" size="xs" />
              </button>
            </li>
          </ul>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 dark:border-[#3F4F43]">
          <BackButton fallback="teacher.resources.index"
            class="px-5 py-2.5 text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white text-xs font-bold transition-colors inline-flex items-center gap-1.5">
            Cancel
          </BackButton>
          <button type="submit" :disabled="form.processing"
            class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] px-6 py-2.5 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 disabled:opacity-50 flex items-center gap-2">
            <Icon icon="plus" size="xs" />
            {{ form.processing ? 'Creating…' : 'Create Lesson' }}
          </button>
        </div>
      </form>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import BackButton from '@/Components/BackButton.vue'
import heroImage from '../../../../assets/img/local/lessons-hero.jpg'

const props = defineProps({
  classroom: { type: Object, default: () => ({}) },
})

const form = useForm({
  title: '',
  body: '',
  position: 0,
  is_published: false,
  attachments: [],
})

const fileInput = ref(null)
const selectedFiles = ref([])

function handleFiles(e) {
  const incoming = Array.from(e.target.files || [])
  const merged = [...selectedFiles.value, ...incoming].slice(0, 5)
  selectedFiles.value = merged
  form.attachments = merged
  if (fileInput.value) fileInput.value.value = ''
}

function removeFile(index) {
  selectedFiles.value.splice(index, 1)
  form.attachments = selectedFiles.value
}

function submit() {
  form.post(route('teacher.classes.lessons.store', props.classroom.id), {
    forceFormData: true,
    preserveScroll: true,
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