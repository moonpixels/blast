<template>
  <BaseHead :title="$t('Register')" />

  <FullPageHeading :title="$t('Create your account')" />

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" data-cy="register-form" @submit.prevent="submit">
      <BaseInput
        v-model="form.name"
        :error="form.errors.name"
        :label="$t('Full name')"
        autocomplete="name"
        required
        @input="form.clearErrors('name')"
      />

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
        autocomplete="new-password"
        required
        type="password"
        @input="form.clearErrors('password')"
      />

      <BaseButton :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('Create account') }}
      </BaseButton>

      <Link :href="route('login')" data-cy="login-link">
        <BaseButton class="mt-4 w-full" variant="plain">
          {{ $t('Already have an account? Login here') }}
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
import BaseButton from '@/components/BaseButton.vue'
import UserData = App.Domain.User.Data.UserData

defineOptions({
  layout: FullPageLayout,
})

const form = useForm<UserData>({
  name: '',
  email: '',
  password: '',
})

function submit(): void {
  form.post(route('register'))
}
</script>
