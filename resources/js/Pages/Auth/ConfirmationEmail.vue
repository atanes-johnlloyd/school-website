<template>
  <Head title="Check Your Email - Salawag LMS" />

  <!-- Full Screen Background Canvas -->
  <div 
    class="min-h-screen w-full flex items-center justify-center p-4 sm:p-6 bg-cover bg-center bg-no-repeat relative font-['Inter'] overflow-hidden"
    :style="{ backgroundImage: `url(${bgImg})` }"
  >
    <!-- Centered Card Container with Entrance Animation -->
    <div class="relative w-full max-w-lg animate-card-slide-up">

      <!-- Main Confirmation Card -->
      <div class="relative z-10 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 sm:p-10 border border-white/40 text-center">
        
        <!-- Status Notification Banner -->
        <div 
          v-if="status === 'verification-link-sent' || status === 'link-sent'" 
          class="mb-6 text-xs font-semibold text-emerald-800 bg-emerald-50 p-3 rounded-xl border border-emerald-200"
        >
          A new verification link has been sent to your email address.
        </div>

        <!-- Green Checkmark Badge Icon -->
        <div class="flex justify-center mb-5">
          <div class="w-14 h-14 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-[#005506] shadow-sm">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
          </div>
        </div>

        <!-- Card Title & Paragraph -->
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-3">
          Check Your Email
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 font-normal leading-relaxed max-w-sm mx-auto mb-8">
          We've sent a password recovery link to your registered email address. Please check your inbox and follow the instructions.
        </p>

        <!-- Action Elements -->
        <div class="space-y-4">
          
          <!-- OPEN EMAIL APP BUTTON -->
          <a 
            href="mailto:" 
            class="w-full py-3.5 bg-[#005506] hover:bg-[#004204] text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform active:scale-[0.99] inline-block text-center"
          >
            Open Email App
          </a>

          <!-- RESEND LINK PROMPT -->
          <form @submit.prevent="resendLink" class="pt-2">
            <p class="text-xs font-medium text-slate-500">
              Didn't receive the email? 
              <button 
                type="submit" 
                :disabled="form.processing"
                class="font-bold text-[#005506] hover:underline focus:outline-none disabled:opacity-50"
              >
                Resend
              </button>
            </p>
          </form>

          <!-- BACK TO SIGN IN -->
          <div class="pt-2">
            <Link 
              href="/login" 
              class="text-xs font-bold text-slate-600 hover:text-[#005506] underline underline-offset-4 transition-colors"
            >
              Back to Sign In
            </Link>
          </div>

        </div>

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
import { Head, Link, useForm } from '@inertiajs/vue3'

import bgImg from '@/../assets/img/login_background.png'

const props = defineProps({
  status: {
    type: String,
  },
})

const form = useForm({})

const resendLink = () => {
  form.post(route('verification.send'))
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

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

.animate-card-slide-up {
  animation: slideUpCard 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
}
</style>