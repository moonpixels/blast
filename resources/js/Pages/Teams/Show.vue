<template>
  <AppHead :title="$t('Team settings')" />

  <ConstrainedContainer>
    <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">
      {{ $t(':team_name settings', { team_name: team.name }) }}
    </h1>

    <TwoColumnFormContainer>
      <GeneralSettingsForm v-if="currentUserIsOwner" :team="team" />

      <TeamMembersForm v-if="currentUserIsOwner" v-bind="$props" />

      <DeleteTeamForm v-if="currentUserIsOwner" :team="team" />

      <LeaveTeamForm v-if="!currentUserIsOwner && teamMembership" :team="team" :team-membership="teamMembership" />
    </TwoColumnFormContainer>
  </ConstrainedContainer>
</template>

<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ConstrainedContainer from '@/Components/Containers/ConstrainedContainer.vue'
import AppHead from '@/Components/App/AppHead.vue'
import TwoColumnFormContainer from '@/Components/Forms/TwoColumnFormContainer.vue'
import TeamMembersForm from '@/Pages/Teams/Partials/TeamMembersForm.vue'
import DeleteTeamForm from '@/Pages/Teams/Partials/DeleteTeamForm.vue'
import { Team, TeamInvitation, TeamMembership } from '@/types/models'
import { PaginatedResponse } from '@/types/framework'
import { usePage } from '@inertiajs/vue3'
import { PageProps } from '@/types'
import LeaveTeamForm from '@/Pages/Teams/Partials/LeaveTeamForm.vue'
import GeneralSettingsForm from '@/Pages/Teams/Partials/GeneralSettingsForm.vue'
import { computed } from 'vue'

defineOptions({
  layout: AppLayout,
})

type Props = {
  team: Team
  teamMembership?: TeamMembership
  memberships?: PaginatedResponse<TeamMembership>
  invitations?: PaginatedResponse<TeamInvitation>
  filters: {
    view: 'members' | 'invitations'
    query?: string
  }
}

const props = defineProps<Props>()

const currentUserIsOwner = computed<boolean>(() => {
  return usePage<PageProps>().props.user.id === props.team.owner?.id
})
</script>
