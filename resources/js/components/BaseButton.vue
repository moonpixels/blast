<template>
  <button
    :class="[sizeClasses, variantClasses, cursorClasses, { 'text-transparent dark:text-transparent': loading }]"
    :disabled="disabled || loading"
    :type="type"
    class="focus-ring group relative inline-flex items-center justify-center overflow-hidden rounded-md border border-transparent font-medium transition-all duration-200 ease-in-out disabled:opacity-75"
  >
    <span v-if="loading" class="absolute inset-0 flex items-center justify-center">
      <LoadingSpinner :size="loadingSpinnerSize" />
    </span>

    <slot />
  </button>
</template>

<script lang="ts" setup>
import LoadingSpinner, { LoadingSpinnerProps } from '@/components/LoadingSpinner.vue'
import { computed } from 'vue'

type ButtonProps = {
  variant?: 'primary' | 'secondary' | 'danger' | 'plain'
  type?: 'button' | 'submit' | 'reset'
  loading?: boolean
  disabled?: boolean
  size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl' | 'icon'
}

const props = withDefaults(defineProps<ButtonProps>(), {
  variant: 'primary',
  type: 'button',
  loading: false,
  disabled: false,
  size: 'md',
})

const sizeClasses = computed<string>(() => {
  switch (props.size) {
    case 'xs':
      return 'px-3 py-2 text-xs'
    case 'sm':
      return 'px-3 py-2 text-sm'
    case 'lg':
      return 'px-5 py-3 text-base'
    case 'xl':
      return 'px-6 py-3.5 text-base'
    case 'icon':
      return 'p-2.5'
    default:
      return 'px-5 py-2.5 text-sm'
  }
})

const variantClasses = computed<string>(() => {
  switch (props.variant) {
    case 'secondary':
      return 'bg-zinc-200 text-zinc-600 enabled:hover:bg-zinc-300 enabled:hover:text-zinc-900 dark:bg-zinc-800 dark:text-zinc-300 enabled:dark:hover:bg-zinc-700 enabled:dark:hover:text-white'
    case 'danger':
      return 'bg-rose-500 text-white enabled:hover:bg-rose-600 dark:bg-rose-600 enabled:dark:hover:bg-rose-500'
    case 'plain':
      return 'bg-transparent text-zinc-600 enabled:hover:bg-zinc-200 enabled:hover:text-zinc-900 disabled:bg-zinc-200 dark:text-zinc-400 enabled:dark:hover:bg-zinc-800 enabled:dark:hover:text-white disabled:dark:bg-zinc-800'
    default:
      return 'bg-violet-500 text-white enabled:hover:bg-violet-600 dark:bg-violet-600 enabled:dark:hover:bg-violet-500'
  }
})

const cursorClasses = computed<string>(() => {
  if (props.loading) {
    return 'cursor-progress'
  }

  if (props.disabled) {
    return 'cursor-not-allowed'
  }

  return 'cursor-pointer'
})

const loadingSpinnerSize = computed<LoadingSpinnerProps['size']>(() => {
  switch (props.size) {
    case 'xs':
      return 'xs'
    case 'sm':
      return 'sm'
    case 'lg':
      return 'lg'
    case 'xl':
      return 'xl'
    default:
      return 'md'
  }
})
</script>
