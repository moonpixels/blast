<template>
  <AppHead :title="$t('Enter password for link')" />

  <AuthHeader :title="$t('Password Required')">
    <p>
      {{ $t('Please enter the password for the link to continue.') }}
    </p>
  </AuthHeader>

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" data-cy="authenticated-redirect-form" @submit.prevent="submit">
      <TextInput
        v-model="form.password"
        :error="form.errors.password"
        :label="$t('Password')"
        autocomplete="none"
        required
        type="password"
        @input="form.clearErrors('password')"
      />

      <PrimaryButton :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('Continue') }}
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
  alias: string
}

const props = defineProps<Props>()

type AuthenticatedRedirectForm = {
  password: string
}

const form = useForm<AuthenticatedRedirectForm>({
  password: '',
})

function submit(): void {
  form.post(route('authenticated-redirect', props.alias))
}
</script>
