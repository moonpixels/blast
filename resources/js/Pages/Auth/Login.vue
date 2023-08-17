<template>
  <AppHead :title="$t('Login')" />

  <AuthHeader :title="$t('Log in to Blast')">
    <p class="text-emerald-600 dark:text-emerald-500" data-cy="status-message">
      {{ status }}
    </p>
  </AuthHeader>

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" data-cy="login-form" @submit.prevent="submit">
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
        :label="$t('Password')"
        autocomplete="current-password"
        required
        type="password"
        @input="form.clearErrors('password')"
      />

      <div class="flex items-center justify-between">
        <Checkbox v-model="form.remember" :error="form.errors.remember" :label="$t('Remember me')" />

        <InternalLink :href="route('password.request')" class="text-sm no-underline" data-cy="forgot-password-link">
          {{ $t('Forgot your password?') }}
        </InternalLink>
      </div>

      <PrimaryButton :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('Log in') }}
      </PrimaryButton>
    </form>

    <div>
      <Link :href="route('register')" data-cy="register-link">
        <SecondaryButton class="w-full">
          {{ $t("Don't have an account? Register here") }}
        </SecondaryButton>
      </Link>
    </div>
  </div>
</template>

<script lang="ts" setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import AuthHeader from '@/Pages/Auth/Partials/AuthHeader.vue'
import AppHead from '@/Components/App/AppHead.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import { Link, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import InternalLink from '@/Components/Links/InternalLink.vue'
import Checkbox from '@/Components/Inputs/Checkbox.vue'

defineOptions({
  layout: AuthLayout,
})

type Props = {
  status: string | null
}

defineProps<Props>()

type LoginForm = {
  email: string
  password: string
  remember: boolean
}

const form = useForm<LoginForm>({
  email: '',
  password: '',
  remember: false,
})

function submit(): void {
  form.post(route('login'))
}
</script>
