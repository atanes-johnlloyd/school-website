<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    classes: Array,
    activeTerm: String,
})
</script>

<template>
    <StudentLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                My Classes
                <span v-if="activeTerm" class="text-sm text-gray-500 font-normal">— {{ activeTerm }}</span>
            </h2>
        </template>

        <div v-if="!classes.length" class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
            You are not enrolled in any classes yet.
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <Link
                v-for="classroom in classes"
                :key="classroom.id"
                :href="route('student.classes.show', classroom.id)"
                class="block bg-white rounded-lg shadow hover:shadow-md transition p-5 border border-gray-200"
            >
                <h3 class="font-semibold text-gray-900">{{ classroom.subject }}</h3>
                <p class="text-sm text-gray-500">{{ classroom.subject_code }}</p>
                <div class="mt-4 text-sm text-gray-600 space-y-1">
                    <p><span class="text-gray-400">Section:</span> {{ classroom.section }}</p>
                    <p><span class="text-gray-400">Teacher:</span> {{ classroom.teacher }}</p>
                </div>
            </Link>
        </div>
    </StudentLayout>
</template>