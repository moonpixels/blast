<template>
  <MenuItem v-slot="{ active, close }">
    <slot />
  </MenuItem>
</template>

<script lang="ts" setup>
import { MenuItem } from '@headlessui/vue'
</script>