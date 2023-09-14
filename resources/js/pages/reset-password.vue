<template>
  <BaseHead :title="$t('Set new password')" />

  <FullPageHeading :title="$t('Set new password')" />

  <div class="mt-10 space-y-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" data-cy="reset-password-form" @submit.prevent="submit">
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
        :label="$t('New password')"
        autocomplete="new-password"
        required
        type="password"
        @input="form.clearErrors('password')"
      />

      <BaseButton :loading="form.processing" class="w-full" data-cy="submit-button" type="submit">
        {{ $t('Reset password') }}
      </BaseButton>
    </form>
  </div>
</template>

<script lang="ts" setup>
import FullPageLayout from '@/layouts/FullPageLayout.vue'
import FullPageHeading from '@/components/FullPageHeading.vue'
import BaseHead from '@/components/BaseHead.vue'
import BaseInput from '@/components/BaseInput.vue'
import { useForm } from '@inertiajs/vue3'
import BaseButton from '@/components/BaseButton.vue'

defineOptions({
  layout: FullPageLayout,
})

type Props = {
  token: string
  email: string
}

const props = defineProps<Props>()

type ResetPasswordForm = {
  token: string
  email: string
  password: string
}

const form = useForm<ResetPasswordForm>({
  token: props.token,
  email: props.email,
  password: '',
})

function submit(): void {
  form.post(route('password.update'))
}
</script>
