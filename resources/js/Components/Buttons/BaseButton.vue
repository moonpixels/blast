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
    <ButtonLoadingAnimation v-if="loading" />

    <slot />
  </button>
</template>

<script lang="ts" setup>
import ButtonLoadingAnimation from '@/Components/Buttons/ButtonLoadingAnimation.vue'
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
