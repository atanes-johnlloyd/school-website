<script setup>
import TeacherLayout from '@/Layouts/TeacherLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    stats: Object,
    activeTerm: String,
})
</script>

<template>
    <TeacherLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                Teacher Dashboard
                <span v-if="activeTerm" class="text-sm text-gray-500 font-normal">— {{ activeTerm }}</span>
            </h2>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <Link
                :href="route('teacher.classes.index')"
                class="bg-white rounded-lg shadow p-6 hover:shadow-md transition border border-gray-200"
            >
                <p class="text-sm text-gray-500">My Classes</p>
                <p class="text-3xl font-bold text-indigo-600 mt-2">{{ stats.classes }}</p>
            </Link>
        </div>

        <div class="text-center">
            <Link :href="route('teacher.classes.index')"
                  class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-md text-sm font-medium">
                View My Classes →
            </Link>
        </div>
    </TeacherLayout>
</template>