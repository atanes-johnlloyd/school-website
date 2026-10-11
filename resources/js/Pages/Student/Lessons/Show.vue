<template>
  <Head :title="`${lesson.title} - Salawag LMS`" />

  <StudentLayout>
    <div class="space-y-6 pb-10 font-['Inter'] min-w-0 max-w-5xl mx-auto w-full">

      <!-- HERO -->
      <div v-observe
        class="animate-fade-in-down relative overflow-hidden w-full rounded-2xl sm:rounded-3xl shadow-lg border border-[#006907]/20 dark:border-none min-h-[220px] sm:min-h-[260px] flex flex-col justify-center p-4 sm:p-8 md:p-10">
        <img
          src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1600&auto=format&fit=crop"
          alt="Lesson Hero" class="absolute inset-0 w-full h-full object-cover z-0 object-center" />

        <div class="absolute inset-0 bg-[#004d05]/85 dark:bg-[#152B1C]/90 animate-overlay z-0 mix-blend-multiply"></div>

        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-300/20 blur-3xl pointer-events-none z-0"></div>
        <div class="absolute right-24 -top-16 w-80 h-80 rounded-full bg-[#F9C20C]/20 blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full space-y-4 sm:space-y-5">

          <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
              <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                <div class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] font-black text-[10px] sm:text-xs px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm tracking-wide uppercase">
                  <Icon icon="document-text" size="xs" />
                  LESSON CONTENT
                </div>

                <div class="inline-flex items-center gap-2 bg-black/30 dark:bg-black/50 backdrop-blur-md text-white border border-white/20 text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full shadow-sm">
                  <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                  {{ classroom.subject || 'Subject' }}
                </div>
              </div>

              <div class="space-y-1">
                <h1 class="font-['Anton'] text-2xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap leading-none text-white drop-shadow-md">
                  <span>LESSON</span>
                  <span class="text-[#F9C20C] drop-shadow-[0_2px_8px_rgba(249,194,12,0.4)]">CONTENT</span>
                </h1>
                <p class="text-white/90 text-xs sm:text-sm leading-relaxed font-medium italic">
                  "Read the full lesson material."
                </p>
              </div>
            </div>

            <button type="button" @click="goBack"
              class="inline-flex items-center justify-center gap-2 bg-black/30 hover:bg-black/40 backdrop-blur-md text-white border border-white/20 font-bold px-4 py-2.5 rounded-xl transition-all active:scale-95 text-xs sm:text-sm cursor-pointer shadow-sm shrink-0 self-start">
              <Icon icon="arrow-left" size="xs" />
              <span>Back</span>
            </button>
          </div>

          <div class="pt-2 space-y-1">
            <div class="flex flex-wrap items-center gap-2">
              <span class="bg-[#F9C20C] text-[#2C3E2D] text-[10px] font-black px-3 py-0.5 rounded-full shadow-xs uppercase tracking-wide">
                {{ classroom.section || '—' }}
              </span>
            </div>
            <h2 class="font-black text-base sm:text-xl text-white tracking-tight leading-snug truncate">
              {{ lesson.title }}
            </h2>
            <p class="text-white/80 text-xs font-medium truncate">
              Published {{ formatDate(lesson.created_at) }} • {{ classroom.teacher || '—' }}
            </p>
          </div>

        </div>
      </div>

      <!-- BODY -->
      <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-4 sm:p-6 md:p-8 shadow-sm space-y-6">

        <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
          <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full shrink-0"></div>
          <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-none">
              Lesson Body
            </h3>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
              Full text from your instructor
            </p>
          </div>
        </div>

        <div v-if="lesson.body"
          class="text-sm sm:text-base text-slate-700 dark:text-slate-300 whitespace-pre-wrap leading-relaxed">
          {{ lesson.body }}
        </div>

        <div v-else
          class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-8 text-center border border-dashed border-slate-300 dark:border-[#3F4F43] space-y-2">
          <div class="flex justify-center text-slate-400">
            <Icon icon="document-text" size="lg" />
          </div>
          <p class="text-xs text-slate-500 dark:text-slate-400">No body content for this lesson.</p>
        </div>
      </div>

      <!-- FOOTER ACTIONS -->
      <div class="animate-fade-slide-up flex flex-col sm:flex-row items-center justify-between gap-3 bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-3xl p-4 sm:p-5 border border-slate-200/80 dark:border-[#3F4F43]">
        <button type="button" @click="goBack"
          class="inline-flex items-center gap-1.5 text-slate-600 dark:text-slate-300 hover:text-[#004d08] dark:hover:text-[#86EFAC] text-xs font-bold transition-colors cursor-pointer">
          <Icon icon="arrow-left" size="xs" />
          Back to all lessons
        </button>

        <Link :href="route('student.classes.show', classroom.id)"
          class="inline-flex items-center gap-1.5 bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-xs font-black px-4 py-2.5 rounded-xl transition-all active:scale-95">
          Open Class
          <Icon icon="arrow-right" size="xs" />
        </Link>
      </div>

    </div>
  </StudentLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import StudentLayout from '@/Layouts/StudentLayout.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  classroom: { type: Object, default: () => ({}) },
  lesson:    { type: Object, default: () => ({}) },
})

function goBack() {
  if (window.history.length > 2 && document.referrer) {
    window.history.back()
    return
  }
  // Fallback: when opened cold, land on the class's lesson list
  router.visit(route('student.classes.lessons.index', props.classroom.id))
}

function formatDate(value) {
  if (!value) return '—'
  try {
    return new Date(value).toLocaleString('en-US', {
      month: 'long', day: 'numeric', year: 'numeric',
      hour: 'numeric', minute: '2-digit', hour12: true,
    })
  } catch { return value }
}
</script>

<style scoped>
@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-20px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes fadeSlideUp {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: translateY(0); }
}

.animate-fade-in-down  { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
</style>