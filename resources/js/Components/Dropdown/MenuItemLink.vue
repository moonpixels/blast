<template>
  <MenuItem v-slot="{ active, close }">
    <Link
      :class="[
        active ? 'bg-zinc-100 text-zinc-900 transition-all ease-in-out dark:bg-zinc-800 dark:text-white' : '',
        'group relative block w-full rounded px-4 py-2 text-left text-sm',
      ]"
      v-bind="{ ...attrs, ...$props }"
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

type Props = InertiaLinkProps

defineProps<Props>()

const attrs = useAttrs()
</script>
