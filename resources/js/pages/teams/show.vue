<template>
  <AppHead :title="$t('Team settings')" />

  <AppContainer>
    <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">
      {{ $t(':team_name settings', { team_name: team.name }) }}
    </h1>

    <TwoColumnBlock>
      <template v-if="currentUserIsOwner">
        <TeamSettingsBlock :team="team" />

        <TeamMembersBlock :filters="filters" :invitations="invitations" :members="members" :team="team" />

        <TeamDeleteBlock :team="team" />
      </template>

      <template v-else>
        <TeamLeaveBlock :team="team" :user="user" />
      </template>
    </TwoColumnBlock>
  </AppContainer>
</template>

<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue'
import AppContainer from '@/components/AppContainer.vue'
import AppHead from '@/components/AppHead.vue'
import TwoColumnBlock from '@/components/TwoColumnBlock.vue'
import TeamMembersBlock from '@/pages/teams/components/TeamMembersBlock.vue'
import TeamDeleteBlock from '@/pages/teams/components/TeamDeleteBlock.vue'
import { Team, TeamInvitation, User } from '@/types/models'
import { PaginatedResponse } from '@/types/framework'
import { usePage } from '@inertiajs/vue3'
import { PageProps } from '@/types'
import TeamLeaveBlock from '@/pages/teams/components/TeamLeaveBlock.vue'
import TeamSettingsBlock from '@/pages/teams/components/TeamSettingsBlock.vue'
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
