<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    classroom: Object,
    stats: Object,   // optional — see controller note below
})
</script>

<template>
    <StudentLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ classroom.subject }}</h2>
                    <p class="text-sm text-gray-500">
                        {{ classroom.section }} · {{ classroom.teacher }}
                    </p>
                </div>
                <Link :href="route('student.classes.index')"
                      class="text-sm text-gray-500 hover:text-gray-700">
                    ← Back to classes
                </Link>
            </div>
        </template>

        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex gap-6">
                <Link
                    :href="route('student.classes.assignments.index', classroom.id)"
                    class="pb-3 text-sm font-medium border-b-2 border-emerald-600 text-emerald-600"
                >
                    Assignments
                </Link>
                <button disabled class="pb-3 text-sm font-medium text-gray-400 cursor-not-allowed">
                    Materials (soon)
                </button>
                <button disabled class="pb-3 text-sm font-medium text-gray-400 cursor-not-allowed">
                    Grades (soon)
                </button>
            </nav>
        </div>

        <!-- Overview card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-800 mb-2">Welcome to {{ classroom.subject }}</h3>
            <p class="text-sm text-gray-500 mb-4">
                Taught by {{ classroom.teacher }} · {{ classroom.section }}
            </p>
            <Link
                :href="route('student.classes.assignments.index', classroom.id)"
                class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-4 py-2 rounded-md"
            >
                View Assignments →
            </Link>
        </div>
    </StudentLayout>
</template>