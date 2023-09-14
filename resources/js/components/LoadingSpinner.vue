<template>
  <svg
    :class="[sizeClasses]"
    class="animate-spin text-white/90"
    fill="none"
    viewBox="0 0 24 24"
    xmlns="http://www.w3.org/2000/svg"
  >
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path
      class="opacity-75"
      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      fill="currentColor"
    ></path>
  </svg>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

export type LoadingSpinnerProps = {
  size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl'
}

const props = withDefaults(defineProps<LoadingSpinnerProps>(), {
  size: 'md',
})

const sizeClasses = computed<string>(() => {
  switch (props.size) {
    case 'xs':
      return 'h-3 w-3'
    case 'sm':
      return 'h-4 w-4'
    case 'lg':
      return 'h-6 w-6'
    case 'xl':
      return 'h-7 w-7'
    default:
      return 'h-5 w-5'
  }
})
</script>
