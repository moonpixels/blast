<template>
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <BlastLogo class="mx-auto h-10 w-auto" type="logomark" />

    <h2 class="mt-6 text-center text-2xl font-semibold leading-9 tracking-tight text-zinc-900 dark:text-white">
      {{ title }}
    </h2>

    <div class="text-sm text-center mt-2">
      <slot />
    </div>
  </div>
</template>

<script lang="ts" setup>
import BlastLogo from '@/Components/Logo/BlastLogo.vue'

interface Props {
  title: string
  description?: string
}

const props = defineProps<Props>()
</script>