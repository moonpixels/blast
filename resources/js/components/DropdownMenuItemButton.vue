<template>
  <MenuItem v-slot="{ active, close }">
    <button
      :class="[
        active ? 'bg-zinc-100 text-zinc-900 transition-all ease-in-out dark:bg-zinc-800 dark:text-white' : '',
        'group relative block w-full rounded px-4 py-2 text-left text-sm',
      ]"
      type="button"
    >
      <slot v-bind="{ active, close }" />
    </button>
  </MenuItem>
</template>

<script lang="ts" setup>
import { MenuItem } from '@headlessui/vue'
</script>
