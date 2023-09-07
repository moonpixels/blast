<template>
  <AppButton
    class="bg-rose-500 text-white hover:bg-rose-600 dark:bg-rose-600 dark:hover:bg-rose-500"
    v-bind="{ ...$props }"
  >
    <slot />
  </AppButton>
</template>

<script lang="ts" setup>
import AppButton, { ButtonProps } from '@/components/AppButton.vue'

defineProps<ButtonProps>()
</script>
