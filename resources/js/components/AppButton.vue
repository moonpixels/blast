<template>
  <button
    :class="[
      sizeClasses,
      shadowClasses,
      'focus-ring group inline-flex items-center justify-center rounded-md border border-transparent font-medium transition-all duration-200 ease-in-out disabled:pointer-events-none disabled:opacity-60',
    ]"
    :disabled="loading"
    :type="type"
  >
    <svg
      v-if="loading"
      class="-ml-1 mr-3 h-5 w-5 animate-spin text-white/90"
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

    <slot />
  </button>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

export type ButtonProps = {
  type?: 'button' | 'submit' | 'reset'
  loading?: boolean
  size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl' | 'icon'
  noShadow?: boolean
}

const props = withDefaults(defineProps<ButtonProps>(), {
  type: 'button',
  loading: false,
  size: 'md',
})

const sizeClasses = computed<string>(() => {
  switch (props.size) {
    case 'xs':
      return 'px-2 py-1 text-xs'
    case 'sm':
      return 'px-3 py-1.5 text-sm'
    case 'md':
      return 'px-4 py-2 text-sm'
    case 'lg':
      return 'px-5 py-2.5 text-base'
    case 'icon':
      return 'p-1.5'
    default:
      return ''
  }
})

const shadowClasses = computed<string>(() => {
  return props.noShadow ? '' : 'shadow-sm'
})
</script>
