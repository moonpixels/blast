<template>
  <TwoColumnForm :description="$t('Update your team\'s general settings.')" :title="$t('General settings')">
    <form class="max-w-md space-y-6" data-cy="general-settings-form" @submit.prevent="submit">
      <TextInput
        v-model="form.name"
        :error="form.errors.name"
        :label="$t('Team name')"
        required
        @input="form.clearErrors('name')"
      />

      <PrimaryButton :loading="form.processing" data-cy="submit-button" type="submit">
        {{ $t('Update team') }}
      </PrimaryButton>
    </form>
  </TwoColumnForm>
</template>

<script lang="ts" setup>
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import TwoColumnForm from '@/Components/Forms/TwoColumnForm.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import { useForm } from '@inertiajs/vue3'
import { Team } from '@/types/models'

interface Props {
  team: Team
}

const props = defineProps<Props>()

type ProfileInfoForm = {
  name: string
}

const form = useForm<ProfileInfoForm>({
  name: props.team.name,
})

function submit(): void {
  form.put(route('teams.update', props.team.id), {
    preserveScroll: true,
  })
}
</script>
