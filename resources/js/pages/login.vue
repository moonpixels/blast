<template>
  <AppHead :title="$t('Login')" />

  <FullPageHeading :title="$t('Log in to Blast')">
    <p class="text-emerald-600 dark:text-emerald-500" data-cy="status-message">
      {{ status }}
    </p>
  </FullPageHeading>

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
        <InputCheckbox v-model="form.remember" :error="form.errors.remember" :label="$t('Remember me')" />

        <AppLink :href="route('password.request')" class="text-sm no-underline" data-cy="forgot-password-link">
          {{ $t('Forgot your password?') }}
        </AppLink>
      </div>

      <ButtonPrimary :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('Log in') }}
      </ButtonPrimary>
    </form>

    <div>
      <Link :href="route('register')" data-cy="register-link">
        <ButtonSecondary class="w-full">
          {{ $t("Don't have an account? Register here") }}
        </ButtonSecondary>
      </Link>
    </div>
  </div>
</template>

<script lang="ts" setup>
import FullPageLayout from '@/layouts/FullPageLayout.vue'
import FullPageHeading from '@/components/FullPageHeading.vue'
import AppHead from '@/components/AppHead.vue'
import TextInput from '@/components/AppInput.vue'
import { Link, useForm } from '@inertiajs/vue3'
import ButtonPrimary from '@/components/ButtonPrimary.vue'
import ButtonSecondary from '@/components/ButtonSecondary.vue'
import AppLink from '@/components/AppLink.vue'
import InputCheckbox from '@/components/InputCheckbox.vue'

defineOptions({
  layout: FullPageLayout,
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
