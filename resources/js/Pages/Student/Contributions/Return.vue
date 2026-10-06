<template>
  <Head title="Payment Status - Salawag LMS" />

  <div :class="[
    'h-screen w-full flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300 overflow-hidden',
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" class="sticky top-0 h-screen shrink-0 z-30" />

    <main class="flex-1 h-full overflow-y-auto overflow-x-hidden min-w-0 flex flex-col justify-between w-full">
      <navbartop searchPlaceholder="Search..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size" />

      <div class="relative z-10 p-3 sm:p-6 md:p-8 flex-1 flex items-center justify-center">
        <div class="max-w-2xl w-full bg-[#fbfdf9] dark:bg-[#2D3A31] rounded-3xl shadow-lg border border-slate-200/60 dark:border-[#3F4F43] p-8 sm:p-12 text-center space-y-6">

          <div :class="iconClass" class="w-20 h-20 rounded-full mx-auto flex items-center justify-center">
            <Icon :icon="iconName" size="xl" />
          </div>

          <div class="space-y-2">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ title }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto">{{ subtitle }}</p>
          </div>

          <div v-if="payment" class="bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-4 text-left space-y-2 border border-slate-200/80 dark:border-[#3F4F43]">
            <div class="flex items-center justify-between text-xs">
              <span class="font-semibold text-slate-500">Reference</span>
              <span class="font-mono font-bold text-slate-800 dark:text-slate-200">{{ reference }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
              <span class="font-semibold text-slate-500">Amount</span>
              <span class="font-black text-slate-900 dark:text-white">₱{{ formatMoney(payment.amount) }}</span>
            </div>
            <div v-if="payment.status === 'paid' && payment.paid_at" class="flex items-center justify-between text-xs">
              <span class="font-semibold text-slate-500">Paid</span>
              <span class="font-bold text-emerald-700 dark:text-[#86EFAC]">{{ formatDate(payment.paid_at) }}</span>
            </div>
          </div>

          <div v-if="polling" class="flex items-center justify-center gap-2 text-xs text-slate-500">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Checking payment status…
          </div>

          <div class="flex flex-col sm:flex-row gap-2 justify-center pt-2">
            <Link :href="route('student.contributions.index')"
              class="bg-[#005506] dark:bg-[#86EFAC] hover:bg-[#004105] text-white dark:text-[#232D26] px-6 py-3 rounded-xl text-xs font-black shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2">
              <Icon icon="arrow-left" size="xs" />
              Back to Contributions
            </Link>
            <button v-if="polling" @click="checkNow"
              class="bg-[#F9F7F1] dark:bg-[#232D26] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-slate-700 dark:text-slate-200 px-6 py-3 rounded-xl text-xs font-bold border border-slate-200/80 dark:border-[#3F4F43] flex items-center justify-center gap-2">
              <Icon icon="arrow-right" size="xs" class="rotate-90" />
              Refresh
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import axios from 'axios'
import Sidebar from '@/Components/Sidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  reference: { type: String, default: null },
  status:    { type: String, default: 'unknown' },
  payment:   { type: Object, default: null },
})

const isSidebarOpen = ref(false)
const fontSizeMode  = ref('base')
const localPayment  = ref(props.payment)
const polling       = ref(props.status !== 'cancelled' && props.payment?.status !== 'paid')
let timer = null

const title = computed(() => {
  if (props.status === 'cancelled') return 'Payment cancelled'
  if (localPayment.value?.status === 'paid') return 'Payment successful!'
  if (localPayment.value?.status === 'failed') return 'Payment failed'
  if (localPayment.value?.status === 'expired') return 'Checkout expired'
  return 'Processing payment…'
})

const subtitle = computed(() => {
  if (props.status === 'cancelled') return 'You cancelled the checkout. No charge was made.'
  if (localPayment.value?.status === 'paid') return 'A receipt has been emailed to you and your guardian.'
  if (localPayment.value?.status === 'failed') return 'The payment did not go through. You can try again from the contributions page.'
  if (localPayment.value?.status === 'expired') return 'This checkout session expired. Please start a new payment.'
  return 'We are waiting for confirmation from PayMongo. This page will update automatically.'
})

const iconName = computed(() => {
  if (props.status === 'cancelled') return 'x-circle'
  if (localPayment.value?.status === 'paid') return 'check-circle'
  if (['failed', 'expired'].includes(localPayment.value?.status)) return 'alert-triangle'
  return 'clock'
})

const iconClass = computed(() => {
  if (props.status === 'cancelled') return 'bg-slate-100 dark:bg-[#232D26] text-slate-500'
  if (localPayment.value?.status === 'paid') return 'bg-emerald-100 dark:bg-emerald-950/60 text-[#005506] dark:text-[#86EFAC]'
  if (['failed', 'expired'].includes(localPayment.value?.status)) return 'bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400'
  return 'bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400'
})

async function checkNow() {
  if (!props.payment?.id) return
  try {
    const { data } = await axios.get(route('student.payments.status', props.payment.id))
    localPayment.value = { ...localPayment.value, ...data }
    if (data.status === 'paid' || ['failed', 'expired'].includes(data.status)) {
      polling.value = false
    }
  } catch (e) {
    // silent
  }
}

onMounted(() => {
  if (!polling.value) return
  checkNow()
  timer = setInterval(() => {
    if (!polling.value) return clearInterval(timer)
    checkNow()
  }, 4000)
})

onUnmounted(() => {
  if (timer) clearInterval(timer)
})

function formatMoney(v) {
  return Number(v || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

function formatDate(v) {
  if (!v) return '—'
  try {
    return new Date(v).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true })
  } catch { return '—' }
}
</script>