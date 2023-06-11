<template>
  <TwoColumnForm
    :description="$t('account.profile_info_settings_description')"
    :title="$t('account.profile_info_settings_title')"
  >
    <form class="max-w-md space-y-6" data-cy="profile-information-form" @submit.prevent="submit">
      <TextInput
        v-model="form.name"
        :error="form.errors.name"
        :label="$t('common.full_name')"
        autocomplete="name"
        required
        @input="form.clearErrors('name')"
      />

      <TextInput
        v-model="form.email"
        :error="form.errors.email"
        :label="$t('common.email')"
        autocomplete="email"
        required
        type="email"
        @input="form.clearErrors('email')"
      />

      <PrimaryButton :loading="form.processing" data-cy="submit-button" type="submit">
        {{ $t('account.profile_info_settings_button') }}
      </PrimaryButton>
    </form>
  </TwoColumnForm>
</template>

<script lang="ts" setup>
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import TwoColumnForm from '@/Components/Forms/TwoColumnForm.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import { useForm } from '@inertiajs/vue3'
import { CurrentUser } from '@/types/models'

interface Props {
  user: CurrentUser
}

const props = defineProps<Props>()

type ProfileInfoForm = {
  name: string
  email: string
}

const form = useForm<ProfileInfoForm>({
  name: props.user.name,
  email: props.user.email,
})

function submit(): void {
  form.put(route('user-profile-information.update'), {
    preserveScroll: true,
  })
}
</script>
