<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    classroom: Object,
    assignments: Array,
})

function statusLabel(sub) {
    if (!sub) return 'Not submitted'
    return sub.status === 'graded' ? 'Graded'
         : sub.status === 'late'   ? 'Submitted (late)'
         : 'Submitted'
}

function statusClass(sub) {
    if (!sub) return 'bg-gray-100 text-gray-600'
    if (sub.status === 'graded') return 'bg-green-100 text-green-700'
    if (sub.status === 'late')   return 'bg-amber-100 text-amber-700'
    return 'bg-blue-100 text-blue-700'
}
</script>

<template>
    <StudentLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ classroom.subject }}</h2>
                    <p class="text-sm text-gray-500">{{ classroom.section }} · {{ classroom.teacher }}</p>
                </div>
                <Link :href="route('student.classes.show', classroom.id)"
                      class="text-sm text-gray-500 hover:text-gray-700">← Class</Link>
            </div>
        </template>

        <div v-if="!assignments.length" class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
            No assignments yet.
        </div>

        <div v-else class="space-y-3">
            <Link v-for="a in assignments" :key="a.id"
                  :href="route('student.assignments.show', a.id)"
                  class="block bg-white rounded-lg shadow hover:shadow-md transition p-5 border border-gray-200">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ a.title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Due {{ new Date(a.due_at).toLocaleString() }} · {{ a.points }} pts
                        </p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full" :class="statusClass(a.submission)">
                        {{ statusLabel(a.submission) }}
                    </span>
                </div>
                <div v-if="a.submission?.grade != null" class="mt-3 pt-3 border-t text-sm">
                    <span class="text-gray-500">Grade: </span>
                    <span class="font-semibold text-green-700">
                        {{ a.submission.grade }} / {{ a.points }}
                    </span>
                </div>
            </Link>
        </div>
    </StudentLayout>
</template>