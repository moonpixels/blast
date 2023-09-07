<template>
  <AppHead :title="$t('API Settings')" />

  <AppContainer>
    <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">
      {{ $t('API settings') }}
    </h1>

    <TwoColumnBlock>
      <TwoColumnBlockItem
        :description="$t('View the API documentation to integrate Blast into your application.')"
        :title="$t('API docs')"
      >
        <div class="flex h-full items-center">
          <a href="https://blst.to/docs">
            <PrimaryButton>
              {{ $t('View API docs') }}
            </PrimaryButton>
          </a>
        </div>
      </TwoColumnBlockItem>

      <ApiTokensBlock :tokens="tokens" />

      <ApiSubscriptionBlock />

      <Modal :show="showNewTokenModal" :title="$t('Your new personal access token')" @close="showNewTokenModal = false">
        <template #body>
          <p>
            {{
              $t(
                'Here is your new personal access token. This is the only time it will be shown, so please store it somewhere safe.'
              )
            }}
          </p>

          <div
            class="mt-4 select-all overflow-x-auto rounded border border-zinc-900/20 bg-zinc-50 p-3 font-mono text-xs dark:border-white/20 dark:bg-zinc-900"
          >
            {{ plainTextToken }}
          </div>
        </template>

        <template #footer>
          <SecondaryButton @click="showNewTokenModal = false">
            {{ $t('Close') }}
          </SecondaryButton>
        </template>
      </Modal>
    </TwoColumnBlock>
  </AppContainer>
</template>

<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue'
import AppContainer from '@/components/AppContainer.vue'
import AppHead from '@/components/AppHead.vue'
import TwoColumnBlock from '@/components/TwoColumnBlock.vue'
import ApiTokensBlock from '@/pages/settings/api/components/ApiTokensBlock.vue'
import { Token } from '@/types/models'
import Modal from '@/components/AppModal.vue'
import SecondaryButton from '@/components/ButtonSecondary.vue'
import { ref, watch } from 'vue'
import ApiSubscriptionBlock from '@/pages/settings/api/components/ApiSubscriptionBlock.vue'
import PrimaryButton from '@/components/ButtonPrimary.vue'
import TwoColumnBlockItem from '@/components/TwoColumnBlockItem.vue'

defineOptions({
  layout: AppLayout,
})

type Props = {
  tokens: Token[]
  plainTextToken: string | null
}

const props = defineProps<Props>()

const showNewTokenModal = ref<boolean>(false)

watch(
  () => props.plainTextToken,
  (value) => {
    if (value) {
      showNewTokenModal.value = true
    }
  }
)
</script>
