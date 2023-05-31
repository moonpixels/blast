<template>
  <MenuItem v-slot="{ active, close }">
    <button
      :class="[active ? 'transition-all ease-in-out bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white' : '', 'block relative group w-full text-left px-4 py-2 text-sm rounded']"
      type="button"
    >
      <slot v-bind="{ active, close }" />
    </button>
  </MenuItem>
</template>

<script lang="ts" setup>
import { MenuItem } from '@headlessui/vue'
</script>