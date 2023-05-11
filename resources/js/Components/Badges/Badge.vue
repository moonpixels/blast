<template>
  <span :class="classes" class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset">
    <slot />
  </span>
</template>

<script lang="ts" setup>

import { computed } from 'vue'

interface Props {
  type?: 'neutral' | 'success' | 'warning' | 'danger'
}

const props = withDefaults(defineProps<Props>(), {
  type: 'neutral',
})

const classes = computed<string>(() => {
  switch (props.type) {
    case 'success':
      return 'bg-emerald-500/10 dark:bg-emerald-400/10 text-emerald-600 dark:text-emerald-500 ring-emerald-500/20 dark:ring-emerald-400/20'
    case 'warning':
      return 'bg-yellow-500/10 dark:bg-yellow-400/10 text-yellow-600 dark:text-yellow-500 ring-yellow-500/20 dark:ring-yellow-400/20'
    case 'danger':
      return 'bg-rose-500/10 dark:bg-rose-400/10 text-rose-600 dark:text-rose-500 ring-rose-500/20 dark:ring-rose-400/20'
    default:
      return 'bg-zinc-500/10 dark:bg-zinc-400/10 text-zinc-600 dark:text-zinc-400 ring-zinc-500/20 dark:ring-zinc-400/20'
  }
})
</script>