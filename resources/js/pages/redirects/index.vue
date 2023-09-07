<template>
  <RedirectExpired v-if="status === 'expired'" />

  <RedirectLimited v-else-if="status === 'limited'" />

  <RedirectProtected v-else-if="status === 'protected'" :alias="alias" />
</template>

<script lang="ts" setup>
import FullPageLayout from '@/layouts/FullPageLayout.vue'
import RedirectExpired from '@/pages/redirects/components/RedirectExpired.vue'
import RedirectLimited from '@/pages/redirects/components/RedirectLimited.vue'
import RedirectProtected from '@/pages/redirects/components/RedirectProtected.vue'

defineOptions({
  layout: FullPageLayout,
})

type Props = {
  alias: string
  status: 'expired' | 'limited' | 'protected'
}

defineProps<Props>()
</script>
