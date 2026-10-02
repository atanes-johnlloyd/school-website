<template>
  <Head title="My Profile - Salawag LMS" />

  <div :class="[
    'min-h-screen flex bg-[#e8f5e9] dark:bg-[#232D26] font-[\'Inter\'] relative transition-colors duration-300',
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

      <div class="relative z-10 p-4 sm:p-6 md:p-8 space-y-6 flex-1 pb-16 max-w-4xl mx-auto w-full">

        <!-- HERO -->
        <div class="animate-fade-in-down w-full bg-[#004d08] dark:bg-[#152B1C] text-white rounded-3xl p-6 sm:p-8 shadow-md border border-[#003805] dark:border-[#3F4F43] relative overflow-hidden space-y-4">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-sheen pointer-events-none"></div>

          <div class="space-y-1 relative z-10">
            <div class="font-['Anton'] text-3xl sm:text-4xl md:text-5xl tracking-wide uppercase flex items-center gap-2 select-none flex-wrap">
              <span class="text-white">MY</span>
              <span class="animated-stroke-text">PROFILE</span>
            </div>
            <p class="text-xs sm:text-sm italic font-medium text-emerald-100/80">
              Manage your account and security settings.
            </p>
            <div class="flex items-center gap-2 pt-1 max-w-xl">
              <div class="h-[1.5px] w-full bg-white/40"></div>
              <span class="text-white text-xs animate-spin-slow">★</span>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row items-center gap-5 pt-3 relative z-10">
            <div class="relative group">
              <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl border-4 border-white/20 bg-white/10 overflow-hidden shrink-0 shadow-lg">
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

            <div class="space-y-1 text-center sm:text-left min-w-0">
              <h2 class="font-bold text-xl sm:text-2xl text-white tracking-tight leading-snug truncate">
                {{ user.name }}
              </h2>
              <p class="text-xs text-emerald-100/70 font-medium truncate">{{ user.email }}</p>
              <span class="inline-block bg-amber-400/20 text-amber-300 text-[11px] font-black uppercase tracking-wider px-3 py-0.5 rounded-full border border-amber-400/30">
                {{ roleLabel }}
              </span>
              <p v-if="avatarError" class="text-[11px] text-rose-300 font-semibold pt-1">{{ avatarError }}</p>
            </div>
          </div>
        </div>

        <!-- PERSONAL INFO -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-6 sm:p-8 shadow-sm space-y-6">
          <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
            <div>
              <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-none">Personal Information</h3>
              <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">Update your name and email address</p>
            </div>
          </div>

          <form @submit.prevent="submitProfile" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">Full Name</label>
                <input v-model="profileForm.name" type="text"
                  class="w-full bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC]" />
                <p v-if="profileForm.errors.name" class="text-rose-600 text-xs font-semibold">{{ profileForm.errors.name }}</p>
              </div>

              <div class="space-y-1.5">
                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">Email Address</label>
                <input v-model="profileForm.email" type="email"
                  class="w-full bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC]" />
                <p v-if="profileForm.errors.email" class="text-rose-600 text-xs font-semibold">{{ profileForm.errors.email }}</p>
              </div>
            </div>

            <div class="flex justify-end pt-2">
              <button type="submit" :disabled="profileForm.processing"
                class="bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-xs font-black px-6 py-3 rounded-2xl transition-all active:scale-95 disabled:opacity-50">
                {{ profileForm.processing ? 'Saving...' : 'Save Changes' }}
              </button>
            </div>
          </form>
        </div>

        <!-- ROLE DETAILS -->
        <div v-if="roleInfo.length"
          class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-6 sm:p-8 shadow-sm space-y-5">
          <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
            <div>
              <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-none">
                {{ roleLabel }} Details
              </h3>
              <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                Managed by the administration — read-only
              </p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div v-for="info in roleInfo" :key="info.label"
              class="flex items-start gap-3 bg-[#f5f7f2] dark:bg-[#232D26] rounded-2xl p-4 border border-slate-200/60 dark:border-[#3F4F43]">
              <div class="w-9 h-9 rounded-xl bg-white dark:bg-[#2D3A31] border border-slate-200 dark:border-[#3F4F43] flex items-center justify-center text-[#004d08] dark:text-[#86EFAC] shrink-0">
                <Icon :icon="info.icon" size="sm" />
              </div>
              <div class="min-w-0">
                <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 block">{{ info.label }}</span>
                <span class="text-sm font-bold text-slate-900 dark:text-white block mt-0.5 truncate">
                  {{ info.value || '—' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- PASSWORD -->
        <div class="animate-fade-slide-up rounded-3xl bg-[#fbfdf9] dark:bg-[#2D3A31] border border-slate-200/80 dark:border-[#3F4F43] p-6 sm:p-8 shadow-sm space-y-6">
          <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60 dark:border-[#3F4F43]">
            <div class="w-2.5 h-7 bg-[#004d08] dark:bg-[#86EFAC] rounded-full"></div>
            <div>
              <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight leading-none">Change Password</h3>
              <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">Use a strong, unique password</p>
            </div>
          </div>

          <form @submit.prevent="submitPassword" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="space-y-1.5">
                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">Current Password</label>
                <input v-model="passwordForm.current_password" type="password"
                  class="w-full bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC]" />
                <p v-if="passwordForm.errors.current_password" class="text-rose-600 text-xs font-semibold">{{ passwordForm.errors.current_password }}</p>
              </div>

              <div class="space-y-1.5">
                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">New Password</label>
                <input v-model="passwordForm.password" type="password"
                  class="w-full bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC]" />
                <p v-if="passwordForm.errors.password" class="text-rose-600 text-xs font-semibold">{{ passwordForm.errors.password }}</p>
              </div>

              <div class="space-y-1.5">
                <label class="block text-[11px] font-black uppercase tracking-wider text-slate-600 dark:text-slate-400">Confirm Password</label>
                <input v-model="passwordForm.password_confirmation" type="password"
                  class="w-full bg-[#f5f7f2] dark:bg-[#232D26] border border-slate-200/80 dark:border-[#3F4F43] rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#004d08] dark:focus:ring-[#86EFAC]" />
              </div>
            </div>

            <div class="flex justify-end pt-2">
              <button type="submit" :disabled="passwordForm.processing"
                class="bg-[#004d08] dark:bg-[#86EFAC] hover:bg-[#003805] text-white dark:text-[#232D26] text-xs font-black px-6 py-3 rounded-2xl transition-all active:scale-95 disabled:opacity-50">
                {{ passwordForm.processing ? 'Updating...' : 'Update Password' }}
              </button>
            </div>
          </form>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, useForm, usePage, router } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import Sidebart from '@/Components/Sidebart.vue'
import AdminSidebar from '@/Components/AdminSidebar.vue'
import navbartop from '@/Components/navbartop.vue'
import Icon from '@/Components/Icon.vue'

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
  profileForm.patch(route('profile.update'), { preserveScroll: true })
}

function submitPassword() {
  passwordForm.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => passwordForm.reset(),
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

function removeAvatar() {
  if (!confirm('Remove your profile picture?')) return
  router.delete(route('profile.avatar.destroy'), {
    preserveScroll: true,
  })
}
</script>

<style scoped>
.animated-stroke-text { color: transparent; -webkit-text-stroke: 1.5px #ffffff; }
@keyframes fadeInDown  { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(30px);  } to { opacity: 1; transform: translateY(0); } }
@keyframes sheenMove   { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
@keyframes spinSlow    { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.animate-fade-in-down  { animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-fade-slide-up { animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
.animate-sheen         { animation: sheenMove 4s ease-in-out infinite; }
.animate-spin-slow     { display: inline-block; animation: spinSlow 12s linear infinite; }
</style>