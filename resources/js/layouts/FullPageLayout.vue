<template>
  <main class="flex min-h-screen flex-1 flex-col px-4 py-12 sm:justify-center sm:px-6 lg:px-8">
    <slot />
  </main>

  <TheFlashNotification />
</template>

<script lang="ts" setup>
import TheFlashNotification from '@/components/TheFlashNotification.vue'
</script>
