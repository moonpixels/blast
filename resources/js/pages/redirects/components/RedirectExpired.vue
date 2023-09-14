<template>
  <BaseHead :title="$t('The link has expired')" />

  <FullPageHeading :title="$t('The link has expired')">
    <p>
      {{ $t('The link you are trying to access has expired.') }}
    </p>
  </FullPageHeading>
</template>

<script lang="ts" setup>
import FullPageHeading from '@/components/FullPageHeading.vue'
import BaseHead from '@/components/BaseHead.vue'
</script>
