<template>
  <TwoColumnBlockItem :description="$t('Update your team\'s general settings.')" :title="$t('General settings')">
    <form class="max-w-md space-y-6" data-cy="general-settings-form" @submit.prevent="submit">
      <BaseInput
        v-model="form.name"
        :error="form.errors.name"
        :label="$t('Team name')"
        required
        @input="form.clearErrors('name')"
      />

      <BaseButton :loading="form.processing" data-cy="submit-button" type="submit">
        {{ $t('Update team') }}
      </BaseButton>
    </form>
  </TwoColumnBlockItem>
</template>

<script lang="ts" setup>
import TwoColumnBlockItem from '@/components/TwoColumnBlockItem.vue'
import BaseInput from '@/components/BaseInput.vue'
import { useForm } from '@inertiajs/vue3'
import { Team } from '@/types/models'
import BaseButton from '@/components/BaseButton.vue'
import TeamData = App.Domain.Team.Data.TeamData

type Props = {
  team: Team
}

const props = defineProps<Props>()

const form = useForm<TeamData>({
  name: props.team.name,
})

function submit(): void {
  form.put(route('teams.update', props.team.id), {
    preserveScroll: true,
  })
}
</script>
