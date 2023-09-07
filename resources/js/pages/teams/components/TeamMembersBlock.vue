<template>
  <TwoColumnBlockItem :description="$t('Manage the members of your team.')" :title="$t('Members')">
    <AppAlert v-if="team.personal_team" data-cy="manage-members-personal-team-alert">
      {{ $t('You cannot invite members to your personal team.') }}
    </AppAlert>

    <ResourcePanel v-else>
      <ResourcePanelHeader>
        <div class="flex flex-grow justify-between gap-2">
          <TeamMembersCreateModal :team="team" />

          <AppButton
            class="flex items-center gap-2 shadow-none focus:ring-0 focus:ring-offset-0"
            data-cy="switch-view-mode-button"
            no-shadow
            @click="switchView"
          >
            <span :class="[filters.view === 'members' ? 'text-zinc-900 dark:text-white' : '']">
              {{ $tChoice('Member|Members', 2) }}
            </span>

            <ArrowsRightLeftIcon
              class="group-hover:animated-spin h-4 w-4 transition-all duration-200 ease-in-out group-hover:text-violet-500 dark:group-hover:text-violet-400"
            />

            <span :class="[filters.view === 'invitations' ? 'text-zinc-900 dark:text-white' : '']">
              {{ $tChoice('Invite|Invites', 2) }}
            </span>
          </AppButton>
        </div>

        <div class="mt-3 lg:mt-0 lg:w-60">
          <TextInput
            v-model="searchQuery"
            :label="$t('Search')"
            :placeholder="$t('Find a team member...')"
            data-cy="search-members-input"
            hide-label
            inverse
            type="search"
          >
            <template #icon>
              <MagnifyingGlassIcon class="pointer-events-none h-4 w-4" />
            </template>
          </TextInput>
        </div>
      </ResourcePanelHeader>

      <template v-if="filters.view === 'members'">
        <AppEmptyState
          v-if="!members?.data.length"
          :description="
            searchQuery ? $t('There are no members matching your search.') : $t('There are no members in this team.')
          "
          :icon="UserGroupIcon"
          :title="$t('No members')"
          data-cy="no-members-empty-state"
        />

        <ResourcePanelList v-else data-cy="members-list">
          <ResourcePanelListItem v-for="member in members.data" :key="member.id">
            <template #content>
              <div class="flex items-center gap-3">
                <AppAvatar :initials="member.initials" class="flex-none" size="md" />

                <div class="flex flex-auto gap-x-4 overflow-hidden">
                  <div class="flex-auto overflow-hidden">
                    <p
                      class="truncate text-sm font-semibold leading-6 text-zinc-900 dark:text-white"
                      data-cy="team-member-name"
                    >
                      {{ member.name }}
                    </p>
                    <p class="truncate text-xs leading-5">{{ member.email }}</p>
                  </div>
                </div>
              </div>
            </template>

            <template #actions>
              <TeamMembersDeleteModal :member="member" :team="team" />
            </template>
          </ResourcePanelListItem>
        </ResourcePanelList>
      </template>

      <template v-else>
        <AppEmptyState
          v-if="!invitations?.data.length"
          :description="
            searchQuery
              ? $t('There are no pending invitations matching your search.')
              : $t('There are no pending invitations for this team.')
          "
          :icon="UserPlusIcon"
          :title="$t('No pending invitations')"
          data-cy="no-invitations-empty-state"
        />

        <ResourcePanelList v-else data-cy="invitations-list">
          <ResourcePanelListItem v-for="invitation in invitations.data" :key="invitation.id">
            <template #content>
              <p class="truncate text-sm font-semibold leading-6 text-zinc-900 dark:text-white">
                {{ invitation.email }}
              </p>
              <p class="truncate text-xs leading-5">
                {{ $t('Invited on') }} {{ useFormatDate(invitation.created_at) }}
              </p>
            </template>

            <template #actions>
              <ButtonSecondary data-cy="resend-invitation-button" size="icon" @click="resendInvitation(invitation)">
                <span class="sr-only">{{ $t('Resend invitation') }}</span>
                <ArrowPathIcon aria-hidden="true" class="h-4 w-4" />
              </ButtonSecondary>

              <ButtonSecondary data-cy="cancel-invitation-button" size="icon" @click="cancelInvitation(invitation)">
                <span class="sr-only">{{ $t('Cancel invitation') }}</span>
                <TrashIcon
                  aria-hidden="true"
                  class="h-4 w-4 transition-all duration-200 ease-in-out group-hover:text-rose-600 dark:group-hover:text-rose-500"
                />
              </ButtonSecondary>
            </template>
          </ResourcePanelListItem>
        </ResourcePanelList>
      </template>

      <ResourcePanelFooter v-if="currentResource?.data.length">
        <PaginationTotals
          :paginated-resource="currentResource"
          :resource-name="
            filters.view === 'members'
              ? $tChoice('member|members', currentResource.meta.total)
              : $tChoice('invitation|invitations', currentResource.meta.total)
          "
          data-cy="pagination-totals"
        />

        <PaginationButtons :paginated-resource="currentResource" />
      </ResourcePanelFooter>
    </ResourcePanel>
  </TwoColumnBlockItem>
</template>

<script lang="ts" setup>
import TwoColumnBlockItem from '@/components/TwoColumnBlockItem.vue'
import AppAlert from '@/components/AppAlert.vue'
import AppAvatar from '@/components/AppAvatar.vue'
import TeamMembersCreateModal from '@/pages/teams/components/TeamMembersCreateModal.vue'
import { router } from '@inertiajs/vue3'
import TextInput from '@/components/AppInput.vue'
import { ArrowPathIcon, ArrowsRightLeftIcon, MagnifyingGlassIcon, TrashIcon } from '@heroicons/vue/20/solid'
import { UserGroupIcon, UserPlusIcon } from '@heroicons/vue/24/outline'
import ButtonSecondary from '@/components/ButtonSecondary.vue'
import AppButton from '@/components/AppButton.vue'
import useFormatDate from '@/composables/useFormatDate'
import { Team, TeamInvitation, User } from '@/types/models'
import { PaginatedResponse } from '@/types/framework'
import AppEmptyState from '@/components/AppEmptyState.vue'
import TeamMembersDeleteModal from '@/pages/teams/components/TeamMembersDeleteModal.vue'
import { computed, ref, watch } from 'vue'
import debounce from 'lodash/debounce'
import PaginationTotals from '@/components/PaginationTotals.vue'
import PaginationButtons from '@/components/PaginationButtons.vue'
import ResourcePanel from '@/components/ResourcePanel.vue'
import ResourcePanelHeader from '@/components/ResourcePanelHeader.vue'
import ResourcePanelList from '@/components/ResourcePanelList.vue'
import ResourcePanelListItem from '@/components/ResourcePanelListItem.vue'
import ResourcePanelFooter from '@/components/ResourcePanelFooter.vue'

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

const searchQuery = ref<string>(props.filters.query ?? '')

const currentResource = computed<PaginatedResponse<User | TeamInvitation> | undefined>(() => {
  return props.filters.view === 'members' ? props.members : props.invitations
})

function search(): void {
  router.reload({
    data: {
      view: props.filters.view,
      query: searchQuery.value,
      page: 1,
    },
    only: ['filters', 'members', 'invitations'],
  })
}

function switchView(): void {
  router.reload({
    data: {
      view: props.filters.view === 'invitations' ? 'members' : 'invitations',
      query: props.filters.query,
      page: 1,
    },
    only: ['filters', 'members', 'invitations'],
  })
}

function cancelInvitation(invitation: TeamInvitation): void {
  router.delete(
    route('teams.invitations.destroy', {
      team: props.team.id,
      invitation: invitation.id,
    }),
    {
      preserveScroll: true,
      preserveState: true,
      only: ['flash', 'invitations'],
    }
  )
}

function resendInvitation(invitation: TeamInvitation): void {
  router.get(
    route('teams.invitations.resend', {
      team: props.team.id,
      invitation: invitation.id,
    }),
    {},
    {
      preserveScroll: true,
      preserveState: true,
      only: ['flash'],
    }
  )
}

watch(searchQuery, debounce(search, 500))
</script>
