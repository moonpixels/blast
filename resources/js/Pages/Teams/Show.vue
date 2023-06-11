<template>
  <AppHead :title="$t(':team_name settings', { team_name: team.name })" />

  <ConstrainedContainer>
    <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">
      {{ $t(':team_name settings', { team_name: team.name }) }}
    </h1>

    <TwoColumnFormContainer>
      <GeneralForm :team="team" />

      <TeamMembersForm :invitations="invitations" :members="members" :team="team" />

      <DeleteTeamForm :team="team" />
    </TwoColumnFormContainer>
  </ConstrainedContainer>
</template>

<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ConstrainedContainer from '@/Components/Containers/ConstrainedContainer.vue'
import AppHead from '@/Components/App/AppHead.vue'
import TwoColumnFormContainer from '@/Components/Forms/TwoColumnFormContainer.vue'
import GeneralForm from '@/Pages/Teams/Partials/GeneralForm.vue'
import TeamMembersForm from '@/Pages/Teams/Partials/TeamMembersForm.vue'
import DeleteTeamForm from '@/Pages/Teams/Partials/DeleteTeamForm.vue'
import { Team, TeamInvitation, User } from '@/types/models'
import { PaginatedResponse } from '@/types/framework'

defineOptions({
  layout: AppLayout,
})

interface Props {
  team: Team
  members: PaginatedResponse<User>
  invitations: PaginatedResponse<TeamInvitation>
}

defineProps<Props>()
</script>
