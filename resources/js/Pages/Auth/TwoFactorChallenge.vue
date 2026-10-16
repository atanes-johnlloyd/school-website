<template>
  <Head title="Verify Login - Salawag LMS" />

  <div
    class="min-h-screen w-full flex items-center justify-center p-4 sm:p-6 bg-cover bg-center bg-no-repeat relative font-['Inter'] overflow-hidden"
    :style="{ backgroundImage: `url(${bgImg})` }"
  >
    <div class="relative w-full max-w-lg animate-card-slide-up">

      <div class="absolute -right-48 sm:-right-60 lg:-right-80 bottom-12 sm:bottom-20 lg:bottom-28 z-20 pointer-events-none animate-cat-pop-in">
        <img :src="catImg" alt="Salawag SHS Mascot"
          class="w-60 sm:w-80 md:w-[420px] lg:w-[480px] h-auto object-contain drop-shadow-2xl animate-cat-bounce" />
      </div>

      <div class="relative z-10 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 sm:p-10 border border-white/40 text-left">

        <div class="flex flex-col items-center text-center mb-6">
          <div class="w-12 h-12 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-[#005506] mb-3 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Verify Your Login
          </h1>
          <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-sm">
            We sent a 6-digit code to <strong class="text-slate-700">{{ email }}</strong>. Enter it below to finish signing in.
          </p>
        </div>

        <div v-if="successMessage"
             class="mb-5 text-xs font-semibold text-emerald-800 bg-emerald-50 p-3 rounded-xl border border-emerald-200">
          {{ successMessage }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
          <div class="space-y-1.5">
            <label for="code" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
              Verification Code
            </label>
            <input
              id="code"
              v-model="form.code"
              type="text"
              inputmode="numeric"
              pattern="[0-9]*"
              maxlength="6"
              autocomplete="one-time-code"
              autofocus
              placeholder="000000"
              class="w-full px-4 py-4 bg-slate-50 border border-slate-300 rounded-xl text-center text-2xl font-mono tracking-[0.5em] text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:border-transparent transition-all"
              @input="onInput"
            />
            <p v-if="form.errors.code" class="text-xs text-red-600 font-medium pl-1">
              {{ form.errors.code }}
            </p>
          </div>

          <button
            type="submit"
            :disabled="form.processing || form.code.length !== 6"
            class="w-full py-3.5 bg-[#005506] hover:bg-[#004204] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform active:scale-[0.99] disabled:opacity-70 disabled:cursor-not-allowed"
          >
            Verify &amp; Sign In
          </button>
        </form>

        <div class="text-center pt-4 text-xs">
          <span class="text-slate-500">Didn't receive the code? </span>
          <button
            type="button"
            :disabled="resendCooldown > 0"
            @click="resend"
            class="font-bold text-[#005506] hover:underline disabled:opacity-40 disabled:cursor-not-allowed"
          >
            {{ resendCooldown > 0 ? `Resend in ${resendCooldown}s` : 'Resend code' }}
          </button>
        </div>

        <div class="text-center pt-3">
          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="text-xs font-bold text-slate-500 hover:text-[#005506] underline underline-offset-4 transition-colors"
          >
            Cancel and sign in as a different user
          </Link>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-200/80 text-center">
          <p class="text-[11px] text-slate-400 font-medium">
            © 2026 Salawag Senior High School - All rights reserved
          </p>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onUnmounted } from 'vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'

import bgImg from '@/../assets/img/login_background.png'
import catImg from '@/../assets/img/cat_login.png'

defineProps({
  email: { type: String, default: '' },
})

const page = usePage()
const successMessage = page.props.flash?.success ?? page.props.success ?? null

const form = useForm({ code: '' })

// Strip non-digits as the user types (mobile keyboards sometimes
// paste spaces/dashes from a copied OTP).
const onInput = (e) => {
  form.code = e.target.value.replace(/\D/g, '').slice(0, 6)
}

const submit = () => {
  if (form.code.length !== 6) return
  
  form.post(route('login.verify.submit'), {
    onFinish: () => form.reset('code'),
    onError: (errors) => {
      // If the code is wrong, Inertia automatically populates form.errors.code
      // No need to manually force anything here, but this prevents 
      // the page from crashing if a 500 error occurs.
    }
  })
}

// Resend cooldown (60s)
const resendCooldown = ref(0)
let ticker = null

const startCooldown = () => {
  resendCooldown.value = 60
  if (ticker) return
  ticker = setInterval(() => {
    resendCooldown.value = Math.max(0, resendCooldown.value - 1)
    if (resendCooldown.value === 0 && ticker) {
      clearInterval(ticker); ticker = null
    }
  }, 1000)
}

const resend = () => {
  if (resendCooldown.value > 0) return
  
  useForm({}).post(route('login.verify.resend'), {
    preserveScroll: true,
    onSuccess: () => startCooldown(),
    onError: () => {
      // If we hit a 429 error, start the cooldown anyway
      // so the user stops hammering the server.
      startCooldown()
    }
  })
}

onUnmounted(() => { if (ticker) clearInterval(ticker) })
</script>

<style scoped>
@keyframes slideUpCard { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }
@keyframes catPopIn { 0% { opacity: 0; transform: scale(0.8) translateY(20px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
@keyframes catFloatBounce { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-8px) rotate(-1.5deg); } }
.animate-card-slide-up { animation: slideUpCard 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
.animate-cat-pop-in { animation: catPopIn 1.0s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both; }
.animate-cat-bounce { animation: catFloatBounce 3.5s ease-in-out infinite; }
</style>