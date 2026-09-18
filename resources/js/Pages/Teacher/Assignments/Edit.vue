<script setup>
import TeacherLayout from '@/Layouts/TeacherLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
    classroom: Object,
    assignment: Object,
})

const form = useForm({
    title: props.assignment.title,
    instructions: props.assignment.instructions ?? '',
    due_at: props.assignment.due_at,          // already formatted as Y-m-d\TH:i by controller
    points: props.assignment.points,
    allow_late: props.assignment.allow_late,
    is_published: props.assignment.is_published,
})

function submit() {
    form.put(route('teacher.assignments.update', props.assignment.id))
}

function destroy() {
    if (! confirm('Delete this assignment? This cannot be undone.')) return
    form.delete(route('teacher.assignments.destroy', props.assignment.id))
}
</script>

<template>
    <TeacherLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Edit Assignment</h2>
                    <p class="text-sm text-gray-500">
                        {{ classroom.subject }} · {{ classroom.section }}
                    </p>
                </div>
                <Link :href="route('teacher.assignments.show', assignment.id)"
                      class="text-sm text-gray-500 hover:text-gray-700">
                    ← Back to assignment
                </Link>
            </div>
        </template>

        <form @submit.prevent="submit" class="bg-white rounded-lg shadow p-6 max-w-3xl space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input v-model="form.title" type="text"
                       class="w-full border border-gray-300 rounded px-3 py-2" />
                <p v-if="form.errors.title" class="text-red-600 text-xs mt-1">{{ form.errors.title }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                <textarea v-model="form.instructions" rows="5"
                          class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Due at</label>
                    <input v-model="form.due_at" type="datetime-local"
                           class="w-full border border-gray-300 rounded px-3 py-2" />
                    <p v-if="form.errors.due_at" class="text-red-600 text-xs mt-1">{{ form.errors.due_at }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                    <input v-model="form.points" type="number" min="1"
                           class="w-full border border-gray-300 rounded px-3 py-2" />
                    <p v-if="form.errors.points" class="text-red-600 text-xs mt-1">{{ form.errors.points }}</p>
                </div>
            </div>

            <div class="flex gap-6">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" v-model="form.allow_late" />
                    Allow late submissions
                </label>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" v-model="form.is_published" />
                    Published (visible to students)
                </label>
            </div>

            <div class="flex justify-between pt-2 border-t">
                <button type="button" @click="destroy" :disabled="form.processing"
                        class="text-sm text-red-600 hover:text-red-800 font-medium">
                    Delete Assignment
                </button>
                <div class="flex gap-3">
                    <Link :href="route('teacher.assignments.show', assignment.id)"
                          class="text-sm px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</Link>
                    <button type="submit" :disabled="form.processing"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-5 py-2 rounded-md disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </div>
        </form>
    </TeacherLayout>
</template>