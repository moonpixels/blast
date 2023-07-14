<template>
  <ConstrainedContainer>
    <div class="space-y-6 lg:flex lg:space-x-6 lg:space-y-0">
      <div class="shrink-0">
        <LinkShortener :shortened-link="shortenedLink" />
      </div>

      <ul class="flex-1">
        <li v-for="link in links.data" :key="link.id">
          {{ link.short_url }}
        </li>
      </ul>
    </div>
  </ConstrainedContainer>
</template>

<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ConstrainedContainer from '@/Components/Containers/ConstrainedContainer.vue'
import LinkShortener from '@/Components/LinkShortener/LinkShortener.vue'
import { Link } from '@/types/models'
import { PaginatedResponse } from '@/types/framework'

defineOptions({
  layout: AppLayout,
})

interface Props {
  shortenedLink?: Link
  links: PaginatedResponse<Link>
}

defineProps<Props>()
</script>
