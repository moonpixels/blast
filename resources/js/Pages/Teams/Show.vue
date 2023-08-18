<template>
  <AppHead :title="$t('Team settings')" />

  <ConstrainedContainer>
    <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">
      {{ $t(':team_name settings', { team_name: team.name }) }}
    </h1>

    <TwoColumnFormContainer>
      <template v-if="currentUserIsOwner">
        <GeneralSettingsForm :team="team" />

        <TeamMembersForm :filters="filters" :invitations="invitations" :members="members" :team="team" />

        <DeleteTeamForm :team="team" />
      </template>

      <template v-else>
        <LeaveTeamForm :team="team" :user="user" />
      </template>
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
import { Team, TeamInvitation, User } from '@/types/models'
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
  members?: PaginatedResponse<User>
  invitations?: PaginatedResponse<TeamInvitation>
  filters: {
    view: 'members' | 'invitations'
    query?: string
  }
}

const props = defineProps<Props>()

const user = computed<User>(() => {
  return usePage<PageProps>().props.user
})

const currentUserIsOwner = computed<boolean>(() => {
  return user.value.id === props.team.owner?.id
})
</script>
