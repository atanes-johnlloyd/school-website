<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Admin Dashboard</h2>
        </template>
        <div class="py-8 max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow p-8 text-gray-500">
                Admin features are not part of the current build. Focus is on the LMS.
            </div>
        </div>
    </AuthenticatedLayout>
</template>