<template>

    <Head title="Log in - Salawag LMS" />

    <!-- Full Screen Background Canvas -->
    <div class="min-h-screen w-full flex items-center justify-center p-4 sm:p-6 bg-cover bg-center bg-no-repeat relative font-['Inter'] overflow-hidden"
        :style="{ backgroundImage: `url(${bgImg})` }">
        <!-- Centered Card Container with Entrance Animation -->
        <div class="relative w-full max-w-lg animate-card-slide-up">

            <!-- Mascot Container with Entrance & Continuous Bounce Animations -->
            <div
                class="absolute -right-48 sm:-right-60 lg:-right-80 bottom-12 sm:bottom-20 lg:bottom-28 z-20 pointer-events-none animate-cat-pop-in">
                <img :src="catImg" alt="Salawag SHS Login Mascot"
                    class="w-60 sm:w-80 md:w-[420px] lg:w-[480px] h-auto object-contain drop-shadow-2xl animate-cat-bounce" />
            </div>

            <!-- Main Login Card -->
            <div
                class="relative z-10 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 sm:p-10 border border-white/40 text-left">

                <!-- Status Banner -->
                <div v-if="status"
                    class="mb-4 text-sm font-medium text-emerald-700 bg-emerald-50 p-3 rounded-lg border border-emerald-200">
                    {{ status }}
                </div>

                <!-- Header: School Logo & Portal Title -->
                <div class="flex flex-col items-center text-center mb-8">
                    <img :src="logoImg" alt="Salawag Senior High School Logo"
                        class="w-20 h-20 sm:w-24 sm:h-24 object-contain mb-3 drop-shadow-sm" />
                    <h1 class="text-2xl sm:text-3xl font-bold text-[#005506] tracking-tight">
                        Salawag Senior High School
                    </h1>
                    <p class="text-sm font-medium text-slate-400 mt-0.5">
                        School Portal
                    </p>
                </div>

                <!-- Form Elements -->
                <form @submit.prevent="submit" class="space-y-5">

                    <!-- EMAIL FIELD -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            EMAIL
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3.5 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input id="email" v-model="form.email" type="email" required autofocus
                                autocomplete="username" placeholder="Enter your email"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:border-transparent transition-all" />
                        </div>
                        <p v-if="form.errors.email" class="text-xs text-red-600 font-medium pl-1">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- PASSWORD FIELD -->
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            PASSWORD
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3.5 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>

                            <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'"
                                required autocomplete="current-password" placeholder="Enter your password"
                                class="w-full pl-11 pr-11 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:border-transparent transition-all" />

                            <!-- Show/Hide Password Toggle -->
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3.5 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <p v-if="form.errors.password" class="text-xs text-red-600 font-medium pl-1">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- REMEMBER ME & FORGOT PASSWORD -->
                    <div class="flex items-center justify-between text-xs font-semibold pt-1">
                        <label class="flex items-center gap-2 text-slate-600 cursor-pointer select-none">
                            <input v-model="form.remember" type="checkbox"
                                class="w-4 h-4 rounded border-slate-300 text-[#005506] focus:ring-[#005506]" />
                            <span>Remember me</span>
                        </label>

                        <Link v-if="canResetPassword" :href="route('password.request')"
                            class="text-[#005506] hover:underline transition-colors">
                            Forgot password?
                        </Link>
                    </div>

                    <!-- LOG IN BUTTON -->
                    <div class="pt-2">
                        <button type="submit" :disabled="form.processing"
                            class="w-full py-3.5 bg-[#005506] hover:bg-[#004204] text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform active:scale-[0.99] disabled:opacity-70 disabled:cursor-not-allowed">
                            Log in
                        </button>
                    </div>

                    <!-- BACK TO HOME -->
                    <div class="text-center pt-2">
                        <Link href="/"
                            class="text-xs font-bold text-slate-600 hover:text-[#005506] underline underline-offset-4 transition-colors">
                            Back to home
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
import catImg from '@/../assets/img/cat_login.png'

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
})

const showPassword = ref(false)

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<style scoped>

/* Entrance animations for pulling up the page */
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
        transform: scale(0.8) translateY(30px);
    }

    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.animate-card-slide-up {
    animation: slideUpCard 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
}

.animate-cat-pop-in {
    animation: catPopIn 1.0s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
}

/* Continuous Soft Floating / Bouncing Animation */
@keyframes catFloatBounce {

    0%,
    100% {
        transform: translateY(0) rotate(0deg);
    }

    50% {
        transform: translateY(-12px) rotate(1.5deg);
    }
}

.animate-cat-bounce {
    animation: catFloatBounce 3.5s ease-in-out infinite;
}
</style>