<template>
  <TwoColumnBlockItem :description="$t('Update your account\'s password.')" :title="$t('Password')">
    <form class="max-w-md space-y-6" data-cy="password-form" @submit.prevent="submit">
      <TextInput
        v-model="form.current_password"
        :error="form.errors.current_password"
        :label="$t('Current password')"
        autocomplete="current-password"
        required
        type="password"
        @input="form.clearErrors('current_password')"
      />

      <TextInput
        v-model="form.password"
        :error="form.errors.password"
        :label="$t('New password')"
        autocomplete="new-password"
        required
        type="password"
        @input="form.clearErrors('password')"
      />

      <ButtonPrimary :loading="form.processing" data-cy="submit-button" type="submit">
        {{ $t('Update password') }}
      </ButtonPrimary>
    </form>
  </TwoColumnBlockItem>
</template>

<script lang="ts" setup>
import ButtonPrimary from '@/components/ButtonPrimary.vue'
import TwoColumnBlockItem from '@/components/TwoColumnBlockItem.vue'
import { useForm } from '@inertiajs/vue3'
import TextInput from '@/components/AppInput.vue'
import { User } from '@/types/models'

type Props = {
  user: User
}

defineProps<Props>()

type PasswordForm = {
  current_password: string
  password: string
}

const form = useForm<PasswordForm>({
  current_password: '',
  password: '',
})

function submit(): void {
  form.put(route('user-password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
    },
  })
}
</script>
