<template>
  <TwoColumnForm :description="$t('Permanently delete this team and all of its resources.')" :title="$t('Delete team')">
    <Alert v-if="team.personal_team" data-cy="delete-personal-team-alert">
      {{ $t('You cannot delete your personal team.') }}
    </Alert>

    <div v-else class="flex h-full items-center">
      <DangerButton data-cy="delete-team-button" @click="showModal = true">
        {{ $t('Delete team') }}
      </DangerButton>
    </div>
  </TwoColumnForm>

  <Modal :show="showModal" :title="$t('Delete team')" data-cy="delete-team-modal" @close="showModal = false">
    <template #body>
      <p class="text-sm">
        {{
          $t(
            'Are you sure you want to delete this team? Once a team is deleted, all of its resources and data will be permanently deleted, including its short links and members.'
          )
        }}
      </p>
    </template>

    <template #footer>
      <DangerButton :loading="form.processing" data-cy="delete-team-button" @click="submit">
        {{ $t('Delete team') }}
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
import Alert from '@/Components/Alerts/Alert.vue'
import { Team } from '@/types/models'

interface Props {
  team: Team
}

const props = defineProps<Props>()

const showModal = ref<boolean>(false)

const form = useForm({})

function submit(): void {
  form.delete(route('teams.destroy', props.team.id), {
    preserveScroll: true,
    onFinish: () => {
      showModal.value = false
    },
  })
}
</script>
