<template>
  <article class="grid grid-cols-1 gap-8 py-10 md:grid-cols-3 md:gap-10">
    <div>
      <h2 class="text-base font-semibold leading-7 text-zinc-900 dark:text-white">
        {{ title }}
      </h2>

      <p class="mt-1 text-sm leading-6">
        {{ description }}
      </p>
    </div>

    <div class="md:col-span-2">
      <slot />
    </div>
  </article>
</template>

<script lang="ts" setup>
type Props = {
  title: string
  description: string
}

defineProps<Props>()
</script>
