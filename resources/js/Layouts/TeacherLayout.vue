<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const user = computed(() => page.props.auth.user)

const nav = [
    { label: 'My Classes', route: 'teacher.classes.index' },
    { label: 'Profile',    route: 'profile.edit' },
]
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Top bar -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        <Link :href="route('teacher.classes.index')" class="font-bold text-lg text-indigo-600">
                            Salawag SHS
                        </Link>
                        <div class="hidden sm:flex gap-6">
                            <Link
                                v-for="item in nav"
                                :key="item.route"
                                :href="route(item.route)"
                                class="text-sm font-medium text-gray-700 hover:text-indigo-600"
                                :class="{ 'text-indigo-600': route().current(item.route) }"
                            >
                                {{ item.label }}
                            </Link>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500">{{ user.name }}</span>
                        <Link :href="route('logout')" method="post" as="button"
                            class="text-sm text-gray-500 hover:text-red-600">
                            Log out
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Optional page header -->
        <header v-if="$slots.header" class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Main -->
        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>