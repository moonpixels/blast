<template>
  <AppHead :title="$t('Team settings')" />

  <ConstrainedContainer>
    <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">
      {{ $t(':team_name settings', { team_name: team.name }) }}
    </h1>

    <TwoColumnFormContainer>
      <GeneralForm v-if="currentUserIsOwner()" :team="team" />

      <TeamMembersForm v-if="currentUserIsOwner()" v-bind="$props" />

      <DeleteTeamForm v-if="currentUserIsOwner()" :team="team" />

      <LeaveTeamForm v-if="!currentUserIsOwner() && teamMembership" :team="team" :team-membership="teamMembership" />
    </TwoColumnFormContainer>
  </ConstrainedContainer>
</template>

<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ConstrainedContainer from '@/Components/Containers/ConstrainedContainer.vue'
import AppHead from '@/Components/App/AppHead.vue'
import TwoColumnFormContainer from '@/Components/Forms/TwoColumnFormContainer.vue'
import GeneralForm from '@/Pages/Teams/Partials/GeneralForm.vue'
import TeamMembersForm, { Filters } from '@/Pages/Teams/Partials/TeamMembersForm.vue'
import DeleteTeamForm from '@/Pages/Teams/Partials/DeleteTeamForm.vue'
import { CurrentUser, Team, TeamInvitation, TeamMembership } from '@/types/models'
import { PaginatedResponse } from '@/types/framework'
import { usePage } from '@inertiajs/vue3'
import { PageProps } from '@/types'
import LeaveTeamForm from '@/Pages/Teams/Partials/LeaveTeamForm.vue'

defineOptions({
  layout: AppLayout,
})

interface Props {
  team: Team
  teamMembership?: TeamMembership
  memberships?: PaginatedResponse<TeamMembership>
  invitations?: PaginatedResponse<TeamInvitation>
  filters: Filters
}

const props = defineProps<Props>()

const currentUser = usePage<PageProps>().props.user as CurrentUser

function currentUserIsOwner(): boolean {
  return currentUser.id === props.team.owner_id
}
</script>
