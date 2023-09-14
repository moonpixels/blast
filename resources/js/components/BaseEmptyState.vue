<template>
  <div class="my-6 text-center">
    <div class="flex justify-center">
      <component :is="icon" class="h-8 w-8 text-zinc-400 dark:text-zinc-500" />
    </div>

    <h3 class="mt-2 text-sm font-semibold text-zinc-900 dark:text-white">
      {{ title }}
    </h3>

    <p class="mt-1 text-sm">
      {{ description }}
    </p>

    <div v-if="$slots.button" class="mt-6">
      <slot name="button" />
    </div>
  </div>
</template>

<script lang="ts" setup>
import { FunctionalComponent } from 'vue'

type Props = {
  icon: FunctionalComponent
  title: string
  description: string
}

defineProps<Props>()
</script>
