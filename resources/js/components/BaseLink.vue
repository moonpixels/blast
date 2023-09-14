<template>
  <component
    :is="external ? 'a' : Link"
    :rel="external ? 'noopener noreferrer' : undefined"
    class="underline decoration-dashed underline-offset-2 transition-all duration-200 ease-in-out hover:text-zinc-900 dark:hover:text-white"
    v-bind="$props"
  >
    <slot />
  </component>
</template>

<script lang="ts" setup>
import { InertiaLinkProps, Link } from '@inertiajs/vue3'

type Props = InertiaLinkProps & {
  external?: boolean
}

withDefaults(defineProps<Props>(), {
  external: false,
})
</script>
