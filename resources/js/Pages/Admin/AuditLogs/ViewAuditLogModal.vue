<template>
  <Modal :show="show" @close="closeModal" max-width="3xl">
    <div class="bg-white dark:bg-[#2D3A31] rounded-2xl border border-gray-200 dark:border-[#3F4F43] font-['Inter'] flex flex-col max-h-[88vh]">

      <!-- Header -->
      <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-gray-100 dark:border-[#3F4F43]">
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-10 h-10 rounded-2xl border flex items-center justify-center shrink-0"
               :class="actionIconClass(log?.action)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" :d="actionIconPath(log?.action)" />
            </svg>
          </div>
          <div class="min-w-0">
            <h3 class="text-xs font-medium uppercase tracking-wider text-gray-900 dark:text-white truncate">
              Audit Log Entry
            </h3>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-normal truncate">
              <template v-if="log">
                <span class="capitalize">{{ log.action }}</span>
                <span v-if="log.auditable_label"> &bull; {{ log.auditable_label }} #{{ log.auditable_id }}</span>
              </template>
              <template v-else>Loading…</template>
            </p>
          </div>
        </div>

        <button type="button" @click="closeModal"
                class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors cursor-pointer shrink-0">
          <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5 space-y-5">

        <div v-if="generalError" class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 text-xs">
          {{ generalError }}
        </div>

        <div v-if="isLoading" class="py-16 text-center">
          <div class="inline-block w-6 h-6 border-2 border-[#004d08] dark:border-[#86EFAC] border-t-transparent rounded-full animate-spin"></div>
          <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Loading log entry…</p>
        </div>

        <template v-else-if="log">

          <!-- Meta -->
          <section class="p-4 rounded-xl bg-gray-50 dark:bg-[#232D26] border border-gray-200/80 dark:border-[#3F4F43]">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Performed By</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ log.user_name }}</p>
                <p v-if="log.user_email" class="text-[10px] text-gray-400 truncate">{{ log.user_email }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">When</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ formatFullDate(log.created_at) }}</p>
                <p class="text-[10px] text-gray-400">{{ formatRelative(log.created_at) }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">Model</p>
                <p class="text-xs text-gray-900 dark:text-white">{{ log.auditable_label || '—' }}</p>
                <p v-if="log.auditable_id" class="text-[10px] text-gray-400 font-mono">ID {{ log.auditable_id }}</p>
              </div>
              <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">IP Address</p>
                <p class="text-xs text-gray-900 dark:text-white font-mono">{{ log.ip_address || '—' }}</p>
              </div>
            </div>
          </section>

          <!-- Changes -->
          <section>
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC]">
                Changes
              </h4>
              <span class="text-[10px] text-gray-400">
                {{ changes.length }} field{{ changes.length === 1 ? '' : 's' }}
              </span>
            </div>

            <div v-if="!changes.length" class="py-8 text-center text-[11px] text-gray-400 italic">
              No field-level changes recorded.
            </div>

            <div v-else class="border border-gray-200/80 dark:border-[#3F4F43] rounded-xl overflow-hidden">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-gray-50/50 dark:bg-[#232D26]/50 text-[10px] font-medium uppercase text-gray-400 tracking-wider border-b border-gray-100 dark:border-[#3F4F43]">
                    <th class="py-2.5 px-4 w-1/4">Field</th>
                    <th class="py-2.5 px-4" :class="log.action === 'created' ? 'w-3/4' : 'w-[37.5%]'">
                      <span v-if="log.action === 'created'" class="text-emerald-600 dark:text-emerald-400">Value</span>
                      <span v-else class="text-red-600 dark:text-red-400">Before</span>
                    </th>
                    <th class="py-2.5 px-4 w-[37.5%]" v-if="log.action !== 'created'">
                      <span v-if="log.action === 'deleted'" class="text-gray-400">Deleted</span>
                      <span v-else class="text-emerald-600 dark:text-emerald-400">After</span>
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-[#3F4F43] text-xs">

                  <tr v-for="row in changes" :key="row.field"
                      class="hover:bg-gray-50/60 dark:hover:bg-white/5 transition-colors">
                    <td class="py-2.5 px-4 align-top">
                      <span class="font-mono text-[11px] text-gray-700 dark:text-gray-300">{{ row.field }}</span>
                    </td>

                    <!-- Created: this column IS the value — show row.new -->
                    <td v-if="log.action === 'created'" class="py-2.5 px-4 align-top">
                      <span v-if="row.new === undefined || row.new === null"
                            class="text-[11px] text-gray-300 dark:text-gray-600 italic">— empty —</span>
                      <span v-else
                            class="text-[11px] text-gray-900 dark:text-gray-100 break-all">
                        {{ formatValue(row.new) }}
                      </span>
                    </td>

                    <!-- Updated / Deleted: show row.old here -->
                    <td v-else class="py-2.5 px-4 align-top">
                      <span v-if="row.old === undefined || row.old === null"
                            class="text-[11px] text-gray-300 dark:text-gray-600 italic">— not set —</span>
                      <span v-else
                            class="text-[11px] break-all"
                            :class="log.action === 'deleted' ? 'text-gray-700 dark:text-gray-300' : 'text-red-700 dark:text-red-400 line-through decoration-red-300'">
                        {{ formatValue(row.old) }}
                      </span>
                    </td>

                    <td v-if="log.action !== 'created'" class="py-2.5 px-4 align-top">
                      <span v-if="log.action === 'deleted'"
                            class="text-[11px] text-gray-400 italic">removed</span>
                      <span v-else-if="row.new === undefined || row.new === null"
                            class="text-[11px] text-gray-300 dark:text-gray-600 italic">— cleared —</span>
                      <span v-else
                            class="text-[11px] text-emerald-700 dark:text-emerald-400 break-all">
                        {{ formatValue(row.new) }}
                      </span>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
          </section>

          <!-- User agent -->
          <section v-if="log.user_agent" class="pt-3 border-t border-gray-100 dark:border-[#3F4F43]">
            <h4 class="text-[10px] font-semibold uppercase tracking-wider text-[#004d08] dark:text-[#86EFAC] mb-2">
              User Agent
            </h4>
            <p class="text-[10px] text-gray-500 dark:text-gray-400 font-mono break-all bg-gray-50 dark:bg-[#232D26] rounded-lg p-2.5 border border-gray-200/80 dark:border-[#3F4F43]">
              {{ log.user_agent }}
            </p>
          </section>
        </template>
      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-[#3F4F43] flex justify-end">
        <button type="button" @click="closeModal"
                class="px-4 py-2 text-xs font-normal text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all cursor-pointer">
          Close
        </button>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  show: { type: Boolean, default: false },
  log:  { type: Object,  default: null },
})

const emit = defineEmits(['close'])

const log = ref(null)
const isLoading = ref(false)
const generalError = ref('')

watch(() => props.show, async (open) => {
  if (open && props.log?.id) {
    log.value = null
    generalError.value = ''
    await fetchDetails()
  }
})

const fetchDetails = async () => {
  isLoading.value = true
  try {
    const { data } = await axios.get(`/admin/audit-logs/${props.log.id}`)
    log.value = data.log
  } catch (e) {
    generalError.value = e.response?.data?.message || 'Failed to load log entry.'
  } finally {
    isLoading.value = false
  }
}

/**
 * Merge old_values and new_values, keep only fields that actually changed.
 */
const changes = computed(() => {
  if (!log.value) return []

  const oldVals = log.value.old_values || {}
  const newVals = log.value.new_values || {}
  const keys = [...new Set([...Object.keys(oldVals), ...Object.keys(newVals)])]

  return keys
    .map(key => ({
      field: key,
      old:   oldVals[key],
      new:   newVals[key],
    }))
    .filter(row => {
      // Created → keep everything in new_values
      if (log.value.action === 'created') {
        return row.new !== undefined
      }
      // Deleted → keep everything in old_values
      if (log.value.action === 'deleted') {
        return row.old !== undefined
      }
      // Updated → keep only what actually changed
      return JSON.stringify(row.old) !== JSON.stringify(row.new)
    })
})

const formatValue = (v) => {
  if (v === null) return 'null'
  if (v === undefined) return '—'
  if (v === true) return 'Yes'
  if (v === false) return 'No'
  if (typeof v === 'object') return JSON.stringify(v)
  return String(v)
}

const formatFullDate = (iso) => {
  if (!iso) return '—'
  try { return new Date(iso).toLocaleString('en-PH', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' }) }
  catch { return iso }
}

const formatRelative = (iso) => {
  if (!iso) return ''
  try {
    const diff = (Date.now() - new Date(iso).getTime()) / 1000
    if (diff < 60) return 'just now'
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago'
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago'
    return Math.floor(diff / 86400) + 'd ago'
  } catch { return '' }
}

const actionIconPath = (action) => ({
  created: 'M12 4v16m8-8H4',
  updated: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
  deleted: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 3h6',
  restored: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
}[action] || 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4')

const actionIconClass = (action) => ({
  created: 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-100 dark:border-emerald-900/50 text-emerald-600 dark:text-emerald-400',
  updated: 'bg-sky-50 dark:bg-sky-950/40 border-sky-100 dark:border-sky-900/50 text-sky-600 dark:text-sky-400',
  deleted: 'bg-red-50 dark:bg-red-950/40 border-red-100 dark:border-red-900/50 text-red-600 dark:text-red-400',
  restored: 'bg-violet-50 dark:bg-violet-950/40 border-violet-100 dark:border-violet-900/50 text-violet-600 dark:text-violet-400',
}[action] || 'bg-gray-50 dark:bg-[#1C261E] border-gray-100 dark:border-[#3F4F43] text-gray-500 dark:text-gray-400')

const closeModal = () => {
  log.value = null
  generalError.value = ''
  emit('close')
}
</script>