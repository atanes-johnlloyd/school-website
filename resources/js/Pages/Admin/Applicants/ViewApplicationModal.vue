<template>
  <!-- ═══════════════ Main View Modal ═══════════════ -->
  <Modal :show="show" @close="closeModal" max-width="2xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] transition-colors flex flex-col max-h-[85vh]">

      <!-- Header -->
      <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div class="min-w-0">
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white truncate">
              Application Review
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate">
              <template v-if="applicant">
                {{ applicant.full_name }} &bull;
                <span class="font-mono">{{ applicant.reference_number }}</span>
              </template>
              <template v-else>Loading applicant details…</template>
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <span
            v-if="applicant"
            class="px-2.5 py-1 rounded-full text-[10px] font-normal uppercase tracking-wider inline-flex items-center gap-1.5 border"
            :class="getStatusBadgeClass(applicant.status)"
          >
            <span class="w-1.5 h-1.5 rounded-full" :class="getStatusDotClass(applicant.status)"></span>
            {{ applicant.status.replace('_', ' ') }}
          </span>

          <button
            type="button"
            @click="closeModal"
            class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Scrollable content -->
      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">

        <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <!-- Lock banner for the "someone else is reviewing" case -->
        <div
          v-if="applicant && isLockedByAnother"
          class="p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-xl text-amber-700 dark:text-amber-300 text-xs flex items-center gap-2"
        >
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
          <span>
            This application is currently under review by
            <strong>{{ applicant.reviewer || 'another admin' }}</strong>.
            No actions are available while it is locked.
          </span>
        </div>

        <div v-if="isLoading" class="py-16 text-center">
          <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading application…</p>
        </div>

        <div v-else-if="applicant" class="space-y-5">

          <section>
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Academic Intent</h4>
            <div class="grid grid-cols-2 gap-3">
              <div v-for="f in academicFields" :key="f.label">
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">{{ f.label }}</p>
                <p class="text-xs text-gray-900 dark:text-white font-normal break-words" :class="f.capitalize ? 'capitalize' : ''">{{ f.value }}</p>
              </div>
            </div>
          </section>

          <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Personal Information</h4>
            <div class="grid grid-cols-2 gap-3">
              <div v-for="f in personalFields" :key="f.label" :class="f.span || ''">
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">{{ f.label }}</p>
                <p class="text-xs text-gray-900 dark:text-white font-normal break-words"
                   :class="[f.mono ? 'font-mono' : '', f.capitalize ? 'capitalize' : '']">{{ f.value }}</p>
              </div>
            </div>
          </section>

          <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Address</h4>
            <div class="grid grid-cols-2 gap-3">
              <div v-for="f in addressFields" :key="f.label">
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">{{ f.label }}</p>
                <p class="text-xs text-gray-900 dark:text-white font-normal break-words">{{ f.value }}</p>
              </div>
            </div>
          </section>

          <section class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Previous School</h4>
            <div class="grid grid-cols-2 gap-3">
              <div v-for="f in schoolFields" :key="f.label">
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">{{ f.label }}</p>
                <p class="text-xs text-gray-900 dark:text-white font-normal break-words" :class="f.capitalize ? 'capitalize' : ''">{{ f.value }}</p>
              </div>
            </div>
          </section>

          <section v-if="contacts.length" class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">Family &amp; Emergency Contacts</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div v-for="contact in contacts" :key="contact.id"
                   class="p-3 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
                <div class="flex items-center justify-between mb-1">
                  <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ contact.role }}</span>
                  <span v-if="contact.relationship" class="text-[10px] text-gray-400 dark:text-gray-500">{{ contact.relationship }}</span>
                </div>
                <p class="text-xs font-medium text-gray-900 dark:text-white">{{ contact.full_name }}</p>
                <p v-if="contact.occupation" class="text-[11px] text-gray-500 dark:text-gray-400">{{ contact.occupation }}</p>
                <div class="mt-1.5 space-y-0.5">
                <p class="text-[11px] font-mono text-gray-600 dark:text-gray-300">
                    {{ contact.contact_number || '—' }}
                </p>
                <div v-if="contact.email" class="flex items-center gap-1.5 min-w-0">
                    <svg class="w-3 h-3 text-gray-400 dark:text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <a
                    :href="`mailto:${contact.email}?subject=${encodeURIComponent('Regarding ' + applicant.full_name + ' — ' + applicant.reference_number)}`"
                    class="text-[11px] text-gray-500 dark:text-gray-400 hover:text-[#004d08] dark:hover:text-[#86EFAC] truncate transition-colors"
                    :title="`Email ${contact.full_name}`"
                    >
                    {{ contact.email }}
                    </a>
                </div>
                </div>
              </div>
            </div>
          </section>

          <section v-if="documents.length" class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">
                Uploaded Documents
            </h4>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-2">
                Image files can be previewed. Other file types can be downloaded.
            </p>
            <div class="space-y-2">

                <!-- Previewable images -->
                <button
                v-for="doc in previewableDocs"
                :key="doc.id"
                type="button"
                @click="openPreview(doc)"
                class="w-full text-left flex items-center justify-between gap-3 p-3 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43] hover:border-[#004d08]/40 dark:hover:border-[#86EFAC]/40 hover:bg-white dark:hover:bg-[#1C261E] transition-colors cursor-pointer group"
                >
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-white dark:bg-[#1C261E] border border-gray-200 dark:border-[#3F4F43] flex items-center justify-center shrink-0 text-gray-500 dark:text-gray-400 group-hover:text-[#004d08] dark:group-hover:text-[#86EFAC] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    </div>
                    <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ doc.document_type }}</p>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">
                        {{ doc.file_name }} &bull; {{ formatFileSize(doc.file_size) }}
                    </p>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-normal uppercase tracking-wider border shrink-0"
                        :class="getDocStatusClass(doc.status)">
                    {{ doc.status }}
                </span>
                </button>

                <!-- Non-previewable: download only -->
                <div
                v-for="doc in nonPreviewableDocs"
                :key="doc.id"
                class="w-full flex items-center justify-between gap-3 p-3 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]"
                >
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-white dark:bg-[#1C261E] border border-gray-200 dark:border-[#3F4F43] flex items-center justify-center shrink-0 text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    </div>
                    <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ doc.document_type }}</p>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">
                        {{ doc.file_name }} &bull; {{ formatFileSize(doc.file_size) }}
                    </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-normal uppercase tracking-wider border"
                        :class="getDocStatusClass(doc.status)">
                    {{ doc.status }}
                    </span>

                    <a
                    v-if="doc.download_url"
                    :href="doc.download_url"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-medium text-[#004d08] dark:text-[#86EFAC] bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 transition-colors"
                    title="Download"
                    >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5m0 0l5-5m-5 5V3" />
                    </svg>
                    Download
                    </a>
                </div>
                </div>

            </div>
            </section>

          <section v-if="applicant.rejection_reason" class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-2">Previous Review Note</h4>
            <p class="text-xs text-gray-700 dark:text-gray-300 bg-amber-50/60 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/50 rounded-xl p-3 leading-relaxed">
              {{ applicant.rejection_reason }}
            </p>
          </section>

        </div>
      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43]">

        <div v-if="applicant && isLockedByAnother" class="flex justify-end">
          <button type="button" @click="closeModal"
                  class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
            Close
          </button>
        </div>

        <div v-else-if="!reasonMode" class="flex flex-wrap justify-end gap-2">
          <button type="button" @click="closeModal"
                  class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
            Close
          </button>

          <button v-if="canRequestResubmission" type="button" :disabled="isActing" @click="reasonMode = 'resubmission'"
                  class="px-4 py-2 text-xs font-medium uppercase tracking-wider bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
            Request Resubmission
          </button>

          <button v-if="canReject" type="button" :disabled="isActing" @click="reasonMode = 'reject'"
                  class="px-4 py-2 text-xs font-medium uppercase tracking-wider bg-red-600 hover:bg-red-700 text-white rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
            Reject
          </button>

          <button v-if="canApprove" type="button" :disabled="isActing" @click="submitSimpleAction('approve')"
                  class="px-5 py-2 text-xs font-medium uppercase tracking-wider bg-[#004d08] hover:bg-emerald-900 text-white rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer">
            {{ isActing ? 'Approving…' : 'Approve Application' }}
          </button>
        </div>

        <div v-else class="space-y-3">
          <div class="flex items-center gap-2">
            <span class="text-[10px] font-semibold uppercase tracking-wider"
                  :class="reasonMode === 'reject' ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400'">
              {{ reasonMode === 'reject' ? 'Rejection Reason' : 'Resubmission Reason' }}
            </span>
            <span class="text-[10px] text-gray-400 dark:text-gray-500">(required, will be emailed to applicant)</span>
          </div>

          <textarea v-model="reason" rows="3"
                    :placeholder="reasonMode === 'reject' ? 'Explain why the application is being rejected…' : 'Explain which documents or details need to be resubmitted…'"
                    class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-[#232D26] border border-gray-200 dark:border-[#3F4F43] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[#004d08] focus:outline-none resize-none"></textarea>

          <p v-if="errors.reason" class="text-[10px] text-red-500">{{ errors.reason[0] }}</p>

          <div class="flex justify-end gap-2">
            <button type="button" @click="cancelReasonMode"
                    class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
              Cancel
            </button>
            <button type="button" :disabled="isActing || !reason.trim()" @click="submitReasonAction"
                    class="px-5 py-2 text-xs font-medium uppercase tracking-wider rounded-xl transition-all shadow-md active:scale-95 disabled:opacity-50 cursor-pointer"
                    :class="reasonMode === 'reject' ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-amber-500 hover:bg-amber-600 text-white'">
              {{ isActing ? (reasonMode === 'reject' ? 'Rejecting…' : 'Sending…') : (reasonMode === 'reject' ? 'Confirm Rejection' : 'Send Request') }}
            </button>
          </div>
        </div>

      </div>
    </div>
  </Modal>

  <!-- ═══════════════ Document Preview Modal — only renders when a preview is active ═══════════════ -->
    <Modal
    v-if="previewOpen"
    :show="true"
    @close="closePreview"
    max-width="4xl"
    >
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] shadow-2xl flex flex-col overflow-hidden max-h-[90vh]">

        <div class="flex items-center justify-between gap-3 px-5 py-3.5 border-b border-gray-100 dark:border-[#3F4F43] shrink-0">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-[#1C261E] border border-emerald-100 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            </div>
            <div class="min-w-0">
            <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ previewName }}</p>
            <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">Image preview</p>
            </div>
        </div>

        <div class="flex items-center gap-1.5 shrink-0">
            <a
            v-if="previewDownloadUrl"
            :href="previewDownloadUrl"
            class="p-1.5 rounded-lg text-gray-500 dark:text-gray-400 hover:text-[#004d08] dark:hover:text-[#86EFAC] hover:bg-gray-100 dark:hover:bg-white/5 transition-colors"
            title="Download"
            >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5m0 0l5-5m-5 5V3" />
            </svg>
            </a>
            <button
            type="button"
            @click="closePreview"
            class="p-1.5 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer"
            title="Close"
            >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            </button>
        </div>
        </div>

        <div class="flex-1 min-h-0 bg-gray-100 dark:bg-[#1C261E] flex items-center justify-center overflow-auto">
        <div v-if="previewLoading" class="py-16 text-center">
            <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading preview…</p>
        </div>

        <img
            v-else-if="previewUrl"
            :src="previewUrl"
            :alt="previewName"
            class="max-w-full max-h-[80vh] object-contain"
        />
        </div>
    </div>
    </Modal>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  show: { type: Boolean, default: false },
  application: { type: Object, default: null },
})

const emit = defineEmits(['close', 'changed'])

const page = usePage()
const currentUserId = computed(() => page.props.auth?.user?.id ?? null)

const applicant = ref(null)
const contacts  = ref([])
const documents = ref([])

const isLoading = ref(false)
const isActing  = ref(false)
const generalError = ref('')
const errors = ref({})

const reasonMode = ref(null)
const reason = ref('')

// ─── Release tracking ─────────────────────────────────────────
const wasPendingOnOpen = ref(false)   // we claimed it this session
const actionTaken = ref(false)        // we acted — don't release

// ─── Preview state ────────────────────────────────────────────
const previewOpen        = ref(false)
const previewUrl         = ref(null)
const previewName        = ref('')
const previewDownloadUrl = ref('')
const previewLoading     = ref(false)

// ─── Fetch on open ────────────────────────────────────────────
watch(() => props.show, async (open) => {
  if (open && props.application?.id) {
    resetState()
    await fetchDetails()
    await autoMarkUnderReview()
  } else if (!open) {
    closePreview()
  }
})

const resetState = () => {
  applicant.value = null
  contacts.value = []
  documents.value = []
  generalError.value = ''
  errors.value = {}
  reasonMode.value = null
  reason.value = ''
  wasPendingOnOpen.value = false
  actionTaken.value = false
}

const fetchDetails = async () => {
  isLoading.value = true
  try {
    const { data } = await axios.get(`/admin/applicants/${props.application.id}`)
    applicant.value = data.applicant
    contacts.value  = data.contacts || []
    documents.value = data.documents || []
  } catch (error) {
    generalError.value = error.response?.data?.message || 'Failed to load application details.'
  } finally {
    isLoading.value = false
  }
}

/**
 * When a pending app is opened, transiently mark it under_review. If the
 * reviewer closes without acting, we release it back to pending.
 */
const autoMarkUnderReview = async () => {
  if (!applicant.value || applicant.value.status !== 'pending') return
  wasPendingOnOpen.value = true

  try {
    await axios.put(`/admin/applicants/${applicant.value.id}/under-review`)
    applicant.value.status         = 'under_review'
    applicant.value.reviewed_by_id = currentUserId.value
    applicant.value.reviewer       = page.props.auth?.user?.name ?? null
    emit('changed')
  } catch (error) {
    console.warn('Auto under-review failed:', error?.response?.data?.message)
  }
}

/**
 * Fire-and-forget release: called on close when we claimed the app
 * this session and never acted on it.
 */
const releaseIfTransient = async () => {
  if (!applicant.value) return
  if (!wasPendingOnOpen.value) return
  if (actionTaken.value) return
  if (applicant.value.status !== 'under_review') return

  const id = applicant.value.id
  try {
    await axios.put(`/admin/applicants/${id}/release`)
    emit('changed')
  } catch (e) {
    console.warn('Failed to release application:', e?.response?.data?.message)
  }
}

// ─── Permissions ──────────────────────────────────────────────
const currentStatus = computed(() => applicant.value?.status)

const isLockedByAnother = computed(() => {
  if (!applicant.value) return false
  if (applicant.value.status !== 'under_review') return false
  const rb = applicant.value.reviewed_by_id
  return rb && rb !== currentUserId.value
})

const canAct = computed(() => !isLockedByAnother.value && !!applicant.value)
const canApprove = computed(() => canAct.value && ['pending', 'under_review'].includes(currentStatus.value))
const canReject  = computed(() => canAct.value && !['enrolled', 'rejected'].includes(currentStatus.value))
const canRequestResubmission = computed(() => canAct.value && !['enrolled', 'rejected'].includes(currentStatus.value))

// ─── Field groups ─────────────────────────────────────────────
const academicFields = computed(() => applicant.value ? [
  { label: 'Applicant Type',      value: applicant.value.applicant_type || '—', capitalize: true },
  { label: 'Desired Grade Level', value: `Grade ${applicant.value.desired_grade_level}` },
  { label: 'Target Strand',       value: applicant.value.strand || 'General Academic' },
] : [])

const personalFields = computed(() => applicant.value ? [
  { label: 'First Name', value: applicant.value.first_name },
  { label: 'Middle Name', value: applicant.value.middle_name || '—' },
  { label: 'Last Name', value: applicant.value.last_name },
  { label: 'Extension', value: applicant.value.extension_name || '—' },
  { label: 'Sex', value: applicant.value.sex || '—', capitalize: true },
  { label: 'Date of Birth', value: formatDate(applicant.value.date_of_birth) },
  { label: 'LRN', value: applicant.value.lrn || '—', mono: true },
  { label: 'Religion', value: applicant.value.religion || '—' },
  { label: 'Contact Number', value: applicant.value.contact_number || '—' },
  { label: 'Email', value: applicant.value.email, span: 'col-span-2' },
] : [])

const addressFields = computed(() => applicant.value ? [
  { label: 'House / Street', value: applicant.value.house_street || '—' },
  { label: 'Barangay', value: applicant.value.barangay || '—' },
  { label: 'Municipality', value: applicant.value.municipality || '—' },
  { label: 'Province', value: applicant.value.province || '—' },
  { label: 'ZIP Code', value: applicant.value.zip_code || '—' },
] : [])

const schoolFields = computed(() => applicant.value ? [
  { label: 'School Name', value: applicant.value.prev_school_name || '—' },
  { label: 'School Address', value: applicant.value.prev_school_address || '—' },
  { label: 'School Type', value: applicant.value.prev_school_type || '—', capitalize: true },
  { label: 'Last School Year', value: applicant.value.last_school_year || '—' },
] : [])

// ─── Document splitting ──────────────────────────────────────
const previewableDocs = computed(() => {
  const list = Array.isArray(documents.value) ? documents.value : []
  return list.filter(d => typeof d?.mime_type === 'string' && d.mime_type.startsWith('image/'))
})

const nonPreviewableDocs = computed(() => {
  const list = Array.isArray(documents.value) ? documents.value : []
  return list.filter(d => !(typeof d?.mime_type === 'string' && d.mime_type.startsWith('image/')))
})

// ─── Style helpers ────────────────────────────────────────────
const getStatusBadgeClass = (status) => ({
  enrolled: 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  approved: 'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  under_review: 'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
  needs_resubmission: 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
  rejected: 'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
}[status] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')

const getStatusDotClass = (status) => ({
  enrolled: 'bg-emerald-500', approved: 'bg-sky-500', under_review: 'bg-blue-500',
  needs_resubmission: 'bg-amber-500', rejected: 'bg-red-500',
}[status] || 'bg-gray-400')

const getDocStatusClass = (status) => ({
  verified: 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
  received: 'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-200 dark:border-sky-800',
  incomplete: 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
  rejected: 'bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
  pending: 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]',
}[status] || 'bg-gray-100 dark:bg-[#232D26] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-[#3F4F43]')

// ─── Actions ──────────────────────────────────────────────────
const submitSimpleAction = async (action) => {
  isActing.value = true
  errors.value = {}
  generalError.value = ''
  try {
    await axios.put(`/admin/applicants/${applicant.value.id}/${action}`)
    actionTaken.value = true
    emit('changed')
    closeModal()
  } catch (error) {
    generalError.value = error.response?.data?.message || 'Action failed.'
  } finally {
    isActing.value = false
  }
}

const submitReasonAction = async () => {
  const trimmed = reason.value.trim()
  if (!trimmed) return

  isActing.value = true
  errors.value = {}
  generalError.value = ''
  try {
    if (reasonMode.value === 'reject') {
      await axios.put(`/admin/applicants/${applicant.value.id}/reject`, { reason: trimmed })
    } else {
      await axios.put(`/admin/applicants/${applicant.value.id}/request-resubmission`, { reason: trimmed })
    }
    actionTaken.value = true
    emit('changed')
    closeModal()
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      generalError.value = error.response.data.message || 'Please provide a valid reason.'
    } else {
      generalError.value = error.response?.data?.message || 'Action failed.'
    }
  } finally {
    isActing.value = false
  }
}

const cancelReasonMode = () => {
  reasonMode.value = null
  reason.value = ''
  errors.value = {}
}

// ─── Preview ──────────────────────────────────────────────────
const openPreview = async (doc) => {
  previewingDoc.value = true
  previewLoading.value = true
  previewUrl.value = null
  previewName.value = doc.file_name || doc.document_type
  previewDownloadUrl.value = doc.download_url || ''

  try {
    const res = await axios.get(doc.download_url, { responseType: 'blob' })
    previewUrl.value = URL.createObjectURL(res.data)
  } catch {
    generalError.value = 'Failed to load document preview.'
  } finally {
    previewLoading.value = false
  }
}

const closePreview = () => {
  if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
  previewingDoc.value = false
  previewUrl.value = null
  previewName.value = ''
  previewDownloadUrl.value = ''
  previewLoading.value = false
}

// ─── Utils ────────────────────────────────────────────────────
const formatDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' }) }
  catch { return iso }
}

const formatFileSize = (bytes) => {
  if (!bytes) return '—'
  const kb = bytes / 1024
  if (kb < 1024) return `${kb.toFixed(1)} KB`
  return `${(kb / 1024).toFixed(2)} MB`
}

const closeModal = () => {
  releaseIfTransient()   // fire-and-forget
  closePreview()
  resetState()
  emit('close')
}
</script>