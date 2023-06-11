<template>
  <AppHead :title="$t('Confirm your password')" />

  <AuthHeader :title="$t('Confirm your password')">
    <p>
      {{ $t('Please confirm your password before continuing.') }}
    </p>
  </AuthHeader>

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" data-cy="confirm-password-form" @submit.prevent="submit">
      <TextInput
        v-model="form.password"
        :error="form.errors.password"
        :label="$t('Password')"
        autocomplete="current-password"
        required
        type="password"
        @input="form.clearErrors('password')"
      />

      <PrimaryButton :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('Confirm password') }}
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

type ConfirmPasswordForm = {
  password: string
}

const form = useForm<ConfirmPasswordForm>({
  password: '',
})

function submit(): void {
  form.post(route('password.confirm'))
}
</script>
