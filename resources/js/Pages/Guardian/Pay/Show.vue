<template>
  <Head :title="`${contribution.title} — Payment Request`" />

  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/40 to-slate-50 dark:from-[#1a2420] dark:via-[#152B1C] dark:to-[#1a2420] py-6 px-4 font-['Inter']">
    <div class="max-w-2xl mx-auto space-y-5">

      <!-- BRAND HEADER -->
      <div class="text-center space-y-2">
        <div class="w-16 h-16 rounded-2xl bg-[#004d08] mx-auto flex items-center justify-center shadow-lg border-2 border-amber-300">
          <Icon icon="academic-cap" size="xl" class="text-white" />
        </div>
        <h1 class="text-sm font-black text-[#004d08] dark:text-[#86EFAC] tracking-widest uppercase">
          SALAWAG SENIOR HIGH SCHOOL
        </h1>
        <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
          Secure Payment Request
        </p>
      </div>

      <!-- MAIN CARD -->
      <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-xl border border-slate-200/60 dark:border-[#3F4F43] overflow-hidden">

        <!-- COLOR BAR -->
        <div class="h-2 bg-gradient-to-r from-[#004d08] via-emerald-500 to-[#F9C20C]"></div>

        <div class="p-6 sm:p-8 space-y-6">

          <!-- GREETING -->
          <div class="space-y-1">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
              Dear {{ guardian }},
            </p>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white leading-tight">
              {{ contribution.requires_consent ? 'Your approval is requested' : 'A payment is due' }}
            </h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
              <strong class="text-slate-900 dark:text-white">{{ teacher }}</strong> has requested a contribution
              for <strong class="text-slate-900 dark:text-white">{{ student }}</strong>.
            </p>
          </div>

          <!-- DETAILS CARD -->
          <div class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 sm:p-5 border border-slate-200/80 dark:border-[#3F4F43] space-y-3">
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC] flex items-center justify-center shrink-0">
                <Icon icon="credit-card" size="md" />
              </div>
              <div class="min-w-0 flex-1">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-500">Contribution</p>
                <p class="text-sm font-extrabold text-slate-900 dark:text-white leading-snug">
                  {{ contribution.title }}
                </p>
                <p v-if="contribution.purpose" class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
                  {{ contribution.purpose }}
                </p>
              </div>
            </div>

            <div v-if="contribution.description" class="border-t border-slate-200/80 dark:border-[#3F4F43] pt-3">
              <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed whitespace-pre-line">
                {{ contribution.description }}
              </p>
            </div>
          </div>

          <!-- AMOUNT -->
          <div class="bg-gradient-to-br from-amber-50 to-amber-100/60 dark:from-amber-950/30 dark:to-amber-950/20 border-2 border-amber-200/80 dark:border-amber-900/50 rounded-2xl p-5 text-center space-y-1">
            <p class="text-[10px] font-black uppercase tracking-widest text-amber-800 dark:text-amber-300">
              Amount to Pay
            </p>
            <p class="text-4xl sm:text-5xl font-black text-amber-900 dark:text-amber-200 leading-none">
              ₱{{ formatMoney(amount) }}
            </p>
            <p v-if="contribution.deadline" class="text-[11px] font-bold text-amber-800 dark:text-amber-300 pt-1">
              Due by {{ formatDate(contribution.deadline) }}
            </p>
          </div>

          <!-- CONSENT NOTICE -->
          <div v-if="contribution.requires_consent"
            class="bg-sky-50 dark:bg-sky-950/30 border border-sky-200/80 dark:border-sky-900/50 rounded-2xl p-4 flex items-start gap-3">
            <Icon icon="info" size="md" class="text-sky-700 dark:text-sky-400 shrink-0 mt-0.5" />
            <div class="text-xs text-sky-900 dark:text-sky-200 leading-relaxed">
              <p class="font-black mb-0.5">Parental approval required</p>
              <p class="font-medium opacity-90">
                Since {{ student }} is a minor, your consent is required before this payment can proceed.
                You may approve and pay, choose to pay in cash, or decline.
              </p>
            </div>
          </div>

          <!-- ACTION BUTTONS -->
          <div v-if="!choiceMade" class="space-y-2.5">
            <button @click="payOnline" :disabled="submitting"
              class="w-full bg-[#004d08] hover:bg-[#003805] text-white font-black py-4 rounded-2xl shadow-md transition-all active:scale-[0.98] disabled:opacity-60 flex items-center justify-center gap-2.5 text-sm sm:text-base">
              <Icon :icon="submitting && pendingAction === 'online' ? 'clock' : 'credit-card'" size="md" />
              {{ submitting && pendingAction === 'online' ? 'Opening PayMongo…' : 'Pay Online (GCash, Maya, Card)' }}
            </button>

            <button @click="payCash" :disabled="submitting"
              class="w-full bg-white dark:bg-[#232D26] hover:bg-amber-50 dark:hover:bg-amber-950/30 text-amber-800 dark:text-amber-300 font-black py-3.5 rounded-2xl border-2 border-amber-300/80 dark:border-amber-900/50 shadow-sm transition-all active:scale-[0.98] disabled:opacity-60 flex items-center justify-center gap-2.5 text-sm">
              <Icon icon="banknotes" size="md" />
              {{ submitting && pendingAction === 'cash' ? 'Recording…' : 'Pay in Cash at School' }}
            </button>

            <button @click="showDecline = true" :disabled="submitting"
              class="w-full text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 font-bold py-2.5 rounded-2xl text-xs transition-colors disabled:opacity-60">
              Decline this request
            </button>
          </div>

          <!-- RESULT SCREEN -->
          <div v-else :class="resultBoxClass" class="rounded-2xl p-5 border-2 text-center space-y-3">
            <div :class="resultIconClass" class="w-14 h-14 rounded-full mx-auto flex items-center justify-center">
              <Icon :icon="resultIcon" size="xl" />
            </div>
            <p class="text-base font-black">{{ resultTitle }}</p>
            <p class="text-xs leading-relaxed opacity-90">{{ resultMessage }}</p>
            <p v-if="pendingAction === 'cash'" class="text-[11px] font-semibold opacity-80 pt-1">
              Please send ₱{{ formatMoney(amount) }} with {{ student }} to school as soon as possible.
            </p>
          </div>

          <p class="text-[10px] text-center text-slate-400 dark:text-slate-500 leading-relaxed pt-2 border-t border-slate-100 dark:border-[#3F4F43]">
            This payment link expires on <strong>{{ formatDate(expires_at) }}</strong>.
            If you did not expect this, contact Salawag SHS.
          </p>
        </div>
      </div>

      <!-- FOOTER -->
      <p class="text-center text-[10px] text-slate-400 dark:text-slate-500 font-medium">
        © {{ new Date().getFullYear() }} Salawag Senior High School. This is a secure payment portal.
      </p>
    </div>

    <!-- DECLINE MODAL -->
    <div v-if="showDecline"
      class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4"
      @click.self="showDecline = false">
      <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-2xl w-full max-w-md p-6 space-y-4">
        <div class="flex items-start gap-3">
          <div class="w-11 h-11 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
            <Icon icon="x-circle" size="md" />
          </div>
          <div>
            <h3 class="font-black text-slate-900 dark:text-white">Decline this request?</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
              {{ student }} will not be able to pay for this contribution. The teacher will be notified.
            </p>
          </div>
        </div>

        <div class="space-y-1">
          <label class="text-[10px] font-black uppercase tracking-wider text-slate-500">Reason (optional)</label>
          <textarea v-model="declineReason" rows="3"
            placeholder="e.g. Financial constraints at the moment"
            class="w-full bg-[#F9F7F1] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-3 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-rose-400 resize-none"></textarea>
        </div>

        <div class="flex items-center justify-end gap-2 pt-1">
          <button @click="showDecline = false"
            class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-300 text-xs font-bold hover:bg-slate-100 dark:hover:bg-[#3F4F43]">
            Cancel
          </button>
          <button @click="decline" :disabled="submitting"
            class="bg-rose-600 hover:bg-rose-700 text-white px-6 py-2.5 rounded-xl text-xs font-black shadow-sm disabled:opacity-50">
            {{ submitting && pendingAction === 'decline' ? 'Recording…' : 'Confirm Decline' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { confirmAction, showError } from '@/Pages/useSweetAlert'
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  token:        { type: String, required: true },
  guardian:     { type: String, default: 'Parent/Guardian' },
  student:      { type: String, default: 'Student' },
  teacher:      { type: String, default: 'Teacher' },
  contribution: { type: Object, default: () => ({}) },
  amount:       { type: Number, default: 0 },
  expires_at:   { type: String, default: null },
})

const submitting    = ref(false)
const pendingAction = ref(null)
const choiceMade    = ref(false)
const showDecline   = ref(false)
const declineReason = ref('')

const resultTitle = computed(() => ({
  online:  'Redirecting to PayMongo…',
  cash:    'Cash payment recorded',
  decline: 'Request declined',
}[pendingAction.value] || ''))

const resultMessage = computed(() => ({
  online:  'You will be redirected shortly. Complete the payment on the PayMongo page.',
  cash:    'The teacher has been notified. They will confirm receipt once cash is received.',
  decline: 'Your response has been sent to the school. Thank you for letting us know.',
}[pendingAction.value] || ''))

const resultIcon = computed(() => ({
  online:  'clock',
  cash:    'check-circle',
  decline: 'x-circle',
}[pendingAction.value] || 'check-circle'))

const resultIconClass = computed(() => ({
  online:  'bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400',
  cash:    'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC]',
  decline: 'bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400',
}[pendingAction.value] || ''))

const resultBoxClass = computed(() => ({
  online:  'bg-amber-50 dark:bg-amber-950/30 border-amber-200/80 dark:border-amber-900/50 text-amber-900 dark:text-amber-200',
  cash:    'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-200/80 dark:border-emerald-900/50 text-emerald-900 dark:text-emerald-200',
  decline: 'bg-rose-50 dark:bg-rose-950/30 border-rose-200/80 dark:border-rose-900/50 text-rose-900 dark:text-rose-200',
}[pendingAction.value] || ''))

async function payOnline() {
  pendingAction.value = 'online'
  submitting.value = true
  try {
    const { data } = await axios.post(route('guardian.pay.pay-online', props.token))
    if (data.checkout_url) {
      window.location.href = data.checkout_url
      return
    }
    submitting.value = false
  } catch (e) {
    showError(e.response?.data?.message || 'Could not start payment.')
    submitting.value = false
    pendingAction.value = null
  }
}

async function payCash() {
  pendingAction.value = 'cash'
  submitting.value = true
  try {
    await axios.post(route('guardian.pay.pay-cash', props.token))
    choiceMade.value = true
  } catch (e) {
    showError(e.response?.data?.message || 'Could not record your choice.')
    pendingAction.value = null
  } finally {
    submitting.value = false
  }
}

async function decline() {
  pendingAction.value = 'decline'
  submitting.value = true
  try {
    await axios.post(route('guardian.pay.decline', props.token), {
      reason: declineReason.value || null,
    })
    showDecline.value = false
    choiceMade.value = true
  } catch (e) {
    showError(e.response?.data?.message || 'Could not record your response.')
    pendingAction.value = null
  } finally {
    submitting.value = false
  }
}

function formatMoney(v) {
  return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

function formatDate(v) {
  if (!v) return '—'
  try { return new Date(v).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }
  catch { return '—' }
}
</script>