<template>
  <TheGuestHeader />

  <main>
    <slot />
  </main>

  <TheFooter />
</template>

<script lang="ts" setup>
import TheFooter from '@/components/TheFooter.vue'
import TheGuestHeader from '@/components/TheGuestHeader.vue'
</script>
