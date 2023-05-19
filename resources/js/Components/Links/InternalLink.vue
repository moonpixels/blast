<template>
  <Link
    class="underline decoration-dashed underline-offset-2 transition-all ease-in-out duration-200 hover:text-zinc-900 dark:hover:text-white"
    v-bind="$props"
  >
    <slot />
  </Link>
</template>

<script lang="ts" setup>
import { InertiaLinkProps, Link } from '@inertiajs/vue3'

interface Props extends InertiaLinkProps {}

defineProps<Props>()
</script>