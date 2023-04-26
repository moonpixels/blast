<template>
  <div>
    <h1 class="text-2xl font-bold">Welcome to Laravel</h1>
    <p class="text-gray-500">Laravel {{ laravelVersion }}</p>
    <p class="text-gray-500">PHP {{ phpVersion }}</p>
  </div>
</template>

<script lang="ts" setup>
defineProps<{
  laravelVersion: string;
  phpVersion: string;
}>()
</script>