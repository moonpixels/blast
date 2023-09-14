<template>
  <BaseButton size="icon" variant="plain">
    <span class="sr-only">
      {{ $t('Close') }}
    </span>

    <XMarkIcon :class="classes" aria-hidden="true" />
  </BaseButton>
</template>

<script lang="ts" setup>
import { XMarkIcon } from '@heroicons/vue/20/solid'
import { computed } from 'vue'
import BaseButton from '@/components/BaseButton.vue'

type ButtonDismissProps = {
  size?: 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<ButtonDismissProps>(), {
  size: 'md',
})

const classes = computed<string>(() => {
  switch (props.size) {
    case 'sm':
      return 'h-4 w-4'
    case 'lg':
      return 'h-6 w-6'
    default:
      return 'h-5 w-5'
  }
})
</script>
