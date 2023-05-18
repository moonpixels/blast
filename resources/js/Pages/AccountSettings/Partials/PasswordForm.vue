<template>
  <TwoColumnForm
    :description="$t('account.password_settings_description')"
    :title="$t('account.password_settings_title')"
  >
    <form class="max-w-md space-y-6" data-cy="password-form" @submit.prevent="submit">
      <TextInput
        v-model="form.current_password"
        :error="form.errors.current_password"
        :label="$t('common.current_password')"
        autocomplete="current-password"
        required
        type="password"
        @input="form.clearErrors('current_password')"
      />

      <TextInput
        v-model="form.password"
        :error="form.errors.password"
        :label="$t('common.new_password')"
        autocomplete="new-password"
        required
        type="password"
        @input="form.clearErrors('password')"
      />

      <PrimaryButton :loading="form.processing" data-cy="submit-button" type="submit">
        {{ $t('account.password_settings_button') }}
      </PrimaryButton>
    </form>
  </TwoColumnForm>
</template>

<script lang="ts" setup>
import { User } from '@/types'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import TwoColumnForm from '@/Components/Forms/TwoColumnForm.vue'
import { useForm } from '@inertiajs/vue3'
import TextInput from '@/Components/Inputs/TextInput.vue'

interface Props {
  user: User
}

const props = defineProps<Props>()

type PasswordForm = {
  current_password: string,
  password: string,
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