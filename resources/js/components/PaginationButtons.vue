<template>
  <div v-if="paginatedResource.meta.total > paginatedResource.meta.per_page" class="flex items-center space-x-3">
    <component
      :is="paginatedResource.links.prev ? Link : 'div'"
      :href="paginatedResource.links.prev"
      data-cy="pagination-previous-link"
      preserve-state
    >
      <BaseButton :disabled="!paginatedResource.links.prev" :size="'sm'" variant="secondary">
        {{ $t('Previous') }}
      </BaseButton>
    </component>

    <component
      :is="paginatedResource.links.next ? Link : 'div'"
      :href="paginatedResource.links.next"
      data-cy="pagination-next-link"
      preserve-state
    >
      <BaseButton :disabled="!paginatedResource.links.next" :size="'sm'" variant="secondary">
        {{ $t('Next') }}
      </BaseButton>
    </component>
  </div>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3'
import { PaginatedResponse } from '@/types/framework'
import BaseButton from '@/components/BaseButton.vue'

type Props = {
  paginatedResource: PaginatedResponse<any>
}

defineProps<Props>()
</script>
