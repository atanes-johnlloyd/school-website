<template>
  <Head :title="`${announcement.title || 'Announcement'} - Salawag LMS`" />

  <AuthenticatedLayout searchPlaceholder="Search announcements...">

    <div class="relative z-10 px-4 sm:px-6 md:px-10 pb-24 flex-1 mt-4">
      <div class="max-w-5xl mx-auto w-full space-y-6">

        <!-- ─────────── TOP ACTION BAR ─────────── -->
        <div v-observe class="anim-fade-down flex items-center justify-between gap-3 flex-wrap">
          <Link :href="route('teacher.announcements.index')"
            class="inline-flex items-center gap-2 bg-white dark:bg-[#2D3A31] hover:bg-slate-50 dark:hover:bg-[#3F4F43] border border-slate-200/80 dark:border-[#3F4F43] text-slate-700 dark:text-slate-200 text-xs font-black px-3.5 py-2 rounded-xl transition-all active:scale-95 shadow-sm">
            <Icon icon="arrow-left" size="xs" />
            Back to Announcements
          </Link>

          <div class="flex items-center gap-1.5">
            <!-- Pin toggle -->
            <button @click="togglePin" :disabled="pinning"
              :class="announcement.is_pinned
                ? 'bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-900/50'
                : 'bg-white dark:bg-[#2D3A31] text-slate-600 dark:text-slate-300 hover:bg-amber-50 dark:hover:bg-amber-950/40 border-slate-200 dark:border-[#3F4F43]'"
              class="inline-flex items-center gap-1.5 border text-xs font-black px-3.5 py-2 rounded-xl transition-all active:scale-95 shadow-sm disabled:opacity-50">
              <Icon icon="star" size="xs" />
              {{ announcement.is_pinned ? 'Pinned' : 'Pin' }}
            </button>

            <!-- Delete -->
            <button @click="confirmDelete = true"
              class="inline-flex items-center gap-1.5 bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] hover:bg-rose-50 dark:hover:bg-rose-950/40 hover:border-rose-200 dark:hover:border-rose-900/50 text-rose-600 dark:text-rose-400 text-xs font-black px-3.5 py-2 rounded-xl transition-all active:scale-95 shadow-sm">
              <Icon icon="trash" size="xs" />
              Delete
            </button>
          </div>
        </div>

        <!-- ─────────── FEATURED IMAGE (if any) ─────────── -->
        <div v-if="announcement.image_url"
          v-observe class="anim-slide-up relative w-full rounded-3xl overflow-hidden shadow-md border border-slate-200/60 dark:border-[#3F4F43]">
          <img :src="announcement.image_url" alt="Announcement cover"
            class="w-full h-auto max-h-[460px] object-cover" />
          <!-- soft bottom fade so title blends -->
          <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/40 to-transparent pointer-events-none"></div>

          <!-- Overlay badges on top of the cover -->
          <div class="absolute top-4 left-4 flex flex-wrap items-center gap-2">
            <span v-if="announcement.is_pinned"
              class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-md">
              <Icon icon="star" size="xs" /> Pinned
            </span>
            <span :class="announcement.is_published
              ? 'bg-emerald-500/90 border-emerald-300/40'
              : 'bg-slate-500/85 border-slate-300/40'"
              class="inline-flex items-center gap-1.5 text-white border text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-md backdrop-blur-sm">
              <Icon :icon="announcement.is_published ? 'check-circle' : 'edit'" size="xs" />
              {{ announcement.is_published ? 'Published' : 'Draft' }}
            </span>
          </div>
        </div>

        <!-- ─────────── ARTICLE ─────────── -->
        <article v-observe class="anim-slide-up space-y-6">

          <!-- Small status row (only when there's no cover image to carry it) -->
          <div v-if="!announcement.image_url" class="flex flex-wrap items-center gap-2">
            <span v-if="announcement.is_pinned"
              class="inline-flex items-center gap-1.5 bg-[#F9C20C] text-[#2C3E2D] text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-sm">
              <Icon icon="star" size="xs" /> Pinned
            </span>
            <span :class="announcement.is_published
              ? 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC] border-emerald-200 dark:border-emerald-900/40'
              : 'bg-slate-100 dark:bg-[#232D26] text-slate-600 dark:text-slate-300 border-slate-200 dark:border-[#3F4F43]'"
              class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full border">
              <Icon :icon="announcement.is_published ? 'check-circle' : 'edit'" size="xs" />
              {{ announcement.is_published ? 'Published' : 'Draft' }}
            </span>
          </div>

          <!-- TITLE -->
          <header class="space-y-5">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-[1.15]">
              {{ announcement.title || 'Untitled Announcement' }}
            </h1>

            <!-- BYLINE -->
            <div class="flex items-center gap-3 pb-5 border-b border-slate-200/70 dark:border-[#3F4F43]">
              <div
                class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#005506] to-[#003805] dark:from-[#86EFAC] dark:to-[#4ade80] text-white dark:text-[#232D26] flex items-center justify-center font-black text-sm shadow-sm shrink-0">
                {{ initialOf(announcement.author) }}
              </div>
              <div class="min-w-0">
                <p class="text-sm font-black text-slate-900 dark:text-white truncate">
                  {{ announcement.author || 'Instructor' }}
                </p>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 flex flex-wrap items-center gap-x-2 gap-y-0.5">
                  <span class="inline-flex items-center gap-1">
                    <Icon icon="clock" size="xs" />
                    {{ announcement.published_human || formatLong(announcement.created_at) }}
                  </span>
                  <span class="text-slate-300 dark:text-[#3F4F43]">•</span>
                  <span class="inline-flex items-center gap-1">
                    <Icon icon="academic-cap" size="xs" />
                    {{ classroom.subject }}
                  </span>
                  <span v-if="classroom.section" class="text-slate-300 dark:text-[#3F4F43]">•</span>
                  <span v-if="classroom.section">{{ classroom.section }}</span>
                </p>
              </div>
            </div>
          </header>

          <!-- BODY — comfortable reading column -->
          <div
            class="rounded-3xl bg-white dark:bg-[#2D3A31] border border-slate-200/60 dark:border-[#3F4F43] shadow-sm p-6 sm:p-8 md:p-10">
            <div
              class="max-w-3xl text-slate-700 dark:text-slate-200 text-[15px] sm:text-base leading-[1.85] whitespace-pre-wrap font-medium">
              {{ announcement.body || 'No body content for this announcement.' }}
            </div>
          </div>

          <!-- FOOTER META — subtle, low-key -->
          <div
            class="rounded-3xl bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/70 dark:border-[#3F4F43] p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
              <div class="space-y-0.5">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Class</p>
                <Link :href="route('teacher.classes.announcements.index', classroom.id)"
                  class="text-xs font-black text-[#005506] dark:text-[#86EFAC] hover:underline inline-flex items-center gap-1">
                  {{ classroom.subject }}
                  <Icon icon="arrow-right" size="xs" />
                </Link>
              </div>

              <div class="space-y-0.5">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Created</p>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ formatLong(announcement.created_at) }}</p>
              </div>

              <div v-if="announcement.expires_at" class="space-y-0.5">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Expires</p>
                <p class="text-xs font-bold text-amber-700 dark:text-amber-400">{{ formatLong(announcement.expires_at) }}</p>
              </div>

              <div v-if="announcement.is_published" class="space-y-0.5">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Published</p>
                <p class="text-xs font-bold text-emerald-700 dark:text-[#86EFAC]">{{ formatLong(announcement.published_at) }}</p>
              </div>
            </div>
          </div>

        </article>

      </div>
    </div>

    <!-- ─────────── DELETE CONFIRM MODAL ─────────── -->
    <div v-if="confirmDelete"
      class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4"
      @click.self="confirmDelete = false">
      <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-2xl w-full max-w-md p-6 space-y-4 border border-slate-100 dark:border-[#3F4F43]">
        <div class="flex items-start gap-3">
          <div
            class="w-11 h-11 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-100 dark:border-rose-900/40">
            <Icon icon="trash" size="md" />
          </div>
          <div class="min-w-0">
            <h3 class="font-black text-slate-900 dark:text-white text-base">Delete this announcement?</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mt-1 leading-relaxed line-clamp-3">
              "{{ announcement.title }}"
            </p>
          </div>
        </div>

        <p class="text-[11px] text-slate-500 dark:text-slate-400 bg-[#F9F7F1] dark:bg-[#232D26] rounded-xl p-3 border border-slate-200/80 dark:border-[#3F4F43]">
          This cannot be undone. Students will no longer see this bulletin.
        </p>

        <div class="flex items-center justify-end gap-2 pt-1">
          <button @click="confirmDelete = false"
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

  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  announcement: { type: Object, default: () => ({}) },
  classroom:    { type: Object, default: () => ({}) },
})

/* ─── ACTIONS ─────────────────────────────────────────── */
const pinning       = ref(false)
const confirmDelete = ref(false)
const deleting      = ref(false)

function togglePin() {
  pinning.value = true
  axios.put(route('teacher.announcements.toggle-pin', props.announcement.id))
    .then(() => router.reload({ only: ['announcement'], preserveScroll: true, preserveState: true }))
    .catch(e => alert(e.response?.data?.message || 'Could not toggle pin.'))
    .finally(() => { pinning.value = false })
}

function destroyConfirmed() {
  deleting.value = true
  axios.delete(route('teacher.announcements.destroy', props.announcement.id))
    .then(() => router.visit(route('teacher.announcements.index')))
    .catch(e => alert(e.response?.data?.message || 'Delete failed.'))
    .finally(() => { deleting.value = false })
}

/* ─── HELPERS ─────────────────────────────────────────── */
function initialOf(name) {
  if (typeof name !== 'string' || name.length === 0) return '?'
  return name.charAt(0).toUpperCase()
}

function formatLong(v) {
  if (!v) return '—'
  try {
    return new Date(v).toLocaleDateString('en-US', {
      month: 'long', day: 'numeric', year: 'numeric',
    })
  } catch { return '—' }
}

/* ─── ANIMATIONS ──────────────────────────────────────── */
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
@keyframes slideUpFade { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInDown  { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }

.not-visible { opacity: 0; }
.is-animated.anim-fade-down { animation: fadeInDown 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up  { animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>