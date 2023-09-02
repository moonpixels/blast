<template>
  <AppHead :title="$t('API Settings')" />

  <ConstrainedContainer>
    <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">
      {{ $t('API settings') }}
    </h1>

    <TwoColumnFormContainer>
      <ApiDocs />
      <ApiTokens :tokens="tokens" />
      <Subscription />

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
    </TwoColumnFormContainer>
  </ConstrainedContainer>
</template>

<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ConstrainedContainer from '@/Components/Containers/ConstrainedContainer.vue'
import AppHead from '@/Components/App/AppHead.vue'
import TwoColumnFormContainer from '@/Components/Forms/TwoColumnFormContainer.vue'
import ApiDocs from '@/Pages/Settings/Api/Partials/ApiDocs.vue'
import ApiTokens from '@/Pages/Settings/Api/Partials/ApiTokens.vue'
import { Token } from '@/types/models'
import Modal from '@/Components/Modals/Modal.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import { ref, watch } from 'vue'
import Subscription from '@/Pages/Settings/Api/Partials/Subscription.vue'

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
