<template>
  <AdminLayout>
    <div class="space-y-6 max-w-4xl mx-auto pb-10 font-['Inter']">

      <!-- Header -->
      <div class="flex items-center gap-3">
        <Link :href="route('admin.school-news.index')"
              class="p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
          </svg>
        </Link>
        <div>
          <h1 class="font-['Anton'] text-2xl md:text-3xl tracking-wide uppercase text-gray-900 dark:text-white">
            {{ isEditing ? 'Edit Announcement' : 'New Announcement' }}
          </h1>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal">
            {{ isEditing ? 'Update this post and its publication settings' : 'Publish news for the public site and all users' }}
          </p>
        </div>
      </div>

      <!-- General error -->
      <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
        {{ generalError }}
      </div>

      <form @submit.prevent="submit(false)" class="space-y-4">

        <!-- Content -->
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-6 space-y-4">
          <h2 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">Content</h2>

          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Title <span class="text-red-500">*</span>
            </label>
            <input v-model="form.title" type="text" maxlength="255"
                   placeholder="e.g. Enrollment for S.Y. 2026-2027 is now open"
                   class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal" />
            <p v-if="errors.title" class="mt-1 text-[11px] text-red-500">{{ errors.title[0] }}</p>
          </div>

          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                Body <span class="text-red-500">*</span>
              </label>
              <div class="flex items-center gap-2">
                <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ form.body.length }} / 5000</span>
                <button type="button" @click="showPreview = !showPreview"
                        class="text-[10px] font-medium uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] hover:underline cursor-pointer">
                  {{ showPreview ? 'Hide Preview' : 'Show Preview' }}
                </button>
              </div>
            </div>

            <textarea v-if="!showPreview"
                      v-model="form.body" rows="12" maxlength="5000"
                      placeholder="Write your announcement here. Use **bold**, *italic*, [link text](https://url), and blank lines for paragraphs."
                      class="w-full px-3.5 py-2.5 text-xs font-mono bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none resize-none leading-relaxed"></textarea>

            <div v-else
                class="prose prose-sm dark:prose-invert max-w-none w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl min-h-[288px] overflow-auto space-y-3 leading-relaxed"
                v-html="renderedBody"></div>

            <p v-if="errors.body" class="mt-1 text-[11px] text-red-500">{{ errors.body[0] }}</p>
            <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">
              Markdown-lite: <code class="font-mono">**bold**</code>, <code class="font-mono">*italic*</code>, <code class="font-mono">[text](url)</code>. Blank line = new paragraph.
            </p>
          </div>

          <!-- Image -->
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
              Cover Image <span class="text-gray-400 font-normal">(optional)</span>
            </label>

            <div v-if="currentImageUrl && !newImage" class="relative rounded-xl overflow-hidden border border-gray-200 dark:border-[#3F4F43] mb-2 max-w-md">
              <img :src="currentImageUrl" alt="Cover" class="w-full h-32 object-cover" />
              <button type="button" @click="removeCurrentImage"
                      class="absolute top-2 right-2 p-1.5 rounded-lg bg-black/60 text-white hover:bg-black/80 transition-colors cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <input ref="fileInput" type="file" accept="image/*" @change="onFilePicked"
                   class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-emerald-50 dark:file:bg-emerald-950/50 file:text-[#004d08] dark:file:text-[#86EFAC] hover:file:bg-emerald-100 cursor-pointer" />

            <p v-if="newImage" class="mt-1 text-[10px] text-emerald-600 dark:text-emerald-400">
              New: {{ newImage.name }} ({{ formatFileSize(newImage.size) }})
            </p>
            <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">Recommended 1200×630. Max 4 MB.</p>
            <p v-if="errors.image" class="mt-1 text-[11px] text-red-500">{{ errors.image[0] }}</p>
          </div>
        </div>

        <!-- Publication -->
        <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200/80 dark:border-[#3F4F43] p-6 space-y-4">
          <h2 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">Publication</h2>

          <!-- Priority -->
          <div>
            <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
              Priority <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
              <label v-for="opt in priorityOptions" :key="opt.value"
                     class="flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition-colors"
                     :class="form.priority === opt.value
                       ? opt.activeClass
                       : 'bg-gray-50 dark:bg-[#232D26] border-gray-200 dark:border-[#3F4F43] hover:border-gray-300 dark:hover:border-gray-500'">
                <input type="radio" :value="opt.value" v-model="form.priority"
                       class="mt-0.5 w-4 h-4 text-[#004d08] focus:ring-[#004d08] cursor-pointer" />
                <div class="min-w-0">
                  <span class="block text-xs font-medium text-gray-900 dark:text-white">{{ opt.label }}</span>
                  <span class="block text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">{{ opt.hint }}</span>
                </div>
              </label>
            </div>
            <div v-if="form.priority === 'urgent'" class="mt-2 p-3 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 rounded-xl text-[11px] text-red-700 dark:text-red-300 flex items-start gap-2">
              <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <span>Publishing an urgent announcement will email <strong>all active users</strong> immediately. Default expiry is 72 hours unless you set a custom date.</span>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                Expiry Date <span class="text-gray-400 font-normal">(optional)</span>
              </label>
              <input v-model="form.expires_at" type="date"
                     class="w-full px-3.5 py-2.5 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none font-normal cursor-pointer" />
              <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">
                Leave blank for priority-based default.
              </p>
              <p v-if="errors.expires_at" class="mt-1 text-[11px] text-red-500">{{ errors.expires_at[0] }}</p>
            </div>

            <div class="space-y-2">
              <label class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] rounded-xl hover:bg-gray-100/60 dark:hover:bg-white/5 transition-colors cursor-pointer">
                <input v-model="form.is_pinned" type="checkbox"
                       class="mt-0.5 w-4 h-4 text-[#004d08] border-gray-300 rounded focus:ring-[#004d08] cursor-pointer" />
                <div>
                  <span class="block text-xs font-medium text-gray-900 dark:text-white">Pin to top</span>
                  <span class="block text-[10px] text-gray-500 dark:text-gray-400 font-normal">Stays above newer posts.</span>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Footer actions -->
        <div class="flex flex-wrap justify-end gap-2.5 pt-2">
          <Link :href="route('admin.school-news.index')"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
            Cancel
          </Link>

          <button type="button" :disabled="saving"
                  @click="submit(false)"
                  class="px-5 py-2 text-xs font-medium uppercase tracking-wider border border-gray-300 dark:border-[#3F4F43] text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all disabled:opacity-50 cursor-pointer">
            {{ saving && !publishing ? 'Saving…' : 'Save Draft' }}
          </button>

          <button type="button" :disabled="saving"
                  @click="submit(true)"
                  class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] hover:bg-emerald-900 text-white rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
            {{ saving && publishing ? 'Publishing…' : (isEditing && wasPublished ? 'Save & Update' : 'Publish') }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'
import axios from 'axios'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  announcement: { type: Object, default: null },
})

const isEditing = computed(() => !!props.announcement?.id)
const wasPublished = computed(() => !!props.announcement?.is_published)

const form = reactive({
  title: '',
  body: '',
  priority: 'normal',
  is_pinned: false,
  expires_at: '',
})

const fileInput = ref(null)
const newImage = ref(null)
const currentImageUrl = ref(null)

const saving = ref(false)
const publishing = ref(false)
const generalError = ref('')
const errors = ref({})
const showPreview = ref(false)

const priorityOptions = [
  { value: 'normal',    label: 'Normal',    hint: 'Standard feed. No expiry.',              activeClass: 'bg-gray-100 dark:bg-[#232D26] border-gray-400 dark:border-gray-500' },
  { value: 'important', label: 'Important', hint: 'Pinned look, 7-day default expiry.',     activeClass: 'bg-amber-50 dark:bg-amber-950/30 border-amber-300 dark:border-amber-800' },
  { value: 'urgent',    label: 'Urgent',    hint: 'Red banner + emails all users.',         activeClass: 'bg-red-50 dark:bg-red-950/30 border-red-300 dark:border-red-800' },
]

watch(() => props.announcement, (a) => {
  if (a) {
    form.title      = a.title || ''
    form.body       = a.body || ''
    form.priority   = a.priority || 'normal'
    form.is_pinned  = !!a.is_pinned
    form.expires_at = a.expires_at || ''
    currentImageUrl.value = a.image_url || null
  }
}, { immediate: true })

const renderedBody = computed(() => {
  if (!form.body) return '<p class="text-gray-400 italic">Preview will appear here.</p>'
  let html = form.body
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')

  html = html.split(/\n{2,}/).map(p => `<p>${p.replace(/\n/g, '<br>')}</p>`).join('')
  html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
  html = html.replace(/(^|[^*])\*([^*]+?)\*(?!\*)/g, '$1<em>$2</em>')
  html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" rel="noopener" class="text-[#004d08] dark:text-[#86EFAC] underline">$1</a>')

  return html
})

const onFilePicked = (e) => {
  const f = e.target.files?.[0]
  if (!f) return
  if (!f.type.startsWith('image/')) {
    generalError.value = 'Please select an image file.'
    return
  }
  if (f.size > 4 * 1024 * 1024) {
    generalError.value = 'Image must be 4 MB or smaller.'
    return
  }
  generalError.value = ''
  newImage.value = f
}

const removeCurrentImage = () => {
  currentImageUrl.value = null
  // We'll signal removal via a flag on submit if needed later
}

const formatFileSize = (b) => {
  if (!b) return '—'
  const kb = b / 1024
  return kb < 1024 ? `${kb.toFixed(1)} KB` : `${(kb / 1024).toFixed(2)} MB`
}

const submit = async (publish) => {
  errors.value = {}
  generalError.value = ''

  // Client-side validation
  if (!form.title.trim()) {
    generalError.value = 'Title is required.'
    return
  }
  if (!form.body.trim()) {
    generalError.value = 'Body is required.'
    return
  }

  saving.value = true
  publishing.value = publish

  const fd = new FormData()
  fd.append('title', form.title.trim())
  fd.append('body', form.body.trim())
  fd.append('priority', form.priority)
  fd.append('is_pinned', form.is_pinned ? '1' : '0')
  fd.append('is_published', publish ? '1' : '0')
  if (form.expires_at) fd.append('expires_at', form.expires_at)
  if (newImage.value) fd.append('image', newImage.value)

  try {
    if (isEditing.value) {
      fd.append('_method', 'PUT')
      await axios.post(`/admin/school-news/${props.announcement.id}`, fd)
    } else {
      await axios.post('/admin/school-news', fd)
    }

    router.visit(route('admin.school-news.index'))
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors || {}
      generalError.value = e.response.data.message || 'Please fix the highlighted fields.'
    } else {
      generalError.value = e.response?.data?.message || 'Failed to save announcement.'
    }
  } finally {
    saving.value = false
    publishing.value = false
  }
}
</script>