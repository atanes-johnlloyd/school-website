<template>
  <Head title="Password Recovery - Salawag LMS" />

  <!-- Full Screen Background Canvas -->
  <div 
    class="min-h-screen w-full flex items-center justify-center p-4 sm:p-6 bg-cover bg-center bg-no-repeat relative font-['Inter'] overflow-hidden"
    :style="{ backgroundImage: `url(${bgImg})` }"
  >
    <!-- Centered Card Wrapper with Entrance Animation -->
    <div class="relative w-full max-w-lg animate-card-slide-up">
      
      <!-- Mascot Head Overlapping Top-Left Edge of Card -->
      <div class="absolute -left-10 sm:-left-12 -top-12 sm:-top-16 z-20 pointer-events-none animate-cat-pop-in">
        <img 
          :src="catImg" 
          alt="Salawag SHS Mascot" 
          class="w-32 sm:w-40 md:w-44 h-auto object-contain drop-shadow-xl animate-cat-bounce"
        />
      </div>

      <!-- Main Recovery Card -->
      <div class="relative z-10 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 sm:p-10 border border-white/40 text-left">
        
        <!-- Status Banner (e.g., Reset Link Sent) -->
        <div v-if="status" class="mb-4 text-sm font-medium text-emerald-700 bg-emerald-50 p-3 rounded-lg border border-emerald-200">
          {{ status }}
        </div>

        <!-- Header: School Logo & Title -->
        <div class="flex flex-col items-center text-center mb-8">
          <img 
            :src="logoImg" 
            alt="Salawag Senior High School Logo" 
            class="w-20 h-20 sm:w-24 sm:h-24 object-contain mb-3 drop-shadow-sm scale-150" 
          />
          <h1 class="text-2xl sm:text-3xl font-bold text-[#005506] tracking-tight">
            Salawag Senior High School
          </h1>
          <p class="text-sm font-medium text-slate-400 mt-0.5">
            Password Recovery
          </p>
        </div>

        <!-- Form Elements -->
        <form @submit.prevent="submit" class="space-y-6">
          
          <!-- EMAIL FIELD -->
          <div class="space-y-1.5">
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
              EMAIL
            </label>
            <div class="relative flex items-center">
              <!-- Leading @ Icon -->
              <span class="absolute left-3.5 text-slate-400 font-semibold text-base select-none">
                @
              </span>

              <input 
                id="email"
                v-model="form.email"
                :type='text'
                required
                autofocus
                placeholder="Enter your email" 
                class="w-full pl-10 pr-11 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:border-transparent transition-all"
              />
            </div>
            <p v-if="form.errors.email" class="text-xs text-red-600 font-medium pl-1">
              {{ form.errors.email }}
            </p>
          </div>

          <!-- SEND RECOVERY BUTTON -->
          <div class="pt-2">
            <button 
              type="submit"
              :disabled="form.processing"
              class="w-full py-3.5 bg-[#005506] hover:bg-[#004204] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform active:scale-[0.99] disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
              <span>Send Recovery</span>
            </button>
          </div>

          <!-- BACK TO LOGIN / HOME -->
          <div class="text-center pt-2">
            <Link 
              href="/login" 
              class="text-xs font-bold text-slate-600 hover:text-[#005506] underline underline-offset-4 transition-colors"
            >
              Back to log in
            </Link>
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
import { Head, Link, useForm } from '@inertiajs/vue3'

import bgImg from '@/../assets/img/login_background.png'
import logoImg from '@/../assets/img/logo_trans_big.png'
import catImg from '@/../assets/img/cat-mobile-track.png'

defineProps({
  status: {
    type: String,
  },
})

const showEmail = ref(false)

const form = useForm({
  email: '',
})

const submit = () => {
  form.post(route('password.email'))
}
</script>

<style scoped>

@keyframes slideUpCard {
  0% {
    opacity: 0;
    transform: translateY(40px);
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