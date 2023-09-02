<template>
  <TwoColumnForm :description="$t('Create and manage your API tokens.')" :title="$t('Tokens')">
    <ResourcePanel>
      <ResourcePanelHeader>
        <CreatePersonalAccessTokenModal />
      </ResourcePanelHeader>

      <SimpleEmptyState
        v-if="!tokens.length"
        :description="$t('You have not created any API tokens yet.')"
        :title="$t('No tokens')"
      >
        <template #icon>
          <TicketIcon class="h-8 w-8 text-zinc-400 dark:text-zinc-500" />
        </template>
      </SimpleEmptyState>

      <ResourcePanelList v-else>
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
            <DeletePersonalAccessTokenModal :token="token" />
          </template>
        </ResourcePanelListItem>
      </ResourcePanelList>
    </ResourcePanel>
  </TwoColumnForm>
</template>

<script lang="ts" setup>
import TwoColumnForm from '@/Components/Forms/TwoColumnForm.vue'
import ResourcePanelHeader from '@/Components/ResourcePanel/ResourcePanelHeader.vue'
import ResourcePanel from '@/Components/ResourcePanel/ResourcePanel.vue'
import { TicketIcon } from '@heroicons/vue/24/outline'
import SimpleEmptyState from '@/Components/EmptyStates/SimpleEmptyState.vue'
import { Token } from '@/types/models'
import ResourcePanelListItem from '@/Components/ResourcePanel/ResourcePanelListItem.vue'
import ResourcePanelList from '@/Components/ResourcePanel/ResourcePanelList.vue'
import useFormatDate from '../../../../composables/useFormatDate'
import DeletePersonalAccessTokenModal from '@/Pages/Settings/Api/Partials/DeletePersonalAccessTokenModal.vue'
import CreatePersonalAccessTokenModal from '@/Pages/Settings/Api/Partials/CreatePersonalAccessTokenModal.vue'

type Props = {
  tokens: Token[]
}

defineProps<Props>()
</script>
