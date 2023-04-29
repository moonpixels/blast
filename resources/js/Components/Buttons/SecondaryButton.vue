<template>
  <button
    :disabled="loading"
    :type="type"
    class="inline-flex items-center justify-center rounded-md px-4 py-2 border border-zinc-900/10 dark:border-white/10 bg-white dark:bg-zinc-800 text-sm font-medium text-zinc-500 dark:text-zinc-400 shadow-sm ease-in-out duration-200 hover:border-zinc-900/20 dark:hover:border-white/20 hover:text-zinc-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-600 focus:ring-offset-2 focus:ring-offset-zinc-50 dark:focus:ring-offset-zinc-900 disabled:bg-violet-400 dark:disabled:bg-violet-400 disabled:cursor-not-allowed"
  >
    <svg
      v-if="loading"
      class="animate-spin -ml-1 mr-3 h-5 w-5 text-white/90"
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
interface Props {
  type?: 'button' | 'submit' | 'reset'
  loading?: boolean
}

withDefaults(defineProps<Props>(), {
  type: 'button',
  loading: false,
})
</script>