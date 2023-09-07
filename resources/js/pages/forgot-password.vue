<template>
  <AppHead :title="$t('Reset your password')" />

  <FullPageHeading :title="$t('Reset your password')">
    <p v-if="resetEmailSent" data-cy="success-message">
      {{
        $t('We sent a password reset link to :email. Please allow a few minutes for it to arrive.', {
          email: form.email,
        })
      }}
    </p>
  </FullPageHeading>

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form v-if="!resetEmailSent" class="space-y-6" data-cy="forgot-password-form" @submit.prevent="submit">
      <TextInput
        v-model="form.email"
        :error="form.errors.email"
        :label="$t('Email')"
        autocomplete="email"
        required
        type="email"
        @input="form.clearErrors('email')"
      />

      <ButtonPrimary :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('Reset password') }}
      </ButtonPrimary>
    </form>
  </div>
</template>

<script lang="ts" setup>
import FullPageLayout from '@/layouts/FullPageLayout.vue'
import FullPageHeading from '@/components/FullPageHeading.vue'
import AppHead from '@/components/AppHead.vue'
import TextInput from '@/components/AppInput.vue'
import { useForm } from '@inertiajs/vue3'
import ButtonPrimary from '@/components/ButtonPrimary.vue'
import { ref } from 'vue'

defineOptions({
  layout: FullPageLayout,
})

type ForgotPasswordForm = {
  email: string
}

const form = useForm<ForgotPasswordForm>({
  email: '',
})

const resetEmailSent = ref<boolean>(false)

function submit(): void {
  form.post(route('password.request'), {
    onSuccess: () => {
      resetEmailSent.value = true
    },
  })
}
</script>
