<template>
  <AppHead :title="$t('account.settings_meta_title')" />

  <ConstrainedContainer>
    <h1 class="text-xl font-semibold text-zinc-900 dark:text-white" data-cy="confirm-password-title">
      {{ $t('account.settings_title') }}
    </h1>

    <TwoColumnFormContainer>
      <PersonalInfoForm :user="user" />

      <PasswordForm :user="user" />

      <TwoFactorForm
        :status="status"
        :two-factor-qr-code="twoFactorQrCode"
        :two-factor-recovery-codes="twoFactorRecoveryCodes"
        :user="user"
      />
    </TwoColumnFormContainer>
  </ConstrainedContainer>
</template>

<script lang="ts" setup>
import AppHead from '@/Components/App/AppHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ConstrainedContainer from '@/Components/Containers/ConstrainedContainer.vue'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { PageProps, User } from '@/types'
import TwoColumnFormContainer from '@/Components/Forms/TwoColumnFormContainer.vue'
import PasswordForm from '@/Pages/AccountSettings/Partials/PasswordForm.vue'
import TwoFactorForm from '@/Pages/AccountSettings/Partials/TwoFactorForm.vue'
import PersonalInfoForm from '@/Pages/AccountSettings/Partials/ProfileInformationForm.vue'

defineOptions({
  layout: AppLayout,
})

interface Props {
  status?: string
  twoFactorQrCode?: string
  twoFactorRecoveryCodes?: string[]
}

defineProps<Props>()

const user = computed<User>(() => {
  return usePage<PageProps<{}>>().props.user
})
</script>
