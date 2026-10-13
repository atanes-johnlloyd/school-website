<template>
  <Head title="My Profile - Salawag LMS" />

  <div :class="[
    'min-h-screen flex font-[\'Inter\'] relative transition-colors duration-300',
    pageBg,
    `text-scale-${fontSizeMode}`
  ]">
    <Sidebar v-if="isStudent" :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />
    <Sidebart v-else-if="isTeacher" :isOpen="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />
    <AdminSidebar v-else-if="isAdmin" />

    <main class="flex-1 relative overflow-y-auto min-h-screen flex flex-col justify-between w-full min-w-0">
      <navbartop
        searchPlaceholder="Search..."
        @open-sidebar="isSidebarOpen = true"
        @font-size-changed="(size) => fontSizeMode = size"
      />

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-4 sm:space-y-6 flex-1 pb-16 max-w-4xl mx-auto w-full">

        <!-- ═══════════════════════════════════════════════════════
             HERO / IDENTITY
             ═══════════════════════════════════════════════════════ -->
        <div v-observe
          class="anim-fade-down relative w-full rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg border border-[#006907]/20 dark:border-none p-4 sm:p-8 md:p-10 space-y-4"
          :style="{
            backgroundImage: 'linear-gradient(rgba(0, 77, 8, 0.94), rgba(0, 77, 8, 0.94)), url(' + heroBg + ')',
            backgroundSize: 'cover',
            backgroundPosition: 'center',
          }">

          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <!-- Title block -->
          <div class="relative z-10 space-y-1">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
              <span class="text-white">MY</span>
              <span class="animated-stroke-text">PROFILE</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              Manage your identity, security, and personal details.
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs animate-spin-slow">★</span>
            </div>
          </div>

          <!-- Avatar + identity row -->
          <div class="flex flex-col sm:flex-row items-center gap-5 pt-3 relative z-10">
            <div class="relative group shrink-0">
              <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl border-4 border-white/20 bg-white/10 overflow-hidden shadow-lg">
                <img v-if="user.avatar_url" :src="user.avatar_url" :alt="user.name" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full bg-emerald-800 text-white font-black flex items-center justify-center text-3xl uppercase">
                  {{ initials }}
                </div>
              </div>

              <div class="absolute -bottom-2 -right-2 flex items-center gap-1">
                <label class="bg-white dark:bg-[#2D3A31] hover:bg-slate-100 dark:hover:bg-[#3F4F43] text-[#004d08] dark:text-[#86EFAC] rounded-full p-2 cursor-pointer shadow-md border border-white/20">
                  <input type="file" class="hidden" accept="image/png,image/jpeg,image/webp" @change="uploadAvatar" />
                  <Icon icon="upload" size="sm" />
                </label>
                <button v-if="user.avatar_url" @click="removeAvatar"
                  class="bg-rose-500 hover:bg-rose-600 text-white rounded-full p-2 cursor-pointer shadow-md">
                  <Icon icon="trash" size="sm" />
                </button>
              </div>
            </div>

            <div class="space-y-1 text-center sm:text-left min-w-0 flex-1">
              <h2 class="font-bold text-xl sm:text-2xl text-white tracking-tight leading-snug truncate">
                {{ user.name }}
              </h2>
              <p class="text-xs text-emerald-100/70 font-medium truncate">{{ user.email }}</p>
              <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-1">
                <span class="inline-block bg-[#F9C20C] text-[#2C3E2D] text-[11px] font-black uppercase tracking-wider px-3 py-0.5 rounded-full shadow-sm">
                  {{ roleLabel }}
                </span>
                <span v-if="user.created_at" class="text-[11px] text-emerald-100/70 font-semibold">
                  Member since {{ user.created_at }}
                </span>
              </div>
              <p v-if="avatarError" class="text-[11px] text-rose-300 font-semibold pt-1">{{ avatarError }}</p>
            </div>
          </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════
             ACCOUNT SUMMARY (read-only metadata)
             ═══════════════════════════════════════════════════════ -->
        <div v-observe style="animation-delay: 100ms;"
          class="anim-slide-up grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-1.5">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Account Status</span>
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            </div>
            <p class="text-sm font-extrabold text-slate-900 dark:text-white">Active</p>
            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">All systems normal</p>
          </div>

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-1.5">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Email</span>
              <Icon icon="mail" size="xs" class="text-slate-400" />
            </div>
            <p class="text-sm font-extrabold text-slate-900 dark:text-white truncate">
              {{ user.email_verified_at ? 'Verified' : 'Unverified' }}
            </p>
            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium truncate">{{ user.email }}</p>
          </div>

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-1.5">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">2FA</span>
              <Icon icon="shield" size="xs" class="text-slate-400" />
            </div>
            <p class="text-sm font-extrabold text-slate-900 dark:text-white">
              {{ needsTwoFactor ? 'Required' : 'Not Required' }}
            </p>
            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">
              {{ needsTwoFactor ? 'On every login' : 'Password only' }}
            </p>
          </div>

          <div class="bg-white dark:bg-[#2D3A31] rounded-2xl sm:rounded-3xl p-4 shadow-sm border border-slate-200/60 dark:border-[#3F4F43] space-y-1.5">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Role</span>
              <Icon icon="badge-check" size="xs" class="text-slate-400" />
            </div>
            <p class="text-sm font-extrabold text-slate-900 dark:text-white truncate">{{ roleLabel }}</p>
            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Assigned by admin</p>
          </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════
             PERSONAL INFORMATION
             ═══════════════════════════════════════════════════════ -->
        <div v-observe style="animation-delay: 200ms;"
          class="anim-slide-up rounded-2xl sm:rounded-3xl bg-white dark:bg-[#2D3A31] border border-slate-200/60 dark:border-[#3F4F43] p-5 sm:p-6 shadow-sm space-y-5">

          <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
            <div>
              <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white tracking-tight leading-none">
                Personal Information
              </h3>
              <p class="text-[11px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                Update your display name and email address
              </p>
            </div>
          </div>

          <form @submit.prevent="submitProfile" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">
                  Full Name
                </label>
                <input
                  v-model="profileForm.name"
                  type="text"
                  pattern="^[\p{L}\s'\-\.]+$"
                  title="Letters, spaces, hyphens, apostrophes, and periods only"
                  class="w-full bg-slate-50 dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-sm font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:border-transparent transition-all"
                  placeholder="Enter your full name"
                />
                <p class="text-[10px] text-slate-500 dark:text-slate-400">
                  Letters, spaces, hyphens, apostrophes, and periods only.
                </p>
                <p v-if="profileForm.errors.name" class="text-rose-600 text-xs font-semibold">
                  {{ profileForm.errors.name }}
                </p>
              </div>

              <div class="space-y-1.5">
                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">
                  Email Address
                </label>
                <input
                  v-model="profileForm.email"
                  type="email"
                  class="w-full bg-slate-50 dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-sm font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:border-transparent transition-all"
                  placeholder="you@example.com"
                />
                <p v-if="profileForm.errors.email" class="text-rose-600 text-xs font-semibold">
                  {{ profileForm.errors.email }}
                </p>
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
              <span v-if="profileForm.recentlySuccessful"
                class="text-xs font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-1.5">
                <Icon icon="check-circle" size="xs" /> Saved
              </span>
              <button
                type="submit"
                :disabled="profileForm.processing || !profileForm.isDirty"
                class="bg-[#005506] hover:bg-[#004204] disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-black uppercase tracking-wider px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all active:scale-95">
                {{ profileForm.processing ? 'Saving…' : 'Save Changes' }}
              </button>
            </div>
          </form>
        </div>

        <!-- ═══════════════════════════════════════════════════════
             ROLE DETAILS (read-only, admin-managed)
             ═══════════════════════════════════════════════════════ -->
        <div v-if="roleInfo.length" v-observe style="animation-delay: 300ms;"
          class="anim-slide-up rounded-2xl sm:rounded-3xl bg-white dark:bg-[#2D3A31] border border-slate-200/60 dark:border-[#3F4F43] p-5 sm:p-6 shadow-sm space-y-5">

          <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
            <div>
              <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white tracking-tight leading-none">
                {{ roleLabel }} Details
              </h3>
              <p class="text-[11px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                Managed by administration — contact your admin to make changes
              </p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div v-for="info in roleInfo" :key="info.label"
              class="flex items-start gap-3 bg-[#F9F7F1] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/60 dark:border-[#3F4F43]">
              <div class="w-9 h-9 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
                <Icon :icon="info.icon" size="sm" />
              </div>
              <div class="min-w-0">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 block">
                  {{ info.label }}
                </span>
                <span class="text-sm font-bold text-slate-900 dark:text-white block mt-0.5 truncate">
                  {{ info.value || '—' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════
             SECURITY / CHANGE PASSWORD
             ═══════════════════════════════════════════════════════ -->
        <div v-observe style="animation-delay: 400ms;"
          class="anim-slide-up rounded-2xl sm:rounded-3xl bg-white dark:bg-[#2D3A31] border border-slate-200/60 dark:border-[#3F4F43] p-5 sm:p-6 shadow-sm space-y-5">

          <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
            <div>
              <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white tracking-tight leading-none">
                Security
              </h3>
              <p class="text-[11px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                Use a strong, unique password that you don't use elsewhere
              </p>
            </div>
          </div>

          <form @submit.prevent="submitPassword" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="space-y-1.5">
                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">
                  Current Password
                </label>
                <input
                  v-model="passwordForm.current_password"
                  type="password"
                  autocomplete="current-password"
                  class="w-full bg-slate-50 dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-sm font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:border-transparent transition-all"
                  placeholder="••••••••"
                />
                <p v-if="passwordForm.errors.current_password" class="text-rose-600 text-xs font-semibold">
                  {{ passwordForm.errors.current_password }}
                </p>
              </div>

              <div class="space-y-1.5">
                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">
                  New Password
                </label>
                <input
                  v-model="passwordForm.password"
                  type="password"
                  autocomplete="new-password"
                  class="w-full bg-slate-50 dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-sm font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:border-transparent transition-all"
                  placeholder="••••••••"
                />
                <p class="text-[10px] text-slate-500 dark:text-slate-400">
                  Must differ from current password.
                </p>
                <p v-if="passwordForm.errors.password" class="text-rose-600 text-xs font-semibold">
                  {{ passwordForm.errors.password }}
                </p>
              </div>

              <div class="space-y-1.5">
                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">
                  Confirm Password
                </label>
                <input
                  v-model="passwordForm.password_confirmation"
                  type="password"
                  autocomplete="new-password"
                  class="w-full bg-slate-50 dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-xl px-4 py-3 text-sm font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005506] focus:border-transparent transition-all"
                  placeholder="••••••••"
                />
                <p v-if="passwordForm.errors.password_confirmation" class="text-rose-600 text-xs font-semibold">
                  {{ passwordForm.errors.password_confirmation }}
                </p>
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
              <span v-if="passwordForm.recentlySuccessful"
                class="text-xs font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-1.5">
                <Icon icon="check-circle" size="xs" /> Updated
              </span>
              <button
                type="submit"
                :disabled="passwordForm.processing || !passwordForm.password"
                class="bg-[#005506] hover:bg-[#004204] disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-black uppercase tracking-wider px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all active:scale-95">
                {{ passwordForm.processing ? 'Updating…' : 'Update Password' }}
              </button>
            </div>
          </form>
        </div>

        <!-- ═══════════════════════════════════════════════════════
             DANGER ZONE
             ═══════════════════════════════════════════════════════ -->
        <div v-observe style="animation-delay: 500ms;"
          class="anim-slide-up rounded-2xl sm:rounded-3xl bg-white dark:bg-[#2D3A31] border border-rose-200/60 dark:border-rose-900/40 p-5 sm:p-6 shadow-sm space-y-5">

          <div class="flex items-center gap-3 pb-3 border-b border-rose-200/60 dark:border-rose-900/40">
            <div class="w-2.5 h-7 bg-rose-500 dark:bg-rose-400 rounded-full"></div>
            <div>
              <h3 class="text-sm sm:text-base font-extrabold text-rose-700 dark:text-rose-400 tracking-tight leading-none">
                Danger Zone
              </h3>
              <p class="text-[11px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                Irreversible and destructive actions
              </p>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-rose-50/60 dark:bg-rose-950/20 rounded-2xl p-4 border border-rose-200/60 dark:border-rose-900/40">
            <div class="space-y-0.5">
              <p class="text-sm font-extrabold text-slate-900 dark:text-white">Delete this account</p>
              <p class="text-[11px] text-slate-600 dark:text-slate-400 font-medium">
                All your data will be permanently removed. This cannot be undone.
              </p>
            </div>
            <button type="button" @click="confirmDeleteAccount"
              class="shrink-0 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all active:scale-95">
              Delete Account
            </button>
          </div>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { confirmAction, showError } from '@/Pages/useSweetAlert'
import { ref, computed } from 'vue'
import { Head, useForm, usePage, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import Sidebar from '@/Components/Sidebar.vue'
import Sidebart from '@/Components/Sidebart.vue'
import AdminSidebar from '@/Components/AdminSidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'

import heroBg from '@/../assets/img/local/dashboard.png'

const props = defineProps({
  roleLabel: { type: String, default: 'User' },
  roleInfo:  { type: Array,  default: () => [] },
})

const page = usePage()
const user = computed(() => page.props.auth?.user || {})
const roles = computed(() => page.props.auth?.roles || [])

const isAdmin   = computed(() => roles.value.includes('admin'))
const isTeacher = computed(() => roles.value.includes('teacher'))
const isStudent = computed(() => roles.value.includes('student'))

const needsTwoFactor = computed(() => isAdmin.value || isTeacher.value)

const initials = computed(() => {
  const name = user.value?.name || 'U'
  const parts = name.trim().split(/\s+/)
  if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  return name.substring(0, 2).toUpperCase()
})

const isSidebarOpen = ref(false)
const fontSizeMode = ref('base')
const avatarError = ref('')

const profileForm = useForm({
  name: user.value?.name || '',
  email: user.value?.email || '',
})

const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

function submitProfile() {
  profileForm.patch(route('profile.update'), {
    preserveScroll: true,
    onSuccess: () => profileForm.reset(),
  })
}

function submitPassword() {
  passwordForm.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => passwordForm.reset(),
    onError: () => passwordForm.reset('password', 'password_confirmation', 'current_password'),
  })
}

function uploadAvatar(event) {
  const file = event.target.files[0]
  if (!file) return

  avatarError.value = ''

  if (file.size > 2 * 1024 * 1024) {
    avatarError.value = 'Image must be smaller than 2 MB.'
    event.target.value = ''
    return
  }

  const allowed = ['image/jpeg', 'image/png', 'image/webp']
  if (!allowed.includes(file.type)) {
    avatarError.value = 'Only JPG, PNG, or WebP allowed.'
    event.target.value = ''
    return
  }

  const form = useForm({ avatar: file })
  form.post(route('profile.avatar.update'), {
    preserveScroll: true,
    forceFormData: true,
    onError: (errors) => {
      avatarError.value = errors.avatar || 'Upload failed.'
    },
    onFinish: () => { event.target.value = '' },
  })
}

async function removeAvatar() {
  if (!await confirmAction('Remove your profile picture?')) return
  router.delete(route('profile.avatar.destroy'), { preserveScroll: true })
}

const pageBg = computed(() => {
  if (isStudent.value) return 'bg-[#e8f5e9]'
  return 'bg-[#F9F7F1]'
})

async function confirmDeleteAccount() {
  const result = await Swal.fire({
    title: 'Delete your account?',
    html: '<p class="text-sm">This action <strong>cannot be undone</strong>. All your data will be permanently removed.</p><p class="text-xs text-slate-500 mt-3">Enter your password to confirm:</p>',
    input: 'password',
    inputPlaceholder: 'Your password',
    inputAttributes: { autocapitalize: 'off', autocomplete: 'current-password' },
    showCancelButton: true,
    confirmButtonText: 'Yes, delete my account',
    confirmButtonColor: '#e11d48',
    cancelButtonText: 'Cancel',
    cancelButtonColor: '#64748b',
    reverseButtons: true,
    inputValidator: (value) => {
      if (!value) return 'Password is required.'
      return null
    },
  })

  if (!result.isConfirmed) return

  router.delete(route('profile.destroy'), {
    data: { password: result.value },
    preserveScroll: true,
    onError: (errors) => {
      showError(errors.password || 'Could not delete account.')
    },
  })
}

const vObserve = {
  mounted(el) {
    el.classList.add('not-visible')
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) el.classList.add('is-animated')
      else el.classList.remove('is-animated')
    }, { threshold: 0.1 })
    observer.observe(el)
  },
}
</script>

<style scoped>
.animated-stroke-text {
  color: transparent;
  -webkit-text-stroke: 1.5px #ffffff;
}

@keyframes fadeInDown  { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideUpFade { from { opacity: 0; transform: translateY(25px); }  to { opacity: 1; transform: translateY(0); } }
@keyframes sheenMove   { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
@keyframes spinSlow    { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

.not-visible { opacity: 0; }
.is-animated.anim-fade-down  { animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.is-animated.anim-slide-up   { animation: slideUpFade 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-sheen               { animation: sheenMove 4s ease-in-out infinite; }
.animate-spin-slow           { display: inline-block; animation: spinSlow 12s linear infinite; }
</style>