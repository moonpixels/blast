<template>
  <BaseButton
    class="bg-rose-500 text-white hover:bg-rose-600 dark:bg-rose-600 dark:hover:bg-rose-500"
    v-bind="{ ...$props }"
  >
    <slot />
  </BaseButton>
</template>

<script lang="ts" setup>
import BaseButton, { ButtonProps } from '@/Components/Buttons/BaseButton.vue'

defineProps<ButtonProps>()
</script>
