<template>
  <DropdownMenu size="md">
    <template #button>
      <MenuButton
        class="focus-ring flex w-40 rounded-md border border-zinc-900/20 bg-zinc-50 py-1.5 pl-3 pr-10 text-sm shadow-sm transition-all ease-in-out dark:border-white/20 dark:bg-zinc-900"
        data-cy="team-switcher-button"
      >
        <span class="block truncate text-zinc-900 dark:text-white">{{ currentTeamName }}</span>
        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
          <ChevronUpDownIcon aria-hidden="true" class="h-5 w-5" />
        </span>
      </MenuButton>
    </template>

    <template #menuItems>
      <div class="space-y-1.5" data-cy="team-switcher-menu">
        <div>
          <span class="pointer-events-none block select-none p-2 pb-0.5 text-xs text-zinc-400 dark:text-zinc-500">
            {{ $tChoice('teams.teams', 2) }}
          </span>

          <MenuItemLink
            v-for="team in user.teams"
            :key="team.id"
            :class="{ 'font-medium text-zinc-900 dark:text-white': user.current_team_id === team.id }"
            :data="{ team_id: team.id }"
            :href="route('current-team.update')"
            as="button"
            method="put"
          >
            <div class="flex items-center">
              <span class="-ml-2 flex-1 truncate">
                {{ team.name }}
              </span>
              <Badge
                v-if="user.current_team_id === team.id"
                class="-mr-2 ml-2 shrink-0"
                data-cy="current-team-badge"
                type="primary"
              >
                {{ $t('teams.current') }}
              </Badge>
            </div>
          </MenuItemLink>
        </div>

        <span class="block border-t border-zinc-900/10 dark:border-white/10"></span>

        <MenuItemButton
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
          {{ $t('teams.create_team') }}
        </MenuItemButton>
      </div>
    </template>
  </DropdownMenu>

  <Modal
    :show="showModal"
    :title="$t('teams.create_team_form.modal_title')"
    data-cy="create-team-modal"
    @close="showModal = false"
  >
    <template #body>
      <p class="mb-4 text-sm">
        {{ $t('teams.create_team_form.modal_text') }}
      </p>

      <form id="create-team-form" class="space-y-6" data-cy="create-team-form" @submit.prevent="submit">
        <TextInput
          v-model="form.name"
          :error="form.errors.name"
          :label="$t('teams.team_name')"
          inverse
          required
          @input="form.clearErrors('name')"
        />
      </form>
    </template>

    <template #footer>
      <PrimaryButton :loading="form.processing" data-cy="submit-button" form="create-team-form" type="submit">
        {{ $t('teams.create_team_form.button') }}
      </PrimaryButton>

      <SecondaryButton data-cy="cancel-button" @click="showModal = false">
        {{ $t('common.cancel') }}
      </SecondaryButton>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import MenuItemLink from '@/Components/Dropdown/MenuItemLink.vue'
import { PlusCircleIcon } from '@heroicons/vue/24/outline'
import Badge from '@/Components/Badges/Badge.vue'
import MenuItemButton from '@/Components/Dropdown/MenuItemButton.vue'
import { MenuButton } from '@headlessui/vue'
import DropdownMenu from '@/Components/Dropdown/DropdownMenu.vue'
import { computed, ref } from 'vue'
import Modal from '@/Components/Modals/Modal.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import { useForm } from '@inertiajs/vue3'
import TextInput from '@/Components/Inputs/TextInput.vue'
import { CurrentUser } from '@/types/models'

interface Props {
  user: CurrentUser
}

const props = defineProps<Props>()

const showModal = ref<boolean>(false)

const currentTeamName = computed<string>(() => {
  return props.user.teams.find((team) => team.id === props.user.current_team_id)?.name ?? ''
})

type CreateTeamForm = {
  name: string
}

const form = useForm<CreateTeamForm>({
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
