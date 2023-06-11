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

interface Props {
  initials: string
  size?: 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
  size: 'sm',
})

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'h-8 w-8'
    case 'md':
      return 'h-10 w-10'
    case 'lg':
      return 'h-12 w-12'
  }
})

const textSizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'text-sm'
    case 'md':
      return 'text-base'
    case 'lg':
      return 'text-lg'
  }
})
</script>
