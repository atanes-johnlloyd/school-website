<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    assignment: Object,
    submission: Object,
})

const form = useForm({
    text_content: props.submission?.text_content ?? '',
})

function submit() {
    form.post(route('student.assignments.submit', props.assignment.id))
}

const isGraded  = props.submission?.status === 'graded'
const isOverdue = new Date(props.assignment.due_at) < new Date()
</script>

<template>
    <StudentLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ assignment.title }}</h2>
                    <p class="text-sm text-gray-500">
                        {{ assignment.subject }} · {{ assignment.teacher }}
                    </p>
                </div>
                <Link :href="route('student.classes.show', assignment.id)"
                      class="text-sm text-gray-500 hover:text-gray-700">← Back</Link>
            </div>
        </template>

        <div class="bg-white rounded-lg shadow p-6 mb-6 space-y-4">
            <div class="flex items-center gap-4 text-sm flex-wrap">
                <span :class="isOverdue ? 'text-red-600 font-semibold' : 'text-gray-700'">
                    Due: {{ new Date(assignment.due_at).toLocaleString() }}
                </span>
                <span class="text-gray-500">· {{ assignment.points }} points</span>
                <span v-if="!assignment.allow_late"
                      class="text-xs bg-red-50 text-red-700 px-2 py-1 rounded-full">
                    No late submissions
                </span>
            </div>
            <div v-if="assignment.instructions"
                 class="text-sm text-gray-700 whitespace-pre-wrap border-t pt-4">
                {{ assignment.instructions }}
            </div>
        </div>

        <div v-if="isGraded" class="bg-green-50 border border-green-200 rounded-lg p-5 mb-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-green-900">Graded</h3>
                <span class="text-2xl font-bold text-green-700">
                    {{ submission.grade }}
                    <span class="text-base font-normal text-green-600">/ {{ assignment.points }}</span>
                </span>
            </div>
            <p v-if="submission.feedback" class="text-sm text-green-900 whitespace-pre-wrap">
                <span class="font-medium">Feedback:</span> {{ submission.feedback }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold mb-3">
                {{ submission ? 'Your Submission' : 'Submit Your Work' }}
            </h3>

            <p v-if="submission" class="text-xs text-gray-500 mb-3">
                Submitted {{ new Date(submission.submitted_at).toLocaleString() }}
                <span v-if="submission.status === 'late'" class="text-amber-700 font-medium">(late)</span>
            </p>

            <textarea v-model="form.text_content" rows="8"
                      :disabled="isGraded"
                      class="w-full border rounded px-3 py-2 disabled:bg-gray-100 disabled:text-gray-500"
                      placeholder="Type your answer here..."></textarea>
            <p v-if="form.errors.text_content" class="text-red-600 text-xs mt-1">
                {{ form.errors.text_content }}
            </p>

            <div class="mt-4 flex justify-end">
                <button v-if="!isGraded" @click="submit" :disabled="form.processing"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-5 py-2 rounded-md disabled:opacity-50">
                    {{ form.processing ? 'Submitting...' : (submission ? 'Resubmit' : 'Submit') }}
                </button>
                <span v-else class="text-sm text-gray-500">Submission locked after grading.</span>
            </div>
        </div>
    </StudentLayout>
</template>