<template>
  <div v-if="paginatedResource.meta.total > paginatedResource.meta.per_page" class="flex items-center space-x-3">
    <component
      :is="paginatedResource.links.prev ? Link : 'div'"
      :href="paginatedResource.links.prev"
      data-cy="pagination-previous-link"
      preserve-scroll
    >
      <SecondaryButton :disabled="!paginatedResource.links.prev" :size="'sm'">
        {{ $t('Previous') }}
      </SecondaryButton>
    </component>

    <component
      :is="paginatedResource.links.next ? Link : 'div'"
      :href="paginatedResource.links.next"
      data-cy="pagination-next-link"
      preserve-scroll
    >
      <SecondaryButton :disabled="!paginatedResource.links.next" :size="'sm'">
        {{ $t('Next') }}
      </SecondaryButton>
    </component>
  </div>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import { PaginatedResponse } from '@/types/framework'

interface Props {
  paginatedResource: PaginatedResponse<any>
}

defineProps<Props>()
</script>
