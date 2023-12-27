<template>
  <div class="w-full rounded-md border border-zinc-900/20 bg-white shadow-sm dark:border-white/20 dark:bg-zinc-950">
    <div
      v-if="$slots.header"
      class="gap-3 border-b border-zinc-900/20 p-3 lg:flex lg:items-center lg:justify-between dark:border-white/20"
    >
      <slot name="header" />
    </div>

    <ul
      v-if="$slots.list"
      :data-cy="listCypressSelector"
      class="divide-y divide-zinc-900/10 dark:divide-white/10"
      role="list"
    >
      <slot name="list" />
    </ul>

    <slot />

    <div
      v-if="$slots.footer"
      class="flex items-center justify-between border-t border-zinc-900/20 p-3 text-sm dark:border-white/20"
    >
      <slot name="footer" />
    </div>
  </div>
</template>

<script lang="ts" setup>
type ResourcePanelProps = {
  listCypressSelector?: string
}

defineProps<ResourcePanelProps>()
</script>
