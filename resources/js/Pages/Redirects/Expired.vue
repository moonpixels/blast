<template>
  <AppHead :title="$t('The link has expired')" />

  <AuthHeader :title="$t('The link has expired')">
    <p>
      {{ $t('The link you are trying to access has expired.') }}
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
