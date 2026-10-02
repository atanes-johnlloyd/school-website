<template>
  <button type="button" @click="goBack" :class="class">
    <Icon icon="arrow-left" :size="iconSize" />
    <slot>Back</slot>
  </button>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  fallback: { type: String, default: 'teacher.tasks.index' },
  iconSize: { type: String, default: 'xs' },
})

function goBack() {
  // Prefer real browser back when there's history
  if (window.history.length > 1) {
    window.history.back()
    return
  }
  // Fallback: land somewhere sensible if this was a fresh tab / bookmark
  try {
    router.visit(route(props.fallback))
  } catch (e) {
    router.visit('/')
  }
}
</script>