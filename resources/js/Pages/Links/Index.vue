<template>
  <AppHead :title="$t('Links')" />

  <ConstrainedContainer>
    <div class="space-y-6 lg:flex lg:space-x-6 lg:space-y-0">
      <div class="flex-none">
        <LinkShortener v-bind="$props" />
      </div>

      <div class="flex-auto overflow-hidden">
        <LinksPanel v-bind="$props" />
      </div>
    </div>
  </ConstrainedContainer>
</template>

<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ConstrainedContainer from '@/Components/Containers/ConstrainedContainer.vue'
import LinkShortener from '@/Components/LinkShortener/LinkShortener.vue'
import { Link } from '@/types/models'
import { PaginatedResponse } from '@/types/framework'
import LinksPanel, { Filters } from '@/Pages/Links/Partials/LinksPanel.vue'
import AppHead from '@/Components/App/AppHead.vue'

defineOptions({
  layout: AppLayout,
})

interface Props {
  shortenedLink?: Link
  links: PaginatedResponse<Link>
  filters: Filters
}

defineProps<Props>()
</script>
