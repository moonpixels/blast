<template>
  <AppHead :title="recoveryMode ? $t('auth.2fa_challenge_meta_title') : $t('auth.2fa_challenge_meta_title')" />

  <AuthHeader :title="recoveryMode ? $t('auth.2fa_challenge_recovery_title') : $t('auth.2fa_challenge_title')">
    <p>
      {{ recoveryMode ? $t('auth.2fa_challenge_recovery_text') : $t('auth.2fa_challenge_text') }}
    </p>
  </AuthHeader>

  <div class="mt-10 sm:w-full sm:max-w-sm sm:mx-auto space-y-10">
    <form v-if="recoveryMode" class="space-y-6" data-cy="2fa-recovery-form" @submit.prevent="submit">
      <TextInput
        v-model="recoveryForm.recovery_code"
        :error="recoveryForm.errors.code || recoveryForm.errors.recovery_code"
        :label="$t('common.recovery_code')"
        required
        @input="recoveryForm.clearErrors('recovery_code')"
      />

      <PrimaryButton :loading="recoveryForm.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('auth.2fa_challenge_recovery_button') }}
      </PrimaryButton>
    </form>

    <form v-else class="space-y-6" data-cy="2fa-challenge-form" @submit.prevent="submit">
      <TextInput
        v-model="challengeForm.code"
        :error="challengeForm.errors.code"
        :label="$t('common.two_factor_code')"
        autocomplete="one-time-code"
        required
        @input="challengeForm.clearErrors('code')"
      />

      <PrimaryButton :loading="challengeForm.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('auth.2fa_challenge_button') }}
      </PrimaryButton>
    </form>

    <div>
      <SecondaryButton as="button" class="w-full" data-cy="switch-mode-button" @click="switchMode">
        {{ recoveryMode ? $t('auth.2fa_challenge_link') : $t('auth.2fa_challenge_recovery_link') }}
      </SecondaryButton>
    </div>
  </div>
</template>

<script lang="ts" setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import AuthHeader from '@/Pages/Auth/Partials/AuthHeader.vue'
import AppHead from '@/Components/App/AppHead.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import { useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import { ref } from 'vue'

defineOptions({
  layout: AuthLayout,
})

const recoveryMode = ref<boolean>(false)

type TwoFactorChallengeForm = {
  code: string,
}

const challengeForm = useForm<TwoFactorChallengeForm>({
  code: '',
})

type TwoFactorRecoveryForm = {
  recovery_code: string,
  code?: string,
}

const recoveryForm = useForm<TwoFactorRecoveryForm>({
  recovery_code: '',
})

function submit(): void {
  if (recoveryMode.value) {
    recoveryForm.post(route('two-factor.login'))
  } else {
    challengeForm.post(route('two-factor.login'))
  }
}

function switchMode(): void {
  recoveryMode.value = !recoveryMode.value
}
</script>