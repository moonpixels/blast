<template>
  <BaseHead :title="$t('Account settings')" />

  <BaseContainer>
    <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">
      {{ $t('Account settings') }}
    </h1>

    <TwoColumnBlock>
      <ProfileInformationBlock :user="user" />

      <ProfilePasswordBlock :user="user" />

      <ProfileTwoFactorBlock
        :status="status"
        :two-factor-qr-code="twoFactorQrCode"
        :two-factor-recovery-codes="twoFactorRecoveryCodes"
        :user="user"
      />

      <ProfileDeleteBlock />
    </TwoColumnBlock>
  </BaseContainer>
</template>

<script lang="ts" setup>
import BaseHead from '@/components/BaseHead.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import BaseContainer from '@/components/BaseContainer.vue'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { PageProps } from '@/types'
import TwoColumnBlock from '@/components/TwoColumnBlock.vue'
import ProfilePasswordBlock from '@/pages/settings/profile/components/ProfilePasswordBlock.vue'
import ProfileTwoFactorBlock from '@/pages/settings/profile/components/ProfileTwoFactorBlock.vue'
import ProfileInformationBlock from '@/pages/settings/profile/components/ProfileInformationBlock.vue'
import ProfileDeleteBlock from '@/pages/settings/profile/components/ProfileDeleteBlock.vue'
import { User } from '@/types/models'

defineOptions({
  layout: AppLayout,
})

type Props = {
  status?: string
  twoFactorQrCode?: string
  twoFactorRecoveryCodes?: string[]
}

defineProps<Props>()

const user = computed<User>(() => {
  return usePage<PageProps>().props.user
})
</script>
