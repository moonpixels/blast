<template>
  <div v-if="paginatedResource.meta.total > paginatedResource.meta.per_page" class="flex items-center space-x-3">
    <component
      :is="paginatedResource.links.prev ? Link : 'div'"
      :href="paginatedResource.links.prev"
      data-cy="pagination-previous-link"
      preserve-state
    >
      <ButtonSecondary :disabled="!paginatedResource.links.prev" :size="'sm'">
        {{ $t('Previous') }}
      </ButtonSecondary>
    </component>

    <component
      :is="paginatedResource.links.next ? Link : 'div'"
      :href="paginatedResource.links.next"
      data-cy="pagination-next-link"
      preserve-state
    >
      <ButtonSecondary :disabled="!paginatedResource.links.next" :size="'sm'">
        {{ $t('Next') }}
      </ButtonSecondary>
    </component>
  </div>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3'
import ButtonSecondary from '@/components/ButtonSecondary.vue'
import { PaginatedResponse } from '@/types/framework'

type Props = {
  paginatedResource: PaginatedResponse<any>
}

defineProps<Props>()
</script>
