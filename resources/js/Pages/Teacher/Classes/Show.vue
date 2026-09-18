<script setup>
import TeacherLayout from '@/Layouts/TeacherLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    classroom: Object,
    students: Array,
})
</script>

<template>
    <TeacherLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ classroom.subject }}</h2>
                    <p class="text-sm text-gray-500">
                        {{ classroom.section }} · {{ classroom.term }}
                    </p>
                </div>
                <Link :href="route('teacher.classes.index')"
                      class="text-sm text-gray-500 hover:text-gray-700">
                    ← Back to classes
                </Link>
            </div>
        </template>

        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex gap-6">
                <button class="pb-3 text-sm font-medium border-b-2 border-indigo-600 text-indigo-600">
                    Students ({{ students.length }})
                </button>

                <Link
                    :href="route('teacher.classes.assignments.index', classroom.id)"
                    class="pb-3 text-sm font-medium text-gray-600 hover:text-indigo-600"
                >
                    Assignments
                </Link>

                <button disabled class="pb-3 text-sm font-medium text-gray-400 cursor-not-allowed">
                    Lessons (soon)
                </button>

                <button disabled class="pb-3 text-sm font-medium text-gray-400 cursor-not-allowed">
                    Grades (soon)
                </button>
            </nav>
        </div>

        <!-- Students table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">LRN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="student in students" :key="student.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ student.name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ student.lrn }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ student.email }}</td>
                    </tr>
                    <tr v-if="!students.length">
                        <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">
                            No students enrolled yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </TeacherLayout>
</template>