<template>
  <AppHead :title="$t('auth.reset_password_meta_title')" />

  <AuthHeader :title="$t('auth.reset_password_title')" />

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" data-cy="reset-password-form" @submit.prevent="submit">
      <TextInput
        v-model="form.email"
        :error="form.errors.email"
        :label="$t('common.email')"
        autocomplete="email"
        required
        type="email"
        @input="form.clearErrors('email')"
      />

      <TextInput
        v-model="form.password"
        :error="form.errors.password"
        :label="$t('common.new_password')"
        autocomplete="new-password"
        required
        type="password"
        @input="form.clearErrors('password')"
      />

      <PrimaryButton :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
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
  token: string
  email: string
  password: string
}

const form = useForm<ResetPasswordForm>({
  token: props.token,
  email: props.email,
  password: '',
})

function submit(): void {
  form.post(route('password.update'))
}
</script>
