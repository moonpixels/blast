<template>
  <TheHeader />

  <main class="space-y-10 py-10">
    <slot />
  </main>

  <TheFlashNotification />
</template>

<script lang="ts" setup>
import TheHeader from '@/components/TheHeader.vue'
import TheFlashNotification from '@/components/TheFlashNotification.vue'
</script>
