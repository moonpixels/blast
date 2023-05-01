<template>
  <AppHead :title="$t('auth.confirm_password_meta_title')" />

  <AuthHeader :title="$t('auth.confirm_password_title')">
    <p>
      {{ $t('auth.confirm_password_text') }}
    </p>
  </AuthHeader>

  <div class="mt-10 sm:w-full sm:max-w-sm sm:mx-auto space-y-10">
    <form class="space-y-4" data-cy="confirm-password-form" @submit.prevent="submit">
      <TextInput
        v-model="confirmPasswordForm.password"
        :error="confirmPasswordForm.errors.password"
        :label="$t('common.password')"
        autocomplete="current-password"
        required
        type="password"
        @input="confirmPasswordForm.clearErrors('password')"
      />

      <PrimaryButton :loading="confirmPasswordForm.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('auth.confirm_password_button') }}
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
  password: string,
}

const props = defineProps<Props>()

type ConfirmPasswordForm = {
  password: string,
}

const confirmPasswordForm = useForm<ConfirmPasswordForm>({
  password: '',
})

function submit(): void {
  confirmPasswordForm.post(route('password.confirm'))
}
</script>