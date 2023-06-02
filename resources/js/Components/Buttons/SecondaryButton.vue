<template>
  <button
    :disabled="loading"
    :type="type"
    class="inline-flex items-center justify-center rounded-md border border-zinc-900/10 bg-white px-4 py-2 text-sm font-medium text-zinc-500 shadow-sm duration-200 ease-in-out hover:border-zinc-900/20 hover:text-zinc-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-zinc-50 disabled:pointer-events-none disabled:opacity-60 dark:border-white/10 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:border-white/20 dark:hover:text-white dark:focus:ring-violet-600 dark:focus:ring-offset-zinc-900"
  >
    <LoadingAnimation v-if="loading" />

    <slot />
  </button>
</template>

<script lang="ts" setup>
import LoadingAnimation from '@/Components/Buttons/ButtonLoadingAnimation.vue'

interface Props {
  type?: 'button' | 'submit' | 'reset'
  loading?: boolean
}

withDefaults(defineProps<Props>(), {
  type: 'button',
  loading: false,
})
</script>
