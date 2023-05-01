<template>
  <AppHead :title="$t('auth.forgot_password_meta_title')" />

  <AuthHeader :title="$t('auth.forgot_password_title')">
    <p v-if="resetEmailSent" data-cy="success-message">
      {{ $t('auth.forgot_password_success', { email: forgotPasswordForm.email }) }}
    </p>
  </AuthHeader>

  <div class="mt-10 sm:w-full sm:max-w-sm sm:mx-auto space-y-10">
    <form v-if="! resetEmailSent" class="space-y-4" data-cy="forgot-password-form" @submit.prevent="submit">
      <TextInput
        v-model="forgotPasswordForm.email"
        :error="forgotPasswordForm.errors.email"
        :label="$t('common.email')"
        autocomplete="email"
        required
        type="email"
        @input="forgotPasswordForm.clearErrors('email')"
      />

      <PrimaryButton :loading="forgotPasswordForm.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('auth.forgot_password_button') }}
      </PrimaryButton>
    </form>
  </div>
</template>

<script lang="ts" setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import AuthHeader from '@/Pages/Auth/Partials/AuthHeader.vue'
import AppHead from '@/Components/App/AppHead.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import { useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import { ref } from 'vue'

defineOptions({
  layout: AuthLayout,
})

type ForgotPasswordForm = {
  email: string,
}

const forgotPasswordForm = useForm<ForgotPasswordForm>({
  email: '',
})

const resetEmailSent = ref<boolean>(false)

function submit(): void {
  forgotPasswordForm.post(route('password.request'), {
    onSuccess: () => {
      resetEmailSent.value = true
    },
  })
}
</script>