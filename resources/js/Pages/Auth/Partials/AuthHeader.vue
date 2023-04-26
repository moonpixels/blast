<template>
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <BlastLogo class="mx-auto h-12 w-auto" type="logomark" />
    <h2 class="mt-6 text-center text-2xl font-semibold leading-9 tracking-tight text-zinc-900 dark:text-zinc-100">
      {{ title }}
    </h2>
  </div>
</template>

<script lang="ts" setup>
import BlastLogo from '@/Components/Logo/BlastLogo.vue'

interface Props {
  title: string
}

const props = defineProps<Props>()
</script>