<template>
  <TwoColumnForm
    :description="$t('auth.personal_info_settings_description')"
    :title="$t('auth.personal_info_settings_title')"
  >
    <form class="max-w-md space-y-6" data-cy="personal-info-form" @submit.prevent="submit">
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
        {{ $t('auth.personal_info_settings_button') }}
      </PrimaryButton>
    </form>
  </TwoColumnForm>
</template>

<script lang="ts" setup>
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import TwoColumnForm from '@/Components/Forms/TwoColumnForm.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import PersonalInfoForm from '@/Pages/AccountSettings/Partials/PersonalInfoForm.vue'
import { useForm } from '@inertiajs/vue3'
import { User } from '@/types'

interface Props {
  user: User
}

const props = defineProps<Props>()

type PersonalInfoForm = {
  name: string,
  email: string,
}

const form = useForm<PersonalInfoForm>({
  name: props.user.name,
  email: props.user.email,
})

function submit(): void {}
</script>