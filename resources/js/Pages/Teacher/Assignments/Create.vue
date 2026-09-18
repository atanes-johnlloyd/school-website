<script setup>
import TeacherLayout from '@/Layouts/TeacherLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({ classroom: Object })

const form = useForm({
    title: '',
    instructions: '',
    due_at: '',
    points: 100,
    allow_late: true,
    is_published: false,
})

function submit() {
    form.post(route('teacher.classes.assignments.store', props.classroom.id))
}
</script>

<template>
    <TeacherLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">New Assignment</h2>
        </template>

        <form @submit.prevent="submit" class="bg-white rounded-lg shadow p-6 max-w-3xl space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input v-model="form.title" type="text" class="w-full border rounded px-3 py-2" />
                <p v-if="form.errors.title" class="text-red-600 text-xs mt-1">{{ form.errors.title }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                <textarea v-model="form.instructions" rows="5" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Due at</label>
                    <input v-model="form.due_at" type="datetime-local" class="w-full border rounded px-3 py-2" />
                    <p v-if="form.errors.due_at" class="text-red-600 text-xs mt-1">{{ form.errors.due_at }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                    <input v-model="form.points" type="number" min="1" class="w-full border rounded px-3 py-2" />
                </div>
            </div>

            <div class="flex gap-6">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" v-model="form.allow_late" /> Allow late submissions
                </label>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" v-model="form.is_published" /> Publish immediately
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <Link :href="route('teacher.classes.assignments.index', classroom.id)"
                      class="text-sm px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</Link>
                <button type="submit" :disabled="form.processing"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-5 py-2 rounded-md disabled:opacity-50">
                    Create
                </button>
            </div>
        </form>
    </TeacherLayout>
</template>