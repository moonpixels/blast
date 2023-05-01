<template>
  <AppHead :title="$t('auth.reset_password_meta_title')" />

  <AuthHeader :title="$t('auth.reset_password_title')" />

  <div class="mt-10 sm:w-full sm:max-w-sm sm:mx-auto space-y-10">
    <form class="space-y-4" data-cy="reset-password-form" @submit.prevent="submit">
      <TextInput
        v-model="resetPasswordForm.email"
        :error="resetPasswordForm.errors.email"
        :label="$t('common.email')"
        autocomplete="email"
        required
        type="email"
        @input="resetPasswordForm.clearErrors('email')"
      />

      <TextInput
        v-model="resetPasswordForm.password"
        :error="resetPasswordForm.errors.password"
        :label="$t('common.new_password')"
        autocomplete="new-password"
        required
        type="password"
        @input="resetPasswordForm.clearErrors('password')"
      />

      <PrimaryButton :loading="resetPasswordForm.processing" class="w-full" data-cy="submit-button" type="submit">
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

defineOptions({
  layout: AuthLayout,
})

interface Props {
  token: string
  email: string
}

const props = defineProps<Props>()

type ResetPasswordForm = {
  token: string,
  email: string,
  password: string,
}

const resetPasswordForm = useForm<ResetPasswordForm>({
  token: props.token,
  email: props.email,
  password: '',
})

function submit(): void {
  resetPasswordForm.post(route('password.update'))
}
</script>