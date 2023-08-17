<template>
  <div class="my-6 text-center">
    <div class="flex justify-center">
      <slot name="icon" />
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
type Props = {
  title: string
  description: string
}

defineProps<Props>()
</script>
