<template>
  <Head :title="`Search — ${query || 'Salawag LMS'}`" />

  <component :is="layout">
    <div class="space-y-6 pb-10 font-['Inter'] max-w-4xl mx-auto">

      <div class="rounded-2xl bg-white dark:bg-[#2D3A31] border border-slate-200/70 dark:border-[#3F4F43] p-6 space-y-2">
        <h1 class="text-lg font-black text-slate-900 dark:text-white">
          Search results for "{{ query }}"
        </h1>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          {{ totalCount }} result{{ totalCount === 1 ? '' : 's' }}
          across {{ groups.length }} group{{ groups.length === 1 ? '' : 's' }}.
        </p>
      </div>

      <div v-if="! groups.length && query.length >= 2"
        class="rounded-2xl bg-white dark:bg-[#2D3A31] border border-dashed border-slate-300 dark:border-[#3F4F43] p-12 text-center space-y-2">
        <p class="text-sm font-bold text-slate-700 dark:text-slate-200">No matches for "{{ query }}".</p>
        <p class="text-xs text-slate-500">Try a different keyword.</p>
      </div>

      <div v-else-if="query.length < 2"
        class="rounded-2xl bg-white dark:bg-[#2D3A31] border border-dashed border-slate-300 dark:border-[#3F4F43] p-12 text-center space-y-2">
        <p class="text-sm font-bold text-slate-700 dark:text-slate-200">Type at least 2 characters.</p>
        <p class="text-xs text-slate-500">Search across students, classes, tasks, quizzes, and lessons.</p>
      </div>

      <div v-for="g in groups" :key="g.label" class="space-y-2">
        <div class="flex items-center justify-between px-1">
          <h2 class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
            {{ g.label }}
          </h2>
          <span class="text-[10px] font-bold text-slate-400">{{ g.count }}</span>
        </div>

        <div class="rounded-2xl bg-white dark:bg-[#2D3A31] border border-slate-200/70 dark:border-[#3F4F43] divide-y divide-slate-100 dark:divide-[#3F4F43] overflow-hidden">
          <Link v-for="item in g.items" :key="item.id" :href="item.url"
            class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
            <p class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ item.title }}</p>
            <p v-if="item.meta" class="text-[11px] text-slate-500 dark:text-slate-400 truncate">{{ item.meta }}</p>
          </Link>
        </div>
      </div>

    </div>
  </component>
</template>

<script setup>
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import StudentLayout from '@/Layouts/StudentLayout.vue'

const props = defineProps({
  query:  { type: String, default: '' },
  groups: { type: Array,  default: () => [] },
})

const page  = usePage()
const roles = computed(() => page.props.auth?.roles || [])
const layout = computed(() => roles.value.includes('student') ? StudentLayout : AuthenticatedLayout)

const totalCount = computed(() => props.groups.reduce((sum, g) => sum + g.count, 0))
</script>