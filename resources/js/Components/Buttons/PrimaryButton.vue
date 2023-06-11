<template>
  <BaseButton
    class="bg-violet-500 text-white hover:bg-violet-600 dark:bg-violet-600 dark:hover:bg-violet-500"
    v-bind="{ ...$props }"
  >
    <slot />
  </BaseButton>
</template>

<script lang="ts" setup>
import BaseButton, { ButtonProps } from '@/Components/Buttons/BaseButton.vue'

defineProps<ButtonProps>()
</script>
