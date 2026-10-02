<template>
  <Head :title="`${lesson.title} - Salawag LMS`" />

  <AuthenticatedLayout searchPlaceholder="Search lessons, resources..">

    <div class="relative z-10 px-3 sm:px-6 md:px-10 pb-16 sm:pb-24 space-y-4 sm:space-y-6 flex-1 mt-2">

      <!-- HERO -->
      <div v-observe
        class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none min-h-[200px] sm:min-h-[220px] flex flex-col justify-center anim-fade-down">
        <img :src="heroImage" alt="Lesson Background"
          class="absolute inset-0 w-full h-full object-cover z-0 object-center" />
        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="relative z-10 p-4 sm:p-8 md:p-10 flex flex-col justify-center space-y-3 sm:space-y-4">
          <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
            <span :class="lesson.is_published
              ? 'bg-emerald-500/90 text-white'
              : 'bg-slate-200/90 text-slate-800'"
              class="text-[10px] sm:text-[11px] md:text-xs font-black uppercase tracking-wider px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon :icon="lesson.is_published ? 'check-circle' : 'edit'" size="xs" />
              {{ lesson.is_published ? 'Published' : 'Draft' }}
            </span>
            <span class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="academic-cap" size="xs" />
              {{ classroom?.subject || '—' }}
              <span v-if="classroom?.section"> • {{ classroom.section }}</span>
            </span>
            <span v-if="attachments.length"
              class="bg-white/20 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-[11px] md:text-xs font-semibold px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
              <Icon icon="paper-clip" size="xs" />
              {{ attachments.length }} {{ attachments.length === 1 ? 'attachment' : 'attachments' }}
            </span>
          </div>

          <div class="space-y-1.5 sm:space-y-2 max-w-3xl">
            <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
              {{ lesson.title }}
            </h2>
          </div>

          <div class="flex flex-wrap gap-2 pt-2">
            <BackButton fallback="teacher.resources.index"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              Back to Resources
            </BackButton>
            <Link :href="route('teacher.lessons.edit', lesson.id)"
              class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur-md text-white border border-white/20 px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all">
              <Icon icon="edit" size="xs" />
              Edit
            </Link>
            <button @click="destroy" :disabled="deleting"
              class="inline-flex items-center gap-1.5 bg-rose-500/90 hover:bg-rose-600 text-white px-3.5 py-2 rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95 disabled:opacity-60">
              <Icon icon="trash" size="xs" />
              {{ deleting ? 'Deleting…' : 'Delete' }}
            </button>
          </div>
        </div>
      </div>

      <!-- BODY -->
      <div v-observe
        class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-4"
        style="animation-delay: 100ms;">

        <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
          <div class="w-2.5 h-7 bg-[#005506] dark:bg-[#86EFAC] rounded-full"></div>
          <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white">Lesson Content</h3>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">Full text as published to students</p>
          </div>
        </div>

        <div v-if="lesson.body" class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">
          {{ lesson.body }}
        </div>
        <div v-else class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-8 text-center border border-dashed border-slate-300 dark:border-[#3F4F43]">
          <p class="text-xs text-slate-500 dark:text-slate-400 italic">No body content.</p>
        </div>
      </div>

      <!-- ATTACHMENTS -->
      <div v-if="attachments.length"
        v-observe
        class="anim-slide-up bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-3"
        style="animation-delay: 150ms;">

        <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-[#3F4F43]">
          <div class="w-2.5 h-7 bg-amber-500 rounded-full"></div>
          <div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white">Attachments</h3>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">{{ attachments.length }} files</p>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div v-for="a in attachments" :key="a.id"
            class="bg-[#F9F7F1] dark:bg-[#232D26] p-3 rounded-xl border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 min-w-0">
              <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-[#86EFAC] flex items-center justify-center shrink-0">
                <Icon icon="paper-clip" size="sm" />
              </div>
              <div class="min-w-0">
                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">{{ a.file_name }}</p>
                <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">{{ formatBytes(a.file_size) }}</p>
              </div>
            </div>
            <a :href="route('teacher.lesson-attachments.download', a.id)"
              class="bg-[#005506] hover:bg-[#004105] text-white text-[11px] font-bold px-2.5 py-1.5 rounded-lg shrink-0">
              Download
            </a>
          </div>
        </div>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'
import BackButton from '@/Components/BackButton.vue'
import heroImage from '../../../../assets/img/local/lessons-hero.jpg'

const props = defineProps({
  classroom:   { type: Object, default: () => ({}) },
  lesson:      { type: Object, default: () => ({}) },
  attachments: { type: Array,  default: () => [] },
})

const deleting = ref(false)

function destroy() {
  if (!confirm('Delete this lesson? This cannot be undone.')) return
  deleting.value = true
  router.delete(route('teacher.lessons.destroy', props.lesson.id), {
    onFinish: () => { deleting.value = false },
  })
}

function formatBytes(bytes) {
  if (!bytes) return '—'
  const sizes = ['B', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(1024))
  return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i]
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
</style>