<template>
  <AppHead :title="$t('Set new password')" />

  <AuthHeader :title="$t('Set new password')" />

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" data-cy="reset-password-form" @submit.prevent="submit">
      <TextInput
        v-model="form.email"
        :error="form.errors.email"
        :label="$t('Email')"
        autocomplete="email"
        required
        type="email"
        @input="form.clearErrors('email')"
      />

      <TextInput
        v-model="form.password"
        :error="form.errors.password"
        :label="$t('New password')"
        autocomplete="new-password"
        required
        type="password"
        @input="form.clearErrors('password')"
      />

      <PrimaryButton :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('Reset password') }}
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

type Props = {
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
