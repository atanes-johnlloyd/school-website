<script setup>
import TeacherLayout from '@/Layouts/TeacherLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    assignment: Object,
    submissions: Array,
    stats: Object,
})

// Grading modal state
const active = ref(null)
const gradeForm = useForm({ grade: '', feedback: '' })

function openGrade(sub) {
    active.value = sub
    gradeForm.grade = sub.grade ?? ''
    gradeForm.feedback = sub.feedback ?? ''
}

function submitGrade() {
    gradeForm.put(route('teacher.submissions.grade', active.value.id), {
        onSuccess: () => { active.value = null; gradeForm.reset() }
    })
}

function statusColor(status) {
    return {
        submitted: 'bg-blue-100 text-blue-700',
        late:      'bg-amber-100 text-amber-700',
        graded:    'bg-green-100 text-green-700',
        not_submitted: 'bg-gray-100 text-gray-500',
    }[status] || 'bg-gray-100 text-gray-700'
}
</script>

<template>
    <TeacherLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ assignment.title }}</h2>
                    <p class="text-sm text-gray-500">
                        {{ assignment.subject }} · {{ assignment.section }} · {{ assignment.points }} pts
                    </p>
                    <div class="flex items-center gap-3">
                        <button
                            v-if="!assignment.is_published"
                            @click="$inertia.put(route('teacher.assignments.update', assignment.id), { is_published: true })"
                            class="text-sm bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded-md font-medium"
                        >
                            Publish Now
                        </button>
                        <span v-else class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">
                            Published
                        </span>
                        <Link :href="route('teacher.assignments.edit', assignment.id)"
                            class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            Edit
                        </Link>
                        <Link :href="route('teacher.classes.assignments.index', assignment.classroom_id)"
                            class="text-sm text-gray-500 hover:text-gray-700">
                            ← Assignments
                        </Link>
                    </div>
                </div>
            </div>
        </template>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-xs text-gray-500">Students</p>
                <p class="text-2xl font-bold text-gray-800">{{ stats.total_students }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-xs text-gray-500">Submitted</p>
                <p class="text-2xl font-bold text-blue-600">{{ stats.submitted }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-xs text-gray-500">Graded</p>
                <p class="text-2xl font-bold text-green-600">{{ stats.graded }}</p>
            </div>
        </div>

        <!-- Submissions table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="s in submissions" :key="s.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ s.student_name }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full" :class="statusColor(s.status)">
                                {{ s.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ s.submitted_at ? new Date(s.submitted_at).toLocaleString() : '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                            {{ s.grade ?? '—' }} <span v-if="s.grade != null">/ {{ assignment.points }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button v-if="s.status !== 'not_submitted'"
                                    @click="openGrade(s)"
                                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                {{ s.grade == null ? 'Grade' : 'Edit' }}
                            </button>
                        </td>
                    </tr>
                    <tr v-if="!submissions.length">
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                            No submissions yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Grading modal -->
        <div v-if="active" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h3 class="font-semibold text-lg mb-1">Grade: {{ active.student_name }}</h3>
                <p class="text-xs text-gray-500 mb-4">Max points: {{ assignment.points }}</p>

                <div class="space-y-4">
                    <div v-if="active.text_content" class="bg-gray-50 border rounded p-3 text-sm text-gray-700 max-h-40 overflow-auto">
                        {{ active.text_content }}
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Grade</label>
                        <input v-model="gradeForm.grade" type="number" step="0.01"
                               class="w-full border rounded px-3 py-2" />
                        <p v-if="gradeForm.errors.grade" class="text-red-600 text-xs mt-1">{{ gradeForm.errors.grade }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Feedback</label>
                        <textarea v-model="gradeForm.feedback" rows="3"
                                  class="w-full border rounded px-3 py-2"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button @click="active = null" class="text-sm px-4 py-2 text-gray-600">Cancel</button>
                    <button @click="submitGrade" :disabled="gradeForm.processing"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm px-5 py-2 rounded-md disabled:opacity-50">
                        Save Grade
                    </button>
                </div>
            </div>
        </div>
    </TeacherLayout>
</template>