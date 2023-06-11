<template>
  <AppHead :title="$t('Register')" />

  <AuthHeader :title="$t('Create your account')" />

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" data-cy="register-form" @submit.prevent="submit">
      <TextInput
        v-model="form.name"
        :error="form.errors.name"
        :label="$t('Full name')"
        autocomplete="name"
        required
        @input="form.clearErrors('name')"
      />

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
        autocomplete="new-password"
        required
        type="password"
        @input="form.clearErrors('password')"
      />

      <PrimaryButton :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('Create account') }}
      </PrimaryButton>
    </form>

    <div>
      <Link :href="route('login')" data-cy="login-link">
        <SecondaryButton class="w-full">
          {{ $t('Already have an account? Login here') }}
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

defineOptions({
  layout: AuthLayout,
})

type RegistrationForm = {
  name: string
  email: string
  password: string
}

const form = useForm<RegistrationForm>({
  name: '',
  email: '',
  password: '',
})

function submit(): void {
  form.post(route('register'))
}
</script>
