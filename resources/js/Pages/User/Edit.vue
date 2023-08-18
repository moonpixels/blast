<template>
  <AppHead :title="$t('Account settings')" />

  <ConstrainedContainer>
    <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">
      {{ $t('Account settings') }}
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

      <DeleteForm />
    </TwoColumnFormContainer>
  </ConstrainedContainer>
</template>

<script lang="ts" setup>
import AppHead from '@/Components/App/AppHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ConstrainedContainer from '@/Components/Containers/ConstrainedContainer.vue'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { PageProps } from '@/types'
import TwoColumnFormContainer from '@/Components/Forms/TwoColumnFormContainer.vue'
import PasswordForm from '@/Pages/User/Partials/PasswordForm.vue'
import TwoFactorForm from '@/Pages/User/Partials/TwoFactorForm.vue'
import PersonalInfoForm from '@/Pages/User/Partials/ProfileInformationForm.vue'
import DeleteForm from '@/Pages/User/Partials/DeleteForm.vue'
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
