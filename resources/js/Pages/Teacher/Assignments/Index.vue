<script setup>
import TeacherLayout from '@/Layouts/TeacherLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    classroom: Object,
    assignments: Array,
})

function isOverdue(due) {
    return due && new Date(due) < new Date()
}
</script>

<template>
    <TeacherLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ classroom.subject }}</h2>
                    <p class="text-sm text-gray-500">{{ classroom.section }}</p>
                </div>
                <div class="flex gap-3">
                    <Link :href="route('teacher.classes.show', classroom.id)"
                          class="text-sm text-gray-500 hover:text-gray-700">← Class</Link>
                    <Link :href="route('teacher.classes.assignments.create', classroom.id)"
                          class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-md">
                        + New Assignment
                    </Link>
                </div>
            </div>
        </template>

        <div v-if="!assignments.length" class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
            No assignments yet.
        </div>

        <div v-else class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submissions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="a in assignments" :key="a.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ a.title }}</td>
                        <td class="px-6 py-4 text-sm" :class="isOverdue(a.due_at) ? 'text-red-600' : 'text-gray-700'">
                            {{ new Date(a.due_at).toLocaleString() }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ a.points }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ a.submissions_count }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full"
                                  :class="a.is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">
                                {{ a.is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <Link :href="route('teacher.assignments.show', a.id)"
                                  class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                View →
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </TeacherLayout>
</template>