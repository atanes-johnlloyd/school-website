<template>
  <Head title="Create New Password - Salawag LMS" />

  <!-- Full Screen Background Canvas -->
  <div 
    class="min-h-screen w-full flex items-center justify-center p-4 sm:p-6 bg-cover bg-center bg-no-repeat relative font-['Inter'] overflow-hidden"
    :style="{ backgroundImage: `url(${bgImg})` }"
  >
    <!-- Centered Card Container with Entrance Animation -->
    <div class="relative w-full max-w-lg animate-card-slide-up">
      
      <!-- Sneaking Cat Mascot Overlapping Top-Left Edge of Card -->
      <div class="absolute -left-12 sm:-left-16 -top-12 sm:-top-16 z-20 pointer-events-none animate-cat-pop-in">
        <img 
          :src="catImg" 
          alt="Salawag SHS Mascot" 
          class="w-36 sm:w-44 md:w-48 h-auto object-contain drop-shadow-2xl animate-cat-bounce"
        />
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
            Create New Password
          </h1>
          <p class="text-xs sm:text-sm text-slate-500 font-normal mt-2 max-w-xs">
            Your new password must be at least 8 characters and include numbers.
          </p>
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

              <button 
                type="button" 
                @click="showPassword = !showPassword"
                class="absolute right-3.5 text-slate-400 hover:text-slate-600 focus:outline-none"
              >
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
              Confirm Password
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

              <button 
                type="button" 
                @click="showConfirmPassword = !showConfirmPassword"
                class="absolute right-3.5 text-slate-400 hover:text-slate-600 focus:outline-none"
              >
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

          <!-- PASSWORD STRENGTH INDICATOR -->
          <div class="pt-1">
            <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
              <span class="text-slate-500">Password Strength</span>
              <span class="text-[#005506] font-bold">{{ strengthLabel }}</span>
            </div>
            <div class="grid grid-cols-3 gap-1.5 h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
              <div :class="strengthScore >= 1 ? 'bg-[#005506]' : 'bg-transparent'" class="h-full transition-colors duration-300"></div>
              <div :class="strengthScore >= 2 ? 'bg-[#005506]' : 'bg-transparent'" class="h-full transition-colors duration-300"></div>
              <div :class="strengthScore >= 3 ? 'bg-[#005506]' : 'bg-transparent'" class="h-full transition-colors duration-300"></div>
            </div>
          </div>

          <!-- CHANGE PASSWORD BUTTON -->
          <div class="pt-3">
            <button 
              type="submit"
              :disabled="form.processing"
              class="w-full py-3.5 bg-[#005506] hover:bg-[#004204] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform active:scale-[0.99] disabled:opacity-70 disabled:cursor-not-allowed"
            >
              Change Password
            </button>
          </div>

        </form>

        <!-- FOOTER COPYRIGHT -->
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
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'

import bgImg from '../../../assets/img/login_background.png'
import catImg from '../../../assets/img/cat-sneaking.png'

const props = defineProps({
  email: {
    type: String,
    required: true,
  },
  token: {
    type: String,
    required: true,
  },
})

const showPassword = ref(false)
const showConfirmPassword = ref(false)

const strengthScore = ref(0)
const strengthLabel = ref('Weak')

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

const assessStrength = () => {
  const val = form.password
  if (!val) {
    strengthScore.value = 0
    strengthLabel.value = 'Weak'
    return
  }

  let score = 0
  if (val.length >= 8) score++
  if (/[0-9]/.test(val)) score++
  if (/[A-Z]/.test(val) || /[^A-Za-z0-9]/.test(val)) score++

  strengthScore.value = score
  if (score === 1) strengthLabel.value = 'Weak'
  else if (score === 2) strengthLabel.value = 'Medium'
  else if (score >= 3) strengthLabel.value = 'Strong'
}

const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<style scoped>

@keyframes slideUpCard {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes catPopIn {
  0% {
    opacity: 0;
    transform: scale(0.8) translateY(20px);
  }
  100% {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

@keyframes catFloatBounce {
  0%, 100% {
    transform: translateY(0) rotate(0deg);
  }
  50% {
    transform: translateY(-8px) rotate(-1.5deg);
  }
}

.animate-card-slide-up {
  animation: slideUpCard 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
}

.animate-cat-pop-in {
  animation: catPopIn 1.0s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
}

.animate-cat-bounce {
  animation: catFloatBounce 3.5s ease-in-out infinite;
}
</style>