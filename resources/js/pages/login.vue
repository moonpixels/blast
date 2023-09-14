<template>
  <BaseHead :title="$t('Login')" />

  <FullPageHeading :title="$t('Log in to Blast')">
    <p class="text-emerald-600 dark:text-emerald-500" data-cy="status-message">
      {{ status }}
    </p>
  </FullPageHeading>

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" data-cy="login-form" @submit.prevent="submit">
      <BaseInput
        v-model="form.email"
        :error="form.errors.email"
        :label="$t('Email')"
        autocomplete="email"
        required
        type="email"
        @input="form.clearErrors('email')"
      />

      <BaseInput
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

        <BaseLink :href="route('password.request')" class="text-sm no-underline" data-cy="forgot-password-link">
          {{ $t('Forgot your password?') }}
        </BaseLink>
      </div>

      <BaseButton :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('Log in') }}
      </BaseButton>

      <Link :href="route('register')" data-cy="register-link">
        <BaseButton class="mt-4 w-full" variant="plain">
          {{ $t("Don't have an account? Register here") }}
        </BaseButton>
      </Link>
    </form>
  </div>
</template>

<script lang="ts" setup>
import FullPageLayout from '@/layouts/FullPageLayout.vue'
import FullPageHeading from '@/components/FullPageHeading.vue'
import BaseHead from '@/components/BaseHead.vue'
import BaseInput from '@/components/BaseInput.vue'
import { Link, useForm } from '@inertiajs/vue3'
import BaseLink from '@/components/BaseLink.vue'
import InputCheckbox from '@/components/InputCheckbox.vue'
import BaseButton from '@/components/BaseButton.vue'

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
