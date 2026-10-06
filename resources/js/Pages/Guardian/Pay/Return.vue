<template>
  <Head title="Payment Status" />
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/40 to-slate-50 dark:from-[#1a2420] dark:via-[#152B1C] dark:to-[#1a2420] py-16 px-4 font-['Inter'] flex items-center justify-center">
    <div class="max-w-lg w-full space-y-5">

      <div class="bg-white dark:bg-[#2D3A31] rounded-3xl shadow-xl border border-slate-200/60 dark:border-[#3F4F43] overflow-hidden">
        <div :class="topBarClass" class="h-2"></div>

        <div class="p-8 sm:p-10 text-center space-y-6">

          <div :class="iconClass" class="w-20 h-20 rounded-3xl mx-auto flex items-center justify-center shadow-md">
            <Icon :icon="iconName" size="xl" />
          </div>

          <div class="space-y-2">
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">{{ title }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto leading-relaxed">{{ subtitle }}</p>
          </div>

          <div v-if="payment" class="bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 text-left space-y-3 border border-slate-200/80 dark:border-[#3F4F43]">
            <div class="flex items-center justify-between text-xs">
              <span class="font-bold uppercase tracking-wider text-slate-500">Reference</span>
              <span class="font-mono font-black text-slate-900 dark:text-white">{{ reference }}</span>
            </div>
            <div class="border-t border-slate-200/60 dark:border-[#3F4F43] pt-3 flex items-center justify-between text-xs">
              <span class="font-bold uppercase tracking-wider text-slate-500">Amount</span>
              <span class="text-lg font-black text-slate-900 dark:text-white">₱{{ formatMoney(payment.amount) }}</span>
            </div>
            <div v-if="payment.status === 'paid' && payment.paid_at" class="border-t border-slate-200/60 dark:border-[#3F4F43] pt-3 flex items-center justify-between text-xs">
              <span class="font-bold uppercase tracking-wider text-slate-500">Paid At</span>
              <span class="font-bold text-emerald-700 dark:text-[#86EFAC]">{{ formatDate(payment.paid_at) }}</span>
            </div>
          </div>

          <div v-if="polling" class="flex items-center justify-center gap-2 text-xs text-slate-500 dark:text-slate-400">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Verifying payment with PayMongo…
          </div>

          <div class="flex flex-col sm:flex-row gap-2 justify-center pt-2">
            <button v-if="polling" @click="checkNow"
              class="bg-[#004d08] hover:bg-[#003805] text-white px-6 py-3 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2">
              <Icon icon="arrow-right" size="xs" class="rotate-90" />
              Refresh Status
            </button>
            <div class="bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl p-4 text-xs text-slate-600 dark:text-slate-400 leading-relaxed max-w-sm mx-auto">
              <strong class="text-slate-900 dark:text-white">What happens next?</strong>
              <p class="mt-1">You'll receive an email receipt once payment is confirmed. You may close this page — no need to wait.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  reference: { type: String, default: null },
  status:    { type: String, default: 'unknown' },
  payment:   { type: Object, default: null },
})

const localPayment = ref(props.payment)
const polling = ref(props.status !== 'cancelled' && props.payment?.status !== 'paid')
let timer = null

const title = computed(() => {
  if (props.status === 'cancelled') return 'Payment Cancelled'
  if (localPayment.value?.status === 'paid') return 'Payment Successful!'
  if (localPayment.value?.status === 'failed') return 'Payment Failed'
  if (localPayment.value?.status === 'expired') return 'Checkout Expired'
  return 'Processing Payment…'
})

const subtitle = computed(() => {
  if (props.status === 'cancelled') return 'You cancelled the checkout. No charge was made.'
  if (localPayment.value?.status === 'paid') return 'Thank you. A receipt has been sent to you and to the school.'
  if (localPayment.value?.status === 'failed') return 'The payment did not go through. Please try again from the original email link.'
  if (localPayment.value?.status === 'expired') return 'This checkout session expired. Please use the original link to start again.'
  return 'We are waiting for confirmation from PayMongo.'
})

const iconName = computed(() => {
  if (props.status === 'cancelled') return 'x-circle'
  if (localPayment.value?.status === 'paid') return 'check-circle'
  if (['failed', 'expired'].includes(localPayment.value?.status)) return 'alert-triangle'
  return 'clock'
})

const iconClass = computed(() => {
  if (props.status === 'cancelled') return 'bg-slate-100 dark:bg-[#232D26] text-slate-500 dark:text-slate-400'
  if (localPayment.value?.status === 'paid') return 'bg-emerald-100 dark:bg-emerald-950/60 text-[#004d08] dark:text-[#86EFAC]'
  if (['failed', 'expired'].includes(localPayment.value?.status)) return 'bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400'
  return 'bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400'
})

const topBarClass = computed(() => {
  if (props.status === 'cancelled') return 'bg-slate-400'
  if (localPayment.value?.status === 'paid') return 'bg-gradient-to-r from-[#004d08] to-emerald-400'
  if (['failed', 'expired'].includes(localPayment.value?.status)) return 'bg-gradient-to-r from-rose-500 to-rose-400'
  return 'bg-gradient-to-r from-[#F9C20C] to-amber-400'
})

async function checkNow() {
  if (!props.reference) return
  try {
    const { data } = await axios.get(route('guardian.pay.status', props.reference))
    localPayment.value = { ...localPayment.value, ...data }
    if (data.status === 'paid' || ['failed', 'expired'].includes(data.status)) {
      polling.value = false
    }
  } catch (e) { /* silent */ }
}

onMounted(() => {
  if (!polling.value) return
  checkNow()
  timer = setInterval(() => {
    if (!polling.value) { clearInterval(timer); return }
    checkNow()
  }, 4000)
})

onUnmounted(() => { if (timer) clearInterval(timer) })

function formatMoney(v) { return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }
function formatDate(v) {
  if (!v) return '—'
  try { return new Date(v).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true }) }
  catch { return '—' }
}
</script>