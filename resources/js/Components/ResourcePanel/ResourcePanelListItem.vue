<template>
  <li class="flex justify-between gap-3 p-3">
    <div class="flex-auto overflow-hidden">
      <slot name="content" />
    </div>

    <div class="flex flex-none items-center gap-2">
      <slot name="actions" />
    </div>
  </li>
</template>
