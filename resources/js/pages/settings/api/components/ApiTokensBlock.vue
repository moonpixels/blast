<template>
  <TwoColumnBlockItem :description="$t('Create and manage your API tokens.')" :title="$t('Tokens')">
    <ResourcePanel>
      <template #header>
        <ApiTokensCreateModal />
      </template>

      <BaseEmptyState
        v-if="!tokens.length"
        :description="$t('You have not created any API tokens yet.')"
        :icon="TicketIcon"
        :title="$t('No tokens')"
      />

      <template v-if="tokens.length" #list>
        <ResourcePanelListItem v-for="token in tokens" :key="token.id">
          <template #content>
            <div class="flex flex-auto gap-x-4 overflow-hidden">
              <div class="flex-auto overflow-hidden">
                <p class="truncate text-sm font-semibold leading-6 text-zinc-900 dark:text-white">
                  {{ token.name }}
                </p>
                <p class="text-xs leading-5">
                  Last used:
                  {{ token.last_used_at ? useFormatDate(token.last_used_at) : $t('Never') }}
                </p>
              </div>
            </div>
          </template>

          <template #actions>
            <ApiTokensDeleteModal :token="token" />
          </template>
        </ResourcePanelListItem>
      </template>
    </ResourcePanel>
  </TwoColumnBlockItem>
</template>

<script lang="ts" setup>
import TwoColumnBlockItem from '@/components/TwoColumnBlockItem.vue'
import ResourcePanel from '@/components/ResourcePanel.vue'
import { TicketIcon } from '@heroicons/vue/24/outline'
import BaseEmptyState from '@/components/BaseEmptyState.vue'
import { Token } from '@/types/models'
import ResourcePanelListItem from '@/components/ResourcePanelListItem.vue'
import useFormatDate from '../../../../composables/useFormatDate'
import ApiTokensDeleteModal from '@/pages/settings/api/components/ApiTokensDeleteModal.vue'
import ApiTokensCreateModal from '@/pages/settings/api/components/ApiTokensCreateModal.vue'

type Props = {
  tokens: Token[]
}

defineProps<Props>()
</script>
