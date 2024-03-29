<template>
  <div>
    <DropdownMenu size="md">
      <template #button>
        <MenuButton
          class="focus-ring flex w-40 rounded-md border border-zinc-900/20 bg-zinc-50 py-1.5 pl-3 pr-10 text-sm shadow-sm transition-all ease-in-out dark:border-white/20 dark:bg-zinc-900"
          data-cy="team-switcher-button"
        >
          <span class="block grow truncate text-zinc-900 dark:text-white">{{ currentTeamName }}</span>

          <span class="pointer-events-none absolute inset-y-0 right-0 flex shrink-0 items-center pr-2">
            <ChevronUpDownIcon aria-hidden="true" class="h-5 w-5" />
          </span>
        </MenuButton>
      </template>

      <template #menuItems>
        <div class="space-y-1.5" data-cy="team-switcher-menu">
          <div>
            <span class="pointer-events-none block select-none p-2 pb-0.5 text-xs text-zinc-400 dark:text-zinc-500">
              {{ $tChoice('Team|Teams', 2) }}
            </span>

            <DropdownMenuItemLink
              v-for="team in user.teams"
              :key="team.id"
              :class="{ 'font-medium text-zinc-900 dark:text-white': user.current_team?.id === team.id }"
              :data="{ current_team_id: team.id }"
              :href="route('user.current-team.update')"
              as="button"
              method="put"
            >
              <div class="flex items-center">
                <span class="-ml-2 flex-1 truncate">
                  {{ team.name }}
                </span>
                <BaseBadge
                  v-if="user.current_team?.id === team.id"
                  class="-mr-2 ml-2 shrink-0"
                  data-cy="current-team-badge"
                  variant="primary"
                >
                  {{ $t('Current') }}
                </BaseBadge>
              </div>
            </DropdownMenuItemLink>
          </div>

          <span class="block border-t border-zinc-900/10 dark:border-white/10"></span>

          <DropdownMenuItemButton
            v-slot="{ active }"
            class="flex w-full items-center"
            data-cy="create-team-button"
            @click="showModal = true"
          >
            <PlusCircleIcon
              :class="[active ? 'text-zinc-900 dark:text-white' : '']"
              aria-hidden="true"
              class="-ml-2 mr-2 h-5 w-5"
            />
            {{ $t('Create team') }}
          </DropdownMenuItemButton>
        </div>
      </template>
    </DropdownMenu>

    <Modal :show="showModal" :title="$t('Create a new team')" data-cy="create-team-modal" @close="showModal = false">
      <template #body>
        <p class="mb-4 text-sm">
          {{ $t('Use this form to create a new team. You can add team members to a team after creating it.') }}
        </p>

        <form id="create-team-form" class="space-y-6" data-cy="create-team-form" @submit.prevent="submit">
          <BaseInput
            v-model="form.name"
            :error="form.errors.name"
            :label="$t('Team name')"
            inverse
            required
            @input="form.clearErrors('name')"
          />
        </form>
      </template>

      <template #footer>
        <BaseButton :loading="form.processing" data-cy="submit-button" form="create-team-form" type="submit">
          {{ $t('Create team') }}
        </BaseButton>

        <BaseButton data-cy="cancel-button" variant="secondary" @click="showModal = false">
          {{ $t('Cancel') }}
        </BaseButton>
      </template>
    </Modal>
  </div>
</template>

<script lang="ts" setup>
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import DropdownMenuItemLink from '@/components/DropdownMenuItemLink.vue'
import { PlusCircleIcon } from '@heroicons/vue/24/outline'
import BaseBadge from '@/components/BaseBadge.vue'
import DropdownMenuItemButton from '@/components/DropdownMenuItemButton.vue'
import { MenuButton } from '@headlessui/vue'
import DropdownMenu from '@/components/DropdownMenu.vue'
import { computed, ref } from 'vue'
import Modal from '@/components/BaseModal.vue'
import { useForm } from '@inertiajs/vue3'
import BaseInput from '@/components/BaseInput.vue'
import { User } from '@/types/models'
import BaseButton from '@/components/BaseButton.vue'
import TeamData = App.Domain.Team.Data.TeamData

type Props = {
  user: User
}

const props = defineProps<Props>()

const showModal = ref<boolean>(false)

const currentTeamName = computed<string>(() => {
  return props.user.teams?.find((team) => team.id === props.user.current_team?.id)?.name as string
})

const form = useForm<TeamData>({
  name: '',
})

function submit(): void {
  form.post(route('teams.store'), {
    preserveState: false,
    onSuccess: () => {
      showModal.value = false
      form.reset()
    },
  })
}
</script>
