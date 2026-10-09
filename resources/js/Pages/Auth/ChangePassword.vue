<template>
  <Head title="Change Password - Salawag LMS" />

  <!-- Full Screen Background Canvas -->
  <div
    class="min-h-screen w-full flex items-center justify-center p-4 sm:p-6 bg-cover bg-center bg-no-repeat relative font-['Inter'] overflow-hidden"
    :style="{ backgroundImage: `url(${bgImg})` }"
  >
    <!-- Centered Card Container with Entrance Animation -->
    <div class="relative w-full max-w-lg animate-card-slide-up">

      <!-- Mascot Container with Entrance & Continuous Bounce Animations -->
            <div
                class="absolute -right-48 sm:-right-60 lg:-right-80 bottom-12 sm:bottom-20 lg:bottom-28 z-20 pointer-events-none animate-cat-pop-in">
                <img :src="catImg" alt="Salawag SHS Login Mascot"
                    class="w-60 sm:w-80 md:w-[420px] lg:w-[480px] h-auto object-contain drop-shadow-2xl animate-cat-bounce" />
            </div>

      <!-- Main Form Card -->
      <div class="relative z-10 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 sm:p-10 border border-white/40 text-left">

        <!-- Header Section -->
        <div class="flex flex-col items-center text-center mb-6">
          <!-- Key Icon Circle Badge -->
          <div class="w-12 h-12 rounded-full bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 mb-3 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
          </div>

          <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Change Your Password
          </h1>
          <p class="text-xs sm:text-sm text-slate-500 font-normal mt-2 max-w-xs">
            Your password must meet all {{ requirements.length }} requirements below.
          </p>
        </div>

        <!-- Info banner if props.mustChange -->
        <div
          v-if="mustChange"
          class="mb-5 text-xs font-semibold text-amber-800 bg-amber-50 p-3 rounded-xl border border-amber-200"
        >
          Your account is using a temporary password. Please choose a new one to continue.
        </div>

        <!-- Form Elements -->
        <form @submit.prevent="submit" class="space-y-5">

          <!-- NEW PASSWORD FIELD -->
          <div class="space-y-1.5">
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
              New Password
            </label>
            <div class="relative flex items-center">
              <input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                autocomplete="new-password"
                placeholder="••••••••••••"
                class="w-full pl-4 pr-11 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:border-transparent transition-all"
                @input="assessStrength"
              />
              <button type="button" @click="showPassword = !showPassword"
                class="absolute right-3.5 text-slate-400 hover:text-slate-600 focus:outline-none">
                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                </svg>
              </button>
            </div>
            <p v-if="form.errors.password" class="text-xs text-red-600 font-medium pl-1">
              {{ form.errors.password }}
            </p>
          </div>

          <!-- CONFIRM PASSWORD FIELD -->
          <div class="space-y-1.5">
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
              Confirm New Password
            </label>
            <div class="relative flex items-center">
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                :type="showConfirmPassword ? 'text' : 'password'"
                required
                autocomplete="new-password"
                placeholder="••••••••••••"
                class="w-full pl-4 pr-11 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:border-transparent transition-all"
              />
              <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                class="absolute right-3.5 text-slate-400 hover:text-slate-600 focus:outline-none">
                <svg v-if="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                </svg>
              </button>
            </div>
            <p v-if="form.errors.password_confirmation" class="text-xs text-red-600 font-medium pl-1">
              {{ form.errors.password_confirmation }}
            </p>
          </div>

          <!-- PASSWORD REQUIREMENTS CHECKLIST -->
          <div class="pt-1">
            <div class="flex items-center justify-between text-xs font-semibold mb-2">
              <span class="text-slate-500">Password Requirements</span>
              <span :class="allRequirementsMet ? 'text-[#005506]' : 'text-slate-400'">
                {{ metCount }} / {{ requirements.length }}
              </span>
            </div>
            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-xs">
              <li v-for="req in requirements" :key="req.key"
                  class="flex items-center gap-2"
                  :class="req.met ? 'text-[#005506] font-medium' : 'text-slate-400'">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                  <path v-if="req.met" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  <path v-else stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                </svg>
                <span>{{ req.label }}</span>
              </li>
            </ul>
          </div>

          <!-- SUBMIT BUTTON -->
          <div class="pt-3">
            <button
              type="submit"
              :disabled="form.processing || !allRequirementsMet"
              class="w-full py-3.5 bg-[#005506] hover:bg-[#004204] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform active:scale-[0.99] disabled:opacity-70 disabled:cursor-not-allowed"
            >
              Update Password
            </button>
          </div>

          <!-- LOGOUT LINK -->
          <div class="text-center pt-2">
            <Link
              :href="route('logout')"
              method="post"
              as="button"
              class="text-xs font-bold text-slate-500 hover:text-[#005506] underline underline-offset-4 transition-colors"
            >
              Sign in as a different user
            </Link>
          </div>

        </form>

        <!-- FOOTER COPYRIGHT -->
        <div class="mt-8 pt-6 border-t border-slate-200/80 text-center">
          <p class="text-[11px] text-slate-400 font-medium">
            © 2026 Salawag Senior High School - All rights reserved
          </p>
          <p v-if="rule.uncompromised" class="text-[10px] text-slate-400 mt-2">
            Passwords that appear in known data breaches will be rejected.
          </p>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'

import bgImg from '@/../assets/img/login_background.png'
import catImg from '@/../assets/img/cat_login.png'

const props = defineProps({
  mustChange: { type: Boolean, default: true },
})

// Removed showCurrent ref since the input is gone
const showPassword = ref(false)
const showConfirmPassword = ref(false)

const strengthScore = ref(0)
const strengthLabel = ref('Weak')

// Removed current_password from useForm
const form = useForm({
  password: '',
  password_confirmation: '',
})

const page = usePage()
const rule = computed(() =>
  page.props.passwordRule || { min: 12, letters: true, mixedCase: true, numbers: true, symbols: true, uncompromised: false }
)

const requirements = computed(() => {
  const p = form.password || ''
  const r = rule.value

  const list = [
    { key: 'length',  label: `At least ${r.min} characters`, met: p.length >= r.min },
  ]

  if (r.letters)   list.push({ key: 'letters',   label: 'Contains a letter',              met: /[A-Za-z]/.test(p) })
  if (r.mixedCase) list.push({ key: 'mixed',     label: 'Upper and lower case',           met: /[a-z]/.test(p) && /[A-Z]/.test(p) })
  if (r.numbers)   list.push({ key: 'numbers',   label: 'Contains a number',              met: /[0-9]/.test(p) })
  if (r.symbols)   list.push({ key: 'symbols',   label: 'Contains a symbol (!@#$…)',      met: /[^A-Za-z0-9]/.test(p) })

  return list
})

const metCount = computed(() => requirements.value.filter(r => r.met).length)
const allRequirementsMet = computed(() => metCount.value === requirements.value.length)

const submit = () => {
  form.put(route('password.change.update'), {
    // Removed current_password from the reset array
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<style scoped>
@keyframes slideUpCard {
  0% { opacity: 0; transform: translateY(30px); }
  100% { opacity: 1; transform: translateY(0); }
}
@keyframes catPopIn {
  0% { opacity: 0; transform: scale(0.8) translateY(20px); }
  100% { opacity: 1; transform: scale(1) translateY(0); }
}
@keyframes catFloatBounce {
  0%, 100% { transform: translateY(0) rotate(0deg); }
  50% { transform: translateY(-8px) rotate(-1.5deg); }
}
.animate-card-slide-up { animation: slideUpCard 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
.animate-cat-pop-in { animation: catPopIn 1.0s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both; }
.animate-cat-bounce { animation: catFloatBounce 3.5s ease-in-out infinite; }
</style>