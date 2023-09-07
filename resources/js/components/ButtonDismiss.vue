<template>
  <AppButton
    class="text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white"
    no-shadow
    size="icon"
    type="button"
  >
    <span class="sr-only">
      {{ $t('common.close') }}
    </span>

    <XMarkIcon :class="classes" aria-hidden="true" />
  </AppButton>
</template>

<script lang="ts" setup>
import { XMarkIcon } from '@heroicons/vue/20/solid'
import { computed } from 'vue'
import AppButton from '@/components/AppButton.vue'

type Props = {
  size?: 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
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
