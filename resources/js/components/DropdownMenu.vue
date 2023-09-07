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
      <MenuItems
        :class="[sizeClasses]"
        class="absolute right-0 z-10 mt-2 origin-top-right rounded-md border border-zinc-900/20 bg-white p-1.5 shadow-md focus:outline-none dark:border-white/20 dark:bg-zinc-950"
      >
        <slot name="menuItems" />
      </MenuItems>
    </transition>
  </Menu>
</template>

<script lang="ts" setup>
import { Menu, MenuItems } from '@headlessui/vue'
import { computed } from 'vue'

type Props = {
  size?: 'xs' | 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
  size: 'sm',
})

const sizeClasses = computed<string>(() => {
  switch (props.size) {
    case 'xs':
      return 'w-48'
    case 'md':
      return 'w-64'
    case 'lg':
      return 'w-80'
    default:
      return 'w-56'
  }
})
</script>
