<template>
  <span
    :class="sizeClasses"
    class="inline-flex items-center justify-center rounded-full bg-zinc-400 text-sm dark:bg-zinc-500"
  >
    <span :class="textSizeClasses" class="font-medium leading-none text-white">
      {{ initials }}
    </span>
  </span>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

type Props = {
  initials: string
  size?: 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
  size: 'sm',
})

const sizeClasses = computed<string>(() => {
  switch (props.size) {
    case 'md':
      return 'h-10 w-10'
    case 'lg':
      return 'h-12 w-12'
    default:
      return 'h-8 w-8'
  }
})

const textSizeClasses = computed(() => {
  switch (props.size) {
    case 'md':
      return 'text-base'
    case 'lg':
      return 'text-lg'
    default:
      return 'text-sm'
  }
})
</script>
