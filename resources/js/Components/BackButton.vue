<template>
  <button type="button" @click="goBack">
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
  // Always navigate to the semantic parent route.
  // We deliberately avoid history.back() here:
  //   1. After a create/update redirect, history.back() returns to the form.
  //   2. On a fresh tab / bookmark, there's nothing to go back to.
  //   3. When landing from an email / external link, the previous page is
  //      not part of our app.
  // A named route gives deterministic, predictable behaviour in all cases.
  try {
    router.visit(route(props.fallback))
  } catch (e) {
    // Route name not registered — last-resort fallback.
    router.visit('/')
  }
}
</script>