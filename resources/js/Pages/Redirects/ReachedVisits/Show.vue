<template>
  <AppHead :title="$t('The link\'s visit limit has been reached')" />

  <AuthHeader :title="$t('The link\'s visit limit has been reached')" full-width>
    <p>
      {{ $t('The link you are trying to access has reached its visit limit.') }}
    </p>
  </AuthHeader>
</template>

<script lang="ts" setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import AuthHeader from '@/Pages/Auth/Partials/AuthHeader.vue'
import AppHead from '@/Components/App/AppHead.vue'

defineOptions({
  layout: AuthLayout,
})
</script>
