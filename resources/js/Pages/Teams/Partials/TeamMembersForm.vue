<template>
  <TwoColumnForm :description="$t('Manage the members of your team.')" :title="$t('Members')">
    <Alert v-if="team.personal_team" data-cy="manage-members-personal-team-alert">
      {{ $t('You cannot invite members to your personal team.') }}
    </Alert>

    <div
      v-else
      class="overflow-hidden rounded-md border border-zinc-900/20 bg-white shadow-sm dark:border-white/20 dark:bg-zinc-950"
    >
      <div
        class="gap-3 border-b border-zinc-900/20 p-3 dark:border-white/20 lg:flex lg:items-center lg:justify-between"
      >
        <div class="flex flex-grow justify-between gap-2">
          <InviteMemberForm :team="team" />

          <BaseButton
            class="flex items-center gap-2 shadow-none"
            data-cy="switch-view-mode-button"
            @click="switchViewMode"
          >
            <span :class="[viewMode === 'members' ? 'text-zinc-900 dark:text-white' : '']">
              {{ $tChoice('Member|Members', 2) }}
            </span>

            <ArrowsRightLeftIcon
              class="group-hover:animated-spin h-4 w-4 transition-all duration-200 ease-in-out group-hover:text-violet-500 dark:group-hover:text-violet-400"
            />

            <span :class="[viewMode === 'invites' ? 'text-zinc-900 dark:text-white' : '']">
              {{ $tChoice('Invite|Invites', 2) }}
            </span>
          </BaseButton>
        </div>

        <form class="mt-3 lg:mt-0 lg:w-60">
          <TextInput
            v-model="form.search"
            :label="$t('Search')"
            :placeholder="$t('Find a team member...')"
            hide-label
            inverse
            type="search"
          >
            <template #icon>
              <MagnifyingGlassIcon class="pointer-events-none h-4 w-4" />
            </template>
          </TextInput>
        </form>
      </div>

      <template v-if="viewMode === 'members'">
        <SimpleEmptyState
          v-if="!members.data.length"
          :description="
            form.search ? $t('There are no members matching your search.') : $t('There are no members in this team.')
          "
          :title="$t('No members')"
          data-cy="no-members-empty-state"
        >
          <template #icon>
            <UserGroupIcon class="h-8 w-8 text-zinc-400 dark:text-zinc-500" />
          </template>
        </SimpleEmptyState>

        <ul v-else class="divide-y divide-zinc-900/10 dark:divide-white/10" data-cy="members-list" role="list">
          <li v-for="user in members.data" :key="user.id" class="flex justify-between gap-4 p-3">
            <div class="flex min-w-0 flex-auto items-center gap-x-4">
              <PlaceholderAvatar :initials="user.initials" class="flex-none" size="md" />

              <div class="flex min-w-0 flex-auto gap-x-4">
                <div class="min-w-0 shrink">
                  <p class="truncate text-sm font-semibold leading-6 text-zinc-900 dark:text-white">
                    {{ user.name }}
                  </p>
                  <p class="truncate text-xs leading-5">{{ user.email }}</p>
                </div>

                <div v-if="user.id === team.owner_id" class="flex-none">
                  <Badge data-cy="owner-badge">
                    {{ $t('Owner') }}
                  </Badge>
                </div>
              </div>
            </div>

            <div class="flex flex-none items-center">
              <DeleteTeamMemberModal :team="team" :user="user" />
            </div>
          </li>
        </ul>
      </template>
      <template v-else>
        <SimpleEmptyState
          v-if="!invitations.data.length"
          :description="
            form.search
              ? $t('There are no pending invitations matching your search.')
              : $t('There are no pending invitations for this team.')
          "
          :title="$t('No pending invitations')"
          data-cy="no-invitations-empty-state"
        >
          <template #icon>
            <UserPlusIcon class="h-8 w-8 text-zinc-400 dark:text-zinc-500" />
          </template>
        </SimpleEmptyState>

        <ul v-else class="divide-y divide-zinc-900/10 dark:divide-white/10" data-cy="invitations-list" role="list">
          <li v-for="invitation in invitations.data" :key="invitation.id" class="flex justify-between gap-4 p-3">
            <div class="flex min-w-0 flex-auto items-center gap-x-4">
              <div class="flex min-w-0 flex-auto gap-x-4">
                <div class="min-w-0 shrink">
                  <p class="truncate text-sm font-semibold leading-6 text-zinc-900 dark:text-white">
                    {{ invitation.email }}
                  </p>
                  <p class="truncate text-xs leading-5">{{ $t('Invited on') }} {{ useDate(invitation.created_at) }}</p>
                </div>
              </div>
            </div>

            <div class="flex flex-none items-center gap-2">
              <SecondaryButton data-cy="resend-invitation-button" size="icon" @click="resendInvitation(invitation)">
                <span class="sr-only">{{ $t('Resend invitation') }}</span>
                <ArrowPathIcon aria-hidden="true" class="h-4 w-4" />
              </SecondaryButton>

              <SecondaryButton data-cy="cancel-invitation-button" size="icon" @click="cancelInvitation(invitation)">
                <span class="sr-only">{{ $t('Cancel invitation') }}</span>
                <TrashIcon
                  aria-hidden="true"
                  class="h-4 w-4 transition-all duration-200 ease-in-out group-hover:text-rose-600 dark:group-hover:text-rose-500"
                />
              </SecondaryButton>
            </div>
          </li>
        </ul>
      </template>

      <div class="border-t border-zinc-900/20 px-3 py-1.5 text-sm dark:border-white/20">Pagination</div>
    </div>
  </TwoColumnForm>
</template>

<script lang="ts" setup>
import TwoColumnForm from '@/Components/Forms/TwoColumnForm.vue'
import Alert from '@/Components/Alerts/Alert.vue'
import PlaceholderAvatar from '@/Components/Avatars/PlaceholderAvatar.vue'
import Badge from '@/Components/Badges/Badge.vue'
import InviteMemberForm from '@/Pages/Teams/Partials/InviteMemberForm.vue'
import { router, useForm } from '@inertiajs/vue3'
import TextInput from '@/Components/Inputs/TextInput.vue'
import { ArrowPathIcon, ArrowsRightLeftIcon, MagnifyingGlassIcon, TrashIcon } from '@heroicons/vue/20/solid'
import { UserGroupIcon, UserPlusIcon } from '@heroicons/vue/24/outline'
import { ref } from 'vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import BaseButton from '@/Components/Buttons/BaseButton.vue'
import useDate from '@/composables/useDate'
import { Team, TeamInvitation, User } from '@/types/models'
import { PaginatedResponse } from '@/types/framework'
import SimpleEmptyState from '@/Components/EmptyStates/SimpleEmptyState.vue'
import DeleteTeamMemberModal from '@/Pages/Teams/Partials/DeleteTeamMemberModal.vue'

interface Props {
  team: Team
  members: PaginatedResponse<User>
  invitations: PaginatedResponse<TeamInvitation>
}

defineProps<Props>()

const viewMode = ref<'members' | 'invites'>('members')

type FindUserForm = {
  search: string
}

const form = useForm<FindUserForm>({
  search: '',
})

function switchViewMode() {
  viewMode.value = viewMode.value === 'members' ? 'invites' : 'members'
}

function cancelInvitation(invitation: TeamInvitation): void {
  router.delete(route('invitations.destroy', { invitation: invitation.id }), {
    preserveScroll: true,
    preserveState: true,
    only: ['flash', 'invitations'],
  })
}

function resendInvitation(invitation: TeamInvitation): void {
  router.post(
    route('resent-invitations.store'),
    {
      invitation_id: invitation.id,
    },
    {
      preserveScroll: true,
      preserveState: true,
      only: ['flash', 'invitations'],
    }
  )
}
</script>
