<template>
  <AppHead :title="$t('Confirm your password')" />

  <FullPageHeading :title="$t('Confirm your password')">
    <p>
      {{ $t('Please confirm your password before continuing.') }}
    </p>
  </FullPageHeading>

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

      <ButtonPrimary :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('Confirm password') }}
      </ButtonPrimary>
    </form>
  </div>
</template>

<script lang="ts" setup>
import FullPageLayout from '@/layouts/FullPageLayout.vue'
import FullPageHeading from '@/components/FullPageHeading.vue'
import AppHead from '@/components/AppHead.vue'
import TextInput from '@/components/AppInput.vue'
import { useForm } from '@inertiajs/vue3'
import ButtonPrimary from '@/components/ButtonPrimary.vue'

defineOptions({
  layout: FullPageLayout,
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
