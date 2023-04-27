<template>
  <AppHead :title="$t('auth.register_meta_title')" />

  <AuthHeader :title="$t('auth.register_title')" />

  <div class="mt-10 sm:w-full sm:max-w-sm sm:mx-auto space-y-10">
    <form class="space-y-4" data-cy="register-form" @submit.prevent="submit">
      <TextInput
        v-model="registrationForm.name"
        :error="registrationForm.errors.name"
        :label="$t('common.full_name')"
        autocomplete="name"
        required
        @input="registrationForm.clearErrors('name')"
      />

      <TextInput
        v-model="registrationForm.email"
        :error="registrationForm.errors.email"
        :label="$t('common.email')"
        autocomplete="email"
        required
        type="email"
        @input="registrationForm.clearErrors('email')"
      />

      <TextInput
        v-model="registrationForm.password"
        :error="registrationForm.errors.password"
        :label="$t('common.password')"
        autocomplete="new-password"
        required type="password"
        @input="registrationForm.clearErrors('password')"
      />

      <PrimaryButton :loading="registrationForm.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('auth.register_button') }}
      </PrimaryButton>
    </form>

    <div>
      <Link :href="route('login')" data-cy="login-link">
        <SecondaryButton class="w-full">
          {{ $t('auth.register_login_link') }}
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
  name: string,
  email: string,
  password: string,
}

const registrationForm = useForm<RegistrationForm>({
  name: '',
  email: '',
  password: '',
})

function submit(): void {
  registrationForm.post(route('register'))
}
</script>