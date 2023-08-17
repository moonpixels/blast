<template>
  <TwoColumnForm
    :description="$t('Permanently remove yourself from the :team_name team.', { team_name: team.name })"
    :title="$t('Leave team')"
  >
    <div class="flex h-full items-center">
      <DangerButton data-cy="leave-team-button" @click="showModal = true">
        {{ $t('Leave team') }}
      </DangerButton>
    </div>
  </TwoColumnForm>

  <Modal
    :show="showModal"
    :title="$t('Leave :team_name', { team_name: team.name })"
    data-cy="leave-team-modal"
    @close="showModal = false"
  >
    <template #body>
      <p class="text-sm">
        {{
          $t(
            "Are you sure you want to leave this team? This action cannot be undone and you'll need be invited back to access the team again."
          )
        }}
      </p>
    </template>

    <template #footer>
      <DangerButton :loading="form.processing" data-cy="leave-team-button" @click="submit">
        {{ $t('Leave team') }}
      </DangerButton>

      <SecondaryButton data-cy="cancel-button" @click="showModal = false">
        {{ $t('Cancel') }}
      </SecondaryButton>
    </template>
  </Modal>
</template>

<script lang="ts" setup>
import TwoColumnForm from '@/Components/Forms/TwoColumnForm.vue'
import DangerButton from '@/Components/Buttons/DangerButton.vue'
import { ref } from 'vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import Modal from '@/Components/Modals/Modal.vue'
import { useForm } from '@inertiajs/vue3'
import { Team, TeamMembership } from '@/types/models'

type Props = {
  team: Team
  teamMembership: TeamMembership
}

const props = defineProps<Props>()

const showModal = ref<boolean>(false)

const form = useForm({})

function submit(): void {
  form.delete(route('team-memberships.destroy', props.teamMembership.id), {
    preserveScroll: true,
    onFinish: () => {
      showModal.value = false
    },
  })
}
</script>
