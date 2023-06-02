<template>
  <ConstrainedContainer>
    {{ $tChoice('links.links', 2) }}
  </ConstrainedContainer>
</template>

<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ConstrainedContainer from '@/Components/Containers/ConstrainedContainer.vue'

defineOptions({
  layout: AppLayout,
})
</script>
