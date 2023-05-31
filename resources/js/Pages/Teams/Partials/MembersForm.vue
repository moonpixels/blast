<template>
  <TwoColumnForm
    :description="$t('teams.members_section.description')"
    :title="$t('teams.members_section.title')"
  >
    <Alert v-if="team.personal_team" data-cy="personal-team-alert">
      {{ $t('teams.members_section.personal_team_text') }}
    </Alert>

    <p v-else class="text-sm">
      Team members stuff will go here.
    </p>
  </TwoColumnForm>
</template>

<script lang="ts" setup>
import TwoColumnForm from '@/Components/Forms/TwoColumnForm.vue'
import { Team } from '@/types'
import Alert from '@/Components/Alerts/Alert.vue'

interface Props {
  team: Team
}

const props = defineProps<Props>()
</script>