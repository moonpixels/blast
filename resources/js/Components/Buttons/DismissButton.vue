<template>
  <button
    class="inline-flex rounded text-zinc-500 transition-all duration-200 ease-in-out hover:text-zinc-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-zinc-50 disabled:bg-violet-400 dark:text-zinc-400 dark:hover:text-white dark:focus:ring-violet-600 dark:focus:ring-offset-zinc-900 dark:disabled:bg-violet-400"
    type="button"
  >
    <span class="sr-only">
      {{ $t('common.close') }}
    </span>
    <XMarkIcon :class="classes" aria-hidden="true" />
  </button>
</template>

<script lang="ts" setup>
import { XMarkIcon } from '@heroicons/vue/20/solid'
import { computed } from 'vue'

interface Props {
  size?: 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
})

const classes = computed<string>(() => {
  switch (props.size) {
    case 'sm':
      return 'h-4 w-4'
    case 'md':
      return 'h-5 w-5'
    case 'lg':
      return 'h-6 w-6'
  }
})
</script>
