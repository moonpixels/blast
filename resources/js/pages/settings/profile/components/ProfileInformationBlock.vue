<template>
  <TwoColumnBlockItem
    :description="$t('Update your account\'s profile information and email address.')"
    :title="$t('Profile information')"
  >
    <form class="max-w-md space-y-6" data-cy="profile-information-form" @submit.prevent="submit">
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

      <BaseButton :loading="form.processing" data-cy="submit-button" type="submit">
        {{ $t('Update information') }}
      </BaseButton>
    </form>
  </TwoColumnBlockItem>
</template>

<script lang="ts" setup>
import TwoColumnBlockItem from '@/components/TwoColumnBlockItem.vue'
import BaseInput from '@/components/BaseInput.vue'
import { useForm } from '@inertiajs/vue3'
import { User } from '@/types/models'
import BaseButton from '@/components/BaseButton.vue'

type Props = {
  user: User
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
