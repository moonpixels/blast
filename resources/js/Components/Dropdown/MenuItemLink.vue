<template>
  <MenuItem v-slot="{ active, close }">
    <Link
      :class="[active ? 'transition-all ease-in-out bg-gray-100 text-zinc-900 dark:bg-zinc-800 dark:text-white' : '', 'block relative w-full text-left px-4 py-2 text-sm rounded']"
      v-bind="{...attrs, ...$props}"
    >
      <span aria-hidden="true" class="absolute inset-0" @click="close" />
      <slot />
    </Link>
  </MenuItem>
</template>

<script lang="ts" setup>
import { InertiaLinkProps, Link } from '@inertiajs/vue3'
import { useAttrs } from 'vue'
import { MenuItem } from '@headlessui/vue'

defineOptions({
  inheritAttrs: false,
})

interface Props extends InertiaLinkProps {
}

defineProps<Props>()

const attrs = useAttrs()
</script>
