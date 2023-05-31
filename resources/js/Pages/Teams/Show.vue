<template>
  <AppHead :title="$t('teams.settings_page.meta_title', { team_name: team.name })" />

  <ConstrainedContainer>
    <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">
      {{ $t('teams.settings_page.page_title', { team_name: team.name }) }}
    </h1>

    <TwoColumnFormContainer>
      <GeneralForm :team="team" />

      <MembersForm :team="team" />

      <DeleteTeamForm :team="team" />
    </TwoColumnFormContainer>
  </ConstrainedContainer>
</template>

<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ConstrainedContainer from '@/Components/Containers/ConstrainedContainer.vue'
import { Team } from '@/types'
import AppHead from '@/Components/App/AppHead.vue'
import TwoColumnFormContainer from '@/Components/Forms/TwoColumnFormContainer.vue'
import GeneralForm from '@/Pages/Teams/Partials/GeneralForm.vue'
import MembersForm from '@/Pages/Teams/Partials/MembersForm.vue'
import DeleteTeamForm from '@/Pages/Teams/Partials/DeleteTeamForm.vue'

defineOptions({
  layout: AppLayout,
})

interface Props {
  team: Team
}

defineProps<Props>()
</script>