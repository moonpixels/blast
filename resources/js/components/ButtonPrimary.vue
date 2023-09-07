<template>
  <AppButton
    class="bg-violet-500 text-white hover:bg-violet-600 dark:bg-violet-600 dark:hover:bg-violet-500"
    v-bind="{ ...$props }"
  >
    <slot />
  </AppButton>
</template>

<script lang="ts" setup>
import AppButton, { ButtonProps } from '@/components/AppButton.vue'

defineProps<ButtonProps>()
</script>
