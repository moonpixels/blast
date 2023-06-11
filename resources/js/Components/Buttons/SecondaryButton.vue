<template>
  <BaseButton
    class="border-zinc-900/10 bg-white text-zinc-500 hover:border-zinc-900/20 hover:text-zinc-900 dark:border-white/10 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:border-white/20 dark:hover:text-white"
    v-bind="{ ...$props }"
  >
    <slot />
  </BaseButton>
</template>

<script lang="ts" setup>
import BaseButton, { ButtonProps } from '@/Components/Buttons/BaseButton.vue'

defineProps<ButtonProps>()
</script>
