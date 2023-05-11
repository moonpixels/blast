<template>
  <Menu as="div" class="relative">
    <div>
      <slot name="button" />
    </div>

    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <MenuItems class="absolute right-0 z-10 mt-2 p-1 w-48 origin-top-right rounded-md border border-zinc-900/20 dark:border-white/20 bg-white dark:bg-zinc-950 py-1 ring-1 ring-black ring-opacity-5 focus:outline-none">
        <slot name="menuItems" />
      </MenuItems>
    </transition>
  </Menu>
</template>

<script lang="ts" setup>
import { Menu, MenuItems } from '@headlessui/vue'
</script>