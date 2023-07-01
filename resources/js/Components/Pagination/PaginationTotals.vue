<template>
  <span class="text-xs">
    {{ $t('Showing') }}
    <span class="font-semibold">{{ paginatedResource.meta.from }}</span>
    {{ $t('to') }}
    <span class="font-semibold">{{ paginatedResource.meta.to }}</span>
    {{ $t('of') }}
    <span class="font-semibold">{{ paginatedResource.meta.total }}</span>
    {{ resourceName }}
  </span>
</template>

<script lang="ts" setup>
import { PaginatedResponse } from '@/types/framework'

interface Props {
  resourceName: string
  paginatedResource: PaginatedResponse<any>
}

defineProps<Props>()
</script>
