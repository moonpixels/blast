<template>
  <button
    class="inline-flex rounded text-zinc-500 dark:text-zinc-400 transition-all ease-in-out duration-200 hover:text-zinc-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-600 focus:ring-offset-2 focus:ring-offset-zinc-50 dark:focus:ring-offset-zinc-900 disabled:bg-violet-400 dark:disabled:bg-violet-400"
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