<template>
  <div :class="[fullWidth ? '' : 'sm:max-w-sm']" class="sm:mx-auto sm:w-full">
    <Link href="/">
      <span class="sr-only"> Blast home page </span>

      <BlastLogo class="mx-auto h-10 w-auto" type="logomark" />
    </Link>

    <h2 class="mt-6 text-center text-2xl font-semibold leading-9 tracking-tight text-zinc-900 dark:text-white">
      {{ title }}
    </h2>

    <div class="mt-2 text-center text-sm">
      <slot />
    </div>
  </div>
</template>

<script lang="ts" setup>
import BlastLogo from '@/Components/Logo/BlastLogo.vue'
import { Link } from '@inertiajs/vue3'

type Props = {
  title: string
  description?: string
  fullWidth?: boolean
}

withDefaults(defineProps<Props>(), {
  description: undefined,
  fullWidth: false,
})
</script>
