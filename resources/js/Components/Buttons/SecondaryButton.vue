<template>
  <button
    :disabled="loading"
    :type="type"
    class="inline-flex items-center justify-center rounded-md px-4 py-2 border border-zinc-900/10 dark:border-white/10 bg-white dark:bg-zinc-800 text-sm font-medium text-zinc-500 dark:text-zinc-400 shadow-sm ease-in-out duration-200 hover:border-zinc-900/20 dark:hover:border-white/20 hover:text-zinc-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-600 focus:ring-offset-2 focus:ring-offset-zinc-50 dark:focus:ring-offset-zinc-900 disabled:opacity-60 disabled:pointer-events-none"
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