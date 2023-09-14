<template>
  <BaseHead :title="$t('The link has reached its visit limit')" />

  <FullPageHeading :title="$t('The link has reached its visit limit')" full-width>
    <p>
      {{ $t('The link you are trying to access has reached its visit limit.') }}
    </p>
  </FullPageHeading>
</template>

<script lang="ts" setup>
import FullPageHeading from '@/components/FullPageHeading.vue'
import BaseHead from '@/components/BaseHead.vue'
</script>
